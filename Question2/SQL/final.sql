-- Adminer 4.8.1 MySQL 5.7.24-log dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` tinyint(3) unsigned NOT NULL,
  `grade` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `student_teachers`;
CREATE TABLE `student_teachers` (
  `student_id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) NOT NULL,
  UNIQUE KEY `student_id_teacher_id` (`student_id`,`teacher_id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `student_teachers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  CONSTRAINT `student_teachers_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subjects_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subjects` (`id`, `name`) VALUES
(2,	'Afr'),
(6,	'Comp'),
(1,	'Eng'),
(7,	'Geo'),
(5,	'Hist'),
(3,	'Math'),
(4,	'Sci');

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_grade_head` tinyint(4) NOT NULL DEFAULT '0',
  `salary` double(14,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `teacher_subjects`;
CREATE TABLE `teacher_subjects` (
  `teacher_id` bigint(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  UNIQUE KEY `teacher_id_subject_id` (`teacher_id`,`subject_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `teacher_subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  CONSTRAINT `teacher_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2021-06-11 10:15:44
