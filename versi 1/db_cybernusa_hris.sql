-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2026 at 03:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cybernusa_hris`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absen`, `id_user`, `tanggal`, `jam_masuk`, `lokasi`, `status`) VALUES
(1, 2, '2026-08-01', '07:55:00', 'Cabang Bandung', 'Hadir'),
(2, 3, '2026-08-01', '08:05:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(3, 4, '2026-08-01', '08:15:00', 'Cabang Surabaya', 'Terlambat'),
(4, 5, '2026-08-01', '07:50:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(5, 6, '2026-08-01', '07:58:00', 'Cabang Bandung', 'Hadir'),
(6, 2, '2026-08-02', '07:45:00', 'Cabang Bandung', 'Hadir'),
(7, 3, '2026-08-02', '08:00:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(8, 4, '2026-08-02', '08:20:00', 'Cabang Surabaya', 'Terlambat'),
(9, 5, '2026-08-02', '08:01:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(10, 6, '2026-08-02', '07:59:00', 'Cabang Bandung', 'Hadir'),
(11, 2, '2026-08-03', '08:10:00', 'Cabang Bandung', 'Terlambat'),
(12, 3, '2026-08-03', '07:55:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(13, 4, '2026-08-03', '07:50:00', 'Cabang Surabaya', 'Hadir'),
(14, 5, '2026-08-03', '09:00:00', 'Kantor Pusat - Jakarta', 'Izin'),
(15, 6, '2026-08-03', '07:55:00', 'Cabang Bandung', 'Hadir'),
(16, 2, '2026-08-04', '07:50:00', 'Cabang Bandung', 'Hadir'),
(17, 3, '2026-08-04', '07:58:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(18, 4, '2026-08-04', '08:00:00', 'Cabang Surabaya', 'Hadir'),
(19, 5, '2026-08-04', '07:59:00', 'Kantor Pusat - Jakarta', 'Hadir'),
(20, 6, '2026-08-04', '08:30:00', 'Cabang Bandung', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id_gaji` int NOT NULL,
  `id_user` int NOT NULL,
  `periode` varchar(50) NOT NULL,
  `gaji_pokok` int NOT NULL,
  `tunjangan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`id_gaji`, `id_user`, `periode`, `gaji_pokok`, `tunjangan`) VALUES
(1, 1, 'Juli 2026', 12000000, 3000000),
(2, 2, 'Juli 2026', 8500000, 1500000),
(3, 3, 'Juli 2026', 7500000, 1000000),
(4, 4, 'Juli 2026', 9000000, 2000000),
(5, 5, 'Juli 2026', 7000000, 1000000),
(6, 6, 'Juli 2026', 8000000, 1500000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','karyawan') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `penempatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `jabatan`, `penempatan`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Diana Pertiwi', 'HR Manager', 'Kantor Pusat - Jakarta'),
(2, 'andi.s', '482c811da5d5b4bc6d497ffa98491e38', 'karyawan', 'Andi Saputra', 'Software Engineer', 'Cabang Bandung'),
(3, 'siti.a', '482c811da5d5b4bc6d497ffa98491e38', 'karyawan', 'Siti Aminah', 'UI/UX Designer', 'Kantor Pusat - Jakarta'),
(4, 'budi.w', '482c811da5d5b4bc6d497ffa98491e38', 'karyawan', 'Budi Wijaya', 'System Administrator', 'Cabang Surabaya'),
(5, 'ratna.m', '482c811da5d5b4bc6d497ffa98491e38', 'karyawan', 'Ratna Mustika', 'QA Analyst', 'Kantor Pusat - Jakarta'),
(6, 'joko.p', '482c811da5d5b4bc6d497ffa98491e38', 'karyawan', 'Joko Prasetyo', 'Database Admin', 'Cabang Bandung');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id_gaji`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id_gaji` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
