<?php
include '../../backend/db/pdo.php';

$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$caseName = substr($url, 57);
$src = 0;
$price = 0;
$items = [];


$stmt = $pdo->prepare("SELECT name, src, price, items FROM cases WHERE name = '$caseName'");
$stmt->execute();
$result = $stmt->fetch();

$result['items'] = unserialize($result['items']);

$result = json_encode($result);

$file = fopen("../../prefs/case.json", "w");
fwrite($file, $result);
fclose($file);

$result = json_decode($result);

$items = [];
if ($result->items) {
    for ($i = 0; $i < count($result->items); $i++) {
        $item = substr($result->items[$i], 0, -4);
        $stmt = $pdo->prepare("SELECT name, src, price, rare FROM items WHERE src = '$item'");
        $stmt->execute();
        array_push($items, $stmt->fetchAll());
    }

    $items = json_encode($items, JSON_UNESCAPED_UNICODE);

    $file = fopen("../../prefs/itemsInCase.json", "w");
    fwrite($file, $items);
    fclose($file);
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
    <script src="../../js/entities/cases.js"></script>
    <script src="../../js/entities/items.js"></script>
</head>
<body class="background">
<?php
include '../../backend/includes/header.php';
?>
<div class="alert-danger alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
     data-delay="3000" data-autohide="true">
    <strong class="dangerAlertText"></strong>
</div>
<div class="casePage">
    <div class="casePageTitle">
        <h1 class="name">кейс</h1>
        <div class="underline"></div>
    </div>
    <div class="caseOpen">
        <div class="casePlace">
            <img class="imgCase">
        </div>
        <div class="buttonPlace">
            <div class="button" onclick="openCase()">Открыть за</div>
        </div>
    </div>
    <div class="itemsInCase"></div>
</div>
</body>
</html>
<script>
    let container;
    let items = [];
    let userItem;

    $.ajax({
        url: '../../prefs/case.json',
        async: false,
        dataType: 'json',
        success: data => {
            container = new Case(data.name, data.price, data.items, data.src);
        },
    });

    $.ajax({
        url: '../../prefs/itemsInCase.json',
        async: false,
        dataType: 'json',
        success: data => {
            data.forEach(value => {
                items.push(new Item(value[0].name, value[0].price, value[0].rare, value[0].src));
            });
        },
    });

    $('.button').html('открыть за ' + container.price + ' &#8381');

    $('.name').html(container.name);

    $('.imgCase').attr('src', '../../assets/img/cases/' + container.src);

    sortArrByItemRare(items);

    fillItemsInCase(items);

    function openCase() {
        if (user.balance > container.price || user.balance === container.price) {
            removeInterfaceBeforeOpenCase();

            let div = document.createElement('div');
            let scrollHolder = document.createElement('div');
            let triangleBottom = document.createElement('img');
            let triangleTop = document.createElement('img');

            $(scrollHolder).addClass('scrollHolder');
            $(triangleBottom).addClass('triangleBottom');
            $(triangleTop).addClass('triangleTop');

            $(div).attr('id', 'case_scroll');
            $(triangleBottom).attr('src', '../../assets/img/UI/triangle.png');
            $(triangleTop).attr('src', '../../assets/img/UI/triangle.png');

            $('.casePlace').append(scrollHolder);
            $(scrollHolder).append(div).append(triangleBottom).append(triangleTop);

            fillCase(div);

            startAnimation(div);

            $.ajax({
                url: '../../api/v.1/user_items.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    item: items[userItem].src + '.png',
                }
            })

            user.balance -= container.price;
            $('.money').html(user.balance + ' &#8381');
            $.ajax({
                url: '../../api/v.1/user_balance.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    balance: user.balance,
                }
            })
        } else {
            $('.dangerAlertText').text('Недостаточно средств');
            $('.alert-danger').toast('show');
        }
    }

    function sellItem() {
        user.balance += items[userItem].price;
        $('.money').html(user.balance + ' &#8381');
        $.ajax({
            url: '../../api/v.1/user_balance.php',
            type: 'POST',
            dataType: 'json',
            data: {
                balance: user.balance,
            }
        })

        $.ajax({
            url: '../../api/v.1/user_items.php',
            type: 'POST',
            dataType: 'json',
            data: {
                sellLastItem: 'yes',
            }
        })

        $('.sellItem').remove();
    }

    function fillCase(itemsPlace) {
        for (let i = 0; i < 70; i++) {
            let img = document.createElement('img');
            let div = document.createElement('div');
            let itemNum = randomItem(items);

            $(img).addClass('itemImg_scroll');
            $(div).addClass('item_scroll');

            $(img).attr('src', '../../assets/img/items/' + items[itemNum].src + '.png');
            switch (items[itemNum].rare.slice(1)) {
                case 'blue':
                    $(div).attr('style', 'background-color: blue');
                    break;
                case 'purple':
                    $(div).attr('style', 'background-color: rgb(108, 50, 178)');
                    break;
                case 'pink':
                    $(div).attr('style', 'background-color: #ff1aff');
                    break;
                case 'red':
                    $(div).attr('style', 'background-color: red');
                    break;
                case 'yellow':
                    $(div).attr('style', 'background-color: yellow');
                    break;
            }

            if (i === 66) {
                userItem = itemNum;
                endAnimation(itemNum);
            }

            $(itemsPlace).append(div);
            $(div).append(img);
        }
    }

    function startAnimation(obj) {
        $(obj).addClass('scrollAnimate');
    }

    function endAnimation(itemNum) {
        let animation = document.getElementById('case_scroll');

        animation.onanimationend = function () {
            let div = document.createElement('div');
            let name = document.createElement('div');
            let reopen = document.createElement('div');
            let sellItem = document.createElement('div');

            $(name).html(items[itemNum].name);
            $(reopen).html('попробовать ещё');
            $(sellItem).html('продать за ' + items[itemNum].price + ' &#8381');

            $(reopen).attr('onclick', ' openCase()');
            $(sellItem).attr('onclick', 'sellItem()');

            $(div).addClass('prizeName');
            $(reopen).addClass('button');
            $(sellItem).addClass('button sellItem');

            $('.buttonPlace').append(reopen).append(sellItem);
            $('.caseOpen').append(div);
            $(div).append(name);
        };
    }

    function removeInterfaceBeforeOpenCase() {
        $('.imgCase').remove();
        $('.button').remove();
        $('.scrollHolder').remove();
        $('.prizeName').remove();
    }

    function fillItemsInCase(items) {
        items.forEach(item => {
            let div = document.createElement('div');
            let name = document.createElement('div');
            let img = document.createElement('img');

            $(div).addClass('itemInCase');
            $(name).addClass('itemName');
            $(img).addClass('itemImg');
            $(img).addClass(item.rare.slice(1));

            $(name).html(item.name);
            $(img).attr('src', '../../assets/img/items/' + item.src + '.png');

            $('.itemsInCase').append(div);
            $(div).append(img);
            $(div).append(name);
        });
    }

    function sortArrByItemRare(arr) {
        return arr.sort((a, b) => a.rare > b.rare ? 1 : -1);
    }

    function randomItem(arr) {
        let minPriceCount = 0;
        let lowPriceCount = 0;
        let midPriceCount = 0;
        let maxPriceCount = 0;

        arr = arr.sort((a, b) => a.price > b.price ? 1 : -1);

        for (let i = 0; i < arr.length; i++) {
            if (arr[i].price < container.price / 2 && arr[i].rare.slice(1) !== 'yellow') {
                minPriceCount++;
            }
            if (arr[i].price < container.price && arr[i].price > container.price / 2 && arr[i].rare.slice(1) !== 'yellow') {
                lowPriceCount++;
            }
            if (arr[i].price > container.price && arr[i].price < container.price * 2 && arr[i].rare.slice(1) !== 'yellow') {
                midPriceCount++;
            }
            if (arr[i].price > container.price * 2 && arr[i].rare.slice(1) !== 'yellow') {
                maxPriceCount++;
            }
        }

        minPriceCount -= 1;
        lowPriceCount += minPriceCount;
        midPriceCount += lowPriceCount;
        maxPriceCount += midPriceCount;

        let randomNum = Math.floor(Math.random() * 1000);

        if (arr[arr.length - 1].rare.slice(1) === 'yellow') {
            if (randomNum < 400) {
                return Math.floor(Math.random() * (minPriceCount - 0 + 1)) + 0;
            }
            if (randomNum > 399 && randomNum < 700) {
                return Math.floor(Math.random() * (lowPriceCount - minPriceCount + 1)) + minPriceCount;
            }
            if (randomNum > 699 && randomNum < 900) {
                return Math.floor(Math.random() * (midPriceCount - lowPriceCount + 1)) + lowPriceCount;
            }
            if (randomNum > 899 && randomNum < 997) {
                return Math.floor(Math.random() * (maxPriceCount - midPriceCount + 1)) + midPriceCount;
            }
            if (randomNum > 996) {
                return Math.floor(Math.random() * (arr.length - 1 - maxPriceCount + 1)) + maxPriceCount;
            }
        } else {
            if (randomNum < 400) {
                return Math.floor(Math.random() * (minPriceCount - 0 + 1)) + 0;
            }
            if (randomNum > 399 && randomNum < 700) {
                return Math.floor(Math.random() * (lowPriceCount - minPriceCount + 1)) + minPriceCount;
            }
            if (randomNum > 699 && randomNum < 900) {
                return Math.floor(Math.random() * (midPriceCount - lowPriceCount + 1)) + lowPriceCount;
            }
            if (randomNum > 899) {
                return Math.floor(Math.random() * (maxPriceCount - midPriceCount + 1)) + midPriceCount;
            }
        }
    }
</script>