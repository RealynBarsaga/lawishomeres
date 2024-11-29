-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2024 at 06:06 PM
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
-- Database: `db_barangay`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbunakan`
--

CREATE TABLE `tblbunakan` (
  `id` int(11) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) NOT NULL,
  `bdate` varchar(20) NOT NULL,
  `bplace` text NOT NULL,
  `age` int(11) NOT NULL,
  `barangay` varchar(20) NOT NULL,
  `totalhousehold` int(5) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `householdnum` int(11) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `houseOwnershipStatus` varchar(50) NOT NULL,
  `landOwnershipStatus` varchar(20) NOT NULL,
  `lightningFacilities` varchar(20) NOT NULL,
  `formerAddress` text NOT NULL,
  `image` text NOT NULL,
  `purok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbunakan`
--

INSERT INTO `tblbunakan` (`id`, `lname`, `fname`, `mname`, `bdate`, `bplace`, `age`, `barangay`, `totalhousehold`, `civilstatus`, `householdnum`, `religion`, `nationality`, `gender`, `houseOwnershipStatus`, `landOwnershipStatus`, `lightningFacilities`, `formerAddress`, `image`, `purok`) VALUES
(1, 'Alegre', 'Kurt Brayan', 'M', '2002-05-03', 'Bunakan', 22, 'Bunakan', 3, 'Single', 3, 'Catholic', 'Filipino', 'Male', 'Own Home', 'Owned', 'Electric', 'Bunakan', '1722693603873_448684245_1686707815457085_1699584897398129282_n.jpg', 'Bilabid');

-- --------------------------------------------------------

--
-- Table structure for table `tblclearance`
--

CREATE TABLE `tblclearance` (
  `id` int(11) NOT NULL,
  `clearanceNo` int(11) NOT NULL,
  `residentid` int(11) NOT NULL,
  `purpose` text NOT NULL,
  `orNo` int(11) NOT NULL,
  `samount` int(11) NOT NULL,
  `dateRecorded` date NOT NULL,
  `recordedBy` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclearance`
--

INSERT INTO `tblclearance` (`id`, `clearanceNo`, `residentid`, `purpose`, `orNo`, `samount`, `dateRecorded`, `recordedBy`, `status`) VALUES
(12, 1, 32, 'For Financial assistance', 213, 150, '2024-07-13', 'admin', ''),
(28, 2, 34, 'Pangguna', 1231, 123, '2024-07-15', 'tabagak', ''),
(30, 3, 37, 'Pangguna', 213, 31214, '2024-07-17', 'admin', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblclearbun`
--

CREATE TABLE `tblclearbun` (
  `id` int(11) NOT NULL,
  `clearanceNo` int(11) NOT NULL,
  `residentid` int(11) NOT NULL,
  `purpose` text NOT NULL,
  `orNo` int(11) NOT NULL,
  `samount` int(11) NOT NULL,
  `dateRecorded` date NOT NULL,
  `recordedBy` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclearbun`
--

INSERT INTO `tblclearbun` (`id`, `clearanceNo`, `residentid`, `purpose`, `orNo`, `samount`, `dateRecorded`, `recordedBy`, `status`) VALUES
(1, 231, 1, '123', 123, 123, '2024-08-03', 'bunakan', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblhousehold`
--

CREATE TABLE `tblhousehold` (
  `id` int(11) NOT NULL,
  `householdno` int(11) NOT NULL,
  `totalhouseholdmembers` int(2) NOT NULL,
  `headoffamily` varchar(100) NOT NULL,
  `purok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblhousehold`
--

INSERT INTO `tblhousehold` (`id`, `householdno`, `totalhouseholdmembers`, `headoffamily`, `purok`) VALUES
(62, 1, 0, '34', ''),
(63, 2, 0, '32', '');

-- --------------------------------------------------------

--
-- Table structure for table `tblhouseholdbun`
--

CREATE TABLE `tblhouseholdbun` (
  `id` int(11) NOT NULL,
  `householdno` int(11) NOT NULL,
  `totalhouseholdmembers` int(2) NOT NULL,
  `headoffamily` varchar(100) NOT NULL,
  `purok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblhouseholdbun`
--

INSERT INTO `tblhouseholdbun` (`id`, `householdno`, `totalhouseholdmembers`, `headoffamily`, `purok`) VALUES
(1, 3, 0, '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `logdate` datetime NOT NULL,
  `action` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbllogs`
--

INSERT INTO `tbllogs` (`id`, `user`, `logdate`, `action`) VALUES
(473, 'Brgy.Bunakan', '2024-08-03 08:42:20', 'Added certificate of residency purpose of baha'),
(474, 'Brgy.Bunakan', '2024-08-03 09:04:03', 'Added Household Number 2');

-- --------------------------------------------------------

--
-- Table structure for table `tblofficial`
--

CREATE TABLE `tblofficial` (
  `id` int(11) NOT NULL,
  `sPosition` varchar(50) NOT NULL,
  `completeName` text NOT NULL,
  `pcontact` varchar(20) NOT NULL,
  `paddress` text NOT NULL,
  `termStart` date NOT NULL,
  `termEnd` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblofficial`
--

INSERT INTO `tblofficial` (`id`, `sPosition`, `completeName`, `pcontact`, `paddress`, `termStart`, `termEnd`, `status`) VALUES
(20, 'Mayor', 'Villaceran Romeo A', '09269948693', 'Pili Madridejos Cebu', '2019-01-22', '2027-01-13', 'Ongoing Term'),
(25, 'Vice Mayor', 'Sevillejo Gilbert S', '24662452521', 'Tabagak Madridejos Cebu', '2023-01-10', '2026-02-18', 'Ongoing Term');

-- --------------------------------------------------------

--
-- Table structure for table `tblpermit`
--

CREATE TABLE `tblpermit` (
  `id` int(11) NOT NULL,
  `Name` varchar(11) NOT NULL,
  `businessName` text NOT NULL,
  `businessAddress` text NOT NULL,
  `typeOfBusiness` varchar(50) NOT NULL,
  `orNo` int(11) NOT NULL,
  `samount` int(11) NOT NULL,
  `dateRecorded` date NOT NULL,
  `recordedBy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblrecidency`
--

CREATE TABLE `tblrecidency` (
  `id` int(20) NOT NULL,
  `resident` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrecidency`
--

INSERT INTO `tblrecidency` (`id`, `resident`, `purpose`, `purok`) VALUES
(1, '32', 'baha', 'Lamon-Lamon'),
(2, '34', 'baha', 'Lamon-Lamon');

-- --------------------------------------------------------

--
-- Table structure for table `tblrecidencybun`
--

CREATE TABLE `tblrecidencybun` (
  `id` int(20) NOT NULL,
  `resident` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrecidencybun`
--

INSERT INTO `tblrecidencybun` (`id`, `resident`, `purpose`, `purok`) VALUES
(1, '1', 'baha', 'Bilabid');

-- --------------------------------------------------------

--
-- Table structure for table `tblresident`
--

CREATE TABLE `tblresident` (
  `id` int(11) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `mname` varchar(20) NOT NULL,
  `bdate` varchar(20) NOT NULL,
  `bplace` text NOT NULL,
  `age` int(11) NOT NULL,
  `barangay` varchar(120) NOT NULL,
  `totalhousehold` int(5) NOT NULL,
  `civilstatus` varchar(20) NOT NULL,
  `householdnum` int(11) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `houseOwnershipStatus` varchar(50) NOT NULL,
  `landOwnershipStatus` varchar(20) NOT NULL,
  `lightningFacilities` varchar(20) NOT NULL,
  `formerAddress` text NOT NULL,
  `image` text NOT NULL,
  `purok` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblresident`
--

INSERT INTO `tblresident` (`id`, `lname`, `fname`, `mname`, `bdate`, `bplace`, `age`, `barangay`, `totalhousehold`, `civilstatus`, `householdnum`, `religion`, `nationality`, `gender`, `houseOwnershipStatus`, `landOwnershipStatus`, `lightningFacilities`, `formerAddress`, `image`, `purok`) VALUES
(32, 'Sevillejo', 'Gilbert', 'J', '2002-05-05', 'Tabagak', 22, 'Tabagak', 10, 'Single', 2, 'Catholic', 'Filipino', 'Male', 'Own Home', 'Owned', 'Electric', 'Tabagak', 'IMG_20240420_120859_607.jpg', 'Lamon-Lamon'),
(34, 'Quijada', 'NeilAlbert', 'Q', '2003-07-03', 'Tabagak', 21, 'Tabagak', 13, 'Single', 1, 'Catholic', 'Filipino', 'Male', 'Own Home', 'Owned', 'Electric', 'Tabagak', 'IMG_20240420_120813_122.jpg', 'Lower-Bangus'),
(37, 'Villacarlos', 'Shara', 'D', '2005-05-08', 'Tabagak', 19, 'Tabagak', 12, 'Single', 3, 'Catholic', 'Filipino', 'Female', 'Own Home', 'Owned', 'Electric', 'Tabagak', '1721054741286_448538302_483961034074211_8876714202630735278_n.jpg', 'Lawihan');

-- --------------------------------------------------------

--
-- Table structure for table `tblstaff`
--

CREATE TABLE `tblstaff` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL UNIQUE,
  `username` varchar(20) NOT NULL UNIQUE,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstaff`
--

INSERT INTO `tblstaff` (`id`, `name`, `username`, `password`) VALUES
(1, 'Brgy.Tabagak', 'tabagak', 'tabagak'),
(2, 'Brgy.Bunakan', 'bunakan', 'bunakan'),
(3, 'Brgy.Tugas', 'tugas', 'tugas'),
(4, 'Brgy.Talangnan', 'talangnan', 'talangnan'),
(5, 'Brgy.Kodia', 'kodia', 'kodia'),
(6, 'Brgy.Mancilang', 'mancilang', 'mancilang'),
(7, 'Brgy.Poblacion', 'poblacion', 'poblacion'),
(8, 'Brgy.Tarong', 'tarong', 'tarong'),
(9, 'Brgy.San Agustin', 'sanagustin', 'sanagustin'),
(10, 'Brgy.Kangwayan', 'kangwayan', 'kangwayan'),
(11, 'Brgy.Pili', 'pili', 'pili'),
(12, 'Brgy.Kaongkod', 'kaongkod', 'kaongkod'),
(13, 'Brgy.Malbago', 'malbago', 'malbago'),
(14, 'Brgy.Maalat', 'maalat', 'maalat');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`id`, `username`, `password`, `type`) VALUES
(1, 'admin', 'admin', 'administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbunakan`
--
ALTER TABLE `tblbunakan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblclearance`
--
ALTER TABLE `tblclearance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblclearbun`
--
ALTER TABLE `tblclearbun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblhousehold`
--
ALTER TABLE `tblhousehold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblhouseholdbun`
--
ALTER TABLE `tblhouseholdbun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbllogs`
--
ALTER TABLE `tbllogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblofficial`
--
ALTER TABLE `tblofficial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpermit`
--
ALTER TABLE `tblpermit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblrecidency`
--
ALTER TABLE `tblrecidency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblrecidencybun`
--
ALTER TABLE `tblrecidencybun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblresident`
--
ALTER TABLE `tblresident`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstaff`
--
ALTER TABLE `tblstaff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbunakan`
--
ALTER TABLE `tblbunakan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblclearance`
--
ALTER TABLE `tblclearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblclearbun`
--
ALTER TABLE `tblclearbun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblhousehold`
--
ALTER TABLE `tblhousehold`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tblhouseholdbun`
--
ALTER TABLE `tblhouseholdbun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbllogs`
--
ALTER TABLE `tbllogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=475;

--
-- AUTO_INCREMENT for table `tblofficial`
--
ALTER TABLE `tblofficial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tblpermit`
--
ALTER TABLE `tblpermit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tblrecidency`
--
ALTER TABLE `tblrecidency`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblrecidencybun`
--
ALTER TABLE `tblrecidencybun`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblresident`
--
ALTER TABLE `tblresident`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tblstaff`
--
ALTER TABLE `tblstaff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
