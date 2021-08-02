<?php
include 'backend/db/pdo.php';
include 'backend/includes/cases/getCasesFromBd.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>case simulator</title>
    <link rel="stylesheet" href="assets/styles/index.css">
    <link rel="stylesheet" href="js/libs/bootstrap/bootstarp@4.6.0.css">
    <script src="js/libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="js/libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="js/libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="js/libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
</head>
<?php
include 'backend/includes/header.php';
?>
<body class="background">
<div class="content" style="display: flex;"></div>
</body>
</html>
<script>
    let cases;

    $.ajax({
        url: 'prefs/cases.json',
        async: false,
        success: (data) => {
            cases = data;
        }
    });

    cases.forEach(cases => {
        let a = document.createElement('a');
        let div = document.createElement('div');
        let img = document.createElement('img');
        let price = document.createElement('div');
        let name = document.createElement('div');
        let strong = document.createElement('strong');

        $(img).attr('src', 'assets/img/cases/' + cases.src);
        $(strong).html(cases.price + ' &#8381');
        $(name).html(cases.name);

        $(a).attr('href', 'http://localhost/bestcases.kz/frontend/php/case_page.php?' + cases.name);

        $(a).addClass('link');
        $(div).addClass('case');
        $(img).addClass('caseImg');
        $(price).addClass('casePrice');
        $(name).addClass('nameCase');

        $('.content').append(a);
        $(a).append(div);
        $(div).append(img);
        $(div).append(name);
        $(price).append(strong);
        $(div).append(price);
    });

    $('.avatar').attr('src', 'assets/img/UI/account_circle.svg');
    $('.registration').attr('href', 'frontend/php/registration.php');
    $('.logOn').attr('href', 'frontend/php/log_on.php');
    $('.createCaseButton').attr('href', 'frontend/php/create_case.php');
    $('.avatar').attr('src', 'assets/img/UI/' + user.avatar);
</script>