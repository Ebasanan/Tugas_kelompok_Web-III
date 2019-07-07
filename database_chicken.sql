-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07 Jul 2019 pada 17.30
-- Versi Server: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_chicken`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_admin`
--

CREATE TABLE `tabel_admin` (
  `kolom_kode_admin` char(4) NOT NULL,
  `kolom_username` varchar(10) NOT NULL,
  `kolom_password` varchar(10) NOT NULL,
  `kolom_password_sha1` varchar(40) NOT NULL,
  `kolom_level` enum('admin','kasir') NOT NULL,
  `kolom_status_admin` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_admin`
--

INSERT INTO `tabel_admin` (`kolom_kode_admin`, `kolom_username`, `kolom_password`, `kolom_password_sha1`, `kolom_level`, `kolom_status_admin`) VALUES
('AD01', 'admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 'on'),
('AD02', 'kasir', 'kasir', '8691e4fc53b99da544ce86e22acba62d13352eff', 'kasir', 'on'),
('AD03', 'jiul', 'jiul', 'd19d16fea794b1032977809754fe8fca366af9a8', 'admin', 'on');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_agen`
--

CREATE TABLE `tabel_agen` (
  `kolom_kode_agen` char(4) NOT NULL,
  `kolom_nama_agen` varchar(60) NOT NULL,
  `kolom_telepon_agen` varchar(12) NOT NULL,
  `kolom_distributor` varchar(20) NOT NULL,
  `kolom_status_agen` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_agen`
--

INSERT INTO `tabel_agen` (`kolom_kode_agen`, `kolom_nama_agen`, `kolom_telepon_agen`, `kolom_distributor`, `kolom_status_agen`) VALUES
('AG01', 'Tukimin', '089697402667', 'PT . Banyak', 'on'),
('AG02', 'Warung murah', '089657336322', 'PT.Sederhana', 'on');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_item_barang`
--

CREATE TABLE `tabel_item_barang` (
  `kolom_kode_item_barang` char(5) NOT NULL,
  `kolom_kode_pembelian` char(5) NOT NULL,
  `kolom_kode_admin` char(4) NOT NULL,
  `kolom_kode_agen` char(4) NOT NULL,
  `kolom_kode_satuan` char(4) NOT NULL,
  `kolom_nama_barang` varchar(60) NOT NULL,
  `kolom_harga_barang` int(10) NOT NULL,
  `kolom_jumlah_barang` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_item_barang`
--

INSERT INTO `tabel_item_barang` (`kolom_kode_item_barang`, `kolom_kode_pembelian`, `kolom_kode_admin`, `kolom_kode_agen`, `kolom_kode_satuan`, `kolom_nama_barang`, `kolom_harga_barang`, `kolom_jumlah_barang`) VALUES
('IB190', 'BL190', 'AD01', 'AG01', 'ST01', 'Tepung', 5000, 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_item_menu`
--

CREATE TABLE `tabel_item_menu` (
  `kolom_kode_item_menu` char(5) NOT NULL,
  `kolom_kode_penjualan` char(5) NOT NULL,
  `kolom_kode_admin` char(4) NOT NULL,
  `kolom_kode_menu` char(4) NOT NULL,
  `kolom_jumlah_porsi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_item_menu`
--

INSERT INTO `tabel_item_menu` (`kolom_kode_item_menu`, `kolom_kode_penjualan`, `kolom_kode_admin`, `kolom_kode_menu`, `kolom_jumlah_porsi`) VALUES
('IM190', 'JL190', 'AD02', 'MN02', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_kategori`
--

CREATE TABLE `tabel_kategori` (
  `kolom_kode_kategori` char(4) NOT NULL,
  `kolom_nama_kategori` varchar(100) NOT NULL,
  `kolom_status_kategori` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_kategori`
--

INSERT INTO `tabel_kategori` (`kolom_kode_kategori`, `kolom_nama_kategori`, `kolom_status_kategori`) VALUES
('KT01', 'Food', 'on'),
('KT02', 'Non Food', 'on'),
('KT03', 'Drink', 'on');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_menu`
--

CREATE TABLE `tabel_menu` (
  `kolom_kode_menu` char(4) NOT NULL,
  `kolom_kode_kategori` char(4) NOT NULL,
  `kolom_nama_menu` varchar(40) NOT NULL,
  `kolom_isi_menu` text NOT NULL,
  `kolom_harga_menu` int(10) NOT NULL,
  `kolom_status_menu` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_menu`
--

INSERT INTO `tabel_menu` (`kolom_kode_menu`, `kolom_kode_kategori`, `kolom_nama_menu`, `kolom_isi_menu`, `kolom_harga_menu`, `kolom_status_menu`) VALUES
('MN01', 'KT01', 'Ayam Gaplok', 'Complete', 40000, 'on'),
('MN02', 'KT01', 'Paha Mulus', 'Complete', 60000, 'on'),
('MN03', 'KT03', 'Es Teh Anget', 'Complete', 30000, 'on');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_pembelian`
--

CREATE TABLE `tabel_pembelian` (
  `kolom_kode_pembelian` char(5) NOT NULL,
  `kolom_tanggal_pembelian` date NOT NULL,
  `kolom_kode_admin` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_pembelian`
--

INSERT INTO `tabel_pembelian` (`kolom_kode_pembelian`, `kolom_tanggal_pembelian`, `kolom_kode_admin`) VALUES
('BL190', '2019-07-05', 'AD01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_penjualan`
--

CREATE TABLE `tabel_penjualan` (
  `kolom_kode_penjualan` char(5) NOT NULL,
  `kolom_tanggal_penjualan` date NOT NULL,
  `kolom_kode_admin` char(4) NOT NULL,
  `kolom_uang_tunai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_penjualan`
--

INSERT INTO `tabel_penjualan` (`kolom_kode_penjualan`, `kolom_tanggal_penjualan`, `kolom_kode_admin`, `kolom_uang_tunai`) VALUES
('JL190', '2019-07-07', 'AD02', 20000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_satuan`
--

CREATE TABLE `tabel_satuan` (
  `kolom_kode_satuan` char(4) NOT NULL,
  `kolom_nama_satuan` varchar(10) NOT NULL,
  `kolom_status_satuan` enum('on','off') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tabel_satuan`
--

INSERT INTO `tabel_satuan` (`kolom_kode_satuan`, `kolom_nama_satuan`, `kolom_status_satuan`) VALUES
('ST01', 'Dus', 'on'),
('ST02', 'Pcs', 'on'),
('ST03', 'Botol', 'on'),
('ST04', 'Kg', 'on');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_admin`
--
ALTER TABLE `tabel_admin`
  ADD PRIMARY KEY (`kolom_kode_admin`);

--
-- Indexes for table `tabel_agen`
--
ALTER TABLE `tabel_agen`
  ADD PRIMARY KEY (`kolom_kode_agen`);

--
-- Indexes for table `tabel_item_barang`
--
ALTER TABLE `tabel_item_barang`
  ADD PRIMARY KEY (`kolom_kode_item_barang`);

--
-- Indexes for table `tabel_item_menu`
--
ALTER TABLE `tabel_item_menu`
  ADD PRIMARY KEY (`kolom_kode_item_menu`);

--
-- Indexes for table `tabel_kategori`
--
ALTER TABLE `tabel_kategori`
  ADD PRIMARY KEY (`kolom_kode_kategori`);

--
-- Indexes for table `tabel_menu`
--
ALTER TABLE `tabel_menu`
  ADD PRIMARY KEY (`kolom_kode_menu`);

--
-- Indexes for table `tabel_pembelian`
--
ALTER TABLE `tabel_pembelian`
  ADD PRIMARY KEY (`kolom_kode_pembelian`);

--
-- Indexes for table `tabel_penjualan`
--
ALTER TABLE `tabel_penjualan`
  ADD PRIMARY KEY (`kolom_kode_penjualan`);

--
-- Indexes for table `tabel_satuan`
--
ALTER TABLE `tabel_satuan`
  ADD PRIMARY KEY (`kolom_kode_satuan`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
