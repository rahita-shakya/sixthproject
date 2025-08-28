-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2025 at 05:36 PM
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
-- Database: `jobselect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'rahina@gmail.com', '$2y$10$DUoQMlmz6Dl4g3ex3YrIUOb9t.cHp2lxXJ468hiTJt9lQW3eNFEhm', '2025-03-18 11:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'rahita shakya', 'rahita@gmail.com', '$2y$10$qKC23J6mgPnLvadGPAlMRuM8K3C7N4KS9W.Ccxhsdxx2a0HRBTrDi', '2025-03-18 10:37:06'),
(2, 'nived shakya', 'nived@gmail.com', '$2y$10$wg3Q5zYhluD9v7hENxikMOryTfhNBZUFIpNU8lVMNwawdadElKlVK', '2025-04-01 14:10:54'),
(3, 'samar shakya', 'samarshakya@gmail.com', '$2y$10$4vmjlJP9u13hTEJUp/p4oOIJ7siN3sifnn01zC5RVCGwtauc1R8Mq', '2025-04-01 14:11:10'),
(4, 'Nived Shakya', 'nivedshakya@gmail.com', '$2y$10$q3G76BnuTXIkChGc7hunDO0WsQD9L.9UZlvGH8vANI9M2mNM9sHSa', '2025-04-12 07:29:36'),
(5, 'Sulav Shakya', 'sulav@gmail.com', '$2y$10$qhh6dPBJeYCL8zvq7pNbGeSFIftmOP2Qpfbr15h7UyNtChE0FajlS', '2025-04-19 11:49:33'),
(6, 'savya shakya', 'savya@gmail.com', '$2y$10$ERNN1tonOlcuk8JWoERFKuLNfl02bSrZddniolZhKgjCoBFAxzykO', '2025-04-22 10:40:55'),
(7, 'yodha singh', 'yodha@gmail.com', '$2y$10$aeuUUwp0wLc.x1cR9cL1me7TmiYk0z5uTkYktXxq2sWx7nQuaMtF.', '2025-05-06 10:19:05'),
(8, 'Sulina Shakya', 'sulinashakya@gmail.com', '$2y$10$y9D2wYaW2TojnA4ju1wA8umiyMg8UnvlrRFM4XQ4hTat7tmfAKY0y', '2025-05-09 11:05:36'),
(9, 'paula shakya', 'paula@gmail.com', '$2y$10$VHCRxAa8PLGiF1Kxgy2Za.v5ZKUI57.xVNUHgiac9dv1.Ad9RwDbu', '2025-05-13 14:20:47'),
(10, 'sawleen shakya', 'sawleen@gmail.com', '$2y$10$Q66kAt8MzT3N50zQDBvZ6OxnKOd9AmCxHFG5XT/JTvlo4MxPE480S', '2025-05-13 14:53:55'),
(12, 'samanta shakya', 'samanta@gmail.com', '$2y$10$tqx2gqT7HvbriVum9e5GZuUcj/VCj.EZDPeH729mkqmpZy/fupg66', '2025-05-26 14:53:04'),
(13, 'Sarthak Shakya', 'sarthak@gmail.com', '$2y$10$YEF20486l7EJc62OK62pROw.3LpU4qa7jPxQfvEQXuMyX3NuLp6GG', '2025-05-29 17:21:04'),
(14, 'jargon shakya', 'jargon@gmail.com', '$2y$10$KwtNAD0t749j9E/FP4/R5.B7U0ARj9q4w1fM2bkSJbeoVtFhWzh7u', '2025-05-30 17:24:15'),
(15, 'test', 'test@gmail.com', '$2y$10$wd0nN4UGbrTDRqxDWWdL2.HmdXfhYWNSCAEEQ.Z4u2AKKFAD3SAiu', '2025-06-01 15:10:35'),
(16, 'test1', 'test1@gmail.com', '$2y$10$quQV81BLeoC8ULXs1iDMZ.5XKVx7IB8rIybZMm6NK2Rn5D2QNmmGu', '2025-06-02 06:20:14'),
(17, 'Saharsh Shakya', 'saharsh@gmail.com', '$2y$10$YUVcztx.G9iEtYH4tCTl0OQRlgA5KeAO5vJaiDH39H/j885VoZojm', '2025-07-29 08:07:04');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resume` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `company_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `applicant_id`, `job_id`, `applied_at`, `resume`, `address`, `message`, `status`, `company_message`) VALUES
(10, 7, 49, '2025-05-06 16:32:37', 'Holi Celebration Program 2025.pdf', 'patan', 'htryhtsgbhtyh srtghtyh5t', 'pending', NULL),
(17, 4, 49, '2025-05-30 08:47:25', 'rahita-shakya-DM-cv.pdf', 'kirtipur', 'this is my resume', 'pending', NULL),
(18, 13, 47, '2025-05-30 17:06:46', '1st page.pdf', 'jamal', 'hiii', 'rejected', NULL),
(19, 14, 47, '2025-05-30 17:25:16', 'Holi Celebration Program 2025.pdf', 'Gongabu', 'hiii', 'rejected', NULL),
(20, 14, 49, '2025-05-31 00:09:59', 'rahitashakya_DM.cv.pdf', 'patan', '', 'pending', NULL),
(21, 14, 50, '2025-05-31 00:18:40', 'rahita-shakya-DM-cv.pdf', 'kirtipur', 'this is my resume', 'accepted', ''),
(22, 3, 47, '2025-05-31 00:20:21', 'rahita-shakya-DM-cv.pdf', 'jamal', 'this is my resume', 'rejected', NULL),
(23, 15, 47, '2025-06-01 15:12:51', '3rd sen case study.pdf', 'patan', 'this is my resume', 'accepted', ''),
(24, 16, 47, '2025-06-02 06:26:50', 'Mid-defense report rahitashakya.pdf', 'patan', 'this is my resume', 'accepted', ''),
(25, 13, 69, '2025-06-12 01:56:55', 'Guest List one.pdf', 'thamel', 'this is my resume', 'rejected', NULL),
(26, 3, 69, '2025-06-12 01:58:00', 'SupervisorLogSheet.pdf', 'kirtipur', 'this is my resume', 'accepted', ''),
(27, 12, 69, '2025-06-12 01:59:20', 'Holi Celebration Program 2025.pdf', 'thamel', 'this is my resume', 'accepted', ''),
(28, 3, 50, '2025-06-19 12:33:56', 'Holi Celebration Program 2025.pdf', 'patan', 'erhygerh', 'rejected', NULL),
(29, 3, 66, '2025-06-29 16:04:54', 'final one.pdf', 'thamel', 'ergrgerg', 'rejected', NULL),
(30, 13, 66, '2025-06-29 16:05:26', 'final one.pdf', 'Gongabu', 'erhr', 'accepted', ''),
(31, 12, 66, '2025-07-29 08:03:39', 'Holi Celebration Program 2025.pdf', 'patan', 'this is ,my resume', 'accepted', ''),
(32, 17, 73, '2025-07-29 08:09:20', 'SupervisorLogSheet.pdf', 'thamel', 'thththtrh', 'accepted', ''),
(33, 3, 73, '2025-07-29 08:13:46', 'Holi Celebration Program 2025.pdf', 'kirtipur', 'fgre', 'rejected', NULL),
(34, 12, 73, '2025-07-29 08:14:22', 'business law.pdf', 'patan', 'uh5th', 'accepted', '');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'developer'),
(2, 'developer'),
(3, 'developer');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `company_login_id` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `description`, `company_login_id`, `location`, `category`) VALUES
(1, 'Test Company', 'I need a managing director', NULL, '', NULL),
(7, 'eraser Company', 'jthis is thge defve', 12, '', NULL),
(8, 'King Own Institute', 'This is tech based company', 13, '', NULL),
(9, 'stadler Technology Company Limited', 'This is US based company in Nepal', 14, '', NULL),
(10, 'Dav College of institute', 'yrbfuew arguehfewf', 15, '', NULL),
(11, 'Coleager', 'ewhfuwe dbuief', 16, '', NULL),
(12, 'merocompany', 'ewyfg8ywegcfbwef', 17, '', NULL),
(13, 'merojobneapl', 'Company profile', 4, 'Dhulikhel', NULL),
(14, 'merosms', 'This is an tech company in the heart of lalitpur', 18, '', NULL),
(15, 'CodeSansar', 'this is my company', 19, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies_login`
--

CREATE TABLE `companies_login` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_id` int(11) DEFAULT NULL,
  `is_subscribed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies_login`
--

INSERT INTO `companies_login` (`id`, `name`, `email`, `password`, `contact_number`, `address`, `created_at`, `company_id`, `is_subscribed`) VALUES
(1, 'xyz company', 'aone@global.com', '$2y$10$dix.ChwAOWTeNxcKL5d6MeBGDVBiv2sYf5fwleCEGQpgUqT/FE7YW', '9869375415', 'Babarmahal, Kathmandu', '2025-03-18 11:22:05', NULL, 0),
(2, 'xyz company', 'xyz@gmail.com', '$2y$10$56Srptj.rHOJfrCJisnDIO0YmFFzA1/.ykr/TSaSDUlT1/5tpfzXK', '9869375415', 'mangalbazar, Lalitpur', '2025-04-01 14:14:16', NULL, 0),
(3, 'ABC Company', 'abc@gmail.com', '$2y$10$dWNJUbhL3N7XLX7K5ckEhuZ93wbqaHyylcR.9Ih11K9T2Ewa4rJe2', '94785748584', 'patan', '2025-04-12 07:35:41', NULL, 0),
(4, 'Mero Job Nepal', 'merojob@gmail.com', '$2y$10$EOT1pIUJfOjo1gmOGUj.aeTJKkH5vIQxDH5x8p4f4eqPiniSFMUlq', '9869765434', 'Dhulikhel', '2025-04-12 07:54:58', 13, 0),
(12, 'eraser Company', 'eraser@gmail.com', '$2y$10$TSJynXu5cBbk6T24zpyJ9O.8L0w.rGigTFrVdzeSSJL2fefGqhuzG', '986475777754', 'patan', '2025-04-12 04:47:22', NULL, 0),
(13, 'King Own Institute', 'king@gmail.com', '$2y$10$U6AM6WIUzrgce132MWm2RughPJgN59Ywc08MJ6HCmhEoc3PfZ18KG', '80485742875', 'kirtipur', '2025-04-14 12:03:47', NULL, 0),
(14, 'stadler Technology Company Limited', 'stadler@gmail.com', '$2y$10$vfXqshGRyt2zGjPsfpCLC.BUplkaoEUwcLTSWecL21SjCtz5G1OJO', '9845747366', 'Gongabu', '2025-04-15 04:58:21', NULL, 0),
(15, 'Dav College of institute', 'dav@gmail.com', '$2y$10$Xg8U6fhvJPwEel5SF9h1FOUA5Yze3AQ72O8RVNLEqaU0iT2jkkL7O', '9876455352', 'Dhibighart', '2025-04-18 08:32:37', NULL, 0),
(16, 'Coleager', 'cooo@gmail.com', '$2y$10$HCuzRwmXhYlxgUE4/g058u5IW4rN8D0807FMshpwoFSCnEjTvqXLi', '93487574574', 'patan', '2025-04-18 11:41:47', NULL, 0),
(17, 'merocompany', 'merocompany@gmail.com', '$2y$10$oVzSfdpw8gYhmDbPhvJDZ.n6IVxCtXronsKU51mo.zDTtmgSvHasC', '7864865734567', 'thamel', '2025-04-19 08:06:24', NULL, 0),
(18, 'merosms', 'merosms@gmail.com', '$2y$10$n7nZpLyAbGFg60rR.Heye.fS4A.e8cYUWVQqVWMAwelx8Z4yzR8Ri', '989874478', 'Gongabu', '2025-05-06 06:29:12', NULL, 0),
(19, 'CodeSansar', 'codesansar@gmail.com', '$2y$10$eG4yNeusapKDsahCSc5jrO.a.JzTWvyFCRtoF1XWCJ2Ix9Njpb5ry', '9045704389', 'kirtipur', '2025-05-30 13:23:32', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_approved` tinyint(4) DEFAULT 0,
  `skills_required` text DEFAULT NULL,
  `applicants_required` int(11) NOT NULL DEFAULT 1,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `category`, `category_id`, `company_id`, `created_at`, `location`, `status`, `is_approved`, `skills_required`, `applicants_required`, `start_date`, `end_date`) VALUES
(47, 'java', 'devscribe', 'Frontend Developer', NULL, 10, '2025-04-22 11:17:18', 'patan', 'approved', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(49, 'flutter developer', 'i need a flutter developer in my company', 'Fullstack developer', NULL, 13, '2025-04-22 14:24:38', 'Kumaripati', 'approved', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(50, 'UI/UX', 'I need an graphic designer for my company', 'Marketing', NULL, 13, '2025-04-22 21:58:09', 'Jamal', 'approved', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(53, 'Front Desk Officer', 'Key Responsibilities:\r\n\r\nGreet and welcome visitors, clients, and employees in a professional and friendly manner.\r\n\r\nAnswer and direct phone calls, take messages, and respond to inquiries.\r\n\r\nManage incoming and outgoing mail, packages, and deliveries.\r\n\r\nSchedule and coordinate appointments, meetings, and events.\r\n\r\nMaintain the front desk area, ensuring it is clean, organized, and welcoming.\r\n\r\nHandle administrative tasks such as filing, data entry, and document management.\r\n\r\nEnsure that all visitors are properly signed in and issued badges.\r\n\r\nAssist with other office duties as required by supervisors or office management.\r\n\r\nEnsure a safe and secure environment by monitoring visitors and enforcing building protocols.\r\n\r\nRequired Skills & Qualifications:\r\n\r\nProven experience as a Front Desk Officer, Receptionist, or in a similar role.\r\n\r\nExcellent verbal and written communication skills.\r\n\r\nStrong organizational and time-management skills.\r\n\r\nProficiency in Microsoft Office Suite (Word, Excel, PowerPoint).\r\n\r\nAbility to multitask and handle stressful situations with professionalism.\r\n\r\nFriendly, approachable, and customer-oriented attitude.\r\n\r\nHigh school diploma or equivalent; additional qualifications in office management or administration are a plus.\r\n\r\nWork Environment:\r\n\r\nFull-time, Monday to Friday.\r\n\r\nTypically based at the front desk in an office, company, or organization.', 'Front Desk officer', NULL, 14, '2025-05-09 16:58:06', 'Lalitpur', 'approved', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(54, 'Senior Level Developer', 'effewgerg', 'Fullstack developer', NULL, 13, '2025-05-22 21:49:36', 'patan', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(57, 'backend', 'RWGERWG', 'Backend developer', NULL, NULL, '2025-05-22 22:22:42', 'Lalitpur', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(58, 'backend', 'RWGERWG', 'Backend developer', NULL, NULL, '2025-05-22 22:25:51', 'Lalitpur', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(59, 'backend', 'RWGERWG', 'Backend developer', NULL, NULL, '2025-05-22 22:26:42', 'Lalitpur', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(60, 'front desk officer', 'bghbngbbfghtrhtrh', 'Front Desk officer', NULL, NULL, '2025-05-26 11:34:15', 'Kumaripati', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(61, 'react developer', 'gvrrbg', 'Fullstack developer', NULL, NULL, '2025-05-26 11:37:18', 'patan', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(62, 'greg', 'rgrg', 'Backend developer', NULL, NULL, '2025-05-26 11:40:14', 'patan', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(64, 'Front Desk Officer', 'The Front Desk Officer is responsible for greeting visitors, managing incoming calls, handling inquiries, and providing general administrative support. They serve as the first point of contact for the organization and ensure a professional and welcoming environment. Key duties include maintaining visitor logs, scheduling appointments, and coordinating communication between departments. Strong communication, organizational, and customer service skills are essential.', 'Front Desk officer', NULL, 10, '2025-05-29 21:23:40', 'Thamel', 'approved', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(65, 'Python Developer', 'We are looking for a skilled Python Developer to join our dynamic development team. The ideal candidate will have strong expertise in Python programming and experience building scalable, high-performance applications. You will work closely with cross-functional teams to design, develop, and maintain backend services and APIs.\r\n\r\nKey Responsibilities:\r\n\r\nDevelop, test, and maintain efficient, reusable, and reliable Python code\r\n\r\nDesign and implement robust backend services and APIs\r\n\r\nCollaborate with front-end developers, product managers, and QA teams to deliver high-quality software solutions\r\n\r\nOptimize applications for maximum speed and scalability\r\n\r\nTroubleshoot, debug, and resolve technical issues\r\n\r\nWrite and maintain technical documentation\r\n\r\nStay updated with the latest industry trends and technologies in Python development', 'Fullstack developer', NULL, 15, '2025-05-30 22:58:51', 'Lalitpur', 'approved', 0, NULL, 1, '2025-06-30', '2025-07-10'),
(66, 'React developer', 'Inefficiencies in traditional job recruitment \r\nLimited job-skill matching for seekers \r\nTalent identification challenges for employers \r\nNeed for an innovative connection platform', 'Fullstack developer', NULL, 10, '2025-05-31 06:08:13', 'Kumaripati', 'approved', 0, NULL, 1, '2025-07-29', '2025-08-10'),
(67, 'Graphic Designer', 'I need a designer', 'Marketing', NULL, 10, '2025-06-02 12:15:34', 'patan', 'pending', 0, NULL, 1, '2025-06-10', '2025-07-10'),
(69, 'graphic designer', 'i need a graphic designer', 'Marketing', NULL, 15, '2025-06-10 21:54:38', 'patan', 'approved', 0, NULL, 2, '2025-06-10', '2025-06-17'),
(70, 'video editor', 'i need a video editor', 'Marketing', NULL, 13, '2025-06-29 20:37:47', 'Kumaripati', 'approved', 0, NULL, 2, '2025-06-29', '2025-06-29'),
(71, 'front desk officer', 'this is it', 'Front Desk officer', NULL, 13, '2025-06-29 21:36:20', 'Jamal', 'pending', 0, NULL, 2, '2025-06-30', '2025-07-01'),
(73, 'Video editor', 'i need a video editor', 'Marketing', NULL, 10, '2025-07-29 13:51:21', 'Jamal', 'approved', 0, NULL, 2, '2025-07-29', '2025-08-09');

-- --------------------------------------------------------

--
-- Table structure for table `job_interactions`
--

CREATE TABLE `job_interactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `action` enum('view','apply') NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_interactions`
--

INSERT INTO `job_interactions` (`id`, `user_id`, `job_id`, `action`, `timestamp`) VALUES
(3, 1, 49, 'view', '2025-05-13 20:04:25'),
(5, 1, 49, 'apply', '2025-05-13 20:04:31'),
(8, 9, 47, 'view', '2025-05-13 20:15:57'),
(10, 1, 50, 'view', '2025-05-13 20:20:19'),
(16, 4, 49, 'apply', '2025-05-30 14:32:25'),
(17, 13, 47, 'apply', '2025-05-30 22:51:46'),
(18, 14, 47, 'apply', '2025-05-30 23:10:16'),
(19, 14, 49, 'apply', '2025-05-31 05:54:59'),
(20, 14, 50, 'apply', '2025-05-31 06:03:40'),
(21, 3, 47, 'apply', '2025-05-31 06:05:21'),
(22, 15, 47, 'apply', '2025-06-01 20:57:51'),
(23, 16, 47, 'apply', '2025-06-02 12:11:50'),
(24, 13, 69, 'apply', '2025-06-12 07:41:55'),
(25, 3, 69, 'apply', '2025-06-12 07:43:00'),
(26, 12, 69, 'apply', '2025-06-12 07:44:20'),
(27, 3, 50, 'apply', '2025-06-19 18:18:56'),
(28, 3, 66, 'apply', '2025-06-29 21:49:54'),
(29, 13, 66, 'apply', '2025-06-29 21:50:26'),
(30, 12, 66, 'apply', '2025-07-29 13:48:39'),
(31, 17, 73, 'apply', '2025-07-29 13:54:20'),
(32, 3, 73, 'apply', '2025-07-29 13:58:46'),
(33, 12, 73, 'apply', '2025-07-29 13:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `job_interest`
--

CREATE TABLE `job_interest` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_interest`
--

INSERT INTO `job_interest` (`id`, `user_id`, `job_id`, `created_at`) VALUES
(1, 8, 49, '2025-05-09 11:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `job_role_skills`
--

CREATE TABLE `job_role_skills` (
  `id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `skill` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_role_skills`
--

INSERT INTO `job_role_skills` (`id`, `role`, `skill`) VALUES
(1, 'Backend Developer', 'PHP'),
(2, 'Backend Developer', 'Java'),
(3, 'Backend Developer', 'Node.js'),
(4, 'Frontend Developer', 'HTML'),
(5, 'Frontend Developer', 'CSS'),
(6, 'Frontend Developer', 'JavaScript'),
(7, 'Fullstack Developer', 'React'),
(8, 'Fullstack Developer', 'PHP'),
(9, 'Fullstack Developer', 'JavaScript'),
(10, 'Marketing', 'SEO'),
(11, 'Marketing', 'Content Writing'),
(12, 'Accounting', 'Tally'),
(13, 'Accounting', 'Excel'),
(14, 'Front Desk Officer', 'Excel'),
(15, 'Front Desk Officer', 'Word'),
(16, 'Front Desk Officer', 'Powerpoint'),
(17, 'Project Manager', 'Leadership'),
(18, 'Project Manager', 'Excel'),
(19, 'Backend Developer', 'Laravel'),
(20, 'Backend Developer', 'CodeIgniter'),
(21, 'Frontend Developer', 'Tailwind CSS'),
(22, 'Fullstack Developer', 'React'),
(23, 'Mobile App Developer', 'Flutter'),
(24, 'Mobile App Developer', 'Java (Android)'),
(25, 'WordPress Developer', 'Elementor'),
(26, 'Digital Marketer', 'SEO'),
(27, 'Digital Marketer', 'Facebook Ads'),
(28, 'Sales Executive', 'Field Sales'),
(29, 'Marketing Officer', 'Brand Promotion'),
(30, 'Accountant', 'Tally'),
(31, 'Accountant', 'VAT Billing'),
(32, 'Finance Assistant', 'Bank Reconciliation'),
(33, 'HR Assistant', 'Recruitment'),
(34, 'Admin Officer', 'Documentation'),
(35, 'Office Secretary', 'Scheduling'),
(36, 'Computer Instructor', 'MS Office'),
(37, 'IT Trainer', 'Basic Programming'),
(38, 'Tuition Teacher', 'Mathematics'),
(39, 'Graphic Designer', 'Photoshop'),
(40, 'Graphic Designer', 'Canva'),
(41, 'Video Editor', 'CapCut'),
(42, 'Customer Support', 'Call Handling'),
(43, 'Receptionist', 'Communication Skills'),
(44, 'Backend Developer', 'Python'),
(45, 'Backend Developer', 'C'),
(46, 'Backend Developer', 'C++'),
(47, 'Backend Developer', 'C#'),
(48, 'Backend Developer', '.NET'),
(49, 'Frontend Developer', 'HTML5'),
(50, 'Frontend Developer', 'Tailwind CSS'),
(51, 'Fullstack Developer', 'Laravel'),
(52, 'Fullstack Developer', 'React'),
(53, 'Fullstack Developer', 'Angular'),
(54, 'Mobile App Developer', 'Flutter'),
(55, 'Mobile App Developer', 'Java (Android)'),
(56, 'Mobile App Developer', 'React Native'),
(57, 'backend developer', 'Python');

-- --------------------------------------------------------

--
-- Table structure for table `job_views`
--

CREATE TABLE `job_views` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `view_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_views`
--

INSERT INTO `job_views` (`id`, `applicant_id`, `job_id`, `view_count`) VALUES
(10, 7, 49, 1),
(17, 4, 49, 1),
(18, 13, 47, 1),
(19, 14, 47, 1),
(20, 14, 49, 1),
(21, 14, 50, 1),
(22, 3, 47, 1),
(23, 15, 47, 1),
(24, 16, 47, 1),
(25, 13, 69, 1),
(26, 3, 69, 1),
(27, 12, 69, 1),
(28, 3, 50, 1),
(29, 3, 66, 1),
(30, 13, 66, 1),
(31, 12, 66, 1),
(32, 17, 73, 1),
(33, 3, 73, 1),
(34, 12, 73, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `job_id`, `skill_name`) VALUES
(1, 59, 'php'),
(2, 60, 'Ms word'),
(3, 60, 'Power point'),
(4, 60, 'excel'),
(5, 61, 'react'),
(6, 61, 'database'),
(7, 61, 'javascript'),
(8, 62, 'php'),
(10, 0, 'java'),
(11, 64, 'Word'),
(12, 64, 'Powerpoint'),
(13, 64, 'Excel'),
(19, 67, 'photoshop'),
(20, 67, 'canva'),
(24, 69, 'canva'),
(25, 69, 'photoshop'),
(26, 70, 'Davinci'),
(27, 70, 'Capcut'),
(28, 70, 'AfterEffect'),
(29, 71, 'Ms word'),
(30, 71, 'Power point'),
(31, 71, 'excel'),
(40, 65, 'python'),
(41, 65, 'database'),
(42, 65, 'html'),
(43, 65, 'css'),
(44, 66, 'react'),
(45, 73, 'Davinci'),
(46, 73, 'Capcut'),
(47, 73, 'AfterEffect');

-- --------------------------------------------------------

--
-- Table structure for table `skill_job_interest`
--

CREATE TABLE `skill_job_interest` (
  `skill` varchar(100) NOT NULL,
  `job_category` varchar(100) NOT NULL,
  `interest_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill_job_interest`
--

INSERT INTO `skill_job_interest` (`skill`, `job_category`, `interest_count`) VALUES
('AfterEffect', 'Marketing', 1),
('Capcut', 'Marketing', 1),
('css', 'Front Desk officer', 1),
('database', 'Accounting', 1),
('database', 'Backend developer', 1),
('database', 'Front Desk officer', 1),
('database', 'Marketing', 9),
('excel', 'Accounting', 1),
('excel', 'Backend developer', 1),
('excel', 'Front Desk officer', 2),
('excel', 'Marketing', 9),
('html', 'Front Desk officer', 2),
('html', 'Marketing', 1),
('java', 'Accounting', 1),
('java', 'Front Desk officer', 1),
('java', 'Marketing', 1),
('Ms word', 'Backend developer', 1),
('Ms word', 'Front Desk officer', 2),
('Ms word', 'Marketing', 9),
('Photoshop', 'Backend developer', 1),
('photoshop', 'Front Desk officer', 1),
('Photoshop', 'Marketing', 9),
('php', 'Accounting', 1),
('php', 'Backend developer', 1),
('php', 'Front Desk officer', 1),
('php', 'Marketing', 10),
('Power point', 'Backend developer', 1),
('Power point', 'Front Desk officer', 1),
('Power point', 'Marketing', 9),
('Powerpoint', 'Front Desk officer', 1),
('Powerpoint', 'Marketing', 1),
('python', 'Front Desk officer', 2),
('Python', 'Marketing', 1),
('react', 'Marketing', 1),
('Word', 'Front Desk officer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_job_interest`
--

CREATE TABLE `user_job_interest` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `interest_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_job_interest`
--

INSERT INTO `user_job_interest` (`id`, `user_id`, `job_id`, `interest_time`) VALUES
(1, 1, 49, '2025-05-09 11:01:55'),
(2, 3, 49, '2025-05-09 11:04:33'),
(3, 8, 49, '2025-05-09 11:06:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `user_id` int(11) NOT NULL,
  `skill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`user_id`, `skill`) VALUES
(6, 'Word'),
(6, 'Photoshop'),
(6, 'Canva'),
(9, 'PHP'),
(1, 'PHP'),
(10, 'PHP'),
(5, 'PHP'),
(5, 'Excel'),
(11, 'java'),
(4, 'PHP'),
(4, 'React'),
(4, 'Word'),
(4, 'database'),
(4, 'php'),
(4, 'python'),
(14, 'Ms word'),
(14, 'Word'),
(14, 'css'),
(14, 'excel'),
(14, 'html'),
(14, 'java'),
(14, 'python'),
(15, 'css'),
(15, 'html'),
(15, 'php'),
(15, 'Word'),
(16, 'css'),
(16, 'excel'),
(16, 'html'),
(13, 'Ms word'),
(13, 'Power point'),
(13, 'Powerpoint'),
(13, 'Python'),
(13, 'database'),
(13, 'excel'),
(13, 'html'),
(13, 'photoshop'),
(13, 'php'),
(12, 'Ms word'),
(12, 'Photoshop'),
(12, 'Power point'),
(12, 'canva'),
(12, 'css'),
(12, 'database'),
(12, 'excel'),
(12, 'html'),
(12, 'php'),
(12, 'python'),
(3, 'AfterEffect'),
(3, 'Capcut'),
(3, 'java'),
(3, 'php'),
(3, 'react'),
(17, 'AfterEffect'),
(17, 'canva'),
(17, 'Capcut'),
(17, 'Davinci'),
(17, 'photoshop'),
(17, 'Power point'),
(17, 'Word');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_ibfk_1` (`applicant_id`),
  ADD KEY `applications_ibfk_2` (`job_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_login_id` (`company_login_id`);

--
-- Indexes for table `companies_login`
--
ALTER TABLE `companies_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_company_id` (`company_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_ibfk_1` (`category_id`),
  ADD KEY `jobs_ibfk_2` (`company_id`);

--
-- Indexes for table `job_interactions`
--
ALTER TABLE `job_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_interactions_ibfk_2` (`job_id`);

--
-- Indexes for table `job_interest`
--
ALTER TABLE `job_interest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `job_role_skills`
--
ALTER TABLE `job_role_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_views`
--
ALTER TABLE `job_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_views_ibfk_1` (`applicant_id`),
  ADD KEY `job_views_ibfk_2` (`job_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `skill_job_interest`
--
ALTER TABLE `skill_job_interest`
  ADD PRIMARY KEY (`skill`,`job_category`);

--
-- Indexes for table `user_job_interest`
--
ALTER TABLE `user_job_interest`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `companies_login`
--
ALTER TABLE `companies_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `job_interactions`
--
ALTER TABLE `job_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `job_interest`
--
ALTER TABLE `job_interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_role_skills`
--
ALTER TABLE `job_role_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `job_views`
--
ALTER TABLE `job_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_job_interest`
--
ALTER TABLE `user_job_interest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `fk_company_login_id` FOREIGN KEY (`company_login_id`) REFERENCES `companies_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies_login`
--
ALTER TABLE `companies_login`
  ADD CONSTRAINT `fk_company_id` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_interactions`
--
ALTER TABLE `job_interactions`
  ADD CONSTRAINT `job_interactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `applicants` (`id`),
  ADD CONSTRAINT `job_interactions_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_interest`
--
ALTER TABLE `job_interest`
  ADD CONSTRAINT `job_interest_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `applicants` (`id`),
  ADD CONSTRAINT `job_interest_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`);

--
-- Constraints for table `job_views`
--
ALTER TABLE `job_views`
  ADD CONSTRAINT `job_views_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_views_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
