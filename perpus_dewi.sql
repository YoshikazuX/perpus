-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2026 at 01:22 PM
-- Server version: 9.4.0
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus_dewi`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `ISBN` varchar(14) NOT NULL,
  `JUDUL_BUKU` varchar(50) NOT NULL,
  `PENGARANG` varchar(50) NOT NULL,
  `PENERBIT` varchar(50) NOT NULL,
  `TAHUN_TERBIT` year NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`ISBN`, `JUDUL_BUKU`, `PENGARANG`, `PENERBIT`, `TAHUN_TERBIT`) VALUES
('213456', 'Indahnya Dirimu', 'Vann Edward', 'VanPedia', '2026'),
('27537565', 'Love is scary', 'Ren Takahashi', 'Hinx', '2019'),
('374348764', 'An Apple', 'Dewi Shimazugawa', 'NXL', '2000'),
('53763254', 'Apakah ini..', 'Yun miyage', 'AwField', '1980'),
('8468374', '8 is mine', 'Handa Shimabukuro', 'Hanase', '1999'),
('907767564', 'How to Fly', 'Revan Hopkins', 'Book sky', '2008');

-- --------------------------------------------------------

--
-- Table structure for table `peminjam`
--

CREATE TABLE `peminjam` (
  `ID_PEMINJAM` varchar(20) NOT NULL,
  `NAMA` varchar(100) NOT NULL,
  `ALAMAT` varchar(100) NOT NULL,
  `GENDER` enum('PRIA','WANITA') NOT NULL,
  `HP` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjam`
--

INSERT INTO `peminjam` (`ID_PEMINJAM`, `NAMA`, `ALAMAT`, `GENDER`, `HP`) VALUES
('PMJ001', 'Haruto Takahashi', 'Sakai, Osaka', 'PRIA', '085167340978'),
('PMJ002', 'Ren Yamamoto', 'Maizuru, Kyoto', 'PRIA', '089287465249'),
('PMJ003', 'Itsuki Watanabe', 'Nishiwaki, Hyogo', 'PRIA', '084376580987'),
('PMJ004', 'Riku Fujimoto', 'Himeji, Hyogo', 'WANITA', '083476890157'),
('PMJ005', 'Kenshin Saito', 'Toyonaka, Osaka', 'PRIA', '081287580934');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `ID_PEMINJAMAN` varchar(20) NOT NULL,
  `ISBN` varchar(20) NOT NULL,
  `ID_PEMINJAM` varchar(20) NOT NULL,
  `ID_PETUGAS` varchar(20) NOT NULL,
  `JUMLAH` int NOT NULL,
  `TANGGAL_MULAI` date NOT NULL,
  `TANGGAL_SELESAI` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`ID_PEMINJAMAN`, `ISBN`, `ID_PEMINJAM`, `ID_PETUGAS`, `JUMLAH`, `TANGGAL_MULAI`, `TANGGAL_SELESAI`) VALUES
('PMJM001', '27537565', 'PMJ001', 'PTG001', 1, '2025-08-24', '2025-08-25'),
('PMJM002', '374348764', 'PMJ002', 'PTG002', 1, '2025-08-24', '2025-08-27'),
('PMJM003', '53763254', 'PMJ003', 'PTG003', 1, '2025-08-27', '2025-08-30'),
('PMJM004', '8468374', 'PMJ004', 'PTG004', 1, '2025-08-29', '2025-08-31'),
('PMJM005', '907767564', 'PMJ005', 'PTG005', 1, '2025-08-31', '2025-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `ID_PETUGAS` varchar(20) NOT NULL,
  `NAMA` varchar(50) NOT NULL,
  `GENDER` enum('PRIA','WANITA') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ALAMAT` varchar(100) NOT NULL,
  `HP` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`ID_PETUGAS`, `NAMA`, `GENDER`, `ALAMAT`, `HP`) VALUES
('PTG001', 'Riko Suzuki', 'WANITA', 'Suma-ku, Kobe', '086309875643'),
('PTG002', 'Mio Takeda', 'WANITA', 'Suma-ku, Kobe', '0853689501563'),
('PTG003', 'Makoto Hayashi', 'PRIA', 'Yamatokoriyama, Nara', '086354625640'),
('PTG004', 'Kaoru Ishikawa', 'PRIA', 'Himeji, Kakogawa', '082042638723'),
('PTG005', 'Aoi Tanaka', 'PRIA', 'Yamanoue, Hirakata', '083576528045');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID_USER` varchar(50) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `LEVEL` enum('admin','petuagas','peminjam') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID_USER`, `USERNAME`, `PASSWORD`, `LEVEL`) VALUES
('001', 'admin', 'admin123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`ISBN`);

--
-- Indexes for table `peminjam`
--
ALTER TABLE `peminjam`
  ADD PRIMARY KEY (`ID_PEMINJAM`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`ID_PEMINJAMAN`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`ID_PETUGAS`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID_USER`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
