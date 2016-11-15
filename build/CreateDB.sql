/*
    Tables       : table_name (always singular)
    Keys         : column_name
    Foreign keys : table_name_column_ame
*/

CREATE TABLE `user` (
    /* GENERAL INFO */
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(64) NOT NULL,
    `xp` INT NOT NULL DEFAULT 0,
    `create_date` DATETIME NOT NULL DEFAULT NOW(),

    /* FIRST-ORDER INFO */
    `fullname` VARCHAR(64),
    `gender` CHAR(1),

    /* SECOND-ORDER INFO */
    `country` VARCHAR(64),
    `city` VARCHAR(64),

    /* THIRD-ORDER INFO */
    `profession` VARCHAR(64),
    /* TODO: ADD ADDITIONAL INFO */

    PRIMARY KEY (`id`)
);


CREATE TABLE `poll` (
    `user_id` INT NOT NULL,
    `category_id` INT NOT NULL,

    `id` INT NOT NULL AUTO_INCREMENT,
    `question` VARCHAR(255) NOT NULL,
    `poll_type` VARCHAR(7) NOT NULL,              # local or global
    `option_type` VARCHAR(7) NOT NULL,              # multi-option or single-option
    `stat` VARCHAR(7) NOT NULL DEFAULT 'open', # open or closed
    `duration` INT NOT NULL DEFAULT 20,

    /* LOCATION */
    `latitude` FLOAT(10,6) NOT NULL,
    `longitude` FLOAT(10,6) NOT NULL,
    `diameter` INT NOT NULL,


    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(id),
    FOREIGN KEY (`category_id`) REFERENCES `category`(id)
);

CREATE TABLE `poll_option` (
    `poll_id` INT NOT NULL,

    `id` INT NOT NULL AUTO_INCREMENT,
    `content` VARCHAR(255) NOT NULL,
    `vote` INT NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`poll_id`) REFERENCES `poll`(`id`) ON DELETE CASCADE
);

CREATE TABLE `category` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `comment` (
    `user_id` INT NOT NULL,

    `id` INT NOT NULL AUTO_INCREMENT,
    `create_date` DATETIME NOT NULL DEFAULT NOW(),
    `content` VARCHAR(255) NOT NULL,
    `up_vote` INT NOT NULL DEFAULT 0,
    `down_vote` INT NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
);

CREATE TABLE `poll_comment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `comment_id` INT NOT NULL,
    `poll_id` INT NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`comment_id`) REFERENCES `comment`(`id`),
    FOREIGN KEY (`poll_id`) REFERENCES `poll`(`id`) ON DELETE CASCADE
);

CREATE TABLE `poll_option_comment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `poll_option_id` INT NOT NULL,
    `comment_id` INT NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`poll_option_id`) REFERENCES `poll_option`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`comment_id`) REFERENCES `comment`(`id`) ON DELETE CASCADE
);

CREATE TABLE `user_poll` (
    `user_id` INT NOT NULL,
    `poll_id` INT NOT NULL,

    `id` INT NOT NULL AUTO_INCREMENT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`poll_id`) REFERENCES `poll`(`id`) ON DELETE CASCADE
);

CREATE TABLE `user_poll_option` (
    `user_poll_id` INT NOT NULL,
    `poll_option_id` INT NOT NULL,

    FOREIGN KEY (`user_poll_id`) REFERENCES `user_poll`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`poll_option_id`) REFERENCES `poll_option`(`id`) ON DELETE CASCADE
);



/* TESTS */

/* users */
INSERT INTO `user` (`id`, `name`, `password`, `email`, `xp`, `create_date`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user1', 'user1passwd', 'user1mail', '0', CURRENT_TIMESTAMP, 'user1fullname', 'e', 'user1country', 'user1city', 'user1profession');
INSERT INTO `user` (`id`, `name`, `password`, `email`, `xp`, `create_date`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user2', 'user2passwd', 'user2mail', '0', CURRENT_TIMESTAMP, 'user2fullname', 'e', 'user2country', 'user2city', 'user2profession');
INSERT INTO `user` (`id`, `name`, `password`, `email`, `xp`, `create_date`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user3', 'user3passwd', 'user3mail', '0', CURRENT_TIMESTAMP, 'user3fullname', 'e', 'user3country', 'user3city', 'user3profession');
INSERT INTO `user` (`id`, `name`, `password`, `email`, `xp`, `create_date`, `fullname`, `gender`, `country`, `city`, `profession`) VALUES (NULL, 'user4', 'user4passwd', 'user4mail', '0', CURRENT_TIMESTAMP, 'user4fullname', 'e', 'user4country', 'user4city', 'user4profession');


/* polls */
INSERT INTO `poll` (`user_id`, `id`, `question`, `poll_type`, `option_type`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('1', 1, 'poll1question', 'local', 'single', 'open', '20', '53.293982', '-6.166417', '100');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option1', '15');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option2', '33');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('1', NULL, 'poll1option3', '323');

INSERT INTO `poll` (`user_id`, `id`, `question`, `poll_type`, `option_type`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('2', 2, 'poll2question', 'local', 'single', 'open', '20', '14.293982', '-22.166417', '130');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option1', '15');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option2', '33');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('2', NULL, 'poll2option3', '323');

INSERT INTO `poll` (`user_id`, `id`, `question`, `poll_type`, `option_type`, `stat`, `duration`, `latitude`, `longitude`, `diameter`) VALUES ('2', 3, 'poll3question', 'local', 'single', 'open', '20', '23.293982', '-4.166417', '100');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('3', NULL, 'poll1option1', '15');
INSERT INTO `poll_option` (`poll_id`, `id`, `content`, `vote`) VALUES ('3', NULL, 'poll1option2', '33');


INSERT INTO `comment` (`user_id`, `id`, `create_date`, `content`, `up_vote`, `down_vote`) VALUES ('1', '1', CURRENT_TIMESTAMP, 'comment1', '22', '12');
INSERT INTO `comment` (`user_id`, `id`, `create_date`, `content`, `up_vote`, `down_vote`) VALUES ('1', '2', CURRENT_TIMESTAMP, 'comment2', '22', '12');
INSERT INTO `comment` (`user_id`, `id`, `create_date`, `content`, `up_vote`, `down_vote`) VALUES ('1', '3', CURRENT_TIMESTAMP, 'comment3', '22', '12');
INSERT INTO `comment` (`user_id`, `id`, `create_date`, `content`, `up_vote`, `down_vote`) VALUES ('2', '4', CURRENT_TIMESTAMP, 'comment4', '22', '12');
INSERT INTO `comment` (`user_id`, `id`, `create_date`, `content`, `up_vote`, `down_vote`) VALUES ('2', '5', CURRENT_TIMESTAMP, 'comment5', '22', '12');


INSERT INTO `poll_comment` (`id`, `comment_id`, `poll_id`) VALUES ('1', '1', '1');
INSERT INTO `poll_comment` (`id`, `comment_id`, `poll_id`) VALUES ('2', '2', '2');
INSERT INTO `poll_comment` (`id`, `comment_id`, `poll_id`) VALUES ('3', '3', '2');


INSERT INTO `poll_option_comment` (`id`, `poll_option_id`, `comment_id`) VALUES ('2', '1', '4');
INSERT INTO `poll_option_comment` (`id`, `poll_option_id`, `comment_id`) VALUES ('3', '1', '5');
