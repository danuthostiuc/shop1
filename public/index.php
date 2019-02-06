<?php
require_once("common.php");

if (isset ($_GET["id"])) {
    array_push($_SESSION["cart"], $_GET["id"]);
}

if (count($_SESSION["cart"]) > 0) {
    $place_holders = implode(',', array_fill(0, count($_SESSION["cart"]), '?'));
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id NOT IN ($place_holders)");
        $stmt->execute($_SESSION["cart"]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err = translate("Error: " . $e->getMessage());
    }
} else {
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err = translate("Error: " . $e->getMessage());
    }
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    <?= translate("Index") ?>
</h1>
<table>
    <?php if (!empty($err_conn_database) && !empty($err)): ?>
        <?= $err_conn_database, $err ?>
    <?php else: ?>
        <?php foreach ($stmt->fetchAll() as $row): ?>
            <tr>
                <td class="cp_img">
                    <img src="img/<?= $row["image"] ?>"/>
                </td>
                <td class="cp_img">
                    <ul>
                        <li><?= $row["title"] ?></li>
                        <li><?= $row["description"] ?></li>
                        <li><?= $row["price"] ?></li>
                    </ul>
                </td>
                <td class="cp_img">
                    <a href="index.php?id=<?= $row["id"] ?>"><?= translate("Add") ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<a href="cart.php"> <?= translate("Go to cart") ?></a>
</body>
</html>
