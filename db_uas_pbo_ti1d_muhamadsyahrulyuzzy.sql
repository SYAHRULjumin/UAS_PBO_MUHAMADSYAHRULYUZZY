-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 25, 2026 at 08:02 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_ti1d_muhamadsyahrulyuzzy`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(15) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(10,2) NOT NULL,
  `jenis_pembiayaan` enum('Mandiri','Bidikmisi','Prestasi') NOT NULL,
  `golongan_ukt` varchar(10) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_syarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembiayaan`, `golongan_ukt`, `nama_instansi_beasiswa`, `minimal_ipk_syarat`) VALUES
(1, 'Muhamad Syahrul Yuzzy', '202601001', 2, 5000000.00, 'Mandiri', 'Golongan 4', NULL, NULL),
(2, 'Akbar Santana', '202601002', 2, 6500000.00, 'Mandiri', 'Golongan 5', NULL, NULL),
(3, 'Citra Lestari', '202601003', 4, 5000000.00, 'Mandiri', 'Golongan 4', NULL, NULL),
(4, 'Dandi Rahmawan', '202601004', 4, 8000000.00, 'Mandiri', 'Golongan 6', NULL, NULL),
(5, 'Eka Saputra', '202601005', 6, 4000000.00, 'Mandiri', 'Golongan 3', NULL, NULL),
(6, 'Fitri Handayani', '202601006', 6, 6500000.00, 'Mandiri', 'Golongan 5', NULL, NULL),
(7, 'Gilang Permana', '202601007', 2, 8000000.00, 'Mandiri', 'Golongan 6', NULL, NULL),
(8, 'Hendra Wijaya', '202602001', 2, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(9, 'Indah Permatasari', '202602002', 2, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(10, 'Joko Susilo', '202602003', 4, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(11, 'Kurniawati', '202602004', 4, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(12, 'Lutfi Hakim', '202602005', 6, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(13, 'Mega Utami', '202602006', 6, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(14, 'Naufal Rizqi', '202602007', 2, 0.00, 'Bidikmisi', NULL, 'Kemendikbud', 2.75),
(15, 'Olivia Kristen', '202603001', 2, 1000000.00, 'Prestasi', NULL, 'Djarum Foundation', 3.50),
(16, 'Panji Asmoro', '202603002', 2, 1500000.00, 'Prestasi', NULL, 'PT Bank Central Asia', 3.40),
(17, 'Qori Sandria', '202603003', 4, 1200000.00, 'Prestasi', NULL, 'Yayasan Toyota Astra', 3.30),
(18, 'Rian Hidayat', '202603004', 4, 1000000.00, 'Prestasi', NULL, 'Djarum Foundation', 3.50),
(19, 'Siti Aminah', '202603005', 6, 2000000.00, 'Prestasi', NULL, 'Pertamina Foundation', 3.25),
(20, 'Taufik Hidayat', '202603006', 6, 1500000.00, 'Prestasi', NULL, 'PT Bank Central Asia', 3.40),
(21, 'Vina Alvionita', '202603007', 2, 1200000.00, 'Prestasi', NULL, 'Yayasan Toyota Astra', 3.30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
