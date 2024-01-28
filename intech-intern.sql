-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2024 at 06:27 AM
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
-- Database: `intech-intern`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `supervisor_id` varchar(20) DEFAULT NULL,
  `appropriateness` int(11) DEFAULT NULL,
  `training` int(11) DEFAULT NULL,
  `difficulty_level` int(11) DEFAULT NULL,
  `teamwork` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessment_id`, `user_id`, `supervisor_id`, `appropriateness`, `training`, `difficulty_level`, `teamwork`, `timestamp`) VALUES
(1, '2019554', '1234', 85, 90, 80, 88, '2023-08-15 10:00:00'),
(2, '2014325', '1234', 90, 85, 75, 92, '2023-08-15 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(0, 2019554, 1234, 'Hi, Supervisor! How are you?', '2023-07-20 01:30:00'),
(1, 1234, 2014325, 'Hello! I\'m doing well. How can I help you today?', '2023-07-20 01:35:00'),
(3, 1234, 2019554, 'Hi Tengku! How is your internship going?', '2023-07-21 02:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `job_type` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `job_brief` text NOT NULL,
  `shift_schedule` text NOT NULL,
  `tools_used` text NOT NULL,
  `company_email` varchar(50) NOT NULL,
  `course` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`, `logo_url`, `salary`, `job_type`, `location`, `details`, `job_brief`, `shift_schedule`, `tools_used`, `company_email`, `course`) VALUES
(1, 'TrueMoney SDN BHD', 'truemoney_logo.png', 5000.00, 'Software Developer', 'Kuala Lumpur', 'Leading financial technology company.', 'Develop and maintain software applications.', '9 AM - 5 PM', 'Git, Visual Studio', 'truemoney@example.com', 'Computer Science'),
(2, 'Petronas', 'petronas_logo.png', 7000.00, 'Petroleum Engineer', 'Kuala Lumpur', 'Global petroleum and natural gas company.', 'Conduct research and analysis for petroleum extraction.', '8 AM - 4 PM', 'SQL,Python', 'petronas@example.com', 'Chemical Engineering'),
(3, 'DELL', 'dell_logo.png', 6000.00, 'IT Support Specialist', 'Penang', 'Technology company providing computer hardware.', 'Provide technical assistance and support for IT issues.', '10 AM - 6 PM', 'Helpdesk software, Troubleshooting tools', 'dell@example.com', 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `progress_reports`
--

CREATE TABLE `progress_reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `report_type` varchar(20) DEFAULT NULL,
  `report_text` text DEFAULT NULL,
  `report_pdf_url` varchar(255) DEFAULT NULL,
  `submission_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress_reports`
--

INSERT INTO `progress_reports` (`report_id`, `user_id`, `company_id`, `month`, `year`, `report_type`, `report_text`, `report_pdf_url`, `submission_date`) VALUES
(1, 2019554, 1, 6, 2023, 'text', 'This is the monthly progress report for July 2023.', NULL, '2023-07-30'),
(2, 2014325, 2, 6, 2023, 'pdf', NULL, 'progress_report_tengku_june.pdf', '2023-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `student_internships`
--

CREATE TABLE `student_internships` (
  `internship_id` int(11) NOT NULL,
  `username` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `offer_letter_url` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `progress_report_submitted` tinyint(1) DEFAULT NULL,
  `progress_report_date` date DEFAULT NULL,
  `csupervisor_name` varchar(100) DEFAULT NULL,
  `usupervisor_id` int(11) DEFAULT NULL,
  `email_csupervisor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_internships`
--

INSERT INTO `student_internships` (`internship_id`, `username`, `company_id`, `offer_letter_url`, `start_date`, `end_date`, `progress_report_submitted`, `progress_report_date`, `csupervisor_name`, `usupervisor_id`, `email_csupervisor`) VALUES
(0, 2019554, 1, 'offer_letter_syaza.pdf', '2023-07-01', '2023-12-31', 0, NULL, 'Syazwan Azri', 1234, 'syazwan@example.com'),
(1, 2014325, 1, 'offer_letter_TENGKU.pdf', '2023-07-01', '2023-12-31', 0, NULL, 'Shahida Adila', 1234, 'shahida@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_ratings`
--

CREATE TABLE `supervisor_ratings` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `appropriateness` int(11) DEFAULT NULL,
  `training` int(11) DEFAULT NULL,
  `difficulty_level` int(11) DEFAULT NULL,
  `teamwork` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `rating_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `user_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `full_name`, `email`, `password`, `birthdate`, `user_type`) VALUES
(1234, 'Suharudin Amin bin Sidik', 'suharudin@example.com', 'Syaza', '1980-01-01', 'Supervisor'),
(2014325, 'Tengku Normariah binti Tengku Ahmad', 'tengku@example.com', 'hashed_password', '1997-09-21', 'Student'),
(2019554, 'Syaza Athirah Binti Suharudin Amin', 'syaza@example.com', 'hashed_password', '1998-05-15', 'Student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `progress_reports`
--
ALTER TABLE `progress_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `student_internships`
--
ALTER TABLE `student_internships`
  ADD PRIMARY KEY (`internship_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `fk_student_internships` (`username`),
  ADD KEY `fk_usupervisor_id` (`usupervisor_id`);

--
-- Indexes for table `supervisor_ratings`
--
ALTER TABLE `supervisor_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`username`);

--
-- Constraints for table `progress_reports`
--
ALTER TABLE `progress_reports`
  ADD CONSTRAINT `progress_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `progress_reports_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Constraints for table `student_internships`
--
ALTER TABLE `student_internships`
  ADD CONSTRAINT `fk_student_internships` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `fk_usupervisor_id` FOREIGN KEY (`usupervisor_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `student_internships_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `student_internships_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`);

--
-- Constraints for table `supervisor_ratings`
--
ALTER TABLE `supervisor_ratings`
  ADD CONSTRAINT `supervisor_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `supervisor_ratings_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
