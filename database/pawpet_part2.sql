-- phpMyAdmin SQL Dump
-- version 5.2.2deb1+deb13u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 24 Apr 2026 pada 18.12
-- Versi server: 11.8.6-MariaDB-0+deb13u1 from Debian-log
-- Versi PHP: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pawpet_test`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `satuan` varchar(20) NOT NULL DEFAULT 'pcs',
  `deskripsi` text DEFAULT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Produk / perlengkapan hewan yang dijual';

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_batch`
--

CREATE TABLE `barang_batch` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_barang` bigint(20) UNSIGNED NOT NULL,
  `id_supplier` bigint(20) UNSIGNED DEFAULT NULL,
  `no_batch` varchar(50) DEFAULT NULL,
  `harga_beli` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jumlah_masuk` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sisa_stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tanggal_masuk` date NOT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Batch pengadaan barang per kiriman supplier';

-- --------------------------------------------------------

--
-- Struktur dari tabel `hewan`
--

CREATE TABLE `hewan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pemilik` bigint(20) UNSIGNED NOT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `ras` varchar(100) DEFAULT NULL,
  `umur` varchar(30) DEFAULT NULL,
  `berat` decimal(5,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Hewan peliharaan milik pemilik (users)';

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_dokter`
--

CREATE TABLE `jadwal_dokter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_dokter` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `kuota` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Jadwal praktik mingguan dokter';

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kamar` varchar(50) NOT NULL,
  `tipe` enum('kecil','sedang','besar') NOT NULL DEFAULT 'sedang',
  `harga_per_hari` decimal(12,2) NOT NULL DEFAULT 0.00,
  `kapasitas` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `status` enum('tersedia','terisi','maintenance') NOT NULL DEFAULT 'tersedia',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Kamar / kandang untuk layanan penitipan hewan';

-- --------------------------------------------------------

--
-- Struktur dari tabel `kartu_stok`
--

CREATE TABLE `kartu_stok` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_batch` bigint(20) UNSIGNED NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `jenis_mutasi` enum('masuk','keluar','adjustment','retur','expired') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `saldo` int(10) UNSIGNED NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `referensi` varchar(100) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Kartu stok per batch; barang didapat via barang_batch.id_barang';

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_layanan` varchar(150) NOT NULL,
  `jenis_layanan` enum('konsultasi','vaksinasi','grooming','operasi','penitipan','lainnya') NOT NULL DEFAULT 'lainnya',
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `durasi_menit` int(10) UNSIGNED DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_dokter` bigint(20) UNSIGNED DEFAULT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Daftar layanan: konsultasi, grooming, vaksinasi, dll';

-- --------------------------------------------------------

--
-- Struktur dari tabel `penitipan`
--

CREATE TABLE `penitipan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_hewan` bigint(20) UNSIGNED NOT NULL,
  `id_kamar` bigint(20) UNSIGNED NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_rencana_keluar` date NOT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `catatan_titip` text DEFAULT NULL,
  `catatan_jemput` text DEFAULT NULL,
  `status` enum('aktif','selesai','batal') NOT NULL DEFAULT 'aktif',
  `total_biaya` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Penitipan hewan; pemilik didapat via hewan.id_pemilik';

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_hewan` bigint(20) UNSIGNED NOT NULL,
  `id_dokter` bigint(20) UNSIGNED DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `resep` text DEFAULT NULL,
  `berat_saat_itu` decimal(5,2) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Rekam medis; id_dokter disimpan tanpa FK (cari via users jika perlu join)';

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_supplier` varchar(150) NOT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Data supplier / pemasok barang';

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pelanggan` bigint(20) UNSIGNED NOT NULL,
  `id_kasir` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_transaksi` varchar(30) NOT NULL,
  `jenis` enum('layanan','barang','campuran') NOT NULL DEFAULT 'campuran',
  `total` decimal(14,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` enum('cash','transfer','ewallet') NOT NULL DEFAULT 'cash',
  `status` enum('pending','lunas','batal') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Header transaksi; id_kasir disimpan tanpa FK constraint';

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_barang`
--

CREATE TABLE `transaksi_barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `id_barang` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(14,2) GENERATED ALWAYS AS (`jumlah` * `harga_satuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Item barang per transaksi';

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_layanan`
--

CREATE TABLE `transaksi_layanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `id_layanan` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `harga_satuan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(14,2) GENERATED ALWAYS AS (`jumlah` * `harga_satuan`) STORED,
  `catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Item layanan per transaksi';

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dokter','pemilik','kasir') NOT NULL DEFAULT 'pemilik',
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci COMMENT='Semua pengguna: admin, dokter, pemilik hewan, kasir';

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barang_batch`
--
ALTER TABLE `barang_batch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_batch_barang` (`id_barang`),
  ADD KEY `fk_batch_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hewan_pemilik` (`id_pemilik`);

--
-- Indeks untuk tabel `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jadwal_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kartu_stok`
--
ALTER TABLE `kartu_stok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ks_batch` (`id_batch`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_layanan_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_titip_hewan` (`id_hewan`),
  ADD KEY `fk_titip_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rekmed_hewan` (`id_hewan`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_trx_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `transaksi_barang`
--
ALTER TABLE `transaksi_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tb_transaksi` (`id_transaksi`),
  ADD KEY `fk_tb_barang` (`id_barang`);

--
-- Indeks untuk tabel `transaksi_layanan`
--
ALTER TABLE `transaksi_layanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tl_transaksi` (`id_transaksi`),
  ADD KEY `fk_tl_layanan` (`id_layanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_users_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `barang_batch`
--
ALTER TABLE `barang_batch`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kartu_stok`
--
ALTER TABLE `kartu_stok`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_barang`
--
ALTER TABLE `transaksi_barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_layanan`
--
ALTER TABLE `transaksi_layanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang_batch`
--
ALTER TABLE `barang_batch`
  ADD CONSTRAINT `fk_batch_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_batch_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `fk_hewan_pemilik` FOREIGN KEY (`id_pemilik`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_dokter`
--
ALTER TABLE `jadwal_dokter`
  ADD CONSTRAINT `fk_jadwal_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kartu_stok`
--
ALTER TABLE `kartu_stok`
  ADD CONSTRAINT `fk_ks_batch` FOREIGN KEY (`id_batch`) REFERENCES `barang_batch` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD CONSTRAINT `fk_layanan_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD CONSTRAINT `fk_titip_hewan` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id`),
  ADD CONSTRAINT `fk_titip_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id`);

--
-- Ketidakleluasaan untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `fk_rekmed_hewan` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_trx_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi_barang`
--
ALTER TABLE `transaksi_barang`
  ADD CONSTRAINT `fk_tb_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`),
  ADD CONSTRAINT `fk_tb_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_layanan`
--
ALTER TABLE `transaksi_layanan`
  ADD CONSTRAINT `fk_tl_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id`),
  ADD CONSTRAINT `fk_tl_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
