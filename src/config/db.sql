/*
 * User managemant
 */
CREATE TABLE `users`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `username` varchar(65) NOT NULL default '',
    `fullname` varchar(65) NOT NULL default '',
    `password` varchar(255) NOT NULL default '',
    PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `login_history`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `user` int(4) UNSIGNED NOT NULL default 1,
    `date` timestamp NOT NULL default current_timestamp,
    `ip` varchar(45) NOT NULL default '0.0.0.0',
    `auth_token` varchar(65) NOT NULL default '',
    `user_agent` varchar(500) NOT NULL default '',
    `success` tinyint(1) NOT NULL default 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES users(`id`) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `login_remember`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `user` int(4) UNSIGNED NOT NULL default 0,
    `remember_token` varchar(65) NOT NULL default '',
    `until` timestamp NOT NULL default current_timestamp,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user`) REFERENCES users(`id`) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `login_bans`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `ip` varchar(45) NOT NULL default '0.0.0.0',
    `until` timestamp NOT NULL default current_timestamp,
    PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

/* Groups */
CREATE TABLE `groups`(
    `id` varchar(65) NOT NULL default '',
    `displayname` varchar(65) NOT NULL default '',
    `description` text NOT NULL default '',
    PRIMARY KEY(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `group_members`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `user` int(4) UNSIGNED NOT NULL default 0,
    `group` varchar(65) NOT NULL default '',
    `primary` tinyint(1) UNSIGNED NOT NULL default 0,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`user`) REFERENCES users(`id`) ON DELETE CASCADE,
    FOREIGN KEY(`group`) REFERENCES groups(`id`) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;



/*
 * FOR MODULE: news
 */
CREATE TABLE `news`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `title` varchar(120) NOT NULL default '',
    `content` text NOT NULL default '',
    `publish` timestamp NOT NULL default current_timestamp,
    `user` int(4) UNSIGNED NOT NULL default 0,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`user`) REFERENCES users(`id`) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `news_for`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `news` int(4) UNSIGNED NOT NULL default 0,
    `group` varchar(65) NOT NULL default '',
    PRIMARY KEY(`id`),
    FOREIGN KEY(`news`) REFERENCES news(`id`) ON DELETE CASCADE,
    FOREIGN KEY(`group`) REFERENCES groups(`id`) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_general_ci;



/*
 * FOR MODULE: polls
 */
CREATE TABLE `polls`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `title` varchar(120) NOT NULL default '',
    `message` text NOT NULL default '',
    `from` timestamp default current_timestamp,
    `until` timestamp default current_timestamp,
    `results_type` tinyint(1) NOT NULL default 0, /* 0:show results when poll is over; 1:show live results after the user voted; 2: show results before the user voted; 3: hide results */
    `allow_change` tinyint(1) NOT NULL default 0,
    `publish` timestamp NOT NULL default current_timestamp,
    PRIMARY KEY(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `polls_for`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `poll` int(4) UNSIGNED NOT NULL default 0,
    `group` varchar(65) NOT NULL default '',
    PRIMARY KEY(`id`),
    FOREIGN KEY(`poll`) REFERENCES polls(`id`),
    FOREIGN KEY(`group`) REFERENCES groups(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `poll_questions`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `poll` int(4) UNSIGNED NOT NULL default 0,
    `question` text NOT NULL default '',
    `type` tinyint(1) UNSIGNED NOT NULL default 0, /* 0:own response; 1:pick from list; 2:multiple selection */
    PRIMARY KEY(`id`),
    FOREIGN KEY(`poll`) REFERENCES polls(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `poll_answers`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `question` int(4) UNSIGNED NOT NULL default 0,
    `answer` text NOT NULL default '',
    PRIMARY KEY(`id`),
    FOREIGN KEY(`question`) REFERENCES poll_questions(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `poll_assignments`(
    `id` int(4) UNSIGNED NOT NULL auto_increment,
    `user` int(4) UNSIGNED NOT NULL default 0,
    `question` int(4) UNSIGNED NOT NULL default 0,
    `answer` int(4) UNSIGNED NOT NULL default 0,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`user`) REFERENCES users(`id`),
    FOREIGN KEY(`question`) REFERENCES poll_questions(`id`),
    FOREIGN KEY(`answer`) REFERENCES poll_answers(`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci;




/* nouser */
INSERT INTO users (`id`, `username`) VALUES (1, 'nouser');
/* Default groups for RBAC */
INSERT INTO groups (`id`) VALUES ('guest'),('admin'),('manager'),('teacher'),('headteacher'),('student');