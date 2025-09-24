-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 01, 2025 at 03:25 PM
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
-- Database: `school_testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `vacancies` smallint(5) UNSIGNED NOT NULL,
  `status` varchar(16) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `course_id`, `title`, `description`, `vacancies`, `status`, `start_date`, `end_date`) VALUES
(1149, 4139, 'Carlos Santos', 'HwypSgj7kFWdmxIeu6Od', 232, 'disponível', '2025-09-01', '2025-09-01'),
(1150, 4140, 'Gabriela Santos', 'lPgagdfH9uds7e85oCZR', 29, 'encerrado', '2025-09-01', '2025-09-01'),
(1151, 4141, 'Felipe Costa', 'L0KwWWOtaBHtFfIqNE75', 239, 'encerrado', '2025-09-01', '2025-09-01'),
(1152, 4142, 'Gabriela Santos', 'T447fXmAsDNbzUFxCoji', 236, 'disponível', '2025-09-01', '2025-09-01'),
(1153, 4143, 'Bob Almeida', 'NI1zZazB10prvtnO24FN', 169, 'encerrado', '2025-09-01', '2025-09-01'),
(1154, 4144, 'Felipe Pereira', 'eojLazehayzpDr0Bqnbq', 255, 'desabilitado', '2025-09-01', '2025-09-01'),
(1155, 4144, 'Bob Silva', 'gmkhab5xzSXTCYVgjzSV', 98, 'desabilitado', '2025-09-01', '2025-09-01'),
(1156, 4145, 'Gabriela Almeida', 'jwtSg4R0fXnhTjWDh859', 44, 'desabilitado', '2025-09-01', '2025-09-01'),
(1157, 4146, 'Bob Santos', 'TxsaoQ6gCxMF1myL5jo7', 172, 'disponível', '2025-08-17', '2025-08-25');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `theme` varchar(16) NOT NULL,
  `url_image` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `theme`, `url_image`) VALUES
(4139, 'Alice Costa', 'XytnBetLznNzB2E9SqpA', 'inovação', 'http://mail.com/u37z0?id=414&ref=yus'),
(4140, 'Bob Silva', '1lhjj956QjqHro4HsW6V', 'empreendedorismo', 'https://mail.com/e3izs?id=668&ref=xnC'),
(4141, 'Diana Almeida', 'eLptI6VLAnJmaUhxU3WL', 'agro', 'http://mail.com/0yjbq?id=289&ref=Nvs'),
(4142, 'Bob Oliveira', 'J3jcTqS2SNKfOzEJ0Djx', 'marketing', 'http://mail.com/98ifs?id=585&ref=msN'),
(4143, 'Felipe Almeida', 'w0Olsf7jBI282GPyMQqD', 'empreendedorismo', 'http://example.com/9znvg?id=703&ref=xrd'),
(4144, 'Gabriela Oliveira', 'XUE4TrB0kr2NxZc29OSM', 'tecnologia', 'http://mail.com/aewho?id=294&ref=ONQ'),
(4145, 'Bob Oliveira', 'yO54LoU67vxqdIHFKzGa', 'marketing', 'https://example.com/kq4pl?id=600&ref=gtC'),
(4146, 'Diana Santos', 'TmG7CVSvf0soFn15Fv7T', 'agro', 'http://test.com/n05iw?id=721&ref=5WQ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `registration` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `registration`) VALUES
(879, 'Diana Almeida', 'alice.costa@example.com', '818');

-- --------------------------------------------------------

--
-- Table structure for table `user_class`
--

CREATE TABLE `user_class` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_course_id_foreign` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_unique` (`email`),
  ADD UNIQUE KEY `user_registration_unique` (`registration`);

--
-- Indexes for table `user_class`
--
ALTER TABLE `user_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_class_user_id_foreign` (`user_id`),
  ADD KEY `user_class_class_id_foreign` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1158;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4147;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=880;

--
-- AUTO_INCREMENT for table `user_class`
--
ALTER TABLE `user_class`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `class_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_class`
--
ALTER TABLE `user_class`
  ADD CONSTRAINT `user_class_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_class_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
