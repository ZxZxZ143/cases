<?php
include '../../backend/db/pdo.php';

$balance = $_POST['balance'] ?? null;

setcookie('balance', $balance, 0, '/');

$stmt = $pdo->prepare("UPDATE users SET balance = '$balance' WHERE login = '$_COOKIE[login]'");

$stmt->execute();