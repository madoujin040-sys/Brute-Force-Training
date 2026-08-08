-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 08, 2026 at 01:42 AM
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
-- Database: `db_cybernusa_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL,
  `id_user` int NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alpa') DEFAULT 'Hadir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_user`, `tanggal`, `waktu_masuk`, `waktu_keluar`, `status_kehadiran`) VALUES
(1, 1, '2026-08-01', '07:45:00', '17:15:00', 'Hadir'),
(2, 2, '2026-08-01', '08:00:00', '17:00:00', 'Hadir'),
(3, 3, '2026-08-01', '08:15:00', '17:30:00', 'Hadir'),
(4, 4, '2026-08-01', '07:50:00', '17:00:00', 'Hadir'),
(5, 5, '2026-08-01', NULL, NULL, 'Sakit'),
(6, 1, '2026-08-02', '07:55:00', '17:05:00', 'Hadir'),
(7, 2, '2026-08-02', '08:05:00', '17:10:00', 'Hadir'),
(8, 3, '2026-08-02', '08:00:00', '17:00:00', 'Hadir'),
(9, 4, '2026-08-02', NULL, NULL, 'Izin'),
(10, 5, '2026-08-02', '08:10:00', '17:00:00', 'Hadir'),
(11, 1, '2026-08-03', '07:50:00', '17:20:00', 'Hadir'),
(12, 3, '2026-08-03', '08:00:00', '17:05:00', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(50) NOT NULL,
  `waktu_coba` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penggajian`
--

CREATE TABLE `penggajian` (
  `id_gaji` int NOT NULL,
  `id_user` int NOT NULL,
  `periode` varchar(50) NOT NULL,
  `gaji_pokok` decimal(12,2) NOT NULL,
  `tunjangan` decimal(12,2) DEFAULT '0.00',
  `potongan` decimal(12,2) DEFAULT '0.00',
  `total_gaji` decimal(12,2) GENERATED ALWAYS AS (((`gaji_pokok` + `tunjangan`) - `potongan`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggajian`
--

INSERT INTO `penggajian` (`id_gaji`, `id_user`, `periode`, `gaji_pokok`, `tunjangan`, `potongan`) VALUES
(1, 1, 'Agustus 2026', '12000000.00', '3000000.00', '200000.00'),
(2, 2, 'Agustus 2026', '15000000.00', '4000000.00', '0.00'),
(3, 3, 'Agustus 2026', '9500000.00', '1500000.00', '100000.00'),
(4, 4, 'Agustus 2026', '8000000.00', '1000000.00', '0.00'),
(5, 5, 'Agustus 2026', '7500000.00', '1200000.00', '50000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `penempatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`, `jabatan`, `penempatan`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'I Komang Narayana Asra Wiguna', 'Administrator', 'IT Infrastructure Manager', 'Denpasar HQ'),
(2, 'dina', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dina Mariana', 'Management', 'Head of Human Resources', 'Denpasar HQ'),
(3, 'budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', 'Employee', 'Senior Software Engineer', 'Pererenan Branch'),
(4, 'siti', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Aminah', 'Employee', 'Financial Analyst', 'Denpasar HQ'),
(5, 'agus', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Agus Pratama', 'Employee', 'Marketing Executive', 'Badung Branch');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD PRIMARY KEY (`id_gaji`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penggajian`
--
ALTER TABLE `penggajian`
  MODIFY `id_gaji` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `penggajian`
--
ALTER TABLE `penggajian`
  ADD CONSTRAINT `penggajian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
