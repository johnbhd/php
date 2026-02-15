-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 15, 2026 at 03:53 PM
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
-- Database: `itc127-it2a-2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`username`, `password`, `usertype`, `status`, `createdby`, `datecreated`) VALUES
('admin', 'admin123', 'ADMINISTRATOR', 'ACTIVE', 'admin', '14/01/26'),
('JohnBenedict', 'Villegas', 'USER', 'ACTIVE', 'admin', '16/01/26'),
('tech', 'tech123', 'TECHNICAL', 'ACTIVE', 'admin', '14/01/26'),
('user', 'user123', 'USER', 'ACTIVE', 'admin', '15/02/26');

-- --------------------------------------------------------

--
-- Table structure for table `tblequipments`
--

CREATE TABLE `tblequipments` (
  `asset_number` varchar(100) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `type` enum('CPU','Printer','Monitor','Keyboard','Mouse','AVR','Projector','MAC') NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `year_model` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `branch` varchar(100) NOT NULL,
  `department` varchar(150) NOT NULL,
  `status` enum('WORKING','ON-REPAIR','RETIRED') DEFAULT 'WORKING',
  `created_by` varchar(50) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblequipments`
--

INSERT INTO `tblequipments` (`asset_number`, `serial_number`, `type`, `manufacturer`, `year_model`, `description`, `branch`, `department`, `status`, `created_by`, `date_created`) VALUES
('ASSET001', 'SN123456', 'Monitor', 'Dells', 2022, '24-inch LED Monitor', 'Jose Abad Santos Campus (AU Pasay)', 'IT Department', 'WORKING', 'admin', '2026-02-01 22:19:34'),
('ASSET0012', 'SN123434', 'AVR', 'Canon', 2009, 'voltage supply to electrical appliances', 'Andres Bonifacio Campus (AU Pasig)', 'College of Nursing', 'WORKING', 'admin', '2026-02-04 16:30:24'),
('ASSET002', 'SN123457', 'Keyboard', 'HP', 2015, 'Core i7 Desktop', 'Plaridel Campus (AU Mandaluyong)', 'Graduate School of Business', 'WORKING', 'admin', '2026-02-01 22:19:34'),
('ASSET003', 'SN123458', 'Keyboard', 'Canon', 2020, 'Laser Printer', 'Jose Rizal Campus (AU Malabon)', 'College of Nursing', 'RETIRED', 'admin', '2026-02-01 22:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(20) NOT NULL,
  `timelog` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `performedby` varchar(50) NOT NULL,
  `performedto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbllogs`
--

INSERT INTO `tbllogs` (`datelog`, `timelog`, `action`, `module`, `performedby`, `performedto`) VALUES
('15/02/2026', '10:36:48pm', 'Update ticket', 'Ticketing Management', 'JohnBenedict', '20260215223331'),
('15/02/2026', '10:37:27pm', 'Delete ticket', 'Ticketing Management', 'JohnBenedict', '20260215223331');

-- --------------------------------------------------------

--
-- Table structure for table `tbltickets`
--

CREATE TABLE `tbltickets` (
  `ticket_number` varchar(100) NOT NULL,
  `problem` varchar(100) NOT NULL,
  `details` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `date_created` varchar(50) NOT NULL,
  `assigned_to` varchar(50) DEFAULT NULL,
  `date_assigned` varchar(50) DEFAULT NULL,
  `date_completed` varchar(50) DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `date_approved` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltickets`
--

INSERT INTO `tbltickets` (`ticket_number`, `problem`, `details`, `status`, `created_by`, `date_created`, `assigned_to`, `date_assigned`, `date_completed`, `approved_by`, `date_approved`) VALUES
('20260215204038', 'Hardware', 'Error PC PH 206 cant open', 'PENDING', 'user', '02-15-26 20:40:38', NULL, NULL, NULL, NULL, NULL),
('20260215204116', 'Connection', 'No internet connection PH 210', 'PENDING', 'user', '02-15-26 20:41:16', NULL, NULL, NULL, NULL, NULL),
('20260215204206', 'Software', 'Error opening Xamp phpmyadmin', 'PENDING', 'user', '02-15-26 20:42:06', NULL, NULL, NULL, NULL, NULL),
('20260215223358', 'Hardware', 'Error mouse cord PC 2 in PH 203', 'PENDING', 'JohnBenedict', '02-15-26 22:34:14', NULL, NULL, NULL, NULL, NULL),
('20260215223422', 'Software', 'Error xampp software not opening in PC 5 in PH 210', 'PENDING', 'JohnBenedict', '02-15-26 22:34:54', NULL, NULL, NULL, NULL, NULL),
('20260215223502', 'Connection', 'No internet connection for PC 16 in 205', 'PENDING', 'JohnBenedict', '02-15-26 22:35:37', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tblequipments`
--
ALTER TABLE `tblequipments`
  ADD UNIQUE KEY `asset_number` (`asset_number`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD PRIMARY KEY (`ticket_number`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblequipments`
--
ALTER TABLE `tblequipments`
  ADD CONSTRAINT `tblequipments_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `tblaccounts` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
