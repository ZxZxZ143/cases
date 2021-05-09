<?php
$stmt = $pdo->prepare('SELECT src, name, price, rare FROM items');
$stmt->execute();
$result = $stmt->fetchAll();

$result = json_encode($result);

$file = fopen("../../prefs/items.json", "w");
fwrite($file, $result);
fclose($file);