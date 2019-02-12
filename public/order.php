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

$order = [];

try {
    $stmt = $conn->prepare("SELECT name, email, comment, creation_date, image, title, description, price FROM orders AS o
                            JOIN prod_ord AS po ON o.id = po.ord_id
                            JOIN products AS p ON po.prod_id = p.id
                            WHERE o.id = ?");
    $stmt->bindValue(1, $_SESSION["order_id"], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $order = $stmt->fetchAll();
} catch (PDOException $e) {
    $php_errormsg = sprintf(translate("Error: %s"), $e->getMessage());
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
<?php if (!empty($err_conn_database) && !empty($php_errormsg)) : ?>
    <?= $err_conn_database ?>
    <?= $php_errormsg ?>
<?php else: ?>
    <table>
        <tr>
            <td rowspan="<?= count($order) + 1 ?>" class="cp_img">
                <?= $order[0]["name"] ?>
            </td>
            <td rowspan="<?= count($order) + 1 ?>" class="cp_img">
                <?= $order[1]["email"] ?>
            </td>
            <td rowspan="<?= count($order) + 1 ?>" class="cp_img">
                <?= $order[2]["comment"] ?>
            </td>
            <td rowspan="<?= count($order) + 1 ?>" class="cp_img">
                <?= $order[3]["creation_date"] ?>
            </td>
        </tr>
        <?php foreach ($order as $row): ?>
            <tr>
                <td class="cp_img">
                    <img src="img/<?= $row["image"] ?>"/>
                </td>
                <td class="cp_img">
                    <?= $row["title"] ?>
                </td>
                <td class="cp_img">
                    <?= $row["description"] ?>
                </td>
                <td class="cp_img">
                    <?= $row["price"] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
