-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 05, 2026 at 11:14 AM
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
('JohnBenedict', 'Villegas', 'ADMINISTRATOR', 'ACTIVE', 'Villegas', '16/01/26'),
('tech', 'tech123', 'TECHNICAL', 'ACTIVE', 'admin', '14/01/26');

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
('07/10/2025', '8:06 pm', 'Clear all logs', 'Logs Managements', 'admin', 'N/A'),
('21/01/2026', '09:08:48am', 'Update account', 'Accounts Management', 'JohnBenedict', 'JohnBenedict'),
('21/01/2026', '09:09:08am', 'Update account', 'Accounts Management', 'JohnBenedict', 'JohnBenedict'),
('21/01/2026', '09:26:32am', 'Delete account', 'Accounts Management', 'JohnBenedict', 'HEELO'),
('23/01/2026', '09:23:22am', 'Update account', 'Accounts Management', 'JohnBenedict', 'testuser'),
('23/01/2026', '09:24:15am', 'Delete account', 'Accounts Management', 'JohnBenedict', 'testuser'),
('29/01/2026', '02:45:11pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'test'),
('29/01/2026', '02:47:30pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'test'),
('29/01/2026', '02:47:45pm', 'Delete account', 'Accounts Management', 'JohnBenedict', 'test'),
('29/01/2026', '03:00:48pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'testuser'),
('29/01/2026', '03:01:30pm', 'Delete account', 'Accounts Management', 'JohnBenedict', 'testuser'),
('31/01/2026', '08:14:46pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'admin'),
('31/01/2026', '08:29:00pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'fa'),
('31/01/2026', '08:29:06pm', 'Delete account', 'Accounts Management', 'JohnBenedict', 'fa'),
('31/01/2026', '08:44:56pm', 'Update account', 'Accounts Management', 'JohnBenedict', 'tech'),
('05/02/2026', '12:10:19pm', 'Update equipments', 'Equipments Managemen', 'admin', 'FAFA'),
('05/02/2026', '12:15:14pm', 'Delete equipments', 'Equipments Managemen', 'admin', 'FAFA'),
('05/02/2026', '12:15:20pm', 'Delete equipments', 'Equipments Managemen', 'admin', '34242'),
('05/02/2026', '12:42:03pm', 'Update equipments', 'Equipments Managemen', 'JohnBenedict', 'ASSET323'),
('05/02/2026', '12:43:21pm', 'Update equipments', 'Equipments Managemen', 'JohnBenedict', 'ASSET323'),
('05/02/2026', '12:49:31pm', 'Delete equipments', 'Equipments Managemen', 'JohnBenedict', 'ASSET323');

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
