-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 24, 2026 at 02:31 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_lab_keperawatan`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat_lab`
--

DROP TABLE IF EXISTS `alat_lab`;
CREATE TABLE IF NOT EXISTS `alat_lab` (
  `id_alat` int(11) NOT NULL AUTO_INCREMENT,
  `kode_alat` varchar(30) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `jumlah_baik` int(11) DEFAULT 0,
  `jumlah_rusak` int(11) DEFAULT 0,
  `lokasi_penyimpanan` varchar(50) DEFAULT 'Lab Keperawatan',
  PRIMARY KEY (`id_alat`),
  UNIQUE KEY `kode_alat` (`kode_alat`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alat_lab`
--

INSERT INTO `alat_lab` (`id_alat`, `kode_alat`, `nama_alat`, `kategori`, `jumlah_baik`, `jumlah_rusak`, `lokasi_penyimpanan`) VALUES
(1, '001', 'Pantum Bayi', 'Phantom / Manekin', 2, 0, 'Lab Keperawatan');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pinjam` varchar(20) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `nis_nip` varchar(30) NOT NULL,
  `kelas_jabatan` varchar(50) NOT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_pinjam` datetime DEFAULT current_timestamp(),
  `tgl_kembali` datetime DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam',
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_peminjaman`),
  UNIQUE KEY `kode_pinjam` (`kode_pinjam`),
  KEY `id_alat` (`id_alat`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_inventaris`
--

DROP TABLE IF EXISTS `tb_inventaris`;
CREATE TABLE IF NOT EXISTS `tb_inventaris` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barcode` varchar(50) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `kategori` varchar(100) DEFAULT 'Lainnya',
  `jenis` enum('Alat','Bahan') NOT NULL DEFAULT 'Alat',
  `stok` int(11) NOT NULL DEFAULT 0,
  `satuan` varchar(30) NOT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL DEFAULT 'Baik',
  `lokasi_penyimpanan` varchar(100) DEFAULT 'Lemari Utama Lab',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_barang`),
  UNIQUE KEY `uk_kode_barcode` (`kode_barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_inventaris`
--

INSERT INTO `tb_inventaris` (`id_barang`, `kode_barcode`, `nama_barang`, `kategori`, `jenis`, `stok`, `satuan`, `kondisi`, `lokasi_penyimpanan`, `created_at`, `updated_at`) VALUES
(15, 'ALT-001', 'Alat Semprot Disinfektan', 'Alat Medis / Instrumen', 'Alat', 2, 'Buah', 'Baik', 'Lab Keperawatan', '2026-10-13 07:33:50', '2026-10-24 14:22:02'),
(16, 'ALT-002', 'APD Lengkap', 'Alat Medis / Instrumen', 'Alat', 10, 'Set', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:38:35', '2026-10-13 07:39:05'),
(17, 'ALT-003', 'Aprone', 'Alat Medis / Instrumen', 'Alat', 14, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:39:42', '2026-10-13 07:39:42'),
(18, 'ALT-004', 'Baju Pasien', 'Alat Medis / Instrumen', 'Alat', 6, 'Pasang', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:40:28', '2026-10-13 07:40:28'),
(19, 'ALT-005', 'Bak Instrumen', 'Alat Medis / Instrumen', 'Alat', 4, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:41:25', '2026-10-13 07:41:25'),
(20, 'ALT-006', 'Bantal Pasien', 'Alat Medis / Instrumen', 'Alat', 6, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:42:01', '2026-10-23 22:45:16'),
(21, 'ALT-007', 'Baskom Besar Aluminium', 'Alat Medis / Instrumen', 'Alat', 2, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:42:42', '2026-10-13 07:42:42'),
(22, 'ALT-008', 'Baskom Besar Plastik', 'Alat Medis / Instrumen', 'Alat', 4, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:43:42', '2026-10-13 07:43:42'),
(23, 'ALT-009', 'Baskom Kecil Aluinium', 'Alat Medis / Instrumen', 'Alat', 3, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:45:11', '2026-10-23 22:45:19'),
(24, 'ALT-010', 'Baskom Kecil Plastik', 'Alat Medis / Instrumen', 'Alat', 12, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:46:07', '2026-10-13 07:46:07'),
(25, 'ALT-011', 'Baskom Sedang Aluminium', 'Alat Medis / Instrumen', 'Alat', 16, 'Buah', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-13 07:46:44', '2026-10-13 07:46:44'),
(27, 'BHP-20261024-444', 'Alkohol Swab', 'Bahan Habis Pakai Medis', 'Bahan', 11, 'Box', 'Baik', 'Ruang Penyimpanan Keperawatan', '2026-10-24 14:11:48', '2026-10-24 14:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jadwal_praktikum`
--

DROP TABLE IF EXISTS `tb_jadwal_praktikum`;
CREATE TABLE IF NOT EXISTS `tb_jadwal_praktikum` (
  `id_jadwal` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(150) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `tgl_praktikum` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `materi_praktikum` varchar(255) NOT NULL,
  `status_persetujuan` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_jadwal`),
  KEY `fk_jadwal_guru` (`id_guru`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_peminjaman`
--

DROP TABLE IF EXISTS `tb_peminjaman`;
CREATE TABLE IF NOT EXISTS `tb_peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_pinjam` int(11) NOT NULL,
  `tgl_pinjam` datetime NOT NULL DEFAULT current_timestamp(),
  `tgl_kembali_rencana` datetime NOT NULL,
  `tgl_kembali_realisasi` datetime DEFAULT NULL,
  `status` enum('Pending','Disetujui','Ditolak','Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Pending',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_peminjaman`),
  UNIQUE KEY `uk_kode_peminjaman` (`kode_peminjaman`),
  KEY `fk_pinjam_user` (`id_user`),
  KEY `fk_pinjam_barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_peminjaman`
--

INSERT INTO `tb_peminjaman` (`id_peminjaman`, `kode_peminjaman`, `id_user`, `id_barang`, `jumlah_pinjam`, `tgl_pinjam`, `tgl_kembali_rencana`, `tgl_kembali_realisasi`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(14, 'PJM-20261022-001', 1, 15, 1, '2026-10-22 00:00:00', '2026-10-23 00:00:00', '2026-10-23 14:45:22', 'Dikembalikan', '', '2026-10-22 01:26:13', '2026-10-23 22:45:22'),
(15, 'PJM-20261022-002', 3, 23, 1, '2026-10-22 00:00:00', '2026-10-23 00:00:00', '2026-10-23 14:45:19', 'Dikembalikan', '', '2026-10-22 02:00:11', '2026-10-23 22:45:19'),
(16, 'PJM-20261022-003', 4, 20, 1, '2026-10-22 00:00:00', '2026-10-23 00:00:00', '2026-10-23 14:45:16', 'Dikembalikan', '', '2026-10-22 02:04:19', '2026-10-23 22:45:16'),
(17, 'PJM-20261024-001', 1, 16, 1, '2026-10-24 00:00:00', '2026-10-25 00:00:00', NULL, 'Pending', '', '2026-10-24 13:09:59', '2026-10-24 21:09:59'),
(18, 'PJM-20261024-002', 1, 25, 1, '2026-10-24 00:00:00', '2026-10-25 00:00:00', NULL, 'Pending', '', '2026-10-24 13:10:06', '2026-10-24 21:10:06'),
(19, 'PJM-20261024-003', 1, 16, 1, '2026-10-24 00:00:00', '2026-10-25 00:00:00', NULL, 'Pending', '', '2026-10-24 13:10:21', '2026-10-24 21:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengadaan`
--

DROP TABLE IF EXISTS `tb_pengadaan`;
CREATE TABLE IF NOT EXISTS `tb_pengadaan` (
  `id_pengadaan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_item` varchar(150) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(30) NOT NULL,
  `estimasi_harga` decimal(12,2) NOT NULL,
  `alasan_pengadaan` text NOT NULL,
  `id_pengaju` int(11) NOT NULL,
  `status_approval` enum('Pending','Disetujui','Ditolak') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengadaan`),
  KEY `fk_pengadaan_user` (`id_pengaju`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pengadaan`
--

INSERT INTO `tb_pengadaan` (`id_pengadaan`, `nama_item`, `jumlah`, `satuan`, `estimasi_harga`, `alasan_pengadaan`, `id_pengaju`, `status_approval`, `created_at`) VALUES
(1, 'Masker Medis', 4, 'Box', '60000.00', 'Kelengkapan USK', 1, 'Disetujui', '2026-09-19 14:27:56'),
(2, 'Masker Medis', 1, 'Box', '36.00', '', 2, 'Disetujui', '2026-10-04 12:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE IF NOT EXISTS `tb_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nip_nik_user` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `role` enum('admin','laboran','guru','siswa','kepsek') NOT NULL DEFAULT 'siswa',
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `nip_nik_user`, `username`, `password`, `nama_lengkap`, `email`, `foto`, `role`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '0', 'admin_lab', '$2y$10$yn7dhcTktvzSDE5qiMhShulfXFr1l4yW5X7Ynj2eIl8RIcMJ4bFyy', 'Administrator Lab', 'admin@smkkesehatan.sch.id', 'user_1_1790303357.png', 'admin', 1, '2026-09-19 14:48:30', '2026-09-25 10:29:17'),
(2, '2022.09.100', 'laboran_siti', '$2y$10$f8W/l.EmUDZ541egpN7T3egrv63wdhQ6a8U4b0rU6UPKqPyj3pyce', 'Putu Ayu Masriani, S.Kep.', 'ayumasriani@smkkesehatan.sch.id', 'user_2_1790304104.png', 'laboran', 1, '2026-09-19 14:48:30', '2026-10-02 10:22:41'),
(3, '0', 'guru_dewi', '$2y$10$zP.1RUmOS1IP2p4SijSeYeOBJfeGvryucU4AOMDfyNFKfiX373gmy', 'Nrs. Dewi Lestari, S.Kep', 'dewi.guru@smkkesehatan.sch.id', NULL, 'guru', 1, '2026-09-19 14:48:30', '2026-09-19 16:50:57'),
(4, '0', 'siswa_budi', '$2y$10$v2iA4Cm6.EO855SwhS/Va.c3IJLyW0xEQhsgV6H8leFt/YDBld3ay', 'Budi Santoso', 'budi.siswa@smkkesehatan.sch.id', NULL, 'siswa', 1, '2026-09-19 14:48:30', '2026-09-19 16:51:05'),
(5, '0', 'kepsek_ahmad', '$2y$10$sv/EgMU2Z45MZ2Oo6tZKK.Le6lda8HeIgnv3wRbndEgkfEhq6wtCa', 'Dr. H. Ahmad, M.Pd', 'kepsek@smkkesehatan.sch.id', NULL, 'kepsek', 1, '2026-09-19 14:48:30', '2026-09-19 16:50:51');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_jadwal_praktikum`
--
ALTER TABLE `tb_jadwal_praktikum`
  ADD CONSTRAINT `fk_jadwal_guru` FOREIGN KEY (`id_guru`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD CONSTRAINT `fk_pinjam_barang` FOREIGN KEY (`id_barang`) REFERENCES `tb_inventaris` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pinjam_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pengadaan`
--
ALTER TABLE `tb_pengadaan`
  ADD CONSTRAINT `fk_pengadaan_user` FOREIGN KEY (`id_pengaju`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
