<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-02-04
 * Time: 11:26 AM
 */
require_once("common.php");

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    die;
}

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    <?= translate("Order") ?>
</h1>
<table>
    <?php if (!empty($err_conn_database) && !empty($err_select)) : ?>
        <?= $err_conn_database, $err_select ?>
    <?php else: ?>
        <tr>
            <th> <?= translate("Name") ?> </th>
            <th> <?= translate("Contact details") ?> </th>
            <th> <?= translate("Comments") ?> </th>
            <th colspan="3"> <?= translate("Products") ?> </th>
        </tr>
        <tr>
        <td rowspan="<?= count($_SESSION["cart"]) ?>" class="cp_img">
            <?= translate($name) ?>
        </td>
        <td rowspan="<?= count($_SESSION["cart"]) ?>" class="cp_img">
            <?= translate($contact) ?>
        </td>
        <td rowspan="<?= count($_SESSION["cart"]) ?>" class="cp_img">
            <?= translate($comment) ?>
        </td>
        <?php foreach ($stmt->fetchAll() as $row): ?>
            <td class="cp_img">
                <img src="/img/<?= $row["image"] ?>"/>
            </td>
            <td class="cp_img">
                <ul>
                    <li><?= $row["title"] ?></li>
                    <li><?= $row["description"] ?></li>
                    <li><?= $row["price"] ?></li>
                </ul>
            </td>
            <td class="cp_img">
                <a href="cart.php?id=<?= $row["id"] ?>"><?= translate("Remove") ?></a>
                <?php if (!empty($err_remove)) : ?>
                    <?= $err_remove ?>
                <?php endif; ?>
            </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<a href="index.php"> <?= translate("Go to index") ?></a>
</body>
</html>
