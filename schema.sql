-- Games table
CREATE TABLE `games`(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Location table
CREATE TABLE `locations`(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Matches table
CREATE TABLE `matches`(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `game_id` INT(11) UNSIGNED NOT NULL,
    `location_id` INT(11) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `game_id` (`game_id`),
    INDEX `location_id` (`location_id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Players table
CREATE TABLE `players`(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `forename` VARCHAR(255) NOT NULL,
    `surname` VARCHAR(255) NOT NULL,
    `contact_no` VARCHAR(20),
    `email` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Results table
CREATE TABLE `results`(
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `match_id` INT(11) UNSIGNED NOT NULL,
    `player_id` INT(11) UNSIGNED NOT NULL,
    `opponent_id` INT(11) UNSIGNED NOT NULL,
    `player_score` INT(11) NOT NULL DEFAULT 0,
    `opponent_score` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    INDEX `match_id` (`match_id`),
    INDEX `player_id` (`player_id`),
    INDEX `opponent_id` (`opponent_id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Foreign keys
ALTER TABLE `matches`
    ADD FOREIGN KEY (`game_id`) REFERENCES `games`(`id`),
    ADD FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`);

ALTER TABLE `results`
    ADD FOREIGN KEY (`match_id`) REFERENCES `matches`(`id`),
    ADD FOREIGN KEY (`player_id`) REFERENCES `players`(`id`),
    ADD FOREIGN KEY (`opponent_id`) REFERENCES `players`(`id`);
