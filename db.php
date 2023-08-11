<?php
$host = 'localhost';
$db = 'guest_book';
$user = 'root';
$pass =  '91r2ax@ba2';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo  = new PDO($dsn, $user, $pass, $options);

var_dump($pdo);