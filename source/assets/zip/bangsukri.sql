-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 18, 2023 at 12:50 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_inventaris`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `satuan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `merk`, `tipe`, `satuan`) VALUES
(1, 'Kursi Kantor', 'Doodook', 'Kursi kantor air spring', 'Unit'),
(2, 'Kursi Sofa', 'Soph', 'Kursi sofa panjang', 'Unit'),
(3, 'Meja Kerja', 'Gawee', 'Meja kerja kayu dan kabinet', 'Unit'),
(4, 'Kipas Angin', 'Semwing', 'Kipas angin dinding', 'Unit'),
(5, 'Karpet', 'Ambal', 'Karpet permadani', 'Lembar'),
(6, 'Monitor Komputer', 'Nitro', 'Ultrawide 24 inch', 'Unit'),
(7, 'Komputer Personal', 'Ternal', 'Core i7 Gen 12, SSD 256 GB, Ram 8 GB', 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `sumber_dana` varchar(255) NOT NULL,
  `pemasok_id` int(11) NOT NULL,
  `karyawan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `tanggal`, `sumber_dana`, `pemasok_id`, `karyawan_id`) VALUES
(1, '2022-12-01', 'Hibah', 1, 1),
(2, '2022-12-08', 'Penganggaran', 2, 1),
(3, '2022-12-22', 'Hibah', 1, 2),
(4, '2023-01-03', 'Hibah', 2, 3),
(5, '2023-01-04', 'Hibah', 1, 3),
(6, '2023-01-10', 'Hibah', 2, 1),
(7, '2023-02-22', 'Hibah', 2, 1),
(8, '2023-02-23', 'Hibah', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk_detail`
--

CREATE TABLE `barang_masuk_detail` (
  `id` int(11) NOT NULL,
  `barang_masuk_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status_kode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang_masuk_detail`
--

INSERT INTO `barang_masuk_detail` (`id`, `barang_masuk_id`, `barang_id`, `jumlah`, `status_kode`) VALUES
(1, 1, 2, 3, 'Sudah'),
(2, 1, 4, 6, 'Sudah'),
(3, 2, 1, 7, 'Sudah'),
(4, 2, 3, 7, 'Sudah'),
(5, 3, 5, 10, 'Belum'),
(6, 4, 1, 2, 'Belum'),
(7, 4, 3, 2, 'Belum'),
(8, 4, 6, 3, 'Belum'),
(9, 4, 7, 3, 'Belum'),
(10, 5, 5, 1, 'Belum'),
(11, 5, 2, 6, 'Belum'),
(12, 5, 4, 3, 'Belum'),
(13, 6, 6, 6, 'Belum'),
(14, 6, 7, 6, 'Belum'),
(15, 7, 6, 1, 'Belum'),
(16, 7, 7, 1, 'Belum'),
(17, 8, 1, 1, 'Belum'),
(18, 8, 3, 1, 'Belum');

-- --------------------------------------------------------

--
-- Table structure for table `inventaris_ruang`
--

CREATE TABLE `inventaris_ruang` (
  `id` int(11) NOT NULL,
  `kode_id` int(11) NOT NULL,
  `ruang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventaris_ruang`
--

INSERT INTO `inventaris_ruang` (`id`, `kode_id`, `ruang_id`) VALUES
(1, 1, 1),
(2, 4, 1),
(3, 10, 1),
(4, 11, 1),
(5, 17, 1),
(6, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int(11) NOT NULL,
  `nama_karyawan` varchar(255) NOT NULL,
  `nomor_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id`, `nama_karyawan`, `nomor_hp`, `alamat`, `email`, `password`, `level`) VALUES
(1, 'Adul', '08100000', 'Sungai Kacang', 'adul@email.com', '997593f7b7af4fc758127e1dc41e3446', 'admin'),
(2, 'Beta', '08200000', 'Pantai Hambawang', 'beta@email.com', '987bcab01b929eb2c07877b224215c92', 'user'),
(3, 'Cita', '08300000', 'Pasar Papan', 'cita@email.com', 'ebea7d75ce0ae38a0440221a067eb2bc', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `kode`
--

CREATE TABLE `kode` (
  `id` int(11) NOT NULL,
  `barang_masuk_detail_id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `kondisi_barang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kode`
--

INSERT INTO `kode` (`id`, `barang_masuk_detail_id`, `kode`, `kondisi_barang`) VALUES
(1, 1, '2022/12/0002/0001', 'Baru'),
(2, 1, '2022/12/0002/0002', 'Baru'),
(3, 1, '2022/12/0002/0003', 'Baru'),
(4, 1, '2022/12/0004/0001', 'Baru'),
(5, 1, '2022/12/0004/0002', 'Baru'),
(6, 1, '2022/12/0004/0003', 'Baru'),
(7, 1, '2022/12/0004/0004', 'Baru'),
(8, 1, '2022/12/0004/0005', 'Baru'),
(9, 1, '2022/12/0004/0006', 'Baru'),
(10, 2, '2022/12/0001/0001', 'Baru'),
(11, 2, '2022/12/0001/0002', 'Baru'),
(12, 2, '2022/12/0001/0003', 'Baru'),
(13, 2, '2022/12/0001/0004', 'Baru'),
(14, 2, '2022/12/0001/0005', 'Baru'),
(15, 2, '2022/12/0001/0006', 'Baru'),
(16, 2, '2022/12/0001/0007', 'Baru'),
(17, 2, '2022/12/0003/0001', 'Baru'),
(18, 2, '2022/12/0003/0002', 'Baru'),
(19, 2, '2022/12/0003/0003', 'Baru'),
(20, 2, '2022/12/0003/0004', 'Baru'),
(21, 2, '2022/12/0003/0005', 'Baru'),
(22, 2, '2022/12/0003/0006', 'Baru'),
(23, 2, '2022/12/0003/0007', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE `pemasok` (
  `id` int(11) NOT NULL,
  `nama_pemasok` varchar(255) NOT NULL,
  `nama_kontak` varchar(255) NOT NULL,
  `nomor_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id`, `nama_pemasok`, `nama_kontak`, `nomor_hp`, `alamat`) VALUES
(1, 'CV Alading', 'Mas Dindin', '08000000', 'Banjarbaru'),
(2, 'CV Benalu', 'Mbak Pia', '08000001', 'Martapura');

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `id` int(11) NOT NULL,
  `nama_ruang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`id`, `nama_ruang`) VALUES
(1, 'Front Office'),
(2, 'Marketing'),
(3, 'Finance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventaris_ruang`
--
ALTER TABLE `inventaris_ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kode`
--
ALTER TABLE `kode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemasok`
--
ALTER TABLE `pemasok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `inventaris_ruang`
--
ALTER TABLE `inventaris_ruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kode`
--
ALTER TABLE `kode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pemasok`
--
ALTER TABLE `pemasok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
