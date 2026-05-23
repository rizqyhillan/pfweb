<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $snapUrl;
    protected bool $isProduction;
    protected int $expiryDuration;
    protected string $expiryUnit;

    public function __construct()
    {
        $this->serverKey      = (string) config('services.midtrans.server_key', '');
        $this->isProduction   = (bool) config('services.midtrans.is_production', false);
        $this->snapUrl        = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : (string) config('services.midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/v1/transactions');
        $this->expiryDuration = (int) config('services.midtrans.expiry_duration', 12);
        $this->expiryUnit     = (string) config('services.midtrans.expiry_unit', 'hour');

        if (empty($this->serverKey)) {
            Log::warning('Midtrans server key is empty. Please check your configuration.');
        }
    }

    /**
     * Create a Midtrans Snap token for the given order.
     *
     * @return array{token: string, redirect_url: string}
     *
     * @throws \Exception
     */
    public function createSnapToken(
        string $orderId,
        int    $grossAmount,
        array  $customerDetails,
        array  $itemDetails = [],
    ): array {
        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'expiry' => [
                'unit'     => $this->expiryUnit,
                'duration' => $this->expiryDuration,
            ],
        ];

        if (!empty($itemDetails)) {
            $payload['item_details'] = $itemDetails;
        }

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($this->snapUrl, $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap token creation failed', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $payload,
            ]);

            throw new \Exception(
                'Gagal membuat Snap token Midtrans: ' . ($response->json('error_messages.0') ?? $response->body())
            );
        }

        $data = $response->json();

        return [
            'token'        => $data['token'],
            'redirect_url' => $data['redirect_url'],
        ];
    }

    /**
     * Verify the SHA-512 signature key from a Midtrans notification.
     *
     * Formula: SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $signatureKey,
    ): bool {
        // 1. Direct match with whatever Midtrans sent
        $input1     = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $generated1 = hash('sha512', $input1);
        if (hash_equals($generated1, $signatureKey)) {
            return true;
        }

        // 2. Format as float with 2 decimal places (common in Midtrans callbacks)
        $formattedAmount = number_format((float) $grossAmount, 2, '.', '');
        $input2     = $orderId . $statusCode . $formattedAmount . $this->serverKey;
        $generated2 = hash('sha512', $input2);
        if (hash_equals($generated2, $signatureKey)) {
            return true;
        }

        // 3. Format as integer (without decimals)
        $intAmount = (string) (int) $grossAmount;
        $input3     = $orderId . $statusCode . $intAmount . $this->serverKey;
        $generated3 = hash('sha512', $input3);
        if (hash_equals($generated3, $signatureKey)) {
            return true;
        }

        return false;
    }

    /**
     * Fetch the current status of a transaction from Midtrans API.
     *
     * @throws \Exception
     */
    public function getTransactionStatus(string $orderId): array
    {
        $statusUrl = $this->isProduction
            ? "https://api.midtrans.com/v2/{$orderId}/status"
            : "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get($statusUrl);

        if ($response->failed()) {
            Log::error('Midtrans status check failed', [
                'order_id' => $orderId,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            throw new \Exception(
                'Gagal mengambil status transaksi dari Midtrans: ' . ($response->json('status_message') ?? $response->body())
            );
        }

        return $response->json();
    }
}
