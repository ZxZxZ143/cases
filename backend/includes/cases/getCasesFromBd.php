<?php
$stmt = $pdo->prepare('SELECT name, src, price, items FROM cases');
$stmt->execute();
$result = $stmt->fetchAll();

$result = json_encode($result, JSON_UNESCAPED_UNICODE);

$file = fopen("prefs/cases.json", "w");
fwrite($file, $result);
fclose($file);