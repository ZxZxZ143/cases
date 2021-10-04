<?php
include '../../backend/db/pdo.php';
include '../../backend/includes/items/itemFromDB.php';
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
    <script src="../../js/scripts/logic/create_case.js"></script>
    <script src="../../js/entities/items.js"></script>
</head>
<body class="background">
<?php
include '../../backend/includes/header.php';
?>
<div class="createCaseBody">
    <h1 class="createCaseTitle">Создайте свой кейс</h1>
    <div class="alert-success alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
         data-delay="3000" data-autohide="true">
        <strong class="successAlertText"></strong>
    </div>

    <div class="alert-danger alert fade hide" role="alert" style="position: fixed; top: 5%; right: 2.5%; z-index: 1"
         data-delay="3000" data-autohide="true">
        <strong class="dangerAlertText"></strong>
    </div>
    <div class="createCaseForm">
        <div>
            <div class="modal_button" data-toggle="modal" data-target="#modalChooseCase">
                <img src="../../assets/img/UI/plus.png" alt="addCase" class="plus">
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
                    <div class="price">Стоимость кейса: 0 &#8381</div>
                    <div class="itemBox" data-toggle="tooltip" data-placement="right"
                         title="кейс должен содержать минимум 3 предмета">
                        <div class="itemBox_header">
                            <input class="itemBox_input">
                            <div class="sort">
                                <span>сортировка по: </span>
                                <span class="sortByPrice">цене</span><img src="../../assets/img/UI/sort_down.svg"
                                                                          class="arrow arrow_price">
                                <span class="sortByRare">качеству</span><img src="../../assets/img/UI/sort_down.svg"
                                                                             class="arrow arrow_rare">
                                <span class="sortByName">названию</span><img src="../../assets/img/UI/sort_down.svg"
                                                                             class="arrow arrow_name">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button class="submitButton" onclick="sendParameters()">Создать кейс</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    let items = [];
    let casePrice = 50;
    let itemsInCase = [];
    let value = [];
    let caseName;
    let sortByPrice = 'down';
    let sortByRare = 'down';
    let sortByName = 'down';

    $.ajax({
        url: "../../prefs/items.json",
        success: (data) => {
            data.forEach(data => getParameters(data.name, data.price, data.rare, data.src));
        },
        async: false,
    });

    fillItemBox();

    function sendParameters() {
        caseName = $('.caseName').val();

        $.ajax({
            url: '../../api/v.1/add_case.php',
            type: 'POST',
            data: {
                name: caseName,
                price: casePrice,
                src: src,
                items: itemsInCase,
            },
            dataType: 'json',
            success: (data) => {
                $('.successAlertText').text(data.status);
                $('.alert-success').toast('show');
            },
            error: (xhr) => {
                let data = JSON.parse(xhr.responseText);

                $('.dangerAlertText').text(data.status);
                $('.alert-danger').toast('show');
            },
        })
    }

    sortArrByItemRare(items);

    function getParameters(name, price, rare, src) {
        items.push(new Item(name, price, rare, src));
    }

    function fillItemBox() {
        items.forEach(item => {
            let div = document.createElement('div');
            let name = document.createElement('div');
            let price = document.createElement('div');
            let img = document.createElement('img');

            $(div).addClass('item');
            $(name).addClass('itemName');
            $(price).addClass('itemPrice');
            $(img).addClass('itemImg');
            $(img).addClass(item.rare.slice(1));

            $(img).attr('src', '../../assets/img/items/' + item.src + '.png');
            $(div).attr('value', item.src + '.png');
            $(img).data('price', item.price);
            $(price).html(item.price + ' &#8381');
            $(name).html(item.name);

            $(div).click(event => {

                if (event.currentTarget.className !== 'item selectItem') {
                    $(event.currentTarget).addClass('selectItem');

                    if (casePrice / 2 + 1 > item.price && item.rare !== 'yellow') {
                        casePrice -= item.price * .5;
                    }
                    if (casePrice / 2 - 1 < item.price && casePrice > item.price && item.rare !== 'yellow') {
                        casePrice -= item.price * .2;
                    }
                    if (casePrice * 2 + 1 > item.price && casePrice < item.price && item.rare !== 'yellow') {
                        casePrice += item.price * .1;
                    }
                    if (casePrice * 2 + 1 < item.price && item.rare !== 'yellow') {
                        casePrice += item.price * .4;
                    }

                    if (casePrice / 2 + 1 > item.price && item.rare === 'yellow') {
                        casePrice -= item.price * .04;
                    }
                    if (casePrice / 2 - 1 < item.price && casePrice > item.price && item.rare === 'yellow') {
                        casePrice -= item.price * .01;
                    }
                    if (casePrice * 2 + 1 > item.price && casePrice < item.price && item.rare === 'yellow') {
                        casePrice += item.price * .01;
                    }
                    if (casePrice * 2 + 1 < item.price && item.rare === 'yellow') {
                        casePrice += item.price * .04;
                    }

                    if (document.getElementsByClassName('selectItem').length < 3) {
                        $('.itemBox').tooltip('enable');
                        $('.itemBox').tooltip('show');

                        $('.price').html('Стоимость кейса: ' + 0 + ' &#8381');
                    } else {
                        $('.itemBox').tooltip('hide');
                        $('.itemBox').tooltip('disable');

                        $('.price').html('Стоимость кейса: ' + Math.round(casePrice) + ' &#8381');
                    }

                    itemsInCase.push($(event.currentTarget).attr('value'));
                } else {
                    $(event.currentTarget).removeClass('selectItem');

                    if (casePrice / 2 + 1 > item.price && item.rare !== 'yellow') {
                        casePrice += item.price * .5;
                    }
                    if (casePrice / 2 - 1 < item.price && casePrice > item.price && item.rare !== 'yellow') {
                        casePrice += item.price * .2;
                    }
                    if (casePrice * 2 + 1 > item.price && casePrice < item.price && item.rare !== 'yellow') {
                        casePrice -= item.price * .1;
                    }
                    if (casePrice * 2 + 1 < item.price && item.rare !== 'yellow') {
                        casePrice -= item.price * .4;
                    }

                    if (casePrice / 2 + 1 > item.price && item.rare === 'yellow') {
                        casePrice += item.price * .04;
                    }
                    if (casePrice / 2 - 1 < item.price && casePrice > item.price && item.rare === 'yellow') {
                        casePrice += item.price * .01;
                    }
                    if (casePrice * 2 + 1 > item.price && casePrice < item.price && item.rare === 'yellow') {
                        casePrice -= item.price * .01;
                    }
                    if (casePrice * 2 + 1 < item.price && item.rare === 'yellow') {
                        casePrice -= item.price * .04;
                    }

                    if (document.getElementsByClassName('selectItem').length < 3) {
                        $('.itemBox').tooltip('enable');
                        $('.itemBox').tooltip('show');

                        $('.price').html('Стоимость кейса: ' + 0 + ' &#8381');
                    } else {
                        $('.itemBox').tooltip('hide');
                        $('.itemBox').tooltip('disable');

                        $('.price').html('Стоимость кейса: ' + Math.round(casePrice) + ' &#8381');
                    }

                    for (let i = 0; i < itemsInCase.length; i++) {
                        if (itemsInCase[i] === $(event.currentTarget).attr('value')) {
                            itemsInCase.splice(i, 1);
                        }
                    }
                }
            });

            $('.itemBox').append(div);
            $(div).append(img);
            $(div).append(name);
            $(div).append(price);
        })
    }

    $('.sortByRare').click(event => {
        if (sortByRare === 'down') {
            sortByRare = 'up';
            $('.arrow_rare').attr('src', '../../assets/img/UI/sort_up.svg')
        } else {
            sortByRare = 'down';
            $('.arrow_rare').attr('src', '../../assets/img/UI/sort_down.svg')
        }
    })

    $('.sortByName').click(event => {
        if (sortByName === 'down') {
            sortByName = 'up';
            $('.arrow_name').attr('src', '../../assets/img/UI/sort_up.svg')
        } else {
            sortByName = 'down';
            $('.arrow_name').attr('src', '../../assets/img/UI/sort_down.svg')
        }
    })

    $('.sortByPrice').click(event => {
        if (sortByPrice === 'down') {
            sortByPrice = 'up';
            $('.arrow_price').attr('src', '../../assets/img/UI/sort_up.svg')
        } else {
            sortByPrice = 'down';
            $('.arrow_price').attr('src', '../../assets/img/UI/sort_down.svg')
        }
    })

    function sortArrByItemRare(arr) {
        return arr.sort((a, b) => a.rare > b.rare ? 1 : -1);
    }

    function sortArrByParams(arr) {
        return arr.sort((a, b) => a.rare > b.rare ? 1 : -1);
    }
</script>