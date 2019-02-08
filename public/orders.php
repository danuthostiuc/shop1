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

try {
    $stmt = $conn->prepare("SELECT name, contact, comment, SUM(p.price) AS 'total' FROM orders AS o
                            JOIN prod_ord AS po ON o.id = po.ord_id
                            JOIN products AS p ON po.prod_id = p.id
                            GROUP BY o.id");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $orders = $stmt->fetchAll();
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
    <?= translate("Orders") ?>
</h1>

<?php if (!empty($err_conn_database) || !empty($err_select)) : ?>
    <?= $err_conn_database ?>
    <?= $php_errormsg ?>
<?php else: ?>
    <table>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td>
                    <?= $row["name"] ?>
                </td>
                <td>
                    <?= $row["contact"] ?>
                </td>
                <td>
                    <?= $row["comment"] ?>
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
