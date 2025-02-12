-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 05:26 PM
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
-- Database: `jonard_schools`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_table`
--

CREATE TABLE `class_table` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_table`
--

INSERT INTO `class_table` (`id`, `class_name`, `school_id`) VALUES
(3, 'primary one', 2),
(4, 'primary two', 2),
(5, 'primary six', 2),
(6, 'primary four', 2),
(7, 'primary seven', 2),
(8, 'primary three', 2);

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `start_time` varchar(15) NOT NULL,
  `end_time` varchar(15) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examination`
--

INSERT INTO `examination` (`id`, `name`, `class_id`, `subject_id`, `start_time`, `end_time`, `date`, `school_id`) VALUES
(2, 'Beau Benson', 7, 7, '21:44', '11:25', '2021-10-06', 2);

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `author` varchar(150) NOT NULL,
  `status` varchar(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library`
--

INSERT INTO `library` (`id`, `name`, `class_id`, `subject_id`, `author`, `status`, `school_id`) VALUES
(2, 'Mark Barron', 7, 4, 'Culpa commodo explic', 'active', 2),
(3, 'Nicole Espinoza', 3, 8, 'Denzal', 'inactive', 2);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(200) NOT NULL,
  `email` varchar(150) NOT NULL,
  `level` varchar(50) NOT NULL,
  `location` varchar(200) NOT NULL,
  `school_badge` varchar(255) NOT NULL,
  `school_code` varchar(20) NOT NULL,
  `slogun` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_name`, `email`, `level`, `location`, `school_badge`, `school_code`, `slogun`, `created_at`) VALUES
(2, 'Kikajjo SDA high sch', 'ssewankamboderick@gmail.com', 'Secondary', 'Matugga', 'uploads/school_badges/1739292058_pngtree-school-logo-design-template-vector-png-image_6705854.png', '462407', 'KISDA', '2025-02-11'),
(3, 'Hilltop Senior secondary school', 'bnanbinomugisha9@gmail.com', 'Secondary', 'Kampala', 'uploads/school_badges/1739377372_NewLogo2.png', '356916', 'HILL', '2025-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `date_of_birth` varchar(10) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `parent_contact` varchar(15) NOT NULL,
  `school_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `status` varchar(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `fname`, `lname`, `gender`, `date_of_birth`, `religion`, `class_id`, `parent_contact`, `school_id`, `image`, `status`, `created_at`) VALUES
(3, 'Adena', 'Mccullough', 'Male', '2014-07-11', 'Islam', 5, 'Ut culpa quos q', 2, 'uploads/students/1739362672_Image 9.jpg', 'Active', '2025-02-12'),
(10, 'Tara', 'Frost', 'Female', '2012-11-27', 'Islam', 4, 'Eum eligendi di', 2, 'uploads/students/1739370251_Image 25.jpg', 'Active', '2025-02-12'),
(11, 'Amethyst', 'Harmon', 'Female', '2022-11-06', 'Born Again', 3, 'In cumque culpa', 2, 'uploads/students/1739370405_Image 19.jpg', 'Inactive', '2025-02-12'),
(12, 'Derick', 'Williamson', 'Female', '2006-02-16', 'Born Again', 8, 'Nihil at iure i', 2, 'uploads/students/1739374013_Image 22.jpg', 'Active', '2025-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `school_id`) VALUES
(4, 'English', 2),
(7, 'Math', 2),
(8, 'science', 2);

-- --------------------------------------------------------

--
-- Table structure for table `teachers_table`
--

CREATE TABLE `teachers_table` (
  `id` int(11) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `date_of_birth` varchar(20) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `joining_date` varchar(25) NOT NULL,
  `qualifications` varchar(100) NOT NULL,
  `experience` varchar(20) NOT NULL,
  `school_id` int(10) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers_table`
--

INSERT INTO `teachers_table` (`id`, `fname`, `lname`, `gender`, `date_of_birth`, `contact`, `joining_date`, `qualifications`, `experience`, `school_id`, `status`, `created_at`) VALUES
(3, 'Lee', 'Juarez', 'Female', '1971-04-27', 'Consectetur elit e', '1970-01-05', 'Id libero doloremque', 'Eveniet quas aut eu', 2, 'Active', '2025-02-12'),
(4, 'Neville', 'Valencia', 'Female', '2022-05-15', 'Culpa quis velit ips', '2013-08-10', 'Qui dolor ut eveniet', 'Aute laborum fuga A', 2, 'Active', '2025-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `time_table`
--

CREATE TABLE `time_table` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `date` varchar(11) NOT NULL,
  `start_time` varchar(11) NOT NULL,
  `end_time` varchar(11) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_table`
--

INSERT INTO `time_table` (`id`, `teacher_id`, `class_id`, `subject_id`, `date`, `start_time`, `end_time`, `school_id`) VALUES
(1, 4, 7, 8, '1983-06-29', '01:40', '17:33', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `password` varchar(150) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact`, `password`, `gender`, `role`, `school_id`, `status`) VALUES
(2, 'Denzal', 'admin@gmail.com', '', '$2y$10$TtwyEyFRCGqzYZGEllBhVuqztj0raFWcps74a8/zdK4tAANq8mcLK', 'male', 'super_admin', 0, 'active'),
(3, 'Ssewankambo derick', 'benja@gmail.com', '0756856058', '$2y$10$oey0XQlFrdNL3gc5gOx.3OM0AV39NxoDLW2dVFeLZSh80N7SQGpK6', 'male', 'school_admin', 2, 'Active'),
(4, 'Derick', 'denzal@gmail.com', '0756856058', '$2y$10$DDH6AYxqZ2qPh0QFVFI2..pfcSo1sIdo3hWTJLNDUNBBcbDVbidOy', 'male', 'school_admin', 3, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_table`
--
ALTER TABLE `class_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_name` (`class_name`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`),
  ADD KEY `date` (`date`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_name` (`name`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `author` (`author`),
  ADD KEY `status` (`status`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_name` (`school_name`),
  ADD KEY `level` (`level`),
  ADD KEY `location` (`location`),
  ADD KEY `badge` (`school_badge`),
  ADD KEY `slogun` (`slogun`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `school_code` (`school_code`),
  ADD KEY `email` (`email`),
  ADD KEY `school_badge` (`school_badge`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fname` (`fname`),
  ADD KEY `lname` (`lname`),
  ADD KEY `gender` (`gender`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `religion` (`religion`),
  ADD KEY `student_class` (`class_id`),
  ADD KEY `parent_contact` (`parent_contact`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `image` (`image`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_name` (`subject_name`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `teachers_table`
--
ALTER TABLE `teachers_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fname` (`fname`),
  ADD KEY `lname` (`lname`),
  ADD KEY `gender` (`gender`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `contact` (`contact`),
  ADD KEY `joining_date` (`joining_date`),
  ADD KEY `qualifications` (`qualifications`),
  ADD KEY `experience` (`experience`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `time_table`
--
ALTER TABLE `time_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `date` (`date`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `password` (`password`),
  ADD KEY `gender` (`gender`),
  ADD KEY `role` (`role`),
  ADD KEY `status` (`status`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `contact` (`contact`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_table`
--
ALTER TABLE `class_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `library`
--
ALTER TABLE `library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teachers_table`
--
ALTER TABLE `teachers_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `time_table`
--
ALTER TABLE `time_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
