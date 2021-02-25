<?php
/**
 *  DPO statements to create following tables:
 *
 *  # todo_lists
 *   - id (unsigned, autoincrement)
 *   - user_id (unsigned - reference to the user table)
 *   - created_at (datetime)
 *
 *  *  # users
 *   - id (unsigned, autoincrement)
 *   - title (varchar)
 *   - pass (varchar)
 *
 *  # todo_tasks
 *   - id (unsigned, autoincrement)
 *   - todo_list_id (unsigned - reference to the todo_lists table)
 *   - is_done (tinyint(1))
 *   - title (varchar)
 *   - created_at (datetime)
 *   - task_position (unsigned)
 *
 */

/** @var \PDO $pdo */
require_once 'app/db/pdo_ini.php';

// Create database
$sql = <<<'SQL'
CREATE DATABASE to_do_list;
SQL;
$pdo->exec($sql);

// Create table "todo_lists"
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

// Create table "users"
$sql = <<<'SQL'
CREATE TABLE `users`
(
    `id`    int(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `login` varchar(200) UNIQUE NOT NULL,
    `pass`  varchar(255)        NOT NULL,
    PRIMARY KEY (`id`)
);
SQL;
$pdo->exec($sql);

// Create table "todo_tasks"
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
