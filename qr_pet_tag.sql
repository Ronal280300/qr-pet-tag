-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-08-2025 a las 22:15:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `qr_pet_tag`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_27_031055_add_fields_to_users_table', 1),
(5, '2025_08_27_031055_create_pets_table', 1),
(6, '2025_08_27_031055_create_qr_codes_table', 1),
(7, '2025_08_27_031055_create_rewards_table', 1),
(8, '2025_08_27_031055_create_scans_table', 1),
(9, '2025_08_27_100000_add_unique_indexes_qr_and_reward', 2),
(10, '2025_08_27_100100_add_zone_to_pets_table', 2),
(11, '2025_08_27_120000_add_fields_to_rewards_table', 3),
(12, '2025_08_27_130000_add_image_to_qr_codes', 4),
(13, '2025_08_28_100000_add_activation_fields_to_qr_codes', 5),
(14, '2025_08_28_110000_make_pets_user_id_nullable', 5),
(15, '2025_08_28_120000_add_activation_code_to_qr_codes_table', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('rosepa2803@gmail.com', '$2y$12$Xi2HDkbNGlM05XFH1dEwUeBEat93rW5bpGI6tWY0HbYxa9tXsG9lK', '2025-08-27 13:09:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pets`
--

CREATE TABLE `pets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `zone` varchar(120) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_lost` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pets`
--

INSERT INTO `pets` (`id`, `user_id`, `name`, `breed`, `zone`, `age`, `medical_conditions`, `photo`, `is_lost`, `created_at`, `updated_at`) VALUES
(11, 3, 'Morgan', 'Labrador', 'San Juan, Grecia, Alajuela', 2, 'No tiene condiciones medicas', 'pets/RwxdOcdu6icRyom9P3ZHUkffugGCxkPG4pd19H1i.jpg', 0, '2025-08-28 11:15:17', '2025-08-28 12:17:15'),
(12, NULL, 'Asha', 'No definida', 'Curridabat', 1, NULL, 'pets/ARednEwzYVaPcPYqAK2MjiXDHqwQcj6m0wyZhN4k.jpg', 0, '2025-08-28 11:47:37', '2025-08-28 12:49:12'),
(13, 3, 'asd', 'asd', 'asd', 2, NULL, 'pets/7Up38026FFAQI1bO5Afx8Ye7NGMiPExkIjt1oGn2.jpg', 0, '2025-08-28 13:12:48', '2025-08-28 13:59:39'),
(14, 3, 'cvh', 'asdfsgh', 'Bolivar, Grecia, Alajuela', 1, NULL, 'pets/QAsFwBpbrURfBmydp4A4Mf5agM14Svp88SWieQZZ.jpg', 0, '2025-08-28 13:21:40', '2025-08-28 14:50:14'),
(16, 6, 'Negro Pomposo', 'Labrador', 'Puraba, Santa Barbara, Heredia', 1, NULL, 'pets/NvEhF1YqOmzqxv2Nu7BOOVByMV8wCZ6BfNFT7Z05.png', 1, '2025-08-28 14:52:29', '2025-08-28 15:19:55'),
(17, NULL, 'Totto', NULL, 'Escazú, Escazú, San José', 5, NULL, 'pets/79mwKTW7Ok8GQXeTvstKD9JyDMicHG0uIrWcP2Pp.jpg', 0, '2025-08-28 15:22:32', '2025-08-28 15:22:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pet_id` bigint(20) UNSIGNED NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `activation_code` varchar(50) NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT 0,
  `activated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `pet_id`, `qr_code`, `slug`, `image`, `activation_code`, `is_activated`, `activated_at`, `is_active`, `created_at`, `updated_at`, `activated_by`) VALUES
(19, 11, 'http://127.0.0.1:8000/pet/asd-11', 'asd-11', 'qrcodes/asd-11.svg', 'POUP-SPZF-5355', 1, '2025-08-28 11:16:56', 1, '2025-08-28 11:15:17', '2025-08-28 11:16:56', 3),
(20, 12, 'http://127.0.0.1:8000/pet/asha-12', 'asha-12', 'qrcodes/asha-12.svg', 'VGUB-FO3B-5194', 0, NULL, 1, '2025-08-28 11:47:37', '2025-08-28 11:47:37', NULL),
(21, 13, 'http://127.0.0.1:8000/pet/asd-13', 'asd-13', 'qrcodes/asd-13.svg', 'D9NEOTQG', 1, '2025-08-28 13:13:11', 1, '2025-08-28 13:12:48', '2025-08-28 14:00:33', 3),
(22, 14, 'http://127.0.0.1:8000/pet/cvh-14', 'cvh-14', 'qrcodes/cvh-14.svg', 'QI28IEWY', 1, '2025-08-28 13:21:53', 1, '2025-08-28 13:21:40', '2025-08-28 13:54:07', 3),
(24, 16, 'http://127.0.0.1:8000/pet/negro-pomposo-16', 'negro-pomposo-16', 'qrcodes/negro-pomposo-16.svg', 'S90UQGEJ', 1, '2025-08-28 14:56:01', 1, '2025-08-28 14:52:29', '2025-08-28 15:19:39', 6),
(25, 17, 'http://127.0.0.1:8000/pet/totto-17', 'totto-17', 'qrcodes/totto-17.svg', 'HDMC-UGPO-6465', 0, NULL, 1, '2025-08-28 15:22:32', '2025-08-28 15:22:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rewards`
--

CREATE TABLE `rewards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pet_id` bigint(20) UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `amount` decimal(8,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rewards`
--

INSERT INTO `rewards` (`id`, `pet_id`, `active`, `amount`, `is_active`, `message`, `created_at`, `updated_at`) VALUES
(7, 11, 0, 0.00, 0, NULL, '2025-08-28 11:18:45', '2025-08-28 12:11:56'),
(8, 12, 0, 0.00, 0, 'Gracias por tu ayuda 🙏', '2025-08-28 12:54:39', '2025-08-28 12:54:39'),
(9, 13, 0, 0.00, 0, 'Gracias por tu ayuda 🙏', '2025-08-28 13:16:39', '2025-08-28 13:59:28'),
(10, 16, 1, 130000.00, 0, 'Gracias por tu ayuda 🙏', '2025-08-28 15:19:42', '2025-08-28 15:19:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scans`
--

CREATE TABLE `scans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qr_code_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7OIckTmaix6oUpb6tz1VwZLLtdhSP4MvtrO2jlW1', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibHE3ZjZXbTNqZVo4V3dueFkxUW5HZEJVNXY4R3R0Q2JsZmZWUWh0TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3J0YWwvcGV0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzU2NDExMDkxO319', 1756411760),
('unRP4xMyKTnTGgAAIeYH9R0BUZ2S0iL0iU53R4tD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTjA5Qm9RUzAxVUJ6U1ZPcVJ5UWJGWUlIV1Q2eTI2czY4UWFwQ20wNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3J0YWwvcGV0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzU2NDExMDc1O319', 1756411772);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `emergency_contact`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ronaldo', 'rosepa2803@gmail.com', '85307942', NULL, NULL, 1, NULL, '$2y$12$u7KD7kS/yKPVPToaCPmkpem614FxT8IIS6vwZHdjEc7E2UY3xxKy2', 'NvjvcCZJuizI5YBpgqHSRNSYcDukJrcaOftshB3PNLH05nPROannGBWAiOa9', '2025-08-27 11:37:01', '2025-08-27 11:37:01'),
(2, 'RonaldoS', 'rosepa28030@gmail.com', '85307942', NULL, NULL, 0, NULL, '$2y$12$eJ85axmP9kae3tjaP9TWC.AsdfDx6aMXKzkJQnO6sRmmUcNushVi2', NULL, '2025-08-27 13:08:37', '2025-08-27 13:08:37'),
(3, 'Priscilla Leiva Ramirez', 'prileiva@gmail.com', '88888888', NULL, NULL, 0, NULL, '$2y$12$Wk4ORLD/px4yX5aakqyJSupBELFr1eFE5QGTX9JR.Lez8IoaBD9V.', NULL, '2025-08-27 14:05:06', '2025-08-27 14:05:06'),
(4, 'Robertho', 'rosepa280300@gmail.com', '85307942', NULL, NULL, 0, NULL, '$2y$12$7XqRQ58exVR1eGfptWxkreZiPRoXFguIYx3BG6Y4KhKASPVEmeGve', NULL, '2025-08-27 14:36:53', '2025-08-27 14:36:53'),
(5, 'Priscilla', 'prileiva1@gmail.com', '45', NULL, NULL, 0, NULL, '$2y$12$5QUFO5G6jce6uF6q.b9/lezy8gw7CubQxz3KjwTnzI4ATTao3I7l6', NULL, '2025-08-27 16:50:10', '2025-08-27 16:50:10'),
(6, 'Ronaldo Ronaldo', 'rosepa2803000@gmail.com', '85307942', NULL, NULL, 0, NULL, '$2y$12$/3opsU8eWu8xVDqWjE6UYOQTIWAQTtOLg4Sh.hg3syH1XbwSTPf3e', NULL, '2025-08-28 13:53:46', '2025-08-28 13:53:46');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pets_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_qr_code_unique` (`qr_code`),
  ADD UNIQUE KEY `qr_codes_slug_unique` (`slug`),
  ADD UNIQUE KEY `qr_codes_pet_id_unique` (`pet_id`),
  ADD KEY `qr_codes_activated_by_foreign` (`activated_by`);

--
-- Indices de la tabla `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rewards_pet_id_unique` (`pet_id`);

--
-- Indices de la tabla `scans`
--
ALTER TABLE `scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scans_qr_code_id_foreign` (`qr_code_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pets`
--
ALTER TABLE `pets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `scans`
--
ALTER TABLE `scans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_activated_by_foreign` FOREIGN KEY (`activated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `qr_codes_pet_id_foreign` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_pet_id_foreign` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `scans`
--
ALTER TABLE `scans`
  ADD CONSTRAINT `scans_qr_code_id_foreign` FOREIGN KEY (`qr_code_id`) REFERENCES `qr_codes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
