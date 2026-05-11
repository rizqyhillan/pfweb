<?php

use App\Events\BoardingCreatedRealtime;
use App\Models\Pet;
use App\Models\Room;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('boarding store dispatches BoardingCreatedRealtime event and notifies admins', function () {
    Event::fake([BoardingCreatedRealtime::class]);
    Notification::fake();

    // Create karyawan user
    $karyawan = User::create([
        'nama' => 'Test Karyawan',
        'email' => 'karyawan-test@example.com',
        'password' => bcrypt('password'),
        'role' => 'karyawan',
    ]);

    // Create admin user to receive notification
    $admin = User::create([
        'nama' => 'Test Admin',
        'email' => 'admin-test@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // Create customer/owner
    $owner = User::create([
        'nama' => 'Test Owner',
        'email' => 'owner-test@example.com',
        'password' => bcrypt('password'),
        'role' => 'customer',
    ]);

    // Create pet
    $pet = Pet::create([
        'id_pemilik' => $owner->id,
        'nama_hewan' => 'Buddy',
        'jenis' => 'Anjing',
        'ras' => 'Golden Retriever',
        'umur' => 3,
        'berat' => 25.5,
    ]);

    // Create room
    $room = Room::create([
        'nama_kamar' => 'Kamar A1',
        'tipe' => 'sedang',
        'harga_per_hari' => 50000,
        'kapasitas' => 1,
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($karyawan)->post(route('karyawan.boardings.store'), [
        'id_hewan' => $pet->id,
        'id_kamar' => $room->id,
        'tanggal_masuk' => now()->toDateString(),
        'tanggal_rencana_keluar' => now()->addDays(3)->toDateString(),
        'catatan_titip' => 'Tolong jaga baik-baik',
        'total_biaya' => 150000,
    ]);

    $response->assertRedirect(route('karyawan.boardings.index'));

    Event::assertDispatched(BoardingCreatedRealtime::class);

    Notification::assertSentTo($admin, SystemNotification::class, function ($notification) {
        return $notification->title === 'Boarding Baru'
            && str_contains($notification->message, 'Buddy');
    });
});
