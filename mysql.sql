-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 24, 2024 at 07:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `miraiadmin`
--

CREATE TABLE `miraiadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraiadmin`
--

INSERT INTO `miraiadmin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$aq5/XZkcU.w4qvwz7n4LMO3BGr/sj2J5OCt.Kvw4gb76TMxaLVlcy', '2024-10-21 08:40:34');

-- --------------------------------------------------------

--
-- Table structure for table `miraiclass`
--

CREATE TABLE `miraiclass` (
  `ID` int(11) NOT NULL,
  `className` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraiclass`
--

INSERT INTO `miraiclass` (`ID`, `className`, `created_at`) VALUES
(1, 'One', '2024-10-21 11:44:04'),
(2, 'Two', '2024-10-21 12:09:08'),
(4, 'Nursery', '2024-10-23 12:23:04'),
(5, 'Lkg', '2024-10-23 12:23:32'),
(6, 'Ukg', '2024-10-23 12:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `miraistudent`
--

CREATE TABLE `miraistudent` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `emailAddress` varchar(255) DEFAULT NULL,
  `otherName` varchar(100) DEFAULT NULL,
  `admissionNumber` varchar(255) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraistudent`
--

INSERT INTO `miraistudent` (`id`, `firstName`, `lastName`, `emailAddress`, `otherName`, `admissionNumber`, `class_id`, `created_at`) VALUES
(12, 'Thomas', 'Omari', 'thomas@gmail.com', 'mas', 'AMS005', 1, '2022-10-30 18:30:00'),
(13, 'Samuel', 'Ondieki', NULL, 'none', 'AMS007', 1, '2022-10-30 18:30:00'),
(14, 'Milagros', 'Oloo', NULL, 'none', 'AMS011', 1, '2022-10-30 18:30:00'),
(15, 'Luis', 'Ayo', NULL, 'none', 'AMS012', 1, '2022-10-30 18:30:00'),
(16, 'Sandra', 'Sagero', NULL, 'none', 'AMS015', 1, '2022-10-30 18:30:00'),
(17, 'Smith', 'Makori', NULL, 'Mack', 'AMS017', 1, '2022-10-30 18:30:00'),
(18, 'Juliana', 'John', 'Juliana@gmail.com', 'none', 'AMS019', 6, '2022-10-30 18:30:00'),
(19, 'Richard', 'hard', 'Richard@gmail.com', 'none', 'AMS021', 6, '2022-10-30 18:30:00'),
(20, 'Jon', 'Mbeeka', NULL, 'none', 'AMS110', 4, '2022-10-06 18:30:00'),
(21, 'Aida', 'Moraa', NULL, 'none', 'AMS133', 4, '2022-10-06 18:30:00'),
(22, 'Miguel', 'Bush', NULL, 'none', 'AMS135', 4, '2022-10-06 18:30:00'),
(23, 'Sergio', 'Hammons', NULL, 'none', 'AMS144', 4, '2022-10-06 18:30:00'),
(24, 'Lyn', 'Rogers', NULL, 'none', 'AMS148', 4, '2022-10-06 18:30:00'),
(25, 'James', 'Dominick', NULL, 'none', 'AMS151', 4, '2022-10-06 18:30:00'),
(26, 'Ethel', 'Quin', NULL, 'none', 'AMS159', 4, '2022-10-06 18:30:00'),
(27, 'Roland', 'Estrada', NULL, 'none', 'AMS161', 4, '2022-10-06 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `miraitakeattendance`
--

CREATE TABLE `miraitakeattendance` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `status` enum('Present','Absent') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraitakeattendance`
--

INSERT INTO `miraitakeattendance` (`id`, `teacher_id`, `class_id`, `student_id`, `attendance_date`, `status`, `created_at`) VALUES
(41, 2, 4, 20, '2024-10-24', 'Present', '2024-10-24 05:52:04'),
(42, 2, 4, 21, '2024-10-24', 'Present', '2024-10-24 05:52:04'),
(43, 2, 4, 22, '2024-10-24', 'Absent', '2024-10-24 05:52:04'),
(44, 2, 4, 23, '2024-10-24', 'Absent', '2024-10-24 05:52:04'),
(45, 2, 4, 24, '2024-10-24', 'Present', '2024-10-24 05:52:04'),
(46, 2, 4, 25, '2024-10-24', 'Present', '2024-10-24 05:52:04'),
(47, 2, 4, 26, '2024-10-24', 'Absent', '2024-10-24 05:52:04'),
(48, 2, 4, 27, '2024-10-24', 'Present', '2024-10-24 05:52:04'),
(49, 2, 5, 11, '2024-10-24', 'Present', '2024-10-24 05:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `miraiteacherclasses`
--

CREATE TABLE `miraiteacherclasses` (
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraiteacherclasses`
--

INSERT INTO `miraiteacherclasses` (`teacher_id`, `class_id`, `student_id`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(2, 4, NULL),
(2, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `miraiteachers`
--

CREATE TABLE `miraiteachers` (
  `Id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(15) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miraiteachers`
--

INSERT INTO `miraiteachers` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `profile_image`, `created_at`) VALUES
(1, 'John', 'carter', 'carter@gmail.com', '$2y$10$O3hIDWw0NZ1jK4UYkBptpegvvpgPAMd6C6FnFD9t2IcF7RcHiBisa', '019938883883', 'ra-jpegPM.png', '2024-10-21 11:47:53'),
(2, 'Micky', 'Aus', 'aus@gmail.com', '$2y$10$cKsgwG44QaEBEZebNGs08OUU1ZVBdhfFfUPXWYKXnF3Mt5Vx9Msam', '019938883883', 'kii.jpeg', '2024-10-21 12:26:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `miraiadmin`
--
ALTER TABLE `miraiadmin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `miraiclass`
--
ALTER TABLE `miraiclass`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `miraistudent`
--
ALTER TABLE `miraistudent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `miraitakeattendance`
--
ALTER TABLE `miraitakeattendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `miraiteacherclasses`
--
ALTER TABLE `miraiteacherclasses`
  ADD PRIMARY KEY (`teacher_id`,`class_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `miraiteachers`
--
ALTER TABLE `miraiteachers`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `miraiadmin`
--
ALTER TABLE `miraiadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `miraiclass`
--
ALTER TABLE `miraiclass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `miraistudent`
--
ALTER TABLE `miraistudent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `miraitakeattendance`
--
ALTER TABLE `miraitakeattendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `miraiteachers`
--
ALTER TABLE `miraiteachers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `miraitakeattendance`
--
ALTER TABLE `miraitakeattendance`
  ADD CONSTRAINT `miraitakeattendance_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `miraiteachers` (`Id`),
  ADD CONSTRAINT `miraitakeattendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `miraiclass` (`ID`),
  ADD CONSTRAINT `miraitakeattendance_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `miraistudent` (`id`);

--
-- Constraints for table `miraiteacherclasses`
--
ALTER TABLE `miraiteacherclasses`
  ADD CONSTRAINT `miraiteacherclasses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `miraiteachers` (`Id`),
  ADD CONSTRAINT `miraiteacherclasses_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `miraiclass` (`ID`),
  ADD CONSTRAINT `miraiteacherclasses_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `miraistudent` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
