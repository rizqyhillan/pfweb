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
        // Bersihkan Data (Opsional jika ingin fresh)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('users')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pw = Hash::make('password');

        // 1. Seed Users
        $admin = User::firstOrCreate(['email' => 'admin@pawpet.com'], [
            'nama' => 'Admin Utama', 'password' => $pw, 'role' => 'admin', 'no_hp' => '081200000001', 'is_aktif' => true
        ]);
        $dokter = User::firstOrCreate(['email' => 'dokter@pawpet.com'], [
            'nama' => 'Drh. Setiawan', 'password' => $pw, 'role' => 'dokter', 'no_hp' => '081200000002', 'is_aktif' => true
        ]);
        $kasir = User::firstOrCreate(['email' => 'kasir@pawpet.com'], [
            'nama' => 'Mbak Kasir', 'password' => $pw, 'role' => 'kasir', 'no_hp' => '081200000003', 'is_aktif' => true
        ]);
        $owner1 = User::firstOrCreate(['email' => 'owner1@gmail.com'], [
            'nama' => 'Budi Santoso', 'password' => $pw, 'role' => 'pemilik', 'no_hp' => '081200000004', 'is_aktif' => true, 'alamat' => 'Jl. Merdeka No 1'
        ]);
        $owner2 = User::firstOrCreate(['email' => 'owner2@gmail.com'], [
            'nama' => 'Siti Aminah', 'password' => $pw, 'role' => 'pemilik', 'no_hp' => '081200000005', 'is_aktif' => true, 'alamat' => 'Jl. Sudirman No 2'
        ]);

        // 2. Seed Kamar
        $kamarA = Room::firstOrCreate(['nama_kamar' => 'Kandang A1'], ['tipe' => 'kecil', 'harga_per_hari' => 50000, 'kapasitas' => 1, 'status' => 'tersedia']);
        $kamarB = Room::firstOrCreate(['nama_kamar' => 'Kandang B1'], ['tipe' => 'sedang', 'harga_per_hari' => 75000, 'kapasitas' => 1, 'status' => 'tersedia']);
        $kamarC = Room::firstOrCreate(['nama_kamar' => 'Kamar VVIP'], ['tipe' => 'besar', 'harga_per_hari' => 150000, 'kapasitas' => 2, 'status' => 'tersedia']);

        // 3. Seed Supplier
        $sup1 = Supplier::firstOrCreate(['nama_supplier' => 'PT Whiskas Indo'], ['kontak' => '081234567890', 'email' => 'info@whiskas.co.id', 'alamat' => 'Jakarta']);
        $sup2 = Supplier::firstOrCreate(['nama_supplier' => 'Royal Canin Dist'], ['kontak' => '081298765432', 'email' => 'sales@rc.co.id', 'alamat' => 'Bandung']);

        // 4. Seed Product & Batches & Stock
        $prod1 = Product::firstOrCreate(['nama_barang' => 'Whiskas Tuna 1kg'], [
            'kategori' => 'Makanan Kucing', 'harga' => 65000, 'stok' => 20, 'satuan' => 'Pcs', 'is_aktif' => true
        ]);
        $batch1 = ProductBatch::firstOrCreate(['no_batch' => 'BATCH-W-01'], [
            'id_barang' => $prod1->id, 'id_supplier' => $sup1->id, 'harga_beli' => 50000, 'jumlah_masuk' => 20, 'sisa_stok' => 20, 
            'tanggal_masuk' => now()->subDays(10), 'tanggal_expired' => now()->addYears(1)
        ]);
        StockCard::firstOrCreate(['referensi' => 'Modal Awal W-01'], [
            'id_barang' => $prod1->id, 'id_batch' => $batch1->id, 'tanggal' => now()->subDays(10), 'jenis_mutasi' => 'masuk', 
            'jumlah' => 20, 'saldo' => 20, 'harga_satuan' => 50000
        ]);

        $prod2 = Product::firstOrCreate(['nama_barang' => 'Royal Canin Kitten 2kg'], [
            'kategori' => 'Makanan Kucing', 'harga' => 250000, 'stok' => 10, 'satuan' => 'Pcs', 'is_aktif' => true
        ]);
        $batch2 = ProductBatch::firstOrCreate(['no_batch' => 'BATCH-RC-01'], [
            'id_barang' => $prod2->id, 'id_supplier' => $sup2->id, 'harga_beli' => 200000, 'jumlah_masuk' => 10, 'sisa_stok' => 10, 
            'tanggal_masuk' => now()->subDays(5), 'tanggal_expired' => now()->addYears(1)
        ]);
        StockCard::firstOrCreate(['referensi' => 'Modal Awal RC-01'], [
            'id_barang' => $prod2->id, 'id_batch' => $batch2->id, 'tanggal' => now()->subDays(5), 'jenis_mutasi' => 'masuk', 
            'jumlah' => 10, 'saldo' => 10, 'harga_satuan' => 200000
        ]);

        // 5. Seed Services
        $srv1 = Service::firstOrCreate(['nama_layanan' => 'Konsultasi Umum'], [
            'jenis_layanan' => 'konsultasi', 'harga' => 100000, 'durasi_menit' => 30, 'id_dokter' => $dokter->id, 'is_aktif' => true
        ]);
        $srv2 = Service::firstOrCreate(['nama_layanan' => 'Vaksinasi Rabies'], [
            'jenis_layanan' => 'vaksinasi', 'harga' => 150000, 'durasi_menit' => 15, 'id_dokter' => $dokter->id, 'is_aktif' => true
        ]);
        $srv3 = Service::firstOrCreate(['nama_layanan' => 'Grooming Basic'], [
            'jenis_layanan' => 'grooming', 'harga' => 80000, 'durasi_menit' => 60, 'id_dokter' => null, 'is_aktif' => true
        ]);

        // 6. Seed Pets
        $pet1 = Pet::firstOrCreate(['nama_hewan' => 'Milo'], [
            'id_pemilik' => $owner1->id, 'jenis' => 'Kucing', 'ras' => 'Persia', 'umur' => '2 Tahun', 'berat' => 4.5
        ]);
        $pet2 = Pet::firstOrCreate(['nama_hewan' => 'Belly'], [
            'id_pemilik' => $owner1->id, 'jenis' => 'Anjing', 'ras' => 'Pomeranian', 'umur' => '1 Tahun', 'berat' => 3.2
        ]);
        $pet3 = Pet::firstOrCreate(['nama_hewan' => 'Oreo'], [
            'id_pemilik' => $owner2->id, 'jenis' => 'Kucing', 'ras' => 'Domestik', 'umur' => '3 Bulan', 'berat' => 1.5
        ]);

        // 7. Seed Boardings
        Boarding::firstOrCreate(['id_hewan' => $pet2->id, 'id_kamar' => $kamarB->id], [
            'tanggal_masuk' => now()->subDays(2), 'tanggal_rencana_keluar' => now()->addDays(3),
            'status' => 'aktif', 'total_biaya' => $kamarB->harga_per_hari * 5, 'catatan_titip' => 'Bawa makanan sendiri'
        ]);
        $kamarB->update(['status' => 'terisi']);

        // 8. Seed Medical Records
        MedicalRecord::firstOrCreate(['id_hewan' => $pet1->id], [
            'id_dokter' => $dokter->id, 'diagnosa' => 'Flu Ringan', 'tindakan' => 'Suntik Vitamin', 
            'resep' => 'Vitamin 1x sehari', 'berat_saat_itu' => 4.5, 'tanggal' => now()->subDays(1)
        ]);

        // 9. Seed Transactions
        $trx = Transaction::firstOrCreate(['kode_transaksi' => 'TRX-'.date('Ymd').'-0001'], [
            'id_pelanggan' => $owner2->id, 'id_kasir' => $kasir->id, 'jenis' => 'campuran',
            'subtotal' => 215000, 'diskon' => 15000, 'total' => 200000, 'jumlah_bayar' => 200000, 'kembalian' => 0,
            'metode_bayar' => 'cash', 'status' => 'lunas', 'tanggal' => now()
        ]);
        
        // Trx Product (Whiskas 1kg x1 = 65000)
        TransactionProduct::firstOrCreate(['id_transaksi' => $trx->id, 'id_barang' => $prod1->id], [
            'jumlah' => 1, 'harga_satuan' => 65000, 'subtotal' => 65000
        ]);
        // Kurangi stok barang & batch
        if($prod1->stok == 20) {
            $prod1->decrement('stok', 1);
            $batch1->decrement('sisa_stok', 1);
            StockCard::create([
                'id_barang' => $prod1->id, 'id_batch' => $batch1->id, 'tanggal' => now(), 'jenis_mutasi' => 'keluar', 
                'jumlah' => 1, 'saldo' => 19, 'harga_satuan' => 0, 'referensi' => $trx->kode_transaksi
            ]);
        }

        // Trx Service (Vaksinasi x1 = 150000)
        TransactionService::firstOrCreate(['id_transaksi' => $trx->id, 'id_layanan' => $srv2->id], [
            'jumlah' => 1, 'harga_satuan' => 150000, 'subtotal' => 150000
        ]);

        echo "Seeding completed successfully!\n";
    }
}
