-- Adminer 4.8.1 MySQL 5.7.24-log dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `prefix_map`;
CREATE TABLE `prefix_map` (
                              `prefix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `destination` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                              UNIQUE KEY `prefix_destination_unique` (`prefix`,`destination`),
                              KEY `prefix_map_prefix` (`prefix`),
                              FULLTEXT KEY `prefix_map_fulltext` (`prefix`,`destination`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-06-11 09:50:39