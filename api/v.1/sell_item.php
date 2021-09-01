<?php
include '../../backend/db/pdo.php';

$item = $_POST['item'] ?? null;

$stmt = $pdo->prepare("SELECT items FROM users WHERE login = '$_COOKIE[login]'");
$stmt->execute();
$items = $stmt->fetch();

$items = unserialize($items['items']);

for ($i = 0; $i < count($items); $i++) {
    if ($items[$i] === $item) {
        array_splice($items, $i, 1);
        break;
    }
}

$items = serialize($items);

$stmt = $pdo->prepare("UPDATE users SET items = '$items' WHERE login = '$_COOKIE[login]'");
$stmt->execute();

$items = unserialize($items);

setcookie("items", '', -1, '/');

if (!empty($items)) {
    for ($i = 0; $i < count($items); $i++) {
        setcookie("items[$i]", $items[$i], 0, '/');
    }
}
