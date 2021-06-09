<?php
include "backend/db/pdo.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="../../js/entities/cases.js"></script>
    <script src="../scripts/index.js"></script>
</head>
<body>

<form enctype="multipart/form-data" action="../../api/v.1/add_item.php" method="post">
    <p>item`s name <br>
    <input type="text" name="name" placeholder="M4A4 Howl">
    </p>
    <p>item`s rare<br>
    <input type="radio" name="rare">Blue
    <input type="radio" name="rare">Purple
    <input type="radio" name="rare">pink
    <input type="radio" name="rare">red
    <input type="radio" name="rare">yellow
    </p>
    <p>case`s picture<br>
        <input type="file" accept="image/*" name="src">
    </p>
    <p>item`s price<br>
        <input type="text" name="price" placeholder="500">
    </p>
    <p>
        <input type="submit" value="Create">
    </p>
</form>

</body>
</html>