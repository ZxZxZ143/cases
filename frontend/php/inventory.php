<?php
include '../../backend/db/pdo.php';

$items = [];

$stmt = $pdo->prepare("SELECT items FROM users WHERE login = '$_COOKIE[login]'");
$stmt->execute();
$userItems = $stmt->fetch();

$userItems = unserialize($userItems['items']);

if (!empty($userItems[0])) {
    for ($i = 0; $i < count($userItems); $i++) {

        $n = strpos($userItems[$i], '.');
        $item = substr($userItems[$i], 0, $n);

        $stmt = $pdo->prepare("SELECT name, src, price, rare FROM items WHERE src = '$item'");
        $stmt->execute();
        $result = $stmt->fetch();

        array_push($items, $result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>case simulator</title>
    <link rel="stylesheet" href="../../assets/styles/index.css">
    <link rel="stylesheet" href="../../js/libs/bootstrap/bootstarp@4.6.0.css">
    <script src="../../js/libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
</head>
<body class="background">
<?php
include '../../backend/includes/header.php';
?>
<div class="alert-danger alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
     data-delay="3000" data-autohide="true">
    <strong class="dangerAlertText">Не удалось получить доступ к инвентарю</strong>
</div>
<div class="inventory_mainPart">
    <div class="inventory_user">
        <img src="../../assets/img/UI/account_circle.svg" alt="avatar" class="inventory_avatar">
        <div class="inventory_userName">
            <span class="inventory_name">unknown</span>
            <div class="inventory_inputDiv" style="display: none">
                <input class="inventory_nameInput">
            </div>
        </div>
        <div class="changeName" onclick="rename()">
            <img src="../../assets/img/UI/create.svg" class="rename">
        </div>
    </div>
    <div class="inventory"></div>
</div>
</body>
<script>
    let renameActive = false;
    let items = '<?php echo json_encode($items);?>';
    let itemIndex = '<?php
        if (!empty($userItems)) {
            echo json_encode($userItems);
        }
        ?>';

    items = $.parseJSON(items);
    if (itemIndex !== '') {
        itemIndex = $.parseJSON(itemIndex);
    }


    $('.inventory_name').html(user.name);

    fillInventory();

    function fillInventory() {
        for (let i = 0; i < items.length; i++) {
            let div = document.createElement('div');
            let img = document.createElement('img');
            let itemName = document.createElement('div');
            let itemPrice = document.createElement('div');
            let sellButton = document.createElement('img');

            $(div).addClass('inventory_item');
            $(img).addClass('inventory_itemImg');
            $(itemName).addClass('inventory_itemName');
            $(itemPrice).addClass('inventory_itemPrice');
            $(sellButton).addClass('inventory_sellButton');
            $(img).addClass(items[i].rare.slice(1));

            $(sellButton).attr('value', itemIndex[i]);
            $(img).attr('src', '../../assets/img/items/' + items[i].src + '.png');
            $(sellButton).attr('src', '../../assets/img/UI/sell.svg');
            $(itemName).html(items[i].name);
            $(itemPrice).html(items[i].price + ' &#8381');

            $('.inventory').append(div);
            $(div).append(img);
            $(div).append(itemName);
            $(div).append(itemPrice);
            $(div).append(sellButton);
        }
    }

    $('.inventory_sellButton').click(event => {
        $.ajax({
            url: '../../api/v.1/sell_item.php',
            type: 'POST',
            data: {
                item: $(event.target).attr('value')
            },
            success: (data) => {
            }
        });

        $(event.target.parentNode).remove();

        let sellItem = $(event.target).attr('value');
        sellItem = sellItem.split('.')[0];

        for (let i = 0; i < items.length; i++) {
            if (items[i].src === sellItem) {

                user.balance = Number(user.balance) + Number(items[i].price);
                $('.money').html(user.balance + ' &#8381');

                $.ajax({
                    url: '../../api/v.1/user_balance.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        balance: user.balance,
                    }
                });
                break;
            }

        }
    });

    function rename() {
        if (!renameActive) {
            renameActive = true;

            $('.rename').attr('src', '../../assets/img/UI/done.svg');

            $('.inventory_name').attr('style', 'display: none');
            $('.inventory_inputDiv').attr('style', 'display: flex');

            $('.inventory_nameInput').attr('value', user.name);
        } else {
            renameActive = false;

            let newName = $('.inventory_nameInput').val();

            user.name = newName;
            $('.inventory_name').html(user.name);

            $.ajax({
                url: '../../api/v.1/change_name.php',
                async: false,
                type: 'POST',
                data: {
                    name: newName
                },
                success: (data) => {
                },
                error: (data) => {
                }
            })

            $('.rename').attr('src', '../../assets/img/UI/create.svg');

            $('.inventory_inputDiv').attr('style', 'display: none');
            $('.inventory_name').attr('style', 'display: inline');
        }

    }
</script>
