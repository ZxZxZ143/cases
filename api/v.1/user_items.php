<?php
include '../../backend/db/pdo.php';

$item = $_POST['item'] ?? null;
$sellLastItem = $_POST['sellLastItem'] ?? null;

$stmt = $pdo->prepare("SELECT items, countItems FROM users WHERE login = '$_COOKIE[login]'");
$stmt->execute();

$items = $stmt->fetch();

$items['items'] = unserialize($items['items']);

if ($sellLastItem === 'yes') {
    array_pop($items['items']);
} else {
    $items['countItems'] += 1;

    if (!empty($items['items'][0])) {
        $item .= $items['countItems'];

        array_push($items['items'], $item);
    } else {
        $items['items'][0] = $item . $items['countItems'];
    }
}

$items['items'] = serialize($items['items']);

$stmt = $pdo->prepare("UPDATE users SET items = '$items[items]', countItems = $items[countItems] WHERE login = '$_COOKIE[login]'");
$stmt->execute();

$items['items'] = unserialize($items['items']);

for ($i = 0; $i < count($_COOKIE['items']); $i++) {
    setcookie("items", '', -1, '/');
}

for ($i = 0; $i < count($items['items']); $i++) {
    setcookie("items[$i]", $items['items'][$i], 0, '/');
}