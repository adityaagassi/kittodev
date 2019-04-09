-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jun 08, 2017 at 11:36 PM
-- Server version: 5.5.42
-- PHP Version: 5.4.42

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `kitto`
--

-- --------------------------------------------------------

--
-- Table structure for table `completions`
--

CREATE TABLE `completions` (
  `id` int(100) NOT NULL,
  `barcode_number` varchar(50) NOT NULL,
  `description_completion` text NOT NULL,
  `location_completion` varchar(50) NOT NULL,
  `issue_plant` varchar(50) NOT NULL,
  `lot_completion` int(10) NOT NULL,
  `material_id` int(50) NOT NULL,
  `limit_used` int(50) NOT NULL,
  `user_id` int(10) NOT NULL,
  `active` int(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `completions_adjustment`
--

CREATE TABLE `completions_adjustment` (
  `id` int(50) NOT NULL,
  `reference_number` text,
  `description_completion` varchar(50) DEFAULT NULL,
  `location_completion` varchar(50) NOT NULL,
  `issue_plant` varchar(50) NOT NULL,
  `lot_completion` int(10) NOT NULL,
  `material_id` int(50) NOT NULL,
  `user_id` int(10) NOT NULL,
  `active` int(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cron_job`
--

CREATE TABLE `cron_job` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `return` text COLLATE utf8_unicode_ci NOT NULL,
  `runtime` float(8,2) NOT NULL,
  `cron_manager_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_manager`
--

CREATE TABLE `cron_manager` (
  `id` int(10) unsigned NOT NULL,
  `rundate` datetime NOT NULL,
  `runtime` float(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `completion_barcode_number` varchar(50) DEFAULT NULL,
  `completion_description` text,
  `completion_location` varchar(50) DEFAULT NULL,
  `completion_issue_plant` varchar(50) DEFAULT NULL,
  `completion_material_id` int(50) DEFAULT NULL,
  `completion_reference_number` varchar(50) DEFAULT NULL,
  `transfer_barcode_number` varchar(50) DEFAULT NULL,
  `transfer_document_number` varchar(50) DEFAULT NULL,
  `transfer_material_id` int(10) DEFAULT NULL,
  `transfer_issue_location` varchar(50) DEFAULT NULL,
  `transfer_issue_plant` varchar(50) DEFAULT NULL,
  `transfer_receive_location` varchar(50) DEFAULT NULL,
  `transfer_receive_plant` varchar(50) DEFAULT NULL,
  `transfer_cost_center` varchar(10) DEFAULT NULL,
  `transfer_gl_account` varchar(10) DEFAULT NULL,
  `transfer_transaction_code` varchar(50) DEFAULT NULL,
  `transfer_movement_type` varchar(50) DEFAULT NULL,
  `transfer_reason_code` varchar(50) DEFAULT NULL,
  `error_description` text,
  `lot` int(10) NOT NULL,
  `reference_file` varchar(100) DEFAULT NULL,
  `synced` int(1) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` int(10) NOT NULL,
  `completion_id` int(10) DEFAULT NULL,
  `transfer_id` int(10) DEFAULT NULL,
  `barcode_number` varchar(50) NOT NULL,
  `lot` int(10) NOT NULL,
  `description` text,
  `issue_location` varchar(50) DEFAULT NULL,
  `last_action` varchar(50) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'superman', NULL, '2016-02-03 17:00:00', '2016-02-03 17:00:00'),
(2, 'administrator', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'inputor', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(100) NOT NULL,
  `material_number` varchar(50) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `lead_time` float NOT NULL,
  `user_id` int(10) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `logged_in` int(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL,
  `upload_resume` int(1) NOT NULL,
  `download_report` int(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `upload_resume`, `download_report`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2017-06-07 17:00:00', '2017-06-08 16:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `barcode_number_transfer` varchar(50) NOT NULL,
  `material_id` int(10) NOT NULL,
  `issue_location` varchar(50) NOT NULL,
  `issue_plant` varchar(50) NOT NULL,
  `receive_location` varchar(50) NOT NULL,
  `receive_plant` varchar(50) NOT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `movement_type` varchar(50) NOT NULL,
  `reason_code` varchar(50) DEFAULT NULL,
  `lot_transfer` int(10) NOT NULL,
  `completion_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transfers_adjustment`
--

CREATE TABLE `transfers_adjustment` (
  `id` int(50) NOT NULL,
  `document_number` varchar(15) DEFAULT NULL,
  `material_id` int(10) NOT NULL,
  `issue_location` varchar(50) NOT NULL,
  `issue_plant` varchar(50) NOT NULL,
  `receive_plant` varchar(50) NOT NULL,
  `receive_location` varchar(50) NOT NULL,
  `lot` int(10) NOT NULL,
  `cost_center` varchar(50) DEFAULT NULL,
  `gl_account` varchar(50) DEFAULT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `movement_type` varchar(50) NOT NULL,
  `reason_code` varchar(50) DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `active` int(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `level_id` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `level_id`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'System', 'super@kitto.com', '$2y$10$Ab2ZpDfR5diiJnPQiW6ETeHH2U9u0fH3dWLFd5lbV9kXDh6ro0Z4q', 1, 'QCxAimrq1D4CwRqN6VJUKaJkqLOFwWALp13XnW6nEgb35S5j3OioArZZl2E5', NULL, '2016-02-03 17:00:00', '2017-06-08 16:30:33'),
(2, 'administrator', 'administrator@kitto.com', '$2y$10$AQXs9K/xNyRkII.OtfDIPOuMgC5aGOgrpO2jfWSmcEf9Tj.knI8Ce', 2, 'zNBXYRlCH2TzoBvZsegV1uDMdK4KIT5WJ27RqHZBRrpsK3t9DrfUJf60cl9e', NULL, '2017-03-06 13:11:32', '2017-06-06 14:32:12'),
(3, 'inputor', 'inputor@kitto.com', '$2y$10$MHXG49eUmzXFZI3UA4mVEuo2HSfisWxtEByeEJ8G4TztLwkbfQg0S', 3, 'JEeD3sXSHMAR0JguUfl15Vu2WLv3GoDub6Xs84ZSwJvUdRwlCpsJHZixclX2', NULL, '2017-03-06 13:11:14', '2017-05-07 15:06:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `completions`
--
ALTER TABLE `completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode_number` (`barcode_number`);

--
-- Indexes for table `completions_adjustment`
--
ALTER TABLE `completions_adjustment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job`
--
ALTER TABLE `cron_job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cron_job_name_cron_manager_id_index` (`name`,`cron_manager_id`);

--
-- Indexes for table `cron_manager`
--
ALTER TABLE `cron_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_number` (`material_number`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`download_report`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode_number_transfer` (`barcode_number_transfer`);

--
-- Indexes for table `transfers_adjustment`
--
ALTER TABLE `transfers_adjustment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `completions`
--
ALTER TABLE `completions`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `completions_adjustment`
--
ALTER TABLE `completions_adjustment`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_job`
--
ALTER TABLE `cron_job`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cron_manager`
--
ALTER TABLE `cron_manager`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transfers_adjustment`
--
ALTER TABLE `transfers_adjustment`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;