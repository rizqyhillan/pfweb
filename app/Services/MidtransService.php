<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $snapUrl;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey    = (string) config('services.midtrans.server_key', '');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
        $this->snapUrl      = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : (string) config('services.midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/v1/transactions');

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
        $input     = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $generated = hash('sha512', $input);

        return hash_equals($generated, $signatureKey);
    }
}
