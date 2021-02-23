<?php

$config = require_once 'app/config.php';

try{
    $pdo = new PDO("mysql:host=localhost;dbname=".$config['dbname'].";charset=utf8", $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
    $Log_File = "app/log.txt";
    file_put_contents($Log_File, date("Y-m-d H:i:s")." -//- ".$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);
    echo '<meta charset="UTF-8">Ошибка базы данных';
}