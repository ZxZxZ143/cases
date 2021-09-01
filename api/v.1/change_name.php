<?php
include '../../backend/db/pdo.php';

$name = $_POST['name'] ?? null;

if ($name === $_COOKIE['name']) {
    exit();
} else {
    $stmt = $pdo->prepare("UPDATE users SET name = '$name' WHERE login = '$_COOKIE[login]'");
    $stmt->execute();

    setcookie('name', $name, 0, '/');
}