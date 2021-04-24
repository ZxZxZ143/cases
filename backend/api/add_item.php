<?php

require '../db/pdo.php';

$name = $_POST['name'] ?? null;
$rare = $_POST['rare'] ?? null;
$src = $_POST['src'] ?? null;
$price = $_POST['price'] ?? null;

if (empty($name)) {
    exit();
}

if (empty($rare)) {
    exit();
}

if (empty($price)) {
    exit();
}

// todo делать проверки и вернуть ошибку, если данные не все или неправильные

$stmt = $pdo->prepare('INSERT INTO items (name, rare, src, price) VALUES (:name, :rare, :src, :price)');
try {
    $stmt->execute([
        'name' => $name,
        'rare' => $rare,
        'src' => $src,
        'price' => $price
    ]);
} catch (PDOException $exception) {
    switch ($exception ->getCode()) {
        case 23000:
            echo "кейс уже существует";
    }
}
