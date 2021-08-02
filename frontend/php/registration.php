<?php
include '../../backend/db/pdo.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>registration</title>
    <link rel="stylesheet" href="../../assets/styles/index.css">
    <link rel="stylesheet" href="../../js/libs/bootstrap/bootstarp@4.6.0.css">
    <script src="../../js/libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
</head>
<body class="background">
<?php include '../../backend/includes/header.php';?>
<div class="alert-success alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
     data-delay="3000" data-autohide="true">
    <strong class="successAlertText"></strong>
</div>
<div class="alert-danger alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
     data-delay="3000" data-autohide="true">
    <strong class="dangerAlertText"></strong>
</div>
<div class="registration_form">
    <div class="registration_border">
        <div class="registration_title">Регистрация</div>
        <div class="registration_inputDiv">
            <input type="text" class="registration_input login" required>
            <label>логин</label>
        </div>
        <div class="registration_inputDiv">
            <input type="text" class="registration_input name" required>
            <label>Имя пользователя</label>
        </div>
        <div class="registration_inputDiv">
            <input type="password" class="registration_input password" required><img
                    src="../../assets/img/UI/visibility.svg" class="visibility">
            <label>Пароль</label>
        </div>
        <div class="registration_inputDiv">
            <input type="password" class="registration_input repeatPassword" required><img
                    src="../../assets/img/UI/visibility.svg" class="visibility">
            <label>Повторите пароль</label>
        </div>
        <div>
            <button class="registration_button">Отправить</button>
        </div>
    </div>
</div>
</body>
<script>
    let visible = false;
    let login;
    let name;
    let password;
    let repeatPassword;

    $('.visibility').click(() => {
        if (!visible) {
            $('.password').attr('type', 'text');
            $('.repeatPassword').attr('type', 'text');
            $('.visibility').attr('src', '../../assets/img/UI/visibility_off.svg');
            visible = true;
        } else {
            $('.password').attr('type', 'password');
            $('.repeatPassword').attr('type', 'password');
            $('.visibility').attr('src', '../../assets/img/UI/visibility.svg');
            visible = false;
        }
    });

    $('.registration_button').click(() => {
        login = $('.login').val();
        name = $('.name').val();
        password = $('.password').val();
        repeatPassword = $('.repeatPassword').val();

        $.ajax({
            url: '../../api/v.1/registration.php',
            type: 'post',
            data: {
                login: login,
                name: name,
                password: password,
                repeatPassword: repeatPassword
            },
            dataType: 'json',
            success: (data) => {
                $('.successAlertText').text(data.status);
                $('.alert-success').toast('show');
                document.location.replace("http://localhost/bestcases.kz/");
            },
            error: (xhr) => {
                let data = JSON.parse(xhr.responseText);

                $('.dangerAlertText').text(data.status);
                $('.alert-danger').toast('show');
            }
        });
    })
</script>