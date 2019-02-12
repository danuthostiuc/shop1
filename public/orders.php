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

$orders = [];

try {
    $stmt = $conn->prepare("SELECT o.*, SUM(p.price) AS total FROM orders AS o
                            JOIN order_product ON o.id = order_product.order_id
                            JOIN products AS p ON order_product.product_id = p.id
                            GROUP BY o.id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $orders = $stmt->fetchAll();
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
    <?= translate("Orders") ?>
</h1>
<?php if (!empty($err_conn_database) || !empty($php_errormsg)) : ?>
    <?= $err_conn_database ?>
    <?= $php_errormsg ?>
<?php else: ?>
    <table>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td>
                    <a href="order.php?id=<?= $row["id"] ?>"><?= $row["id"] ?></a>
                </td>
                <td>
                    <?= $row["name"] ?>
                </td>
                <td>
                    <?= $row["email"] ?>
                </td>
                <td>
                    <?= $row["comment"] ?>
                </td>
                <td>
                    <?= $row["creation_date"] ?>
                </td>
                <td>
                    <?= $row["total"] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
