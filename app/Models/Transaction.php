<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [ 'id_pelanggan', 'id_kasir', 'kode_transaksi', 'jenis', 'subtotal', 'diskon', 'total', 'jumlah_bayar', 'kembalian', 'metode_bayar', 'status', 'payment_provider', 'payment_channel', 'payment_reference', 'payment_token', 'payment_redirect_url', 'payment_status', 'payment_type', 'payment_expired_at', 'paid_at', 'catatan', 'tanggal', ];
    protected $casts = [ 'subtotal' => 'decimal:2', 'diskon' => 'decimal:2', 'total' => 'decimal:2', 'jumlah_bayar' => 'decimal:2', 'kembalian' => 'decimal:2', 'tanggal' => 'datetime', 'payment_expired_at' => 'datetime', 'paid_at' => 'datetime', ];

    // Default status when creating
    protected $attributes = [
        'status' => 'pending',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }
    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }
    public function barang()
    {
        return $this->hasMany(TransactionProduct::class, 'id_transaksi');
    }
    public function layanan()
    {
        return $this->hasMany(TransactionService::class, 'id_transaksi');
    }
    // Backward compat
    public function customer()
    {
        return $this->pelanggan();
    }
    public function cashier()
    {
        return $this->kasir();
    }
    public function products()
    {
        return $this->barang();
    }
    public function services()
    {
        return $this->layanan();
    }

    public function bookingDokter() 
    {
    return $this->hasOne(DoctorBooking::class, 'id_transaksi');
    }

    /**
     * Check if the transaction's payment has expired and update status to 'batal' if it is.
     * Restores the products stock.
     */
    public function checkAndUpdateStatusIfExpired(): bool
    {
        if ($this->status === 'pending' && 
            $this->payment_expired_at && 
            $this->payment_expired_at->isPast()) {
            
            \Illuminate\Support\Facades\DB::transaction(function () {
                // Restore stock
                foreach ($this->barang as $item) {
                    if ($item->barang) {
                        $item->barang->increment('stok', $item->jumlah);
                    }
                }
                
                $this->update([
                    'status' => 'batal',
                    'payment_status' => 'expire',
                    'catatan' => trim(($this->catatan ? $this->catatan . "\n" : '') . 'Dibatalkan otomatis karena batas waktu pembayaran habis (12 jam).'),
                ]);
            });
            
            return true;
        }
        
        return false;
    }

    /**
     * Update transaction status based on Midtrans API/webhook status payload.
     *
     * @param array $payload The webhook payload or status response from Midtrans
     * @return void
     */
    public function updateStatusFromMidtrans(array $payload): void
    {
        // Idempotency: skip if already paid
        if (in_array($this->payment_status, ['settlement', 'capture'])) {
            return;
        }

        $trxStatus   = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? 'accept';
        $paymentType = $payload['payment_type'] ?? '';

        \Illuminate\Support\Facades\DB::transaction(function () use ($trxStatus, $fraudStatus, $paymentType, $payload) {
            $paymentStatus = $trxStatus;

            if ($trxStatus === 'capture') {
                // For credit-card: check fraud status
                $paymentStatus = ($fraudStatus === 'accept') ? 'capture' : 'deny';
            }

            switch ($paymentStatus) {
                case 'capture':
                case 'settlement':
                    $this->update([
                        'status'            => 'lunas',
                        'payment_status'    => $paymentStatus,
                        'payment_type'      => $paymentType,
                        'payment_reference' => $payload['transaction_id'] ?? null,
                        'paid_at'           => isset($payload['settlement_time']) 
                            ? \Carbon\Carbon::parse($payload['settlement_time']) 
                            : now(),
                    ]);
                    \Illuminate\Support\Facades\Log::info("Midtrans — Transaction {$this->kode_transaksi} status updated to paid ($paymentStatus).");
                    break;

                case 'pending':
                    $this->update([
                        'payment_status' => 'pending',
                        'payment_type'   => $paymentType,
                    ]);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    // Only cancel and restore stock if transaction is still pending
                    if ($this->status === 'pending') {
                        // Restore product stock
                        foreach ($this->barang as $item) {
                            if ($item->barang) {
                                $item->barang->increment('stok', $item->jumlah);
                            }
                        }

                        $this->update([
                            'status'         => 'batal',
                            'payment_status' => $paymentStatus,
                            'catatan'        => trim(
                                ($this->catatan ? $this->catatan . "\n" : '') .
                                "Dibatalkan otomatis oleh Midtrans ({$paymentStatus})."
                            ),
                        ]);

                        \Illuminate\Support\Facades\Log::info("Midtrans — Transaction {$this->kode_transaksi} cancelled ({$paymentStatus}), stock restored.");
                    }
                    break;

                default:
                    \Illuminate\Support\Facades\Log::info("Midtrans — Unhandled status: {$trxStatus} for {$this->kode_transaksi}");
                    break;
            }
        });
    }
}
