CREATE DATABASE IF NOT EXISTS `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username`    VARCHAR(50) NOT NULL,
    `email`       VARCHAR(255) UNIQUE NOT NULL,
    `pwd_hash`    VARCHAR(255) NOT NULL,
    `confirmed`   BOOLEAN NOT NULL DEFAULT 0,
    `push_notif`  BOOLEAN NOT NULL DEFAULT 1
);

INSERT INTO `users` (`id`, `username`, `email`, `pwd_hash`, `confirmed`)
            VALUES  ('1', 'Bob', 'bob@gmail.com', '$2y$10$Wt2XXhvVfFyWSkupAL0OzOv3I9b9AZvPMUpoCo7FLosiAyzsD9FiW', 1),
                    ('2', 'John', 'jdoe@gmail.com', '$2y$10$Fza7OXvlIuDKsxNHtG/zuO7.BKlaZyRy.KVRpK0nA3wxhjav3LVHK', 0);