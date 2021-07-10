<?php
include '../../backend/db/pdo.php';

$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

//if (!$login || !$password) {
//    response(400, 'Введены не все данные');
//}

$stmt = $pdo->prepare("SELECT (login, password, salt) FROM users WHERE login = '$login'");


try {
    $result = $stmt->execute();
    response(500, $result);

} catch (PDOException $exception) {

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