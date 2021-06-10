## Original Tables

```sql
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`age` int(11) NOT NULL,
`grade` int(11) NOT NULL,
`classTeacher` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`subjects` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
`name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`subjects` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`headOfGrade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`salary` int(11) NOT NULL,
PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Changes

### Teachers and Students

- They could be the same table with a person_type table
- Age could be made nullable
- That is a bit overkill for this though

### remove current primary key

```sql
ALTER TABLE `student` DROP INDEX `PRIMARY`;
ALTER TABLE `teachers` DROP INDEX `PRIMARY`;
```

- Primary Keys are usually numeric and should auto increment
- Names shouldn't be used as a primary key because they're not unique

### add new primary key named id

```sql
ALTER TABLE `student` ADD `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `teachers`ADD `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
```

- Now records can be added, and the id field can now automatically increment
- Most frameworks name the PK id as a convention
- Some devs name it student_id for example, but you're already in the context of the table, so no need to prefix the field names

### change data types for age and grade

```sql
ALTER TABLE `student`
CHANGE `age` `age` tinyint unsigned NOT NULL AFTER `name`,
CHANGE `grade` `grade` tinyint unsigned NOT NULL AFTER `age`;
```

- The smallest data type needed should be used when creating database fields
- Changed the fields to unsigned to also only allow positive values.

### create two name columns instead

```sql
ALTER TABLE `student`
CHANGE `name` `first_name` varchar(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `id`,
ADD `last_name` varchar(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `first_name`;

ALTER TABLE `teachers`
    CHANGE `name` `first_name` varchar(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL FIRST,
    ADD `last_name` varchar(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL AFTER `first_name`;
```

- You cannot always assume that the surname would be after the first space of the name
- To make it easier for sending out communication later on

### create separate a table for subjects

```sql
CREATE TABLE `subjects` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(150) NOT NULL
);

ALTER TABLE `subjects`
    ADD UNIQUE `subjects_name` (`name`);
```

- Rather link the subjects to a student as a one-to-many relationship 
- With the old table, you won't be able to update the name of a subject easily.
- With a separate table, you can update the subject in one place
- You can then leverage framework ORM conventions to create fluid scoped relationships as well.
- Make the name unique to avoid adding of duplicates


### rename tables

```sql
ALTER TABLE `student` RENAME TO `students`;
ALTER TABLE `teacher` RENAME TO `teachers`;
```

- Most modern open-source frameworks have a convention that table names need to be plural

### remove columns

```sql
ALTER TABLE `students`
DROP `classTeacher`,
DROP `subjects`;

ALTER TABLE `teachers`
DROP `subjects`;
```

- Remove columns that will become relations
- Students could also have multiple teachers and subjects per teacher

### change teacher is head of grade

```sql
ALTER TABLE `teachers`
CHANGE `headOfGrade` `is_grade_head` tinyint NOT NULL DEFAULT '0' AFTER `last_name`;
```

- This could be done two ways, as a boolean value above
- Or as an enum
- Boolean works better from a code perspective that you can use true or false
- It's also better to rather use snake_case if dealing with frameworks

### change teacher salary data type

```sql
ALTER TABLE `teachers`
CHANGE `salary` `salary` double(14,2) NOT NULL AFTER `is_grade_head`;
```

- currency fields should always allow decimals

### create table teacher_subjects

```sql
CREATE TABLE `teacher_subjects` (
  `teacher_id` bigint(20) NOT NULL,
  `subject_id` int(11) NOT NULL,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT
);

ALTER TABLE `teacher_subjects` ADD UNIQUE `teacher_id_subject_id` (`teacher_id`, `subject_id`);
```

- one-to-many relationship table
- linking teachers to subjects
- teachers shouldn't have duplicate subjects either

### create table student_teachers

```sql
CREATE TABLE `student_teachers` (
  `student_id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) NOT NULL,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`)
);

ALTER TABLE `student_teachers` ADD UNIQUE `student_id_teacher_id` (`student_id`, `teacher_id`);
```

- one-to-many relationship table
- linking students to teachers
- linking students to subjects via their teachers

### alternative solution

- create grades table
- link teachers to grades
- then student could be linked via a grade to teachers
