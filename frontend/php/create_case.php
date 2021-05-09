<?php
include "../../backend/db/pdo.php";
include "../../backend/includes/items/itemFromDB.php"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="../../js/entities/cases.js"></script>
    <link rel="stylesheet" href="../../assets/styles/index.css">
    <link rel="stylesheet" href="../../js/libs/bootstrap/bootstarp@4.6.0.css">
    <script src="../../js/libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="../../js/libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
    <script src="../../js/scripts/logic/create_case.js"></script>
    <script src="../../js/entities/items.js"></script>
</head>
<body class="background">
<h1 class="title">Создайте свой кейс</h1>
<div class="createCaseForm">
    <form action="../../api/v.1/add_case.php" method="post">
        <div>
            <div class="modal_button" data-toggle="modal" data-target="#modalChooseCase">
                <input type="text" name="src" class="src invisibleInput"><img src="../../assets/img/UI/plus.png"
                                                                              alt="addCase" class="plus">
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
                <div style="width: 700px; margin-left: auto;margin-right: auto">
                    <input type="text" class="invisibleInput casePrice" name="price">
                    <div class="price">Стоимость кейса: 0 &#8381</div>
                    <div class="itemBox row justify-content-around"></div>
                </div>
            </div>

            <div>
                <input type="submit" class="submitButton" value="Создать кейс">
            </div>
    </form>
</div>
</body>
</html>
<script>
    let items = [];
    let casePrice = 0;
    let itemsInCase = [];
    let value = [];

    $.ajax({
        url: "../../prefs/items.json",
        success: (data) => {
            data.forEach(data => items.push(new Item(data.name, data.price, data.rare, data.src)));

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

                $(img).attr('src', '../../assets/img/items/' + item.src + '.png');
                $(div).attr('value', item.src + '.png');
                $(img).data('price', item.price);
                $(price).html(item.price + ' &#8381');
                $(name).html(item.name);

                $(div).click(event => {
                    let input = document.createElement('input');

                    if (event.currentTarget.className !== 'item selectItem') {
                        $(event.currentTarget).addClass('selectItem');
                        switch (item.rare) {
                            case 'blue':
                                casePrice += item.price * .6;
                                break;
                            case 'purple':
                                casePrice += item.price * .4;
                                break;
                            case 'pink':
                                casePrice += item.price * .3;
                                break;
                            case 'red':
                                casePrice += item.price * .2;
                                break;
                            case 'yellow':
                                casePrice += item.price * .08;
                                break;
                        }

                        $(input).attr('name', 'items[]');
                        $(input).attr('value', $(event.currentTarget).attr('value'));

                        $(input).addClass('invisibleInput');
                        $(input).addClass('itemsInCase');

                        $(event.currentTarget).append(input);

                        itemsInCase.push($(event.currentTarget).attr('value'));
                    } else {
                        $(event.currentTarget).removeClass('selectItem');
                        switch (item.rare) {
                            case 'blue':
                                casePrice -= item.price * .6;
                                break;
                            case 'purple':
                                casePrice -= item.price * .4;
                                break;
                            case 'pink':
                                casePrice -= item.price * .3;
                                break;
                            case 'red':
                                casePrice -= item.price * .2;
                                break;
                            case 'yellow':
                                casePrice -= item.price * .08;
                                break;
                        }

                        $('.itemsInCase').map(function (index, element) {
                            if ($(element).attr('value') === $(event.currentTarget).attr('value')) {
                                $(element).remove();
                            }
                        });

                    }

                    $('.price').html('Стоимость кейса: ' + Math.round(casePrice) + ' &#8381');
                    $('.casePrice').attr('value', Math.round(casePrice));
                });

                $('.itemBox').append(div);
                $(div).append(img);
                $(div).append(name);
                $(div).append(price);
            })
        }
    })
</script>