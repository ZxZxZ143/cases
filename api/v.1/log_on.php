<?php
include '../../backend/db/pdo.php';

$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

if (!$login || !$password) {
    response(400, 'Введены не все данные');
}

$stmt = $pdo->prepare("SELECT login, password, salt FROM users WHERE login = '$login'");


try {
    $stmt->execute();
    $result = $stmt->fetch();

    if (!empty($result)) {
        if ($result['password'] === sha1($password . $result['salt'])) {
            $stmt = $pdo->prepare("SELECT login, name, balance, avatar FROM users WHERE login = '$result[login]'");

            $stmt->execute();
            $user = $stmt->fetch();

//            $user['items'] = unserialize($user['items']);
//
//            for ($i = 0; $i < count($user['items']); $i++) {
//                setcookie("items[$i]", $user['items'][$i], 0, '/');
//            }

            setcookie('login', $login, 0, '/');
            setcookie('balance', $user['balance'], 0, '/');
            setcookie('name', $user['name'], 0, '/');
            setcookie('avatar', $user['avatar'], 0, '/');

            response(201, 'Авторизация успешна');
        } else {
            response(400, 'Неверный логин или пароль');
        }
    } else {
        response(400, 'Неверный логин или пароль');
    }
} catch (PDOException $exception) {
    response(500, 'Попробуте позже');
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