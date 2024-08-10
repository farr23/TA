-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Agu 2024 pada 13.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cqmalang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_kartu_stok_bahan`
--

CREATE TABLE `riwayat_kartu_stok_bahan` (
  `id_riwayat` int(11) NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `riwayat_kartu_stok_bahan`
--

INSERT INTO `riwayat_kartu_stok_bahan` (`id_riwayat`, `id_bahan`, `stok`) VALUES
(1, 'B-002', 16),
(2, '', 10),
(3, 'B-001', 20027),
(4, 'B-003', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_kartu_stok_produk`
--

CREATE TABLE `riwayat_kartu_stok_produk` (
  `id_riwayat` int(11) NOT NULL,
  `id_produk` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_bahan`
--

CREATE TABLE `tb_bahan` (
  `id_bahan` varchar(100) NOT NULL,
  `nm_bahan` varchar(100) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `stok_min` int(11) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_bahan`
--

INSERT INTO `tb_bahan` (`id_bahan`, `nm_bahan`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `stok_min`, `gambar`, `status`) VALUES
('B-001', 'teh', 150000, 170000, 12, 'g', 10, 'default.png', 1),
('B-002', 'kopi', 200000, 250000, 13, 'g', 10, 'default.png', 1),
('B-003', 'susu', 20000, 25000, 12, 'ml', 0, 'default.png', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_customer`
--

CREATE TABLE `tb_customer` (
  `id_customer` int(11) NOT NULL,
  `nm_customer` varchar(256) NOT NULL,
  `telp` varchar(25) NOT NULL,
  `alamat` text NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_customer`
--

INSERT INTO `tb_customer` (`id_customer`, `nm_customer`, `telp`, `alamat`, `status`) VALUES
(3, 'Arfi', '0821367564565', 'malang\r\n', 1),
(4, 'tuki', '0821367564565', 'malang', 1),
(5, 'Rafli', '0821367564565', 'malang', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail_pesanan` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `id_produk` varchar(100) NOT NULL,
  `nm_produk` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `jml_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_detail_pesanan`
--

INSERT INTO `tb_detail_pesanan` (`id_detail_pesanan`, `id_pesanan`, `tanggal_pesanan`, `id_produk`, `nm_produk`, `jumlah`, `harga_jual`, `jml_harga`) VALUES
(1, 2147483647, '2024-08-08', 'P-001', 'americano', 1, 18000, 18000),
(2, 2147483647, '2024-08-08', 'P-001', 'americano', 1, 18000, 18000),
(3, 2147483647, '2024-08-08', 'P-003', 'latte', 2, 18000, 36000),
(4, 2147483647, '2024-08-08', 'P-003', 'latte', 1, 18000, 18000),
(5, 2147483647, '2024-08-08', 'P-003', 'latte', 1, 18000, 18000),
(6, 0, '2024-08-08', 'P-001', 'americano', 1, 18000, 18000),
(7, 0, '2024-08-09', 'P-003', 'latte', 1, 18000, 18000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nm_kategori` varchar(256) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nm_kategori`, `status`) VALUES
(1, 'Coffee', 1),
(2, 'Milk Base', 1),
(3, 'Cincau', 1),
(4, 'Frappe', 1),
(5, 'Refreshing', 1),
(6, 'Yakult', 1),
(7, 'Others', 1),
(8, 'Snack', 1),
(9, 'Food', 1),
(10, 'bun', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penerimaan_bahan`
--

CREATE TABLE `tb_penerimaan_bahan` (
  `id_penerimaan` int(11) NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `jumlah_masuk` decimal(10,2) NOT NULL,
  `tgl_penerimaan` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_penerimaan_bahan`
--

INSERT INTO `tb_penerimaan_bahan` (`id_penerimaan`, `id_bahan`, `jumlah_masuk`, `tgl_penerimaan`, `keterangan`) VALUES
(1, '0', 2.00, '2024-08-09', ''),
(2, '0', 10.00, '2024-08-09', ''),
(3, '0', 12.00, '2024-08-09', 'passs'),
(4, '0', 12.00, '2024-08-09', 'pas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` varchar(20) NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `nm_customer` varchar(256) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jml_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `status` enum('Pending','Diproduksi','Selesai') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pesanan`
--

INSERT INTO `tb_pesanan` (`id_pesanan`, `tanggal_pesanan`, `nm_customer`, `total`, `keterangan`, `jml_bayar`, `kembalian`, `status`) VALUES
('202408082489', '2024-08-08', 'ardi', 90000, '', 90000, 0, 'Pending'),
('2147483647', '2024-08-08', 'tuki', 36000, '', 40000, 4000, 'Pending'),
('PS4749', '2024-08-09', 'pani', 54000, '', 60000, 6000, 'Pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id_produk` varchar(100) NOT NULL,
  `nm_produk` varchar(100) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `deskripsi` varchar(256) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `harga_modal` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `stok_min` int(11) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_produk`
--

INSERT INTO `tb_produk` (`id_produk`, `nm_produk`, `id_kategori`, `deskripsi`, `harga_jual`, `harga_modal`, `stok`, `stok_min`, `gambar`, `status`) VALUES
('P-001', 'americano', 1, 'kopi tanpa ampas', 18000, 14000, -4, 12, 'default.png', 1),
('P-002', 'Espresso', 1, 'Kopi Espresso', 14000, 8000, 0, 5, 'default.png', 1),
('P-003', 'latte', 1, 'coffe dengan foam tebal', 18000, 15000, -5, 2, 'default.png', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produksi`
--

CREATE TABLE `tb_produksi` (
  `id_produksi` int(11) NOT NULL,
  `no_produksi` varchar(20) NOT NULL,
  `tgl_produksi` date NOT NULL,
  `id_produk` varchar(100) NOT NULL,
  `total_produksi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_produksi`
--

INSERT INTO `tb_produksi` (`id_produksi`, `no_produksi`, `tgl_produksi`, `id_produk`, `total_produksi`) VALUES
(5, 'PROD0001', '2024-08-05', 'P-001', 0),
(6, 'PROD0006', '2024-08-05', 'P-001', 0),
(7, 'PROD0007', '2024-08-05', 'P-001', 0),
(8, 'PROD0008', '2024-08-06', 'P-001', 0),
(9, 'PROD0009', '2024-08-06', 'P-001', 0),
(10, 'PROD0010', '2024-08-06', 'P-002', 2),
(11, 'PROD0011', '2024-08-06', 'P-001', 0),
(12, 'PROD0012', '2024-08-06', 'P-002', 12),
(13, 'PROD0013', '2024-08-06', 'P-001', 0),
(14, 'PROD0014', '2024-08-07', 'P-001', 9),
(15, 'PROD0015', '2024-08-08', 'P-001', 20),
(16, 'PROD0016', '2024-08-08', 'P-001', 5),
(17, 'PROD0017', '2024-08-08', 'P-002', 6),
(18, 'PROD0018', '2024-08-08', 'P-002', 5),
(19, 'PROD0019', '2024-08-08', 'P-002', 10),
(20, 'PROD0020', '2024-08-09', 'P-002', 2),
(21, 'PROD0021', '2024-08-09', 'P-003', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produksi_detail`
--

CREATE TABLE `tb_produksi_detail` (
  `id_produksi_detail` int(11) NOT NULL,
  `no_produksi` varchar(20) NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_produksi_detail`
--

INSERT INTO `tb_produksi_detail` (`id_produksi_detail`, `no_produksi`, `id_bahan`, `jumlah`) VALUES
(2, 'PROD0001', 'B-001', 4000.00),
(3, 'PROD0001', 'B-002', 4000.00),
(4, 'PROD0006', 'B-001', 2000.00),
(5, 'PROD0006', 'B-002', 2000.00),
(6, 'PROD0007', 'B-001', 2000.00),
(7, 'PROD0007', 'B-002', 2000.00),
(8, 'PROD0008', 'B-001', 5000.00),
(9, 'PROD0008', 'B-002', 5000.00),
(10, 'PROD0009', 'B-001', 4000.00),
(11, 'PROD0009', 'B-002', 4000.00),
(12, 'PROD0010', 'B-002', 30.00),
(13, 'PROD0011', 'B-001', 5000.00),
(14, 'PROD0011', 'B-002', 5000.00),
(15, 'PROD0012', 'B-002', 120.00),
(16, 'PROD0013', 'B-001', 2000.00),
(17, 'PROD0013', 'B-002', 2000.00),
(18, 'PROD0014', 'B-001', 10000.00),
(19, 'PROD0014', 'B-002', 10000.00),
(20, 'PROD0015', 'B-001', 20000.00),
(21, 'PROD0015', 'B-002', 20000.00),
(22, 'PROD0016', 'B-001', 5000.00),
(23, 'PROD0016', 'B-002', 5000.00),
(24, 'PROD0017', 'B-002', 60.00),
(25, 'PROD0018', 'B-002', 50.00),
(26, 'PROD0019', 'B-002', 100.00),
(27, 'PROD0020', 'B-002', 20.00),
(28, 'PROD0021', 'B-003', 4.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_resep`
--

CREATE TABLE `tb_resep` (
  `id_resep` int(11) NOT NULL,
  `id_produk` varchar(100) NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_resep`
--

INSERT INTO `tb_resep` (`id_resep`, `id_produk`, `id_bahan`, `jumlah`, `status`) VALUES
(1, 'P-001', 'B-001', 1000, 1),
(2, 'P-001', 'B-002', 1000, 1),
(3, 'P-002', 'B-002', 10, 1),
(4, 'P-003', 'B-003', 2, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_riwayat_harga_jual`
--

CREATE TABLE `tb_riwayat_harga_jual` (
  `id_riwayat_hj` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_produk` varchar(100) NOT NULL,
  `harga_jual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_stok_opname`
--

CREATE TABLE `tb_stok_opname` (
  `id_opname` int(11) NOT NULL,
  `tgl_opname` date NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `stok_sistem` int(11) NOT NULL,
  `stok_fisik` int(11) NOT NULL,
  `selisih` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_stok_opname`
--

INSERT INTO `tb_stok_opname` (`id_opname`, `tgl_opname`, `id_bahan`, `stok_sistem`, `stok_fisik`, `selisih`, `keterangan`) VALUES
(1, '2024-08-06', 'B-002', 10000, 10000, 0, 'pass'),
(2, '2024-08-06', 'B-001', 982001, 982000, -1, ''),
(3, '2024-08-06', 'B-003', 2, 5, 3, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `id_supplier` int(11) NOT NULL,
  `nm_supplier` varchar(256) NOT NULL,
  `telp` varchar(25) NOT NULL,
  `deskripsi` varchar(256) NOT NULL,
  `alamat` text NOT NULL,
  `status` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_supplier`
--

INSERT INTO `tb_supplier` (`id_supplier`, `nm_supplier`, `telp`, `deskripsi`, `alamat`, `status`) VALUES
(2, 'ardi', '082139786751', 'distributor kacang', 'malang', '1'),
(3, 'ardianti', '0821367564565', 'kacang polong', 'malang', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi_bahan`
--

CREATE TABLE `tb_transaksi_bahan` (
  `id_transaksi` int(11) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jenis_transaksi` enum('Masuk','Keluar') NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `nm_bahan` varchar(100) NOT NULL,
  `jumlah_awal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `jumlah_masuk` decimal(10,2) DEFAULT 0.00,
  `jumlah_keluar` decimal(10,2) DEFAULT 0.00,
  `jumlah_akhir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_transaksi_bahan`
--

INSERT INTO `tb_transaksi_bahan` (`id_transaksi`, `tgl_transaksi`, `jenis_transaksi`, `id_bahan`, `nm_bahan`, `jumlah_awal`, `jumlah_masuk`, `jumlah_keluar`, `jumlah_akhir`, `keterangan`) VALUES
(1, '2024-08-09', 'Keluar', '0', 'teh', 945003.00, 0.00, 2.00, 945001.00, ''),
(2, '2024-08-09', 'Masuk', '0', 'teh', 945001.00, 12.00, 0.00, 945013.00, 'aman'),
(3, '2024-08-09', 'Masuk', '0', 'teh', 945015.00, 945014.00, 0.00, 1890029.00, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `level` int(1) NOT NULL COMMENT '1-administrator\r\n2-manager\r\n3-operator',
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `username`, `fullname`, `password`, `alamat`, `level`, `foto`) VALUES
(5, 'admin1', 'administrator', '$2y$10$.9LNUVofT8GJuo/6B0wgM.zNZCmaEQUiaV7pFRfQNBzgu3sjDRWga', 'malang', 1, '493-cq.png'),
(6, 'kasir', 'ahmad', '$2y$10$p179/ar470Ni0l9mc8JdSeSYmxEa8LsjEyP34aWaQu6i9YsyYCuJq', 'magelang', 3, 'default.png'),
(7, 'manager', 'bukina', '$2y$10$qLjM75PtwNo6I0H/rhFL8ezE98d8HEfMtjDFqRKY0pptj87vmokOi', 'malang', 2, 'default.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_pembelian`
--

CREATE TABLE `trans_pembelian` (
  `no_transbeli` varchar(20) NOT NULL,
  `tgl_transbeli` date NOT NULL,
  `nm_supplier` varchar(256) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_pembelian`
--

INSERT INTO `trans_pembelian` (`no_transbeli`, `tgl_transbeli`, `nm_supplier`, `total`, `keterangan`) VALUES
('BL0001', '2024-08-05', 'Toeman Merjo', 300000, ''),
('BL0002', '2024-08-05', 'Toeman Merjo', 400000, ''),
('BL0003', '2024-08-05', '', 150000, ''),
('BL0004', '2024-08-06', 'ardi', 350000, ''),
('BL0005', '2024-08-06', 'ardi', 200000, ''),
('BL0006', '2024-08-06', 'ardi', 400000, 'kopi '),
('BL0007', '2024-08-06', 'ardi', 1200000, ''),
('BL0008', '2024-08-06', 'ardi', 40000, ''),
('BL0009', '2024-08-08', 'ardi', 200000, ''),
('BL0010', '2024-08-08', 'ardi', 20000, ''),
('BL0011', '2024-08-08', 'ardi', 150000, ''),
('BL0012', '2024-08-08', 'ardi', 200000, ''),
('BL0013', '2024-08-08', 'ardi', 350000, ''),
('BL0014', '2024-08-09', 'ardi', 300000, ''),
('BL0015', '2024-08-09', 'ardi', 20000, ''),
('BL0016', '2024-08-09', 'ardi', 200000, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_pemdetail`
--

CREATE TABLE `trans_pemdetail` (
  `id_pembelian` int(11) NOT NULL,
  `no_transbeli` varchar(20) NOT NULL,
  `tgl_transbeli` date NOT NULL,
  `id_bahan` varchar(100) NOT NULL,
  `nm_bahan` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `jml_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_pemdetail`
--

INSERT INTO `trans_pemdetail` (`id_pembelian`, `no_transbeli`, `tgl_transbeli`, `id_bahan`, `nm_bahan`, `jumlah`, `harga_beli`, `jml_harga`) VALUES
(3, 'BL0001', '2024-08-05', 'B-001', 'teh', 2, 150000, 300000),
(4, 'BL0002', '2024-08-05', 'B-002', 'kopi', 2, 200000, 400000),
(7, 'BL0003', '2024-08-05', 'B-001', 'teh', 1, 150000, 150000),
(15, 'BL0004', '2024-08-06', 'B-002', 'kopi', 1, 200000, 200000),
(16, 'BL0004', '2024-08-06', 'B-001', 'teh', 1, 150000, 150000),
(17, 'BL0005', '2024-08-06', 'B-002', 'kopi', 1, 200000, 200000),
(20, 'BL0006', '2024-08-06', 'B-002', 'kopi', 2, 200000, 400000),
(21, 'BL0007', '2024-08-06', 'B-002', 'kopi', 6, 200000, 1200000),
(22, 'BL0008', '2024-08-06', 'B-003', 'susu', 2, 20000, 40000),
(23, 'BL0009', '2024-08-08', 'B-002', 'kopi', 1, 200000, 200000),
(24, 'BL0010', '2024-08-08', 'B-003', 'susu', 1, 20000, 20000),
(25, 'BL0011', '2024-08-08', 'B-001', 'teh', 1, 150000, 150000),
(26, 'BL0012', '2024-08-08', 'B-002', 'kopi', 1, 200000, 200000),
(27, 'BL0013', '2024-08-08', 'B-001', 'teh', 1, 150000, 150000),
(28, 'BL0013', '2024-08-08', 'B-002', 'kopi', 1, 200000, 200000),
(29, 'BL0014', '2024-08-09', 'B-001', 'teh', 2, 150000, 300000),
(30, 'BL0015', '2024-08-09', 'B-003', 'susu', 1, 20000, 20000),
(31, 'BL0016', '2024-08-09', 'B-002', 'kopi', 1, 200000, 200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_penjualan`
--

CREATE TABLE `trans_penjualan` (
  `no_transjual` varchar(20) NOT NULL,
  `tgl_transjual` date NOT NULL,
  `nm_customer` varchar(256) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jml_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `trans_penjualan`
--

INSERT INTO `trans_penjualan` (`no_transjual`, `tgl_transjual`, `nm_customer`, `total`, `keterangan`, `jml_bayar`, `kembalian`) VALUES
('PJ0001', '2024-08-06', 'apri', 126000, '', 150000, 150000),
('PJ0002', '2024-08-06', '', 18000, '', 100000, 100000),
('PJ0003', '2024-08-07', 'apri', 18000, '', 1235789, 1235789),
('PJ0004', '2024-08-09', 'Arfi', 18000, '', 20000, 20000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_penjudetail`
--

CREATE TABLE `trans_penjudetail` (
  `id_penjualan` int(11) NOT NULL,
  `no_transjual` varchar(20) NOT NULL,
  `tgl_transjual` date NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nm_produk` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `jml_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `riwayat_kartu_stok_bahan`
--
ALTER TABLE `riwayat_kartu_stok_bahan`
  ADD PRIMARY KEY (`id_riwayat`);

--
-- Indeks untuk tabel `riwayat_kartu_stok_produk`
--
ALTER TABLE `riwayat_kartu_stok_produk`
  ADD PRIMARY KEY (`id_riwayat`);

--
-- Indeks untuk tabel `tb_bahan`
--
ALTER TABLE `tb_bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`);

--
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_penerimaan_bahan`
--
ALTER TABLE `tb_penerimaan_bahan`
  ADD PRIMARY KEY (`id_penerimaan`);

--
-- Indeks untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indeks untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `tb_produksi`
--
ALTER TABLE `tb_produksi`
  ADD PRIMARY KEY (`id_produksi`);

--
-- Indeks untuk tabel `tb_produksi_detail`
--
ALTER TABLE `tb_produksi_detail`
  ADD PRIMARY KEY (`id_produksi_detail`);

--
-- Indeks untuk tabel `tb_resep`
--
ALTER TABLE `tb_resep`
  ADD PRIMARY KEY (`id_resep`);

--
-- Indeks untuk tabel `tb_riwayat_harga_jual`
--
ALTER TABLE `tb_riwayat_harga_jual`
  ADD PRIMARY KEY (`id_riwayat_hj`);

--
-- Indeks untuk tabel `tb_stok_opname`
--
ALTER TABLE `tb_stok_opname`
  ADD PRIMARY KEY (`id_opname`);

--
-- Indeks untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `tb_transaksi_bahan`
--
ALTER TABLE `tb_transaksi_bahan`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `trans_pembelian`
--
ALTER TABLE `trans_pembelian`
  ADD PRIMARY KEY (`no_transbeli`);

--
-- Indeks untuk tabel `trans_pemdetail`
--
ALTER TABLE `trans_pemdetail`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indeks untuk tabel `trans_penjualan`
--
ALTER TABLE `trans_penjualan`
  ADD PRIMARY KEY (`no_transjual`);

--
-- Indeks untuk tabel `trans_penjudetail`
--
ALTER TABLE `trans_penjudetail`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `riwayat_kartu_stok_bahan`
--
ALTER TABLE `riwayat_kartu_stok_bahan`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `riwayat_kartu_stok_produk`
--
ALTER TABLE `riwayat_kartu_stok_produk`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_customer`
--
ALTER TABLE `tb_customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tb_penerimaan_bahan`
--
ALTER TABLE `tb_penerimaan_bahan`
  MODIFY `id_penerimaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_produksi`
--
ALTER TABLE `tb_produksi`
  MODIFY `id_produksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `tb_produksi_detail`
--
ALTER TABLE `tb_produksi_detail`
  MODIFY `id_produksi_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `tb_resep`
--
ALTER TABLE `tb_resep`
  MODIFY `id_resep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_riwayat_harga_jual`
--
ALTER TABLE `tb_riwayat_harga_jual`
  MODIFY `id_riwayat_hj` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_stok_opname`
--
ALTER TABLE `tb_stok_opname`
  MODIFY `id_opname` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi_bahan`
--
ALTER TABLE `tb_transaksi_bahan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `trans_pemdetail`
--
ALTER TABLE `trans_pemdetail`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `trans_penjudetail`
--
ALTER TABLE `trans_penjudetail`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
