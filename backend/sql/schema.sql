-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16 ديسمبر 2025 الساعة 20:17
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tadreeb_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `university` varchar(150) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `transcript_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','signed','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `academic_level` varchar(50) DEFAULT NULL,
  `passed_hours` int(11) DEFAULT NULL,
  `self_definition` text DEFAULT NULL,
  `profile_pic_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `applications`
--

INSERT INTO `applications` (`application_id`, `user_id`, `internship_id`, `university`, `gpa`, `cv_path`, `transcript_path`, `status`, `created_at`, `full_name`, `email`, `linkedin`, `major`, `student_id`, `academic_level`, `passed_hours`, `self_definition`, `profile_pic_path`) VALUES
(2, 7, 3, 'Uqu', 4.00, 'uploads/7_cv_1765909947.pdf', 'uploads/7_transcript_1765909947.pdf', 'pending', '2025-12-16 18:32:27', 'Jumanh', 'jumanhsami@outlook.com', 'https://www.linkedin.com/home?originalSubdomain=sa', 'CS', '44510025', '5', 70, '', NULL),
(3, 7, 1, 'Uqu', 5.00, 'uploads/7_cv_1765910178.pdf', 'uploads/7_transcript_1765910178.pdf', 'pending', '2025-12-16 18:36:18', 'Jumanh', 'jmanh68@gmail.com', 'https://www.linkedin.com/home?originalSubdomain=sa', 'CS', '44510025', '5', 70, '', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `internship`
--

CREATE TABLE `internship` (
  `internshipID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `major` varchar(150) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `image_url` varchar(500) DEFAULT NULL,
  `application_link` varchar(500) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `internship`
--

INSERT INTO `internship` (`internshipID`, `title`, `major`, `location`, `short_description`, `full_description`, `requirements`, `image_url`, `application_link`, `deadline`, `status`) VALUES
(1, 'Saudi Aramco University Internship Program', 'Engineering, Science, Business', 'Dhahran', 'Hands-on training at Saudi Aramco for high-caliber students', 'Saudi Aramco offers internships to university and vocational college students allowing practical experience in engineering, business, and technical fields.', '[\"Academic enrollment\", \"strong GPA\"]', 'https://www.aramco.com/-/media/publications/career-brochure.jpg', 'https://www.aramco.com/en/careers/for-saudi-applicants/student-opportunities/university-and-vocational-college-internship-program', '2026-02-28', 'open'),
(2, 'Microsoft Internship Program (Ru’aa Program)', 'Computer Science, IT, Marketing', 'Riyadh', 'Microsoft structured internship program offering real project work', 'Microsoft’s Ru’aa internship connects interns with roles in IT, marketing, and services within Microsoft Saudi Arabia.', '[\"Enrollment in related field\", \"proactive learner\"]', 'https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg', 'https://microsoft.recsolu.com/external/requisitions/CpAJJGwEljwZYP2JtSlNUA', '2026-03-31', 'open'),
(3, 'KAUST Visiting Student Research Program', 'STEM Fields', 'Thuwal', 'Research internship at King Abdullah University of Science and Technology', 'KAUST VSRP places students with faculty on real STEM research projects, ideal for science and engineering majors.', '[\"3rd/4th year bachelor’s or masters\"]', 'https://www.kaust.edu.sa/sites/default/files/files/kaust_campus1.jpg', 'https://admissions.kaust.edu.sa/study/internships', '2026-04-30', 'open'),
(4, 'Siemens Energy Marketing Internship', 'Business, Marketing', 'Dammam', 'Marketing internship with Siemens Energy in Dammam', 'Assist in communications, operations, and marketing activities at Siemens Energy focused on energy solutions and industrial clients in Saudi Arabia.', '[\"Enrollment in business/marketing major\"]', 'https://upload.wikimedia.org/wikipedia/commons/0/04/Siemens_Energy_Logo.svg', 'https://www.siemens.com/sa/en/company/jobs', '2026-05-20', 'closed'),
(5, 'P&G 2026 Internship Program', 'Business, Engineering, Marketing', 'Riyadh', 'Internship across business functions at Procter & Gamble', 'P&G’s internship offers experience in functional areas with mentorship and industry exposure in Saudi Arabia.', '[\"Active student\", \"strong academic records\"]', 'https://upload.wikimedia.org/wikipedia/commons/8/8e/Procter_%26_Gamble_logo.svg', 'https://www.pgcareers.com/global/en/job/R000138219/P-G-2026-Internship-Program-Riyadh', '2026-05-15', 'open'),
(6, 'BCG Riyadh Visiting Associate Internship', 'Business, Consulting', 'Riyadh', 'Consulting internship with Boston Consulting Group in Riyadh', 'BCG’s Visiting Associate program integrates interns into real consulting teams on client projects, ideal for strategy & business majors.', '[\"University student or recent grad passionate about consulting\"]', 'https://upload.wikimedia.org/wikipedia/commons/5/51/BCG_logo.svg', 'https://careers.bcg.com/global/en/locations/saudi-arabia/visiting-associate', '2026-03-15', 'closed');

-- --------------------------------------------------------

--
-- بنية الجدول `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `organization` varchar(150) NOT NULL,
  `city` varchar(100) NOT NULL,
  `major` varchar(100) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `name`, `organization`, `city`, `major`, `review_text`, `created_at`) VALUES
(1, 7, 'Jumanh', 'oahfo;awfh;paw', 'makkha', 'Cybersecurity', '23tyhxbvdzsergxhtcjmv', '2025-12-16 18:51:18'),
(2, 7, 'meaw', 'aeg', 'makkha', 'Cybersecurity', 'zewarxty tgykh', '2025-12-16 18:52:58');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(6, 'Admin User', 'admin@tadreeb.com', 'admin123', 'admin', '2025-12-16 17:45:03'),
(7, 'Jumanh', 'jmanh68@gmail.com', '$2y$10$taTODIssAjtCu80FCMUpl.MwJHnwtBzt9BD3O0Jkgr9U5Kn6tlKiu', 'student', '2025-12-16 17:50:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `internship_id` (`internship_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internship`
--
ALTER TABLE `internship`
  ADD PRIMARY KEY (`internshipID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internship`
--
ALTER TABLE `internship`
  MODIFY `internshipID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`internship_id`) REFERENCES `internship` (`internshipID`);

--
-- قيود الجداول `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
