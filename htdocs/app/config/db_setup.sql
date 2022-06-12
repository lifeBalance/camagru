CREATE DATABASE IF NOT EXISTS `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username`    VARCHAR(50) NOT NULL,
    `email`       VARCHAR(255) UNIQUE NOT NULL,
    `pwd_hash`    VARCHAR(255) NOT NULL
);

-- INSERT INTO `users` (`id`, `username`, `email`, `pwd_hash`)
--             VALUES  ('1', 'Bob', 'bob@gmail.com', '$2y$10$/iW4SWEKZapxI0t71uOruOUIGFw5yRiJFGUYWvxLq1.egdFkOoyaW'),
--                     ('2', 'John', 'jdoe@gmail.com', '$2y$10$cCLLuVSIw7QwaPA439UYU.4c7..EnUlNXKOR0gQivKpH05qBeMhaq');