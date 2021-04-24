<?php
include "../../backend/db/pdo.php";
include "../../backend/api/items/itemFromDB.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="../../entities/cases.js"></script>
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../libs/bootstrap/bootstarp@4.6.0.css">
    <script src="../libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="../libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="../libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="../libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
    <script src="../scripts/create_case.js"></script>
</head>
<body class="background">
<h1 class="title">Создайте свой кейс</h1>
<div class="createCaseForm">
    <form action="../../backend/api/add_case.php" method="post">
        <div>
            <div class="modal_button" data-toggle="modal" data-target="#modalChooseCase">
                <img src="../img/UI/plus.png" alt="addCase" class="plus">
            </div>

            <div class="modal fade" id="modalChooseCase" tabindex="-1" role="dialog"
                 aria-labelledby="modal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modalDarkTheme">
                            <h5 class="modal-title modalDarkTheme" id="modalChooseCaseTitle">Choose case</h5>
                            <button type="button" class="close" aria-label="Close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body modalDarkTheme">
                            <div id="caseBody" class="modalDarkTheme"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="caseNameBlock">
                <input type="text" name="name" placeholder="название кейса" class="caseName">
            </div>

            <div class="line"></div>

            <div>
                <h4 class="chooseItemTitle">предметы в кейсе</h4>
                <div class="itemBox row justify-content-around">

                </div>
            </div>
            <div>

            </div>
    </form>
</div>
</body>
</html>
<script>
    let items;
    $.ajax({
        url: "../../backend/prefs/items.json",
        success: (data) => {
            items = data;

            items.forEach(item => {
                let div = document.createElement('div');
                let name = document.createElement('div');
                let price = document.createElement('div');
                let img = document.createElement('img');

                $(div).addClass('item');
                $(name).addClass('itemName');
                $(price).addClass('itemPrice');
                $(img).addClass('itemImg');
                $(img).addClass(item.rare);

                $(div).click(event => {
                    if (event.currentTarget.className !== 'item selectItem') {
                        $(event.currentTarget).addClass('selectItem');
                    } else {
                        $(event.currentTarget).removeClass('selectItem');
                    }

                });

                $(img).attr('src', '../img/items/' + item.src + '.png');
                $(price).html(item.price + ' &#8381');
                $(name).html(item.name);

                $('.itemBox').append(div);
                $(div).append(img);
                $(div).append(name);
                $(div).append(price);
            })
        }
    })
</script>