<?php
include '../backend/db/pdo.php';

$login = 'ccvbcvvc';

$stmt = $pdo->prepare("SELECT login, password, salt FROM users WHERE login = '$login'");


$stmt->execute();
$result = $stmt->fetchAll();

var_dump($result[0]['password']);