-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 12, 2024 at 08:02 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_petfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

DROP TABLE IF EXISTS `animals`;
CREATE TABLE IF NOT EXISTS `animals` (
  `gbif_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('wild','tame/domestic','exotic') COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `animals_gbif_id_unique` (`gbif_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`gbif_id`, `category`) VALUES
('102084770', 'wild'),
('102094770', 'wild'),
('102095770', 'wild'),
('123218197', 'wild');

-- --------------------------------------------------------

--
-- Table structure for table `animal_pictures`
--

DROP TABLE IF EXISTS `animal_pictures`;
CREATE TABLE IF NOT EXISTS `animal_pictures` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `species_id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animal_pictures`
--

INSERT INTO `animal_pictures` (`id`, `species_id`, `filename`, `source`, `created_at`, `updated_at`) VALUES
(1, 0, 'frog.png', NULL, NULL, NULL),
(2, 0, 'toad.png', NULL, NULL, NULL),
(3, 0, 'salamander.png', NULL, NULL, NULL),
(4, 0, 'caecilian.png', NULL, NULL, NULL),
(5, 0, 'newt.png', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `common`
--

DROP TABLE IF EXISTS `common`;
CREATE TABLE IF NOT EXISTS `common` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gbif_classification_id` int NOT NULL,
  `gbif_key` int NOT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `on_display` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `common`
--

INSERT INTO `common` (`id`, `gbif_classification_id`, `gbif_key`, `class`, `slug`, `emoji`, `hex_color`, `description`, `on_display`, `created_at`, `updated_at`) VALUES
(1, 100206692, 0, 'Aves', 'aves', '🦜', '#55c0be', 'Common species: Parrots, Canaries, Finches, Budgerigars, Cockatiels.\nSome eat seeds, berries, fruit, insects, other birds, eggs, small mammals, fish, buds, larvae, aquatic invertebrates, acorns and other nuts, aquatic vegetation, grain, dead animals, garbage, and much more… During the spring and summer months, most songbirds eat mainly insects and spiders.', 1, NULL, NULL),
(2, 220525669, 0, 'Amphibians', 'amphibia', '🐸', '#7a8ada', 'Common species: Frogs, Toads, Salamanders.\nMost adult terrestrial and aquatic amphibians feed on invertebrates (animals that do not have backbones), including earthworms, bloodworms, black worms, white worms, tubifex worms, springtails, fruit flies, fly larvae, mealworms, and crickets.', 1, NULL, NULL),
(3, 0, 0, 'Livestock', 'livestock', '🐷', '#c32070', 'Often kept on farms. Most common species: Cows, Pigs, Sheep, Goats, Ducks, Chickens.', 1, NULL, NULL),
(4, 0, 0, 'Cats', 'cats', '😺', '#c05555', 'Whether you\'re feeding a cat or a kitten, “Cats need animal-based protein as part of their main diet,” explains Purina Nutritionist Karina Carbo-Johnson, MS. Some protein-rich foods cats like to eat include: A variety of fish, such as salmon, trout, tuna and whitefish. Poultry like chicken, turkey and pheasant.', 1, NULL, NULL),
(5, 0, 0, 'Dogs', 'dogs', '🐶', '#FFFF00', 'Domesticated dogs are largely carnivores but will also eat plant-based foods. Wild dogs eat a variety of food which comprise mainly of prey animals, consisting of raw meat, bones, organs and a small amount of the vegetable matter contained in the gut of their prey.', 1, NULL, NULL),
(6, 0, 0, 'Rodents', 'rodents', '🐀', '#e4d448', 'Common species:  Mice, Rats, Guinea Pigs, Hamsters, Gerbils.\nMost rodents tend to prefer eating plant foods like seeds, grains, and small fruit. However, mice and rats are omnivores, which means that their diet can consist of animal products, too.', 1, NULL, NULL),
(7, 0, 0, 'Reptiles', 'reptilia', '🐢', '#c0c055', 'Common species: Snakes, Turtles, Lizards, Geckos, Bearded Dragons. With few exceptions, modern reptiles feed on some form of animal life (such as insects, mollusks, birds, frogs, mammals, fishes, or even other reptiles). Land tortoises are vegetarians, eating leaves, grass, and even cactus in some cases.', 1, NULL, NULL),
(8, 0, 0, 'Hedgehogs', 'hedgehogs', '🦔', '#c05555', 'The most important invertebrates in their diet are worms, beetles, slugs, caterpillars, earwigs and millipedes. As well as these, they also eat a wide range of other insects. More infrequently, they will take advantage of carrion, frogs, baby rodents, baby birds, birds\' eggs and fallen fruit.', 1, NULL, NULL),
(9, 0, 0, 'Chinchillas', 'chinchillas', '🐹', '#848484', 'Chinchillas\' digestive systems need hay and grass to function properly, and in the wild, they naturally eat grasses, leaves and twigs.', 1, NULL, NULL),
(10, 0, 0, 'Fish', 'fish', '🐟', '#8c55c0', 'Common species: Goldfish, Betta Fish, Guppies, Tetras, Cichlids.\nFish generally eat other fish but their diet can also consist of eggs, algae, plants, crustaceans, worms, mollusks, insects, insect larvae, amphibians, and plankton. River fish are opportunistic feeders and their diet can vary depending on what is available in their environment.', 1, NULL, NULL),
(11, 0, 0, 'Rabbits', 'rabbits', '🐰', '#69c055', 'Fresh, clean drinking water and good quality hay and grass should make up the majority of your rabbits\' diet. A rabbit\'s digestive system needs hay or grass to function properly so a healthy supply is extremely important. You can supplement with leafy greens and a small amount of pellets.', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `common_pets`
--

DROP TABLE IF EXISTS `common_pets`;
CREATE TABLE IF NOT EXISTS `common_pets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `common_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `img_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `common_pets`
--

INSERT INTO `common_pets` (`id`, `common_id`, `name`, `img_id`) VALUES
(1, 2, 'Frog', '1'),
(2, 2, 'Toad', '2'),
(3, 2, 'Salamander', '3'),
(4, 2, 'Newt', '5'),
(5, 2, 'Caelician', '4');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

DROP TABLE IF EXISTS `foods`;
CREATE TABLE IF NOT EXISTS `foods` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `food` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_safety`
--

DROP TABLE IF EXISTS `food_safety`;
CREATE TABLE IF NOT EXISTS `food_safety` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gbif_classification`
--

DROP TABLE IF EXISTS `gbif_classification`;
CREATE TABLE IF NOT EXISTS `gbif_classification` (
  `gbif_id` int NOT NULL,
  `language` varchar(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tier` enum('GENUS','FAMILY','ORDER','CLASS','SUPERCLASS','INFRAPHYLUM','SUBPHYLUM','PHYLUM','INFRAKINGDOM','SUBKINGDOM','KINGDOM') NOT NULL,
  PRIMARY KEY (`gbif_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gbif_classification`
--

INSERT INTO `gbif_classification` (`gbif_id`, `language`, `name`, `tier`) VALUES
(102074563, 'en', 'Amphibians', 'CLASS'),
(100206692, 'en', 'Aves', 'CLASS');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_12_19_144232_create_roles_table', 1),
(7, '2023_12_19_144519_create_role_users_table', 1),
(8, '2023_12_19_150339_create_foods_table', 1),
(9, '2023_12_19_152857_create_classes_table', 1),
(10, '2023_12_19_160908_create_searches_table', 1),
(11, '2023_12_19_161748_create_food_safety_table', 1),
(12, '2023_12_19_162625_create_animal_pictures_table', 1),
(13, '2024_04_14_184941_create_animals_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL),
(2, 'Expert', NULL, NULL),
(3, 'Pet Owner', NULL, NULL),
(4, 'Auditor', NULL, NULL),
(5, 'Content Creator', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

DROP TABLE IF EXISTS `searches`;
CREATE TABLE IF NOT EXISTS `searches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `search_term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` tinyint(1) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `google_id`, `facebook_id`, `created_at`, `updated_at`, `filename`, `display_name`, `username`) VALUES
(3, 'Kārlis Ivars Braķis', 'arlisbrais@gmail.com', NULL, '$2y$12$k4kYv75HgX6E0vIQDyjEoumX0t4AzmffppgmbQhRnLU1sUraJm.ea', NULL, NULL, NULL, '2024-03-03 13:36:32', '2024-03-03 13:36:32', NULL, NULL, NULL),
(13, 'aaa', 'aaa@gmail.com', NULL, '$2y$12$k.NXEfhZGgYnMUa5hZGpcuBaoDGfDwAn9sAsKVgowIEp2zmtWT8mK', NULL, NULL, NULL, '2024-03-04 13:55:10', '2024-03-04 13:55:10', NULL, NULL, NULL),
(14, 'fff', 'fff@gmail.com', NULL, '$2y$12$wm33QLBO5d..NhbOhCKRsukLBNSjj9jMPsE7xl8Ceiuy9Ep1kPTRK', NULL, NULL, NULL, '2024-03-04 15:24:35', '2024-03-04 15:24:35', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
