<?php
/**
 *  DPO statements to create following tables:
 *
 *  # todo_lists
 *   - id (unsigned, autoincrement)
 *   - created_at (datetime)
 *
 *  # todo_tasks
 *   - id (unsigned, autoincrement)
 *   - todo_list_id (unsigned - reference to the todo_lists table)
 *   - is_done (tinyint(1))
 *   - title (varchar)
 *   - created_at (datetime)
 */

/** @var \PDO $pdo */
require_once './pdo_ini.php';

// todo_lists
$sql = <<<'SQL'
CREATE TABLE `todo_lists`
(
    `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT(10) UNSIGNED NOT NULL,
    `created_at` DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
);
SQL;
$pdo->exec($sql);

// users
$sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `users`
(
    `id`    int(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `login` varchar(200) UNIQUE NOT NULL,
    `pass`  varchar(32)         NOT NULL,
    PRIMARY KEY (`id`)
);
SQL;
$pdo->exec($sql);

// todo_tasks
$sql = <<<'SQL'
CREATE TABLE `todo_tasks`
(
    `id`           INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `todo_list_id` INT(10) UNSIGNED    NOT NULL,
    `is_done`      TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `title`        VARCHAR(50)         NOT NULL COLLATE 'utf8_general_ci',
    `created_at`   DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `task_position` INT(10) UNSIGNED   NOT NULL DEFAULT 100,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`todo_list_id`) REFERENCES `todo_lists` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
);
SQL;
$pdo->exec($sql);
