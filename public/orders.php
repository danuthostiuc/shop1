<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-02-04
 * Time: 11:26 AM
 */
require_once("common.php");
var_dump($_SESSION["orders"]);
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?= CSS_PATH ?>">
</head>
<body>
<h1>
    <?= translate("Orders") ?>
</h1>

<?php foreach ($_SESSION["orders"] as $order): ?>
    <?php if (!$order instanceof Order): ?>
        <?php continue; ?>
    <?php endif; ?>
    <?= $order->displayOrder(); ?>
<?php endforeach; ?>

<a href="index.php"> <?= translate("Go to index") ?></a>
<a href="login.php"> <?= translate("Log In") ?></a>
</body>
</html>
