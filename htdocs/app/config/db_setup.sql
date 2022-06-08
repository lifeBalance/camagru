CREATE DATABASE `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`          INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username`    VARCHAR(255) NOT NULL,
    `email`       VARCHAR(255) NOT NULL,
    `password`    VARCHAR(255) NOT NULL
);