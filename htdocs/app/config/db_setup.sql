CREATE DATABASE `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`          INT(11) UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username`    VARCHAR(255) NOT NULL,
    `email`       VARCHAR(255) NOT NULL,
    `password`    VARCHAR(255) NOT NULL
);

INSERT INTO `users` (`id`, `username`, `email`, `password`)
            VALUES  ('1', 'Bob', 'bob@gmail.com', '1234'),
                    ('2', 'John', 'jdoe@gmail.com', '1234');