CREATE DATABASE IF NOT EXISTS `camagru`;

USE `camagru`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `pwd_hash` VARCHAR(255) NOT NULL,
    `confirmed` BOOLEAN NOT NULL DEFAULT 0,
    `push_notif` BOOLEAN NOT NULL DEFAULT 1,
    `token` VARCHAR(64) UNIQUE NULL,
    `profile_pic` VARCHAR(255) NOT NULL DEFAULT ''
);

INSERT INTO
    `users` (
        `id`,
        `username`,
        `profile_pic`,
        `email`,
        `pwd_hash`,
        `confirmed`
    )
VALUES
    (
        1,
        'Bob',
        '',
        'camagru69@outlook.com',
        '$2y$10$Wt2XXhvVfFyWSkupAL0OzOv3I9b9AZvPMUpoCo7FLosiAyzsD9FiW',
        1
    ),
    (
        2,
        'Lynda',
        'https://robohash.org/44585be6e8575964e1823fab8af2d66d?set=set4&bgset=&size=200x200',
        'agrucam@hotmail.com',
        '$2y$10$Fza7OXvlIuDKsxNHtG/zuO7.BKlaZyRy.KVRpK0nA3wxhjav3LVHK',
        1
    );

CREATE TABLE IF NOT EXISTS `pics` (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `user_id` INT NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL,
    `likes` INT NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL
);

INSERT INTO
    `pics` (
        `user_id`,
        `created_at`,
        `filename`
    )
VALUES
    (
        1,
        '2009-01-10 18:38:02',
        '62b9dab68c48a'
    ),
    (
        1,
        '2009-01-10 19:38:02',
        '62b9db5fc8859'
    ),
    (
        2,
        '2020-03-07 09:03:50',
        '62c6a17616763'
    ),
    (
        2,
        '2021-03-07 19:38:02',
        '62c6a2838c647'
    ),
    (
        1,
        '2022-07-07 09:43:47',
        '62c6aad31ef8a'
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
        `pic_id`,
        `user_id`,
        `created_at`,
        `comment`
    )
VALUES
    (
        1,
        1,
        '2009-01-10 18:38:02',
        "Das my boi Alfred. Ain't it cute!"
    ),
    (
        1,
        2,
        '2009-01-10 18:40:02',
        'Nice doggy!'
    ),
    (
        1,
        1,
        '2009-01-10 19:45:02',
        'thanks gurl :-)'
    ),
    (
        2,
        2,
        '2009-01-11 19:38:02',
        'His name is Earl!'
    ),
    (
        2,
        1,
        '2009-01-11 19:40:02',
        'Meh, quite a shitty dog!'
    ),
    (
        2,
        2,
        '2009-01-11 19:41:02',
        'F*ck you John!'
    ),
    (
        3,
        2,
        '2020-03-07 09:03:50',
        'Riiiight...'
    ),
    (
        4,
        2,
        '2021-03-07 19:38:02',
        "When someone starts crying and you don't know what to do...
        - Do you want water?"
    ),
    (
        5,
        1,
        '2022-07-07 09:43:47',
        "Wanna see my Linux?"
    ),
    (
        5,
        2,
        '2022-07-07 09:53:47',
        "Sure, let's go!"
    );

CREATE TABLE IF NOT EXISTS `likes` (
	`pic_id` INT UNSIGNED NOT NULL,
	`user_id` INT UNSIGNED NOT NULL
);

INSERT INTO
    `likes` (
        `pic_id`,
        `user_id`
    )
VALUES
    (
        1,
        2
    ),
    (
        1,
        1
    );
