-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2025 at 06:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `makrab`
--

-- --------------------------------------------------------

--
-- Table structure for table `footer_header`
--

CREATE TABLE `footer_header` (
  `id` int(11) NOT NULL,
  `website_name` varchar(255) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_header`
--

INSERT INTO `footer_header` (`id`, `website_name`, `slogan`, `alamat`) VALUES
(2, 'Proposal makrab', 'Tetap Semangat', 'Jl. Cendrawasih Raya Blok B7/P Bintaro Jaya, Sawah Baru, Ciputat, Tangerang Selatan 15413');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `koordinator_hima` enum('Setuju','Tidak Setuju','Pending') DEFAULT 'Pending',
  `kaprodi` enum('Setuju','Tidak Setuju','Pending') DEFAULT 'Pending',
  `fakultas` enum('Setuju','Tidak Setuju','Pending') DEFAULT 'Pending',
  `bkal` enum('Setuju','Tidak Setuju','Pending') DEFAULT 'Pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Verified','Declined','Updated') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id`, `title`, `description`, `koordinator_hima`, `kaprodi`, `fakultas`, `bkal`, `created_by`, `created_at`, `updated_at`, `file_path`, `status`) VALUES
(57, 'Makrab SIF', 'Kegiatan Makrab SIF-B', 'Setuju', 'Setuju', 'Setuju', 'Setuju', 33, '2025-01-07 18:21:30', '2025-01-07 18:23:24', 'uploads/1736274090_Laporan_UAS_KPBD[2].pdf', 'Verified'),
(58, 'Makrab INF', 'Kegiatan Makrab INF-A', 'Tidak Setuju', 'Setuju', 'Pending', 'Pending', 33, '2025-01-07 18:21:46', '2025-01-07 18:24:57', 'uploads/1736274106_Laporan_UAS_KPBD[2].pdf', 'Declined'),
(59, 'Makrab KOM', 'Kegiatan Makrab KOM', 'Pending', 'Tidak Setuju', 'Pending', 'Pending', 33, '2025-01-07 18:22:13', '2025-01-07 18:22:43', 'uploads/1736274133_Laporan_UAS_KPBD[2].pdf', 'Declined');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `kode_role` varchar(3) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `kode_role`, `nama_lengkap`, `created_at`, `role`) VALUES
(33, '01user', '123', 'MHS', 'Abid Ramadhan', '2025-01-07 22:51:35', 'Mahasiswa'),
(34, '02user', '123', 'PRD', 'Muhammad Arsya', '2025-01-07 22:53:59', 'Kaprodi'),
(35, '03user', '123', 'KRD', 'Adie Suryo', '2025-01-07 22:54:12', 'Koordinator HIMA'),
(36, '04user', '123', 'FKT', 'Virdan Dhani', '2025-01-07 22:54:24', 'Fakultas'),
(37, '05user', '123', 'BKL', 'Muhammad Ugi', '2025-01-07 22:54:35', 'Biro Kemahasiswaan Alumni'),
(38, '01bokang', '123', 'MHS', 'Bokang Balabijo', '2025-01-08 01:24:05', 'Mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `footer_header`
--
ALTER TABLE `footer_header`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `footer_header`
--
ALTER TABLE `footer_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
