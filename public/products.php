<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-01-31
 * Time: 2:10 PM
 */
require_once("common.php");

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    die;
}

if (isset($_GET["logout"])) {
    unset($_SESSION["admin"]);
    header("Location: index.php");
    die;
}

if (isset($_GET["id"])) {
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bindValue(1, $_GET["id"], PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        $php_errormsg = translate("Error: " . $e->getMessage());
    }
}

$products = [];

try {
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $php_errormsg = translate("Error: " . $e->getMessage());
}

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    <?= translate("Products") ?>
</h1>
<table>
    <?php foreach ($products as $row): ?>
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
                <a href="product.php?id=<?= $row["id"] ?>" class=""><?= translate("Edit") ?></a>
            </td>
            <td class="cp_img">
                <a href="products.php?id=<?= $row["id"] ?>" class=""><?= translate("Delete") ?></a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="product.php"> <?= translate("Add") ?></a>
<a href="products.php?logout"> <?= translate("Logout") ?></a>
</body>
</html>
