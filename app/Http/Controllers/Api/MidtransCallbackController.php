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

        // ----- 4. Update status using centralized model method -----
        try {
            $transaction->updateStatusFromMidtrans($payload);
            return response()->json(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans callback status update failed', [
                'order_id' => $orderId,
                'error'    => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Callback processing failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
