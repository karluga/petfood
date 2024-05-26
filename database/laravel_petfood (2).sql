-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 26, 2024 at 09:29 PM
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
  `id` int NOT NULL AUTO_INCREMENT,
  `gbif_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `language` varchar(2) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `single` varchar(255) DEFAULT NULL,
  `category` enum('wild','domestic','exotic') DEFAULT NULL,
  `rank` enum('SUBSPECIES','SPECIES','GENUS','FAMILY','ORDER','CLASS','SUPERCLASS','INFRAPHYLUM','SUBPHYLUM','PHYLUM','INFRAKINGDOM','SUBKINGDOM','KINGDOM') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cover_image_id` int DEFAULT NULL,
  `appearance` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `food` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `gbif_id`, `parent_id`, `language`, `slug`, `name`, `single`, `category`, `rank`, `cover_image_id`, `appearance`, `food`) VALUES
(1, 135226770, NULL, 'en', 'amphibians', 'Amphibians', 'Amphibian', NULL, 'CLASS', NULL, 'Moist, permeable skin; typically undergo metamorphosis (e.g., tadpoles to frogs)	', 'Insects, worms, small invertebrates'),
(2, 100206692, NULL, 'en', 'birds', 'Aves|Birds', 'Ave|Bird', NULL, 'CLASS', NULL, 'Feathers, beaks, wings (for most species), lightweight skeletons	', 'Seeds, fruits, insects, fish, small mammals'),
(3, 115058156, NULL, 'en', 'reptiles', 'Reptiles', 'Reptile', NULL, 'ORDER', NULL, 'Scaled skin, cold-blooded, lay eggs (except some snakes and lizards)	', 'Insects, fish, small mammals, plants'),
(4, 104049909, NULL, 'en', 'rodents', 'Rodents', 'Rodent', NULL, 'ORDER', NULL, 'Small mammals with continuously growing incisors	', 'Seeds, nuts, fruits, vegetation'),
(5, 201325994, NULL, 'en', 'hedgehogs', 'Hedgehogs', 'Hedgehog', NULL, 'FAMILY', NULL, 'Spiny coat, nocturnal, insectivorous	', 'Insects, worms, small vertebrates'),
(6, 201360602, NULL, 'en', 'chinchillas', 'Chinchillas', 'Chinchilla', NULL, 'FAMILY', NULL, 'Soft fur, large ears, herbivorous	', 'Hay, pellets, fresh vegetables'),
(7, 135226796, 135226783, 'en', 'frogs', 'Frogs|Ranidae', 'Frog|Ranidae', NULL, 'FAMILY', 1, 'Moist skin, long hind legs, powerful jumpers	', 'Insects, spiders, small vertebrates'),
(8, 135226770, NULL, 'lv', 'abinieki', 'Abinieki', 'Abinieks', NULL, 'CLASS', NULL, 'Mitra, caurlaidīga āda; parasti piedzīvo metamorfozi (piemēram, kurkuļi pārvēršas par vardēm)	', 'Kukaiņi, tārpi, mazi bezmugurkaulnieki'),
(9, 100206692, NULL, 'lv', 'putni', 'Putni', 'Putns', NULL, 'CLASS', NULL, 'Spalvas, knābis, spārni (lielākajai daļai sugas), viegls skelets	', 'Sēklas, augļi, kukaiņi, zivis, mazi zīdītāji\r\n'),
(10, 115058156, NULL, 'lv', 'reptiļi', 'Reptiļi	', 'Reptilis', NULL, 'ORDER', NULL, 'Čaumalota āda, aukstasinīgi, dēj olas (izņemot dažas čūskas un ķirzakas)', 'Kukaiņi, zivis, mazi zīdītāji, augi\r\n'),
(11, 104049909, NULL, 'lv', 'grauzēji', 'Grauzēji', 'Grauzējs', NULL, 'ORDER', NULL, 'Mazie zīdītāji ar nepārtraukti augošiem priekšējiem zobiem', 'Sēklas, rieksti, augļi, dārzeņi\r\n'),
(12, 201325994, NULL, 'lv', 'eži', 'Eži', 'Ezis', NULL, 'FAMILY', NULL, 'Adatains kažoks, nakts aktīvi.	', 'Kukaiņi, tārpi, mazie mugurkaulnieki '),
(13, 201360602, NULL, 'lv', 'šinšilas', 'Šinšilas', 'Šinšila', NULL, 'FAMILY', NULL, 'Mīksta kažokāda, lielas auss, zālēdājs	', 'Siena, granulas, svaigi dārzeņi\r\n'),
(14, 135226796, 135226783, 'lv', 'vardes', 'Vardes|Ranidae', 'Varde|Ranidae', NULL, 'FAMILY', 1, 'Mitrā āda, garas aizmugurējās kājas, spēcīgi lēcēji', 'Kukaiņi, zirnekļi, mazi mugurkaulnieki'),
(15, 194431376, NULL, 'en', 'fish', 'Fish', 'Fish', NULL, 'INFRAPHYLUM', NULL, 'Fish come in various shapes and sizes. They typically have streamlined bodies, scales covering their skin, fins for swimming, and gills for breathing underwater.	', 'Depending on the species, fish are omnivores, carnivores or herbivores. Their diet can include algae, plankton, smaller fish, insects and even small mammals or birds.\n'),
(16, 194431376, NULL, 'lv', 'zivis', 'Zivis', 'Zivs', NULL, 'INFRAPHYLUM', NULL, 'Zivis ir sastopamas dažādās formās un izmēros. Parasti tām ir plūsotu ķermeņu, ādas klātu vairogi, zvīņas peldēšanai un skābekļa uzņemšanai ūdenī.	', 'Atkarībā no sugas zivis ir visēdājas, gaļēdājas vai zālēdājas. Viņu uzturā var būt aļģes, planktons, mazākas zivis, kukaiņi un pat mazi zīdītāji vai putni.\n\n\n\n'),
(17, 201387639, NULL, 'en', 'cats', 'Cats|Felines', 'Cat|Feline', NULL, 'FAMILY', NULL, 'Cats are small to medium-sized carnivorous mammals. They have a sleek and agile body, sharp retractable claws, keen senses, including excellent night vision, and a short muzzle with whiskers.', 'Cats are obligate carnivores, meaning they primarily eat meat. Their diet consists of various meats, including poultry, beef, fish, and sometimes small mammals such as mice or rats.'),
(18, 201387639, NULL, 'lv', 'kaķi', 'Kaķi', 'Kaķis', NULL, 'FAMILY', NULL, 'Kaķi ir mazi līdz vidēji lieli karnivorie zīdītāji. Tiem ir gluda un izturīga ķermeņa forma, asas retraktējamas nagas, izteiktas sajūtas, tostarp lieliska nakts redze, un īss mute ar ūsām.	', 'Kaķi ir obligāti gaļēdāji, kas nozīmē, ka viņi galvenokārt ēd gaļu. Viņu uzturs sastāv no dažādas gaļas, tostarp mājputnu gaļas, liellopu gaļas, zivīm un dažreiz maziem zīdītājiem, piemēram, pelēm vai žurkām.'),
(19, 201360574, NULL, 'en', 'dogs', 'Dogs|Canines', 'Dog|Canine', NULL, 'FAMILY', NULL, 'Dogs vary widely in size, shape, and color, depending on the breed. They have a muscular body, a well-developed sense of smell, sharp teeth for tearing and chewing, and a tail used for communication.', 'Dogs are omnivorous, but they are primarily carnivores. Their diet includes meat, such as beef, poultry, and fish, as well as vegetables, grains, and fruits.'),
(20, 201360574, NULL, 'lv', 'suņi', 'Suņi', 'Suns', NULL, 'FAMILY', NULL, 'Atkarībā no šķirnes suņi ir ļoti atšķirīgi pēc izmēra, formas un krāsas. Viņiem ir muskuļots ķermenis, labi attīstīta oža, asi zobi plēsšanai un košļāšanai, kā arī saziņai izmantota aste.', 'Suņi ir visēdāji, taču tie galvenokārt ir plēsēji. Viņu uzturā ietilpst gaļa, piemēram, liellopu gaļa, mājputni un zivis, kā arī dārzeņi, graudi un augļi.'),
(21, 199467270, NULL, 'en', 'rabbits', 'Rabbits', 'Rabbit', NULL, 'FAMILY', 10, 'Rabbits are small mammals with long ears, short fluffy tails, and strong, hind legs built for hopping. They typically have soft fur that can be various colors, and their front teeth never stop growing.', 'Rabbits are herbivores and primarily eat grasses, hay, and leafy greens. They also enjoy vegetables like carrots, broccoli, and spinach, as well as fruits like apples and berries.'),
(22, 199467270, NULL, 'lv', 'zaķi', 'Truši|Zaķi', 'Trusis|Zaķis', NULL, 'FAMILY', 10, 'Truši ir mazi zīdītāji ar garām ausīm, īsām pūkainām astēm un spēcīgām pakaļkājām, kas veidotas lēcienam. Viņiem parasti ir mīksta kažokāda, kas var būt dažādās krāsās, un viņu priekšējie zobi nekad nepārstāj augt.', 'Truši ir zālēdāji un galvenokārt ēd zāli, sienu un lapu zaļumus. Viņiem patīk arī dārzeņi, piemēram, burkāni, brokoļi un spināti, kā arī augļi, piemēram, āboli un ogas.'),
(23, 180179734, NULL, 'en', 'bovidae', 'Bovidae', 'Bovidae', NULL, 'FAMILY', NULL, NULL, NULL),
(24, 209386215, NULL, 'en', 'pigs', 'Pigs|Swine', 'Pig|Swine', 'domestic', 'FAMILY', 11, NULL, NULL),
(25, 196669998, NULL, 'en', 'ovis', 'Ovis', 'Ovis', NULL, 'GENUS', NULL, NULL, NULL),
(26, 177669418, NULL, 'en', 'sheep', 'Sheep|Ovis aries', 'Sheep|Ovis aries', 'domestic', 'SPECIES', 8, NULL, NULL),
(27, 180179873, NULL, 'en', 'cattle', 'Cattle|Bos', 'Cattle|Bos', 'domestic', 'GENUS', 9, NULL, NULL),
(28, 116893048, NULL, 'en', 'goats', 'Goats|Capra hircus', 'Goats|Capra hircus', 'domestic', 'SPECIES', 6, NULL, NULL),
(29, 160796498, NULL, 'en', 'ducks', 'Ducks|Anas platyrhynchos', 'Duck|Anas platyrhynchos', 'domestic', 'SPECIES', 7, NULL, NULL),
(30, 217145891, NULL, 'en', 'chickens', 'Chickens|Gallus gallus', 'Chicken|Gallus gallus', 'domestic', 'SPECIES', 13, NULL, NULL),
(31, 217127894, NULL, 'en', 'horses', 'Horses|Equus ferus caballus', 'Horse|Equus ferus caballus', 'domestic', 'SUBSPECIES', 12, NULL, NULL),
(32, 160796498, NULL, 'lv', 'pīles', 'Pīles|Anas platyrhynchos', 'Pīle|Anas platyrhynchos', 'domestic', 'SPECIES', 7, NULL, NULL),
(33, 116893048, NULL, 'lv', 'kazas', 'Kazas|Capra hircus', 'Kaza|Capra hircus', 'domestic', 'SPECIES', 6, NULL, NULL),
(34, 180179873, NULL, 'lv', 'govis', 'Govis|Bos', 'Govs|Bos', 'domestic', 'GENUS', 9, NULL, NULL),
(35, 217127894, NULL, 'lv', 'zirgi', 'Zirgi|Equus ferus caballus', 'Zirgs|Equus ferus caballus', 'domestic', 'SUBSPECIES', 12, NULL, NULL),
(36, 196669998, NULL, 'lv', 'avis', 'Avis', 'Avs', NULL, 'GENUS', NULL, NULL, NULL),
(37, 217145891, NULL, 'lv', 'vistas', 'Vistas|Gallus gallus', 'Vista|Gallus gallus', 'domestic', 'SPECIES', 13, NULL, NULL),
(38, 177669418, NULL, 'lv', 'aitas', 'Aitas|Ovis aries', 'Aita|Ovis aries', 'domestic', 'SPECIES', 8, NULL, NULL),
(39, 209386215, NULL, 'lv', 'cūkas', 'Cūkas', 'Cūka', 'domestic', 'FAMILY', 11, NULL, NULL),
(41, 135226783, 135226770, 'en', 'anura', 'Anura', 'Anura', NULL, 'ORDER', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `animal_pictures`
--

DROP TABLE IF EXISTS `animal_pictures`;
CREATE TABLE IF NOT EXISTS `animal_pictures` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `gbif_id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `animal_pictures`
--

INSERT INTO `animal_pictures` (`id`, `gbif_id`, `filename`, `source`, `created_at`, `updated_at`) VALUES
(1, 135226796, 'frog.png', NULL, NULL, NULL),
(2, 0, 'toad.png', NULL, NULL, NULL),
(3, 0, 'salamander.png', NULL, NULL, NULL),
(4, 0, 'caecilian.png', NULL, NULL, NULL),
(5, 0, 'newt.png', NULL, NULL, NULL),
(6, 116893048, 'goat.jpg', NULL, NULL, NULL),
(7, 160796498, 'duckjpg.jpg', NULL, NULL, NULL),
(8, 177669418, 'sheep.png', NULL, NULL, NULL),
(9, 180179873, 'cow.png', NULL, NULL, NULL),
(10, 199467270, 'rabbits.jpg', NULL, NULL, NULL),
(11, 209386215, 'pig.jpg', NULL, NULL, NULL),
(12, 217127894, 'horse.png', NULL, NULL, NULL),
(13, 217145891, 'chickens.png', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `common`
--

DROP TABLE IF EXISTS `common`;
CREATE TABLE IF NOT EXISTS `common` (
  `gbif_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `emoji` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_color` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `on_display` tinyint DEFAULT NULL,
  PRIMARY KEY (`gbif_id`)
) ENGINE=MyISAM AUTO_INCREMENT=201387640 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `common`
--

INSERT INTO `common` (`gbif_id`, `emoji`, `hex_color`, `on_display`) VALUES
(100206692, '🦜', '#55c0be', 1),
(135226770, '🐸', '#7a8ada', 1),
(3, '🐷', '#c32070', 1),
(201387639, '😺', '#c05555', 1),
(201360574, '🐶', '#ffec00', 1),
(104049909, '🐀', '#e4d448', 1),
(115058156, '🐢', '#c0c055', 1),
(201325994, '🦔', '#c05555', 1),
(201360602, '🐹', '#848484', 1),
(194431376, '🐟', '#8c55c0', 1),
(199467270, '🐰', '#69c055', 1);

-- --------------------------------------------------------

--
-- Table structure for table `common_old`
--

DROP TABLE IF EXISTS `common_old`;
CREATE TABLE IF NOT EXISTS `common_old` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
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
-- Dumping data for table `common_old`
--

INSERT INTO `common_old` (`id`, `class`, `slug`, `emoji`, `hex_color`, `description`, `on_display`, `created_at`, `updated_at`) VALUES
(1, 'Aves', 'aves', '🦜', '#55c0be', 'Common species: Parrots, Canaries, Finches, Budgerigars, Cockatiels.\nSome eat seeds, berries, fruit, insects, other birds, eggs, small mammals, fish, buds, larvae, aquatic invertebrates, acorns and other nuts, aquatic vegetation, grain, dead animals, garbage, and much more… During the spring and summer months, most songbirds eat mainly insects and spiders.', 1, NULL, NULL),
(2, 'Amphibians', 'amphibia', '🐸', '#7a8ada', 'Common species: Frogs, Toads, Salamanders.\nMost adult terrestrial and aquatic amphibians feed on invertebrates (animals that do not have backbones), including earthworms, bloodworms, black worms, white worms, tubifex worms, springtails, fruit flies, fly larvae, mealworms, and crickets.', 1, NULL, NULL),
(3, 'Livestock', 'livestock', '🐷', '#c32070', 'Often kept on farms. Most common species: Cows, Pigs, Sheep, Goats, Ducks, Chickens.', 1, NULL, NULL),
(4, 'Cats', 'cats', '😺', '#c05555', 'Whether you\'re feeding a cat or a kitten, “Cats need animal-based protein as part of their main diet,” explains Purina Nutritionist Karina Carbo-Johnson, MS. Some protein-rich foods cats like to eat include: A variety of fish, such as salmon, trout, tuna and whitefish. Poultry like chicken, turkey and pheasant.', 1, NULL, NULL),
(5, 'Dogs', 'dogs', '🐶', '#FFFF00', 'Domesticated dogs are largely carnivores but will also eat plant-based foods. Wild dogs eat a variety of food which comprise mainly of prey animals, consisting of raw meat, bones, organs and a small amount of the vegetable matter contained in the gut of their prey.', 1, NULL, NULL),
(6, 'Rodents', 'rodents', '🐀', '#e4d448', 'Common species:  Mice, Rats, Guinea Pigs, Hamsters, Gerbils.\nMost rodents tend to prefer eating plant foods like seeds, grains, and small fruit. However, mice and rats are omnivores, which means that their diet can consist of animal products, too.', 1, NULL, NULL),
(7, 'Reptiles', 'reptilia', '🐢', '#c0c055', 'Common species: Snakes, Turtles, Lizards, Geckos, Bearded Dragons. With few exceptions, modern reptiles feed on some form of animal life (such as insects, mollusks, birds, frogs, mammals, fishes, or even other reptiles). Land tortoises are vegetarians, eating leaves, grass, and even cactus in some cases.', 1, NULL, NULL),
(8, 'Hedgehogs', 'hedgehogs', '🦔', '#c05555', 'The most important invertebrates in their diet are worms, beetles, slugs, caterpillars, earwigs and millipedes. As well as these, they also eat a wide range of other insects. More infrequently, they will take advantage of carrion, frogs, baby rodents, baby birds, birds\' eggs and fallen fruit.', 1, NULL, NULL),
(9, 'Chinchillas', 'chinchillas', '🐹', '#848484', 'Chinchillas\' digestive systems need hay and grass to function properly, and in the wild, they naturally eat grasses, leaves and twigs.', 1, NULL, NULL),
(10, 'Fish', 'fish', '🐟', '#8c55c0', 'Common species: Goldfish, Betta Fish, Guppies, Tetras, Cichlids.\nFish generally eat other fish but their diet can also consist of eggs, algae, plants, crustaceans, worms, mollusks, insects, insect larvae, amphibians, and plankton. River fish are opportunistic feeders and their diet can vary depending on what is available in their environment.', 1, NULL, NULL),
(11, 'Rabbits', 'rabbits', '🐰', '#69c055', 'Fresh, clean drinking water and good quality hay and grass should make up the majority of your rabbits\' diet. A rabbit\'s digestive system needs hay or grass to function properly so a healthy supply is extremely important. You can supplement with leafy greens and a small amount of pellets.', 1, NULL, NULL);

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
  `preferred_language` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `google_id`, `facebook_id`, `created_at`, `updated_at`, `filename`, `display_name`, `username`, `preferred_language`) VALUES
(3, 'Kārlis Ivars Braķis', 'arlisbrais@gmail.com', NULL, '$2y$12$ULkBZF/SE5DB..EnZlUAZ.UK8.8k7p4fAxNeo/ERH0wfbx2.7rtVS', NULL, NULL, NULL, '2024-03-03 13:36:32', '2024-05-18 10:13:07', NULL, NULL, NULL, 'en'),
(13, 'aaa', 'aaa@gmail.com', NULL, '$2y$12$k.NXEfhZGgYnMUa5hZGpcuBaoDGfDwAn9sAsKVgowIEp2zmtWT8mK', NULL, NULL, NULL, '2024-03-04 13:55:10', '2024-03-04 13:55:10', NULL, NULL, NULL, NULL),
(14, 'fff', 'fff@gmail.com', NULL, '$2y$12$wm33QLBO5d..NhbOhCKRsukLBNSjj9jMPsE7xl8Ceiuy9Ep1kPTRK', NULL, NULL, NULL, '2024-03-04 15:24:35', '2024-03-04 15:24:35', NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
