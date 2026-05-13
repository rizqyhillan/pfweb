<?php

use App\Models\PackageType;
use App\Models\Room;
use App\Models\User;

it('allows admin to create package types and assign them to a room', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.package-types.store'), [
        'name' => 'vip',
        'label' => 'VIP',
        'description' => 'Paket VIP dengan fasilitas khusus',
    ]);

    $response->assertRedirect(route('admin.package-types.index'));
    $this->assertDatabaseHas('package_types', ['name' => 'vip', 'label' => 'VIP']);

    $response = $this->actingAs($admin)->post(route('admin.rooms.store'), [
        'nama_kamar' => 'Kamar VIP 1',
        'paket' => 'vip',
        'harga_per_hari' => 250000,
        'kapasitas' => 2,
        'keterangan' => 'Kamar VIP',
    ]);

    $response->assertRedirect(route('admin.rooms.index'));
    $this->assertDatabaseHas('kamar', ['nama_kamar' => 'Kamar VIP 1', 'paket' => 'vip']);
});

it('prevents deleting a package type that is used by an existing room', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $packageType = PackageType::create(['name' => 'vip', 'label' => 'VIP', 'description' => 'VIP package']);
    Room::create([
        'nama_kamar' => 'Kamar VIP 2',
        'paket' => 'vip',
        'harga_per_hari' => 200000,
        'kapasitas' => 1,
        'status' => 'tersedia',
        'keterangan' => null,
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.package-types.destroy', $packageType));

    $response->assertRedirect(route('admin.package-types.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('package_types', ['name' => 'vip']);
});
