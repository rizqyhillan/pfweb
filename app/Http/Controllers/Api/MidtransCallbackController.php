<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    /**
     * Handle incoming payment notification from Midtrans.
     *
     * This endpoint is called by Midtrans servers (webhook) — no auth required.
     * It verifies the SHA-512 signature, then updates the transaction accordingly.
     */
    public function handle(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        Log::info('Midtrans callback received', $payload);

        // ----- 1. Extract required fields -----
        $orderId      = $payload['order_id']           ?? null;
        $statusCode   = (string) ($payload['status_code']  ?? '');
        $grossAmount  = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = $payload['signature_key']      ?? '';
        $trxStatus    = $payload['transaction_status'] ?? '';
        $paymentType  = $payload['payment_type']       ?? '';
        $fraudStatus  = $payload['fraud_status']       ?? 'accept';

        if (!$orderId || !$signatureKey) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // ----- 2. Verify signature -----
        if (!$midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans callback — invalid signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ----- 3. Find the transaction -----
        $transaction = Transaction::where('kode_transaksi', $orderId)->first();

        if (!$transaction) {
            Log::warning('Midtrans callback — transaction not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // ----- 4. Idempotency: skip if already settled -----
        if (in_array($transaction->payment_status, ['settlement', 'capture'])) {
            return response()->json(['message' => 'Already processed'], 200);
        }

        // ----- 5. Determine new status -----
        return DB::transaction(function () use ($transaction, $trxStatus, $fraudStatus, $paymentType, $payload) {

            $paymentStatus = $trxStatus;

            if ($trxStatus === 'capture') {
                // For credit-card: check fraud status
                $paymentStatus = ($fraudStatus === 'accept') ? 'capture' : 'deny';
            }

            switch ($paymentStatus) {
                case 'capture':
                case 'settlement':
                    $transaction->update([
                        'status'            => 'lunas',
                        'payment_status'    => $paymentStatus,
                        'payment_type'      => $paymentType,
                        'payment_reference' => $payload['transaction_id'] ?? null,
                        'paid_at'           => now(),
                    ]);
                    Log::info("Midtrans — Transaction {$transaction->kode_transaksi} paid (settlement).");
                    break;

                case 'pending':
                    $transaction->update([
                        'payment_status' => 'pending',
                        'payment_type'   => $paymentType,
                    ]);
                    break;

                case 'deny':
                case 'cancel':
                case 'expire':
                    // Only restore stock if still pending (not already cancelled)
                    if ($transaction->status === 'pending') {
                        // Restore product stock
                        foreach ($transaction->barang as $item) {
                            if ($item->barang) {
                                $item->barang->increment('stok', $item->jumlah);
                            }
                        }

                        $transaction->update([
                            'status'         => 'batal',
                            'payment_status' => $paymentStatus,
                            'catatan'        => trim(
                                ($transaction->catatan ? $transaction->catatan . "\n" : '') .
                                "Dibatalkan otomatis oleh Midtrans ({$paymentStatus})."
                            ),
                        ]);

                        Log::info("Midtrans — Transaction {$transaction->kode_transaksi} cancelled ({$paymentStatus}), stock restored.");
                    }
                    break;

                default:
                    Log::info("Midtrans — Unhandled status: {$trxStatus} for {$transaction->kode_transaksi}");
                    break;
            }

            return response()->json(['message' => 'OK'], 200);
        });
    }
}
