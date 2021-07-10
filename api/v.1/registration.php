<?php
include '../../backend/db/pdo.php';

$login = $_POST['login'] ?? null;
$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;
$repeatPassword = $_POST['repeatPassword'] ?? null;

if (!$login || !$name || !$password || !$repeatPassword) {
    response(400, 'Введены не все данные');
}

if (strlen($login) < 3 || strlen($login) > 15) {
    response(400, 'Логин должен содержать от 3 до 15 символов');
}

if (strlen($password) < 4 || strlen($password) > 16) {
    response(400, 'Пароль должен содержать от 5 до 15 символов');
}

if (!($password === $repeatPassword)) {
    response(400, 'Пароли не совпадают');
}

$salt = randomString();

$password = sha1($password . $salt);

$stmt = $pdo->prepare('INSERT INTO users (login, name, password, salt) VALUES (:login, :name, :password, :salt)');

try {
    $stmt->execute([
        'login' => $login,
        'name' => $name,
        'password' => $password,
        'salt' => $salt
    ]);

    response(201, 'Пользователь создан');
} catch (PDOException $exception) {
    switch ($exception->getCode()) {
        case 23000:
            response(409, 'Логин занят');
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

function randomString() {
    return rand(10000, 99999);
}