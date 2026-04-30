<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\StockCard;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Boarding;
use App\Models\MedicalRecord;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TransactionService;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pw = Hash::make('password');

        // 1. Seed Users (Akun Inti + Customers)
        $admin = User::firstOrCreate(['email' => 'admin@pawpet.com'], [
            'nama' => 'Admin Utama', 'password' => $pw, 'role' => 'admin', 'no_hp' => '081200000001', 'is_aktif' => true
        ]);
        $doctor = User::firstOrCreate(['email' => 'dokter@pawpet.com'], [
            'nama' => 'Drh. Setiawan', 'password' => $pw, 'role' => 'doctor', 'no_hp' => '081200000002', 'is_aktif' => true
        ]);
        $kasir = User::firstOrCreate(['email' => 'kasir@pawpet.com'], [
            'nama' => 'Mbak Kasir', 'password' => $pw, 'role' => 'karyawan', 'no_hp' => '081200000003', 'is_aktif' => true
        ]);

        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = User::firstOrCreate(['email' => "customer$i@pawpet.com"], [
                'nama' => "Pelanggan $i", 'password' => $pw, 'role' => 'customer', 'no_hp' => "08130000000$i", 'is_aktif' => true
            ]);
        }

        // 2. Suppliers
        $suppliers = [];
        for ($i = 1; $i <= 5; $i++) {
            $suppliers[] = Supplier::firstOrCreate(['nama_supplier' => "Supplier $i"], [
                'kontak' => "08140000000$i", 'alamat' => "Jalan Supplier $i, Kota $i"
            ]);
        }

        // 3. Rooms
        $rooms = [];
        $tipeKamar = ['standar', 'vip', 'standar', 'vip', 'standar'];
        for ($i = 1; $i <= 5; $i++) {
            $rooms[] = Room::firstOrCreate(['nama_kamar' => "Kamar $i"], [
                'tipe' => $tipeKamar[$i - 1], 'harga_per_hari' => ($tipeKamar[$i - 1] == 'vip' ? 100000 : 50000), 'status' => 'tersedia'
            ]);
        }

        // 4. Services
        $services = [];
        $namaLayanan = ['Vaksinasi Kucing', 'Grooming Anjing', 'Pemeriksaan Umum', 'Operasi Minor', 'Scaling Gigi'];
        for ($i = 0; $i < 5; $i++) {
            $services[] = Service::firstOrCreate(['nama_layanan' => $namaLayanan[$i]], [
                'deskripsi' => "Layanan " . $namaLayanan[$i], 'harga' => 50000 + ($i * 25000), 'id_dokter' => $doctor->id, 'is_aktif' => true
            ]);
        }

        // 5. Products & Batches
        $products = [];
        $namaProduk = ['Whiskas Tuna 1kg', 'Royal Canin Kitten', 'Shampoo Kucing', 'Kalung Anjing', 'Vitamin Bulu'];
        for ($i = 0; $i < 5; $i++) {
            $product = Product::firstOrCreate(['nama_barang' => $namaProduk[$i]], [
                'kategori' => 'makanan', 'harga' => 20000 + ($i * 15000), 'stok' => 50, 'is_aktif' => true
            ]);
            $products[] = $product;
            
            $batch = ProductBatch::create([
                'id_barang' => $product->id, 'id_supplier' => $suppliers[$i]->id, 'no_batch' => "BCH-2026-" . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'jumlah_masuk' => 50, 'sisa_stok' => 50, 'harga_beli' => $product->harga * 0.7, 'tanggal_masuk' => now()->subDays(10), 'keterangan' => 'Stok Awal'
            ]);
            StockCard::create([
                'id_barang' => $product->id, 'id_batch' => $batch->id, 'tanggal' => now()->subDays(10), 'jenis_mutasi' => 'masuk',
                'jumlah' => 50, 'saldo' => 50, 'harga_satuan' => $batch->harga_beli, 'referensi' => $batch->no_batch, 'keterangan' => 'Stok Awal'
            ]);
        }

        // 6. Pets
        $pets = [];
        $jenisHewan = ['kucing', 'anjing', 'kucing', 'kelinci', 'burung'];
        for ($i = 0; $i < 5; $i++) {
            $pets[] = Pet::firstOrCreate(['nama_hewan' => "Hewan $i"], [
                'id_pemilik' => $customers[$i]->id, 'jenis' => $jenisHewan[$i], 'ras' => 'Mix', 'umur' => '1 Tahun', 'berat' => 4.5 + $i, 'catatan' => 'Sehat'
            ]);
        }

        // 7. Medical Records
        for ($i = 0; $i < 5; $i++) {
            MedicalRecord::create([
                'id_hewan' => $pets[$i]->id, 'id_dokter' => $doctor->id, 'diagnosa' => "Demam Ringan $i", 'tindakan' => "Pemberian Vitamin $i",
                'resep' => "Obat $i", 'berat_saat_itu' => $pets[$i]->berat, 'tanggal' => now()->subDays(5 - $i)
            ]);
        }

        // 8. Boardings
        for ($i = 0; $i < 5; $i++) {
            Boarding::create([
                'id_hewan' => $pets[$i]->id, 'id_kamar' => $rooms[$i]->id, 'tanggal_masuk' => now()->addDays($i), 'tanggal_rencana_keluar' => now()->addDays($i + 2),
                'status' => 'pending', 'total_biaya' => $rooms[$i]->harga_per_hari * 2
            ]);
        }

        // 9. Doctor Schedules
        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        for ($i = 0; $i < 5; $i++) {
            \App\Models\DoctorSchedule::create([
                'id_dokter' => $doctor->id, 'hari' => $hari[$i], 'jam_mulai' => '09:00:00', 'jam_selesai' => '15:00:00', 'is_aktif' => true
            ]);
        }

        // 10. Transactions
        for ($i = 0; $i < 5; $i++) {
            $trx = Transaction::create([
                'id_pelanggan' => $customers[$i]->id, 'id_kasir' => $kasir->id, 'kode_transaksi' => 'TRX-' . date('Ymd') . '-00' . ($i + 1),
                'jenis' => 'campuran', 'subtotal' => 0, 'diskon' => 0, 'total' => 0, 'jumlah_bayar' => 0, 'kembalian' => 0,
                'metode_bayar' => 'cash', 'status' => 'lunas', 'tanggal' => now()->subDays(5 - $i)
            ]);

            $p = $products[$i];
            $trx->barang()->create(['id_barang' => $p->id, 'jumlah' => 1, 'harga_satuan' => $p->harga, 'subtotal' => $p->harga]);
            $s = $services[$i];
            $trx->layanan()->create(['id_layanan' => $s->id, 'jumlah' => 1, 'harga_satuan' => $s->harga, 'subtotal' => $s->harga]);
            
            $trx->update(['subtotal' => $p->harga + $s->harga, 'total' => $p->harga + $s->harga, 'jumlah_bayar' => $p->harga + $s->harga + 10000, 'kembalian' => 10000]);
        }

        echo "Seeding completed: Semua tabel telah diisi minimal 5 data dummy.\n";
    }
}
