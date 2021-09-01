<div class="header">
    <div>
        <a href="http://localhost/bestcases.kz/index.php" class="link title"><h1>case simulator</h1></a>
    </div>
    <div class="logOnUser user" style="display: none">
            <img src="../../assets/img/UI/account_circle.svg" alt="avatar" class="avatar" onclick="redirectToInventory()">
            <div class="userName" onclick="redirectToInventory()">unknown</div>
        <div class="balance">
            <span style="margin-left: 5px; margin-right: 5px" class="money">2000 &#8381</span>
            <div class="fillBalance"></div>
        </div>
    </div>
    <div class="unLogOnUser user">
        <a href="../../frontend/php/log_on.php" class="link logOn">войти</a>
        <div>|</div>
        <a href="../../frontend/php/registration.php" class="link registration">зарегестрироваться</a>
    </div>
    <div class="headerLine"></div>
    <a href="../../frontend/php/create_case.php" class="link createCaseButton">
        <div class="createCase">создать кейс</div>
    </a>
</div>
<script>
    let user = {
        'login': '<?php
            if (isset($_COOKIE['login'])) {
                echo $_COOKIE['login'];
            }?>',
        'balance': '<?php
            if (isset($_COOKIE['balance'])) {
                echo $_COOKIE['balance'];
            }
            ?>',
        'name': '<?php
            if (isset($_COOKIE['name'])) {
                echo $_COOKIE['name'];
            }
            ?>',
        'avatar': '<?php
            if (isset($_COOKIE['avatar'])) {
                echo $_COOKIE['avatar'];
            }
            ?>',
    };

    if (user.login !== '') {
        $('.unLogOnUser').attr('style', 'display: none');
        $('.logOnUser').attr('style', 'display: flex');

        $('.avatar').attr('src', '../../assets/img/UI/' + user.avatar);

        $('.money').html(user.balance + ' &#8381');
        $('.userName').html(user.name);
    }

    function redirectToInventory() {
        document.location.replace("http://localhost/bestcases.kz/frontend/php/inventory.php");
    }
</script>