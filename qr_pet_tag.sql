-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2025 a las 08:56:51
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

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('qr-pet-tag-cache-illuminate:queue:restart', 'i:1757054481;', 2072414481);

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

--
-- Volcado de datos para la tabla `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'fffa95eb-71e3-4230-8d80-c8e502fd30bd', 'database', 'default', '{\"uuid\":\"fffa95eb-71e3-4230-8d80-c8e502fd30bd\",\"displayName\":\"App\\\\Jobs\\\\PostPetToFacebookJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":5,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"10,30,60,120,240\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PostPetToFacebookJob\",\"command\":\"O:29:\\\"App\\\\Jobs\\\\PostPetToFacebookJob\\\":1:{s:6:\\\"postId\\\";i:1;}\"},\"createdAt\":1757054012,\"delay\":null}', 'Error: Call to undefined method App\\Services\\FacebookPoster::postPhotoFile() in C:\\Users\\Ronaldo\\qr-pet-tag\\app\\Jobs\\PostPetToFacebookJob.php:58\nStack trace:\n#0 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\PostPetToFacebookJob->handle(Object(App\\Services\\FacebookPoster))\n#1 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#3 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#4 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#5 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#6 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#7 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#8 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#9 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\PostPetToFacebookJob), false)\n#10 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#11 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Middleware\\RateLimited.php(64): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#12 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(219): Illuminate\\Queue\\Middleware\\RateLimited->handle(Object(App\\Jobs\\PostPetToFacebookJob), Object(Closure))\n#13 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#14 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\PostPetToFacebookJob))\n#16 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#28 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(1110): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(194): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 C:\\Users\\Ronaldo\\qr-pet-tag\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#36 {main}', '2025-09-05 12:37:20'),
(2, 'b883e940-dff3-43a9-b6bd-32c249153ceb', 'database', 'default', '{\"uuid\":\"b883e940-dff3-43a9-b6bd-32c249153ceb\",\"displayName\":\"App\\\\Jobs\\\\PostPetToFacebookJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":5,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"10,30,60,120,240\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PostPetToFacebookJob\",\"command\":\"O:29:\\\"App\\\\Jobs\\\\PostPetToFacebookJob\\\":1:{s:6:\\\"postId\\\";i:2;}\"},\"createdAt\":1757054167,\"delay\":null}', 'Error: Call to undefined method App\\Services\\FacebookPoster::postPhotoFile() in C:\\Users\\Ronaldo\\qr-pet-tag\\app\\Jobs\\PostPetToFacebookJob.php:58\nStack trace:\n#0 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\PostPetToFacebookJob->handle(Object(App\\Services\\FacebookPoster))\n#1 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#3 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#4 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#5 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Container\\Container->call(Array)\n#6 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#7 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#8 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#9 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\PostPetToFacebookJob), false)\n#10 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#11 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Middleware\\RateLimited.php(64): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#12 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(219): Illuminate\\Queue\\Middleware\\RateLimited->handle(Object(App\\Jobs\\PostPetToFacebookJob), Object(Closure))\n#13 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\PostPetToFacebookJob))\n#14 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#15 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\PostPetToFacebookJob))\n#16 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#17 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#18 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(394): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#19 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(180): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#21 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#22 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#23 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#24 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#25 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#26 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(836): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#27 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#28 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Command\\Command.php(318): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(1110): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\symfony\\console\\Application.php(194): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(197): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 C:\\Users\\Ronaldo\\qr-pet-tag\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 C:\\Users\\Ronaldo\\qr-pet-tag\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#36 {main}', '2025-09-05 12:39:53');

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
(9, '2025_08_27_100000_add_unique_indexes_qr_and_reward', 1),
(10, '2025_08_27_100100_add_zone_to_pets_table', 1),
(11, '2025_08_27_120000_add_fields_to_rewards_table', 1),
(12, '2025_08_27_130000_add_image_to_qr_codes', 1),
(13, '2025_08_28_100000_add_activation_fields_to_qr_codes', 1),
(14, '2025_08_28_110000_make_pets_user_id_nullable', 1),
(15, '2025_08_28_211605_drop_qr_code_from_qr_codes_table', 1),
(16, '2025_08_29_000001_add_code_fields_to_qr_codes_table', 1),
(17, '2025_08_29_000001_create_pet_photos_table', 1),
(18, '2025_08_30_175518_add_sort_order_to_pet_photos', 2),
(19, '2025_09_02_200913_add_google_fields_to_users_table', 3),
(20, '2025_09_02_000000_add_unique_index_to_users_phone', 4),
(21, '2025_09_02_000000_add_core_fields_to_pets_table', 5),
(22, '2025_09_04_000000_add_fb_post_fields_to_pets_table', 6),
(23, '0001_01_01_000001_create_pet_fb_posts_table', 7),
(24, '2025_09_19_055411_create_subscriptions_table', 8);

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
('info.qrpettag@gmail.com', '$2y$12$1Dif3ucinC9WvPTKtS/sVOp5eUMAIagFZxQ/l673bLFZ1soUr5IHa', '2025-09-03 08:07:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pets`
--

CREATE TABLE `pets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `species` varchar(20) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(80) DEFAULT NULL,
  `is_neutered` tinyint(1) DEFAULT NULL,
  `rabies_vaccine` tinyint(1) DEFAULT NULL,
  `zone` varchar(120) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_lost` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_fb_post_id` varchar(100) DEFAULT NULL,
  `last_fb_page_id` varchar(50) DEFAULT NULL,
  `last_fb_posted_at` timestamp NULL DEFAULT NULL,
  `last_fb_post_hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pets`
--

INSERT INTO `pets` (`id`, `user_id`, `name`, `breed`, `species`, `sex`, `size`, `color`, `is_neutered`, `rabies_vaccine`, `zone`, `age`, `medical_conditions`, `photo`, `is_lost`, `created_at`, `updated_at`, `last_fb_post_id`, `last_fb_page_id`, `last_fb_posted_at`, `last_fb_post_hash`) VALUES
(1, 2, 'asd', 'asd', NULL, NULL, NULL, NULL, NULL, NULL, 'San Rafael, Escazú, San José', 1, 'asdasd', 'pets/0f28JXAxvixziTrObWS3EtImGhoLkYjXB7fccC9L.jpg', 0, '2025-08-31 04:40:12', '2025-09-05 12:16:14', NULL, NULL, NULL, NULL),
(3, NULL, 'sdf', 'sdf', NULL, 'male', NULL, NULL, 0, 0, 'Mansión, Nicoya, Guanacaste', 1, NULL, 'pets/qsQ4ZFtfoBD0LrHvZZBFHKrll36TqdVbLGekLfIz.jpg', 0, '2025-09-02 09:10:10', '2025-09-03 11:33:21', NULL, NULL, NULL, NULL),
(4, 2, 'Enen', 'Zbsb', NULL, 'female', NULL, NULL, 0, 1, 'Nosara, Nicoya, Guanacaste', 1, NULL, 'pets/L0YS5W8pI7o78I4Ifo19uXci8KucmxeR11LwnOxU.jpg', 0, '2025-09-02 09:26:01', '2025-09-03 10:20:11', NULL, NULL, NULL, NULL),
(5, 2, 'Morgan', 'ert', NULL, 'female', NULL, NULL, 0, 0, 'San Juan De Dios, Desamparados, San José', 2, NULL, 'pets/blSJLl5OsZm06d32Xmf9y64MWDSVuIQXc12GRchF.jpg', 1, '2025-09-03 09:38:35', '2025-09-04 10:05:16', NULL, NULL, NULL, NULL),
(8, 1, 'Asha', 'Sin Raza', NULL, 'female', NULL, NULL, 0, 0, 'Bolivar, Grecia, Alajuela', 7, 'Asha actualmente es ciega, pero si anda por todas partes porque logró desarrollar su audición.', 'pets/SCy9uEO4e4Lu0CaZ0Fxyd2Cb7tsawayat0pjlRkC.jpg', 0, '2025-09-03 10:38:42', '2025-09-03 10:40:16', NULL, NULL, NULL, NULL),
(9, 1, 'Asha', 'Sin Raza', NULL, 'female', NULL, NULL, 1, 1, 'Bolivar, Grecia, Alajuela', 7, NULL, 'pets/TfrGr7L9RC6yUPHXUfnVrajXjdw9KHGYdLdQogx5.jpg', 1, '2025-09-03 10:43:20', '2025-09-05 06:52:33', NULL, NULL, NULL, NULL),
(10, 1, 'asd', 'asd', NULL, 'female', NULL, NULL, 1, 1, 'San Pablo, Turrubares, San José', 12, NULL, 'pets/XfNW1w9r5zqa7YWJp5AWk4RT770tNUkahXPDdqkO.jpg', 1, '2025-09-03 10:44:08', '2025-09-04 09:27:56', NULL, NULL, NULL, NULL),
(11, 2, 'asd', 'asd', NULL, 'male', NULL, NULL, 1, 1, 'San José, Central, Alajuela', 1, 'asdasdasd', 'pets/LjOUEZu2sIVjpyzWJwf81T9ZkWkEMj0QMAmcAznu.jpg', 0, '2025-09-03 10:50:45', '2025-09-05 09:53:11', NULL, NULL, NULL, NULL),
(13, 2, 'Morgan', 'Labrador', NULL, 'male', NULL, NULL, 1, 1, 'Bolivar, Grecia, Alajuela', 2, 'Si tiene observaciones esta mascota', 'pets/mWLEup4cWqDahUg0z43JKz07owv5DhR7yaBsXQPc.jpg', 1, '2025-09-04 10:51:32', '2025-09-05 12:00:39', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pet_fb_posts`
--

CREATE TABLE `pet_fb_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pet_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'queued',
  `post_id` varchar(120) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `fingerprint` varchar(64) DEFAULT NULL,
  `attempts` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `image_kind` varchar(10) DEFAULT NULL,
  `image_ref` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pet_fb_posts`
--

INSERT INTO `pet_fb_posts` (`id`, `pet_id`, `status`, `post_id`, `message`, `fingerprint`, `attempts`, `last_attempt_at`, `error_message`, `image_kind`, `image_ref`, `created_at`, `updated_at`) VALUES
(1, 8, 'failed', NULL, '🐾 Asha\n🐶 Raza: Sin Raza\n🚻 Sexo: Hembra ♀️\n🎂 Edad: 7 años\n📍 Zona: Bolivar, Grecia, Alajuela\n🔗 Perfil: http://127.0.0.1:8000/p/asha-8-ix2qlv\nQR-Pet Tag', '59ab3782474009bef912ae9a33e364c3c030bbd8305a34d808ce862cb8ac1f23', 5, '2025-09-05 12:37:20', 'Call to undefined method App\\Services\\FacebookPoster::postPhotoFile()', 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/SCy9uEO4e4Lu0CaZ0Fxyd2Cb7tsawayat0pjlRkC.jpg', '2025-09-05 12:33:31', '2025-09-05 12:37:20'),
(2, 8, 'failed', NULL, '🐾 Asha\n🐶 Raza: Sin Raza\n🚻 Sexo: Hembra ♀️\n🎂 Edad: 7 años\n📍 Zona: Bolivar, Grecia, Alajuela\n🔗 Perfil: http://127.0.0.1:8000/p/asha-8-ix2qlv\nQR-Pet Tag', '59ab3782474009bef912ae9a33e364c3c030bbd8305a34d808ce862cb8ac1f23', 5, '2025-09-05 12:39:53', 'Call to undefined method App\\Services\\FacebookPoster::postPhotoFile()', 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/SCy9uEO4e4Lu0CaZ0Fxyd2Cb7tsawayat0pjlRkC.jpg', '2025-09-05 12:36:07', '2025-09-05 12:39:53'),
(3, 8, 'success', '735239436346292_122105476400996268', '🐾 Asha\n🐶 Raza: Sin Raza\n🚻 Sexo: Hembra ♀️\n🎂 Edad: 7 años\n📍 Zona: Bolivar, Grecia, Alajuela\n🔗 Perfil: http://127.0.0.1:8000/p/asha-8-ix2qlv\nQR-Pet Tag', '59ab3782474009bef912ae9a33e364c3c030bbd8305a34d808ce862cb8ac1f23', 1, '2025-09-05 12:41:34', NULL, 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/SCy9uEO4e4Lu0CaZ0Fxyd2Cb7tsawayat0pjlRkC.jpg', '2025-09-05 12:41:34', '2025-09-05 12:41:39'),
(4, 13, 'success', '735239436346292_122105476922996268', '🐾 Morgan\n🐶 Raza: Labrador\n🚻 Sexo: Macho ♂️\n🎂 Edad: 2 años\n📍 Zona: Bolivar, Grecia, Alajuela\n⚠️ Reportada como perdida/robada\n🔗 Perfil: http://127.0.0.1:8000/p/morgan-13-sqq7bz\nQR-Pet Tag', 'b74075d5c35d7d74aac818146bbb677d9c8c9aa3460aab1969d0ec013051060f', 1, '2025-09-05 12:43:09', NULL, 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/mWLEup4cWqDahUg0z43JKz07owv5DhR7yaBsXQPc.jpg', '2025-09-05 12:43:08', '2025-09-05 12:43:13'),
(5, 13, 'success', '735239436346292_122105477204996268', '🐾 Morgan\n🐶 Raza: Labrador\n🚻 Sexo: Macho ♂️\n🎂 Edad: 2 años\n📍 Zona: Bolivar, Grecia, Alajuela\n⚠️ Reportada como perdida/robada\n🔗 Perfil: http://127.0.0.1:8000/p/morgan-13-sqq7bz\nQR-Pet Tag', 'b74075d5c35d7d74aac818146bbb677d9c8c9aa3460aab1969d0ec013051060f', 1, '2025-09-05 12:44:37', NULL, 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/mWLEup4cWqDahUg0z43JKz07owv5DhR7yaBsXQPc.jpg', '2025-09-05 12:44:35', '2025-09-05 12:44:42'),
(6, 13, 'success', '735239436346292_122105477720996268', '🐾 Morgan\nRaza: Labrador\nSexo: Macho ♂️\nEdad: 2 años\nZona: Bolivar, Grecia, Alajuela\n⚠️ Reportada como perdida/robada\nPerfil: http://127.0.0.1:8000/p/morgan-13-sqq7bz\nQR-Pet Tag', '1c203c3db15e62fdc4b933b82b689bd24a27daa592cb5ab67b4830d892b7b88b', 1, '2025-09-05 12:47:22', NULL, 'file', 'C:\\Users\\Ronaldo\\qr-pet-tag\\storage\\app/public/pets/mWLEup4cWqDahUg0z43JKz07owv5DhR7yaBsXQPc.jpg', '2025-09-05 12:47:19', '2025-09-05 12:47:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pet_photos`
--

CREATE TABLE `pet_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pet_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pet_photos`
--

INSERT INTO `pet_photos` (`id`, `pet_id`, `path`, `sort_order`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'pets/0f28JXAxvixziTrObWS3EtImGhoLkYjXB7fccC9L.jpg', 1, 0, '2025-08-31 04:40:12', '2025-08-31 04:40:12'),
(7, 3, 'pets/qsQ4ZFtfoBD0LrHvZZBFHKrll36TqdVbLGekLfIz.jpg', 1, 0, '2025-09-02 09:10:10', '2025-09-02 09:10:10'),
(8, 3, 'pets/photos/DGlry2rH4OT2YojNhHQM4vqaixaHzkYeT3dDePgR.jpg', 2, 0, '2025-09-02 09:10:38', '2025-09-02 09:10:38'),
(9, 3, 'pets/photos/xRYouQTeem3HzByqcYdIdMc61PuAGNyR3Rc6scK0.jpg', 3, 0, '2025-09-02 09:10:38', '2025-09-02 09:10:38'),
(10, 4, 'pets/L0YS5W8pI7o78I4Ifo19uXci8KucmxeR11LwnOxU.jpg', 1, 0, '2025-09-02 09:26:01', '2025-09-02 09:26:01'),
(11, 5, 'pets/blSJLl5OsZm06d32Xmf9y64MWDSVuIQXc12GRchF.jpg', 1, 0, '2025-09-03 09:38:35', '2025-09-03 09:38:35'),
(16, 8, 'pets/SCy9uEO4e4Lu0CaZ0Fxyd2Cb7tsawayat0pjlRkC.jpg', 1, 0, '2025-09-03 10:38:42', '2025-09-03 10:38:42'),
(17, 8, 'pets/photos/Q2zTzpOmGc1KlwKginrhFZv1kHYzOFn3ADz4m2u3.jpg', 2, 0, '2025-09-03 10:41:36', '2025-09-03 10:41:36'),
(18, 9, 'pets/TfrGr7L9RC6yUPHXUfnVrajXjdw9KHGYdLdQogx5.jpg', 1, 0, '2025-09-03 10:43:20', '2025-09-03 10:43:20'),
(19, 10, 'pets/XfNW1w9r5zqa7YWJp5AWk4RT770tNUkahXPDdqkO.jpg', 1, 0, '2025-09-03 10:44:08', '2025-09-03 10:44:08'),
(20, 11, 'pets/LjOUEZu2sIVjpyzWJwf81T9ZkWkEMj0QMAmcAznu.jpg', 1, 0, '2025-09-03 10:50:45', '2025-09-03 10:50:45'),
(22, 13, 'pets/mWLEup4cWqDahUg0z43JKz07owv5DhR7yaBsXQPc.jpg', 1, 0, '2025-09-04 10:51:32', '2025-09-04 10:51:32'),
(23, 13, 'pets/photos/pEJHZy5f4QzIrxS8Zhoqq408src1GUGvOl0nkP9U.jpg', 2, 0, '2025-09-04 10:53:26', '2025-09-04 10:53:26'),
(24, 13, 'pets/photos/JeWeRnvHrgXYnTnzzsSqB46zvV1XrFKhRmDqkowf.jpg', 3, 0, '2025-09-04 10:53:26', '2025-09-04 10:53:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pet_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `code` varchar(32) DEFAULT NULL,
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

INSERT INTO `qr_codes` (`id`, `pet_id`, `slug`, `code`, `image`, `activation_code`, `is_activated`, `activated_at`, `is_active`, `created_at`, `updated_at`, `activated_by`) VALUES
(1, 1, 'asd-1-xa94ma', NULL, 'qrcodes/asd-1-xa94ma.svg', '08LY-WSNY-9725', 1, '2025-08-31 05:54:50', 1, '2025-08-31 04:40:12', '2025-09-05 12:15:16', 2),
(3, 3, 'sdf-3-7sb7fk', NULL, 'qrcodes/sdf-3-7sb7fk.svg', 'HFXV-MZV7-2065', 0, NULL, 1, '2025-09-02 09:10:10', '2025-09-02 09:10:18', NULL),
(4, 4, 'enen-4-bifgme', NULL, 'qrcodes/enen-4-bifgme.svg', '5AGB-9SX2-4862', 1, '2025-09-02 10:05:51', 1, '2025-09-02 09:26:01', '2025-09-02 10:05:51', 2),
(5, 5, 'ert-5-xlsxrn', NULL, 'qrcodes/ert-5-xlsxrn.svg', 'XJJK-5JUK-5709', 1, '2025-09-03 09:39:09', 1, '2025-09-03 09:38:35', '2025-09-03 09:39:09', 2),
(8, 8, 'asha-8-ix2qlv', NULL, 'qrcodes/asha-8-ix2qlv.svg', 'X7M3-UNBL-4116', 0, NULL, 1, '2025-09-03 10:38:42', '2025-09-03 10:38:42', NULL),
(9, 9, 'asha-9-h6nuv7', NULL, 'qrcodes/asha-9-h6nuv7.svg', 'NRLQ-CIUF-2137', 0, NULL, 1, '2025-09-03 10:43:20', '2025-09-03 10:43:20', NULL),
(10, 10, 'asd-10-bkpacf', NULL, 'qrcodes/asd-10-bkpacf.svg', 'HQGE-E7EN-5881', 0, NULL, 1, '2025-09-03 10:44:08', '2025-09-03 10:44:08', NULL),
(11, 11, 'asd-11-m7stx5', NULL, 'qrcodes/asd-11-m7stx5.svg', 'BNF0-BYEO-2193', 1, '2025-09-03 10:51:02', 1, '2025-09-03 10:50:45', '2025-09-03 10:51:02', 2),
(13, 13, 'morgan-13-sqq7bz', NULL, 'qrcodes/morgan-13-sqq7bz.svg', 'QAKX-E4SU-3410', 1, '2025-09-04 10:52:08', 1, '2025-09-04 10:51:32', '2025-09-05 10:27:59', 2);

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
(2, 1, 0, 0.00, 0, NULL, '2025-08-31 11:29:59', '2025-09-05 12:15:21'),
(3, 4, 0, 0.00, 0, NULL, '2025-09-03 08:20:54', '2025-09-03 08:48:17'),
(5, 11, 1, 1500.00, 0, 'Gracias por tu ayuda 🙏', '2025-09-03 10:59:12', '2025-09-03 11:25:08'),
(7, 13, 0, 0.00, 0, NULL, '2025-09-04 10:53:58', '2025-09-05 10:15:12');

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
('02eLR4am0MdDSbQlsqoYQr8fUQLu5Dg1rtgUEqzz', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRDVETk5YUHh1aFNKQWZHcGY3MHRFd0M4S1hsczZWV2piQU84UXBZcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3J0YWwvcGV0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzU4MjYxNjYyO319', 1758261667),
('C9UAF1xwl946jMWu0zxAUD3PGfhijLZws4wOboS5', 1, '192.168.50.204', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibXFWRWp1Y3JTVVRPZTEydUljbExFcFBCWnAxMXNxTlFFaThRTVFMMSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTkyLjE2OC41MC4xNzE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzU3NTY2NTMxO319', 1757566578),
('Q1ZYUDaKbDW8mQpWFDQPttKRgVJijeUt9PkgJm26', NULL, '192.168.50.204', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicDBZR3FKc1FIcnNjRWJWVm1udnR1aWpJaTlHand6c0FSVGdTT0luZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovLzE5Mi4xNjguNTAuMTcxOjgwMDAvcG9ydGFsL3BldHMvMzMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovLzE5Mi4xNjguNTAuMTcxOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1757566490),
('Svj9LT8pdV26Wem3A9u6aX1WfYx78FN3fKX4iRDD', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkkxM0dRcW9XUHIyWTloQzJFSFlzVE5DNnltbDJFbURjQjQ1bUxacSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3J0YWwvcGV0cy8xNSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO30=', 1757565656),
('uuu5XvHMf6NXn75hCj8GJCe1WAsdYibssHQkKCxn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHlJdzlmNm8xZkVZVVg2RVBFOGN0WVpza21MVTBlTGlJbjNqVVVkcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758256816),
('vQSpZKfHaTUHDDTbvrzxn8aHDzwA0rX0IhmW6tOR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidTJRRVpaVEpINGYxN1QwY2VoRExhZVkyREFQc0hERkNNY0ZGNFpMaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NToic3RhdGUiO3M6NDA6IlNZVWo0Y1dublBxM3p6d0NPU2hlcXZNTWVBTnQzSnpjR3Znb2NDVG4iO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzU3NTY1NDE4O319', 1757566364),
('z6TYdpXwZG7n7mw7X2VyMCm5CBN5kGIO7OhlAlo1', NULL, '192.168.50.204', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOVh1dTBrdVk0d3NHdjdLYU5ZTEpxQlhISnh6ZHppaGJocGJXUDZ3SiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MjoiaHR0cDovLzE5Mi4xNjguNTAuMTcxOjgwMDAvcG9ydGFsL2FkbWluL2FjdGl2YXRlLXRhZyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vMTkyLjE2OC41MC4xNzE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757566489);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `paypal_subscription_id` varchar(255) NOT NULL,
  `paypal_plan_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'APPROVAL_PENDING',
  `pets_limit` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `next_billing_time` timestamp NULL DEFAULT NULL,
  `raw` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `emergency_contact`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES
(1, 'Admin', 'admin@gmail.com', '', NULL, NULL, 1, NULL, '$2y$12$QCjWyELsOP1mmNuf6D9OOOPqPK.Oqd8BGIqZQQ7v7InHp3VIDFxU.', 'mGlmyJbQPDfpMpJuxEJRxJDXsvz2hEzO6ohC7KJatUNjiHhNzED6LGsjfSk1', '2025-08-30 23:48:51', '2025-08-31 11:31:45', NULL),
(2, 'Ronaldo Segura Paniagua', 'rosepa2803@gmail.com', '+50685307943', NULL, NULL, 0, '2025-09-03 02:53:52', '$2y$12$sX3URoeWnPTmneEv3Nbbquh1rx5Qbfh3MFSG0dNKdFv3Wt/eZZOhO', 'a3aBLzFO8SwzgQqEn1SZ0SeJ0k2tV5MxHmtO5e3oEUxdhGTyiicJDupx9926', '2025-08-30 23:49:39', '2025-09-04 10:50:28', '106809972244431993920'),
(8, 'QR-Pet-Tag', 'info.qrpettag@gmail.com', NULL, NULL, NULL, 0, NULL, '$2y$12$Ad91MCowWp5k7FkznA.MAOtt2I65Qf99J8VPnFhGQiezoZqQj9O.6', 'GV7dx11tsx4W4yA0N6O5VExaeo1PMib4mjd3rn2yQ42UH9fhKgRNJPI2Oo3L', '2025-09-03 05:15:07', '2025-09-03 05:15:07', '116401846431375573214');

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
  ADD KEY `pets_user_id_foreign` (`user_id`),
  ADD KEY `pets_last_fb_post_hash_index` (`last_fb_post_hash`);

--
-- Indices de la tabla `pet_fb_posts`
--
ALTER TABLE `pet_fb_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_fb_posts_pet_id_foreign` (`pet_id`),
  ADD KEY `pet_fb_posts_fingerprint_index` (`fingerprint`);

--
-- Indices de la tabla `pet_photos`
--
ALTER TABLE `pet_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_photos_pet_id_sort_order_index` (`pet_id`,`sort_order`);

--
-- Indices de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_slug_unique` (`slug`),
  ADD UNIQUE KEY `qr_codes_pet_id_unique` (`pet_id`),
  ADD UNIQUE KEY `qr_codes_activation_code_unique` (`activation_code`),
  ADD UNIQUE KEY `qr_codes_code_unique` (`code`),
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
-- Indices de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_paypal_subscription_id_unique` (`paypal_subscription_id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_google_id_index` (`google_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `pets`
--
ALTER TABLE `pets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pet_fb_posts`
--
ALTER TABLE `pet_fb_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pet_photos`
--
ALTER TABLE `pet_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `scans`
--
ALTER TABLE `scans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pet_fb_posts`
--
ALTER TABLE `pet_fb_posts`
  ADD CONSTRAINT `pet_fb_posts_pet_id_foreign` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pet_photos`
--
ALTER TABLE `pet_photos`
  ADD CONSTRAINT `pet_photos_pet_id_foreign` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

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

--
-- Filtros para la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
