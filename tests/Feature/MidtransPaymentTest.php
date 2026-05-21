<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Enable foreign keys for SQLite
    DB::statement('PRAGMA foreign_keys = ON');

    // Create user
    $this->user = User::create([
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
        'no_hp' => '08123456789',
    ]);

    // Create product
    $this->product = Product::create([
        'nama_barang' => 'Dog Food Premium',
        'kategori' => 'Makanan',
        'harga' => 100000,
        'stok' => 10,
        'satuan' => 'pcs',
        'is_aktif' => true,
    ]);
});

test('shop checkout creates a transaction and generates snap token', function () {
    // Arrange: Mock Midtrans Snap response
    Http::fake([
        'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
            'token' => 'mock-snap-token-123',
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v1/payment/mock-snap-token-123'
        ], 201),
    ]);

    // Create active cart for the user
    $cart = Cart::create([
        'id_user' => $this->user->id,
        'status' => 'aktif',
    ]);

    // Add item to cart
    CartItem::create([
        'id_keranjang' => $cart->id,
        'id_barang' => $this->product->id,
        'jumlah' => 2,
        'harga_satuan' => 100000,
        'subtotal' => 200000,
    ]);

    // Act
    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/shop/checkout', [
            'metode_bayar' => 'ewallet',
            'catatan' => 'Kirim cepat ya',
        ]);

    // Assert
    $response->assertStatus(201)
        ->assertJsonPath('message', 'Checkout berhasil dibuat.')
        ->assertJsonStructure([
            'data' => [
                'id',
                'kode_transaksi',
                'status',
                'snap_token',
                'redirect_url',
                'payment_status',
            ],
        ]);

    $this->product->refresh();
    expect($this->product->stok)->toBe(8); // Decremented from 10 to 8

    $transaction = Transaction::first();
    expect($transaction->kode_transaksi)->toBe($response->json('data.kode_transaksi'));
    expect($transaction->status)->toBe('pending');
    expect($transaction->payment_status)->toBe('pending');
    expect($transaction->payment_token)->toBe('mock-snap-token-123');
    expect($transaction->payment_redirect_url)->toBe('https://app.sandbox.midtrans.com/snap/v1/payment/mock-snap-token-123');
});

test('midtrans callback with settlement updates transaction to lunas', function () {
    // Arrange: Create a transaction
    $transaction = Transaction::create([
        'id_pelanggan' => $this->user->id,
        'kode_transaksi' => 'SHOP-12345678',
        'jenis' => 'shopping',
        'subtotal' => 100000,
        'total' => 100000,
        'metode_bayar' => 'ewallet',
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_provider' => 'midtrans',
    ]);

    // Setup midtrans server key configuration
    config(['services.midtrans.server_key' => 'my-secret-key']);

    // Generate valid signature: SHA512(order_id + status_code + gross_amount + server_key)
    $signature = hash('sha512', 'SHOP-12345678' . '200' . '100000' . 'my-secret-key');

    // Act: Send callback payload
    $response = $this->postJson('/api/midtrans/callback', [
        'order_id' => 'SHOP-12345678',
        'status_code' => '200',
        'gross_amount' => '100000',
        'signature_key' => $signature,
        'transaction_status' => 'settlement',
        'payment_type' => 'gopay',
        'transaction_id' => 'midtrans-trx-id-abc',
    ]);

    // Assert
    $response->assertStatus(200);
    $transaction->refresh();
    expect($transaction->status)->toBe('lunas');
    expect($transaction->payment_status)->toBe('settlement');
    expect($transaction->payment_type)->toBe('gopay');
    expect($transaction->payment_reference)->toBe('midtrans-trx-id-abc');
    expect($transaction->paid_at)->not->toBeNull();
});

test('midtrans callback with cancel or expire restores stock and marks as batal', function () {
    // Arrange: Create transaction & detail barang
    $transaction = Transaction::create([
        'id_pelanggan' => $this->user->id,
        'kode_transaksi' => 'SHOP-87654321',
        'jenis' => 'shopping',
        'subtotal' => 200000,
        'total' => 200000,
        'metode_bayar' => 'ewallet',
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_provider' => 'midtrans',
    ]);

    // Transaction details (products purchased)
    $transaction->barang()->create([
        'id_barang' => $this->product->id,
        'jumlah' => 3,
        'harga_satuan' => 100000,
        'subtotal' => 300000,
    ]);

    // Let's set the stock beforehand (reduced during checkout)
    $this->product->update(['stok' => 7]);

    // Setup midtrans server key configuration
    config(['services.midtrans.server_key' => 'my-secret-key']);

    // Generate valid signature
    $signature = hash('sha512', 'SHOP-87654321' . '407' . '200000' . 'my-secret-key');

    // Act: Send callback for expire
    $response = $this->postJson('/api/midtrans/callback', [
        'order_id' => 'SHOP-87654321',
        'status_code' => '407',
        'gross_amount' => '200000',
        'signature_key' => $signature,
        'transaction_status' => 'expire',
        'payment_type' => 'gopay',
    ]);

    // Assert
    $response->assertStatus(200);
    $transaction->refresh();
    expect($transaction->status)->toBe('batal');
    expect($transaction->payment_status)->toBe('expire');

    $this->product->refresh();
    expect($this->product->stok)->toBe(10); // Restored from 7 to 10
});

test('midtrans callback rejects invalid signature key', function () {
    $transaction = Transaction::create([
        'id_pelanggan' => $this->user->id,
        'kode_transaksi' => 'SHOP-999',
        'jenis' => 'shopping',
        'subtotal' => 100000,
        'total' => 100000,
        'metode_bayar' => 'ewallet',
        'status' => 'pending',
        'payment_status' => 'pending',
    ]);

    // Act: callback with invalid signature
    $response = $this->postJson('/api/midtrans/callback', [
        'order_id' => 'SHOP-999',
        'status_code' => '200',
        'gross_amount' => '100000',
        'signature_key' => 'invalid-signature-key-goes-here',
        'transaction_status' => 'settlement',
    ]);

    // Assert
    $response->assertStatus(403);
    $transaction->refresh();
    expect($transaction->status)->toBe('pending');
});
