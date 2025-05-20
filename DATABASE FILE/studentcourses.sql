-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 07:25 AM
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
-- Database: `studentcourses`
--

-- --------------------------------------------------------

--
-- Table structure for table `late_attendance_log`
--

CREATE TABLE `late_attendance_log` (
  `id` int(11) NOT NULL,
  `TeacherId` int(11) DEFAULT NULL,
  `CourseId` int(11) DEFAULT NULL,
  `ShiftId` int(11) DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'IT', 'Admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `tblattend`
--

CREATE TABLE `tblattend` (
  `id` int(3) NOT NULL,
  `reg` varchar(50) DEFAULT NULL,
  `course_id` int(3) DEFAULT NULL,
  `shift_id` int(3) DEFAULT NULL,
  `class_number` int(11) DEFAULT NULL,
  `attendanceStatus` varchar(20) NOT NULL,
  `attendance_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE `tblcourse` (
  `Id` int(3) NOT NULL,
  `courseName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionals_course_shift`
--

CREATE TABLE `tblsessionals_course_shift` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `marks` int(2) NOT NULL,
  `deadline` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblshift`
--

CREATE TABLE `tblshift` (
  `id` int(3) NOT NULL,
  `shiftName` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblshift_course`
--

CREATE TABLE `tblshift_course` (
  `id` int(11) NOT NULL,
  `course_id` int(3) NOT NULL,
  `shift_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `id` int(3) NOT NULL,
  `reg` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expiry` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_course_shift`
--

CREATE TABLE `tblstudent_course_shift` (
  `id` int(3) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `Id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `batchCor` tinyint(1) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher_course_assignment`
--

CREATE TABLE `tblteacher_course_assignment` (
  `id` int(11) NOT NULL,
  `teacherId` int(11) DEFAULT NULL,
  `courseId` int(11) DEFAULT NULL,
  `shiftId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `CourseId` int(11) NOT NULL,
  `ShiftId` int(11) NOT NULL,
  `Day` varchar(15) NOT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `late_attendance_log`
--
ALTER TABLE `late_attendance_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TeacherId` (`TeacherId`),
  ADD KEY `CourseId` (`CourseId`),
  ADD KEY `ShiftId` (`ShiftId`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattend`
--
ALTER TABLE `tblattend`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attend_course` (`course_id`),
  ADD KEY `fk_attend_shift` (`shift_id`);

--
-- Indexes for table `tblcourse`
--
ALTER TABLE `tblcourse`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionals_course_shift`
--
ALTER TABLE `tblsessionals_course_shift`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblshift`
--
ALTER TABLE `tblshift`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblshift_course`
--
ALTER TABLE `tblshift_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course_id` (`course_id`),
  ADD KEY `idx_shift_id` (`shift_id`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudent_course_shift`
--
ALTER TABLE `tblstudent_course_shift`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `tblteacher_course_assignment`
--
ALTER TABLE `tblteacher_course_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacherId` (`teacherId`),
  ADD KEY `courseId` (`courseId`),
  ADD KEY `shiftId` (`shiftId`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetable_ibfk_1` (`CourseId`),
  ADD KEY `timetable_ibfk_2` (`ShiftId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `late_attendance_log`
--
ALTER TABLE `late_attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattend`
--
ALTER TABLE `tblattend`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47535;

--
-- AUTO_INCREMENT for table `tblcourse`
--
ALTER TABLE `tblcourse`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `tblsessionals_course_shift`
--
ALTER TABLE `tblsessionals_course_shift`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblshift`
--
ALTER TABLE `tblshift`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblshift_course`
--
ALTER TABLE `tblshift_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;

--
-- AUTO_INCREMENT for table `tblstudent_course_shift`
--
ALTER TABLE `tblstudent_course_shift`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2425;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tblteacher_course_assignment`
--
ALTER TABLE `tblteacher_course_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `late_attendance_log`
--
ALTER TABLE `late_attendance_log`
  ADD CONSTRAINT `late_attendance_log_ibfk_1` FOREIGN KEY (`TeacherId`) REFERENCES `tblteacher` (`Id`),
  ADD CONSTRAINT `late_attendance_log_ibfk_2` FOREIGN KEY (`CourseId`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `late_attendance_log_ibfk_3` FOREIGN KEY (`ShiftId`) REFERENCES `tblshift` (`id`);

--
-- Constraints for table `tblattend`
--
ALTER TABLE `tblattend`
  ADD CONSTRAINT `fk_attend_course` FOREIGN KEY (`course_id`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `fk_attend_shift` FOREIGN KEY (`shift_id`) REFERENCES `tblshift` (`id`);

--
-- Constraints for table `tblsessionals_course_shift`
--
ALTER TABLE `tblsessionals_course_shift`
  ADD CONSTRAINT `tblsessionals_course_shift_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `tblcourse` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblsessionals_course_shift_ibfk_2` FOREIGN KEY (`shift_id`) REFERENCES `tblshift` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblshift_course`
--
ALTER TABLE `tblshift_course`
  ADD CONSTRAINT `fk_course` FOREIGN KEY (`course_id`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `fk_shift` FOREIGN KEY (`shift_id`) REFERENCES `tblshift` (`id`);

--
-- Constraints for table `tblstudent_course_shift`
--
ALTER TABLE `tblstudent_course_shift`
  ADD CONSTRAINT `tblstudent_course_shift_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`id`),
  ADD CONSTRAINT `tblstudent_course_shift_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `tblstudent_course_shift_ibfk_3` FOREIGN KEY (`shift_id`) REFERENCES `tblshift` (`id`);

--
-- Constraints for table `tblteacher_course_assignment`
--
ALTER TABLE `tblteacher_course_assignment`
  ADD CONSTRAINT `tblteacher_course_assignment_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `tblteacher` (`Id`),
  ADD CONSTRAINT `tblteacher_course_assignment_ibfk_2` FOREIGN KEY (`courseId`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `tblteacher_course_assignment_ibfk_3` FOREIGN KEY (`shiftId`) REFERENCES `tblshift` (`id`);

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`CourseId`) REFERENCES `tblcourse` (`Id`),
  ADD CONSTRAINT `timetable_ibfk_2` FOREIGN KEY (`ShiftId`) REFERENCES `tblshift` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
