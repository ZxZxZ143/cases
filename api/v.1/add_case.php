<?php
include '../../backend/db/pdo.php';

$name = $_POST['name'] ?? null;
$src = $_POST['src'] ?? null;
$price = $_POST['price'] ?? null;
$items = $_POST['items'] ?? null;

if (!$name || !$src || !$price || !$items) {
    response(400, 'Введены не все данные');
}

if (count($items) < 3) {
    response(400, 'Введены не все данные');
}

$items = serialize($items);

$stmt = $pdo->prepare('INSERT INTO cases (name, src, price, items) VALUES (:name, :src, :price, :items)');

try {
    $stmt->execute([
        'name' => $name,
        'src' => $src,
        'price' => $price,
        'items' => $items
    ]);

    response(201, "Кейс '$name' создан!");
} catch (PDOException $exception) {
    switch ($exception->getCode()) {
        case 23000:
            response(409, "Кейс с именем '$name' существует.");
        default:
            response(500, 'Ошибка сервера, попробуйте позднее.');
    }
}

function response($code, $status)
{
    $response = [
        'code' => $code,
        'status' => $status
    ];

    header("HTTP/1.1 $code");
    exit(json_encode($response, JSON_UNESCAPED_UNICODE));
}
