CREATE DATABASE IF NOT EXISTS `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `pwd_hash` VARCHAR(255) NOT NULL,
    `confirmed` BOOLEAN NOT NULL DEFAULT 0,
    `push_notif` BOOLEAN NOT NULL DEFAULT 1,
    `token` VARCHAR(64) UNIQUE NULL
);

INSERT INTO
    `users` (
        `id`,
        `username`,
        `email`,
        `pwd_hash`,
        `confirmed`
    )
VALUES
    (
        1,
        'Bob',
        'bob@gmail.com',
        '$2y$10$Wt2XXhvVfFyWSkupAL0OzOv3I9b9AZvPMUpoCo7FLosiAyzsD9FiW',
        1
    ),
    (
        2,
        'John',
        'jdoe@gmail.com',
        '$2y$10$Fza7OXvlIuDKsxNHtG/zuO7.BKlaZyRy.KVRpK0nA3wxhjav3LVHK',
        0
    );

CREATE TABLE IF NOT EXISTS `pics` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INT NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL,
    `filename` VARCHAR(255) NOT NULL
);

INSERT INTO
    `pics` (
        `id`,
        `user_id`,
        `created_at`,
        `filename`
    )
VALUES
    (
        1,
        1,
        '2009-01-10 18:38:02',
        '62b9dab68c48a'
    ),
    (
        2,
        1,
        '2009-01-10 19:38:02',
        '62b9db5fc8859'
    );

CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INT NOT NULL,
    `pic_id` INT NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL,
    `comment` VARCHAR(255) NOT NULL
);

INSERT INTO
    `comments` (
        `id`,
        `user_id`,
        `created_at`,
        `comment`
    )
VALUES
    (
        1,
        1,
        '2009-01-10 18:38:02',
        'Lorem ipsum'
    ),
    (
        2,
        1,
        '2009-01-10 19:38:02',
        'Foo bar'
    );
