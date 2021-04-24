<?php
include "backend/db/pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="entities/cases.js"></script>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="assets/libs/bootstrap/bootstarp@4.6.0.css">
    <script src="assets/libs/jQuery@3.6.0/jQuery@3.6.0.js"></script>
    <script src="assets/libs/bootstrap/bootstrap@4.6.0.js"></script>
    <script src="assets/libs/bootstrap/bootstrap_bundle@4.6.0.js"></script>
    <script src="assets/libs/bootstrap/bootstrap_popper@4.6.0.js"></script>
    <script src="create_case.js"></script>
</head>
<body class="background">
<h1 class="title">Создайте свой кейс</h1>
<div class="createCaseForm">
    <form action="backend/api/add_case.php" method="post">
        <div>
            <div class="modal_button" data-toggle="modal" data-target="#modalChooseCase">
                <img src="assets/img/UI/plus.png" alt="addCase" class="plus">
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

            <div>
                <input type="text" name="name" placeholder="название кейса" class="caseName">
            </div>

            <div class="line"></div>

            <div>
                <h4 class="chooseItemTitle">предметы в кейсе</h4>
                <div class="itemBox row justify-content-around">
                    <?php
                    $stmt = $pdo->prepare('SELECT src, item_name, price FROM items');
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    $name = $result["item_name"];
                    foreach ($result as $raw) {
                        $src = $raw["src"];
                        $name = $raw["item_name"];
                        $price = $raw["price"];

                        $src = trim($src, ".png");
//                        echo "<div class='item'>
//                                <img src='assets/img/items/$src.png' class='itemImg'>
//                                <div class='itemName'>$name</div>
//                                <div class='itemPrice'>$price &#8381</div>
//                               </div>";
                    }
                    ?>
                </div>
            </div>
            <div>

            </div>
    </form>
</div>
</body>
</html>
<script>
let name = '<?php echo $name?>';
console.log(name);
</script>