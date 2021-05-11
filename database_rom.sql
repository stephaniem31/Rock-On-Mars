
/*Suppression de la data base si elle existe */
DROP DATABASE IF EXISTS rockonmars;

/*Création de la base de donnée*/
CREATE DATABASE rockonmars;

/*Utilisation de la base de donnée*/
USE rockonmars;

/*Création des tables*/
CREATE TABLE `member`(
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(80) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`image` VARCHAR(255),
	`is_logged` BOOLEAN,
    `favorite_activity` VARCHAR(255) NOT NULL,
	`bio` TEXT NOT NULL,
	PRIMARY KEY(`id`)
	);

CREATE TABLE `activity`(
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`image` VARCHAR(255) NOT NULL,
	`likes` INT NOT NULL DEFAULT 0,
	`dislikes` INT NOT NULL DEFAULT 0,
    `localisation` VARCHAR(255),
    `start_at` DATETIME NOT NULL,
    `end_at` DATETIME NOT NULL,
    `activity_type` VARCHAR (255) NOT NULL,
    `content` VARCHAR(255) NOT NULL,
    `max_registered_members` INT NOT NULL,
    `member_id` INT NOT NULL,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`member_id`) REFERENCES `member`(`id`)
	);

CREATE TABLE `group`(
	`id` INT NOT NULL AUTO_INCREMENT,
	`activity_id` INT NOT NULL,	
	`member_id` INT NOT NULL,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`activity_id`) REFERENCES `activity`(`id`),
	FOREIGN KEY(`member_id`) REFERENCES `member`(`id`)
	);

CREATE TABLE `comment`(
	`id` INT NOT NULL AUTO_INCREMENT,
	`content` VARCHAR(255) NOT NULL,
	`member_id` INT NOT NULL,
	`activity_id` INT NOT NULL,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`member_id`) REFERENCES `member`(`id`),
	FOREIGN KEY(`activity_id`) REFERENCES `activity`(`id`)
);

