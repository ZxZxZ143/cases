<?php

require '../db/pdo.php';

$name = $_POST['name'] ?? null;
$src = $_POST['src'] ?? null;
$price = $_POST['price'] ?? null;
$items = $_POST['items'] ?? null;

if (empty($name)) {
    exit();
}

if (empty($src)) {
    exit();
}

if (empty($price)) {
    exit();
}

if (empty($items)) {
    exit();
} else {
    $items = implode($items, " ");
}

$stmt = $pdo->prepare('INSERT INTO cases (case_name, src, price, items) VALUES (:name, :src, :price, :items)');
try {
    $stmt->execute([
        'name' => $name,
        'src' => $src,
        'price' => $price,
        'items' => $items
    ]);
} catch (PDOException $exception) {
    switch ($exception->getCode()) {
        case 23000:
            echo "кейс уже существует";
    }
}
