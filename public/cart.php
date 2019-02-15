<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 3:48 PM
 */
require_once("common.php");

if (isset ($_GET["id"])) {
    if (($key = array_search($_GET["id"], $_SESSION["cart"])) !== false) {
        array_splice($_SESSION["cart"], $key, 1);
    } else {
        $err_remove = translate("Failed to delete element");
    }
}

$products = [];

if (count($_SESSION["cart"]) > 0) {
    try {
        $place_holders = implode(',', array_fill(0, count($_SESSION["cart"]), '?'));
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($place_holders)");
        $stmt->execute($_SESSION["cart"]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $products = $stmt->fetchAll();
    } catch (PDOException $e) {
        $err_select = sprintf(translate("Error: %s"), $e->getMessage());
    }
}

if (isset($_POST["name"]) && isset($_POST["contact"]) && isset($_POST["comment"])) {
    if (!empty($_POST["name"]) && !empty($_POST["contact"]) && !empty($_POST["comment"])) {
        $protocol = $_SERVER["HTTPS"] === "on" ? "https://" : "http://";
        $name = testInput($_POST["name"]);
        $to = testInput($_POST["contact"]);
        $comment = testInput($_POST["comment"]);
        $subject = translate("Test");
        $message =
            '<html>' .
                '<body>' .
                    '<h1>' .
                         translate("Cart") .
                    '</h1>' .
                    '<table>';
                    foreach ($products as $row) {
                        $message .= '<tr>' .
                                        '<td>' .
                                            '<img src="' . $protocol . $_SERVER["HTTP_HOST"] . '/img/' . $row["image"] .
                                            '" width="600" border="0" style="display: block; />' .
                                        '</td>' .
                                        '<td>' .
                                            '<ul>' .
                                                '<li>' .
                                                    $row["title"] .
                                                '</li>' .
                                                '<li>' .
                                                    $row["description"] .
                                                '</li>' .
                                                '<li>' .
                                                    $row["price"] .
                                                '</li>' .
                                            '</ul>' .
                                        '</td>' .
                                    '</tr>';
                    }
        $message .= '</table>'.
                '</body>' .
            '</html>';
        $headers = "MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $message, $headers);

        try {
            $stmt = $conn->prepare("INSERT INTO orders(name, email, comment) VALUES (:name, :email, :comment)");
            $stmt->execute(array(':name' => $name, ':email' => $to, ':comment' => $comment));
            $last_insert_id = $conn->lastInsertId();
            $stmt = $conn->prepare("INSERT INTO order_product(product_id, order_id) VALUES (:product_id, :order_id)");
            foreach ($_SESSION["cart"] as $row) {
                $stmt->execute(array('product_id' => $row, 'order_id' => $last_insert_id));
            }
        } catch (PDOException $e) {
            $php_errormsg = sprintf(translate("Error: %s"), $e->getMessage());
        }

        unset($_SESSION["cart"]);
        header("Location: index.php");
        die;
    } else {
        $php_errormsg = translate("Complete all fields");
    }
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    <?= translate("Cart") ?>
</h1>
<?php if (!empty($err_conn_database) || !empty($err_select)) : ?>
    <?= $err_conn_database ?>
    <?= $err_select ?>
<?php else: ?>
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
                    <a href="cart.php?id=<?= $row["id"] ?>"><?= translate("Remove") ?></a>
                    <?php if (!empty($err_remove)) : ?>
                        <?= $err_remove ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if (!empty($php_errormsg)): ?>
    <?= $php_errormsg ?>
<?php endif; ?>

<br>
<form method="post">
    <input type="text" name="name" value="<?= isset($_POST["name"]) ? htmlentities($_POST["name"]) : "" ?>"
           placeholder="<?= translate("Name") ?>">
    <br>
    <input type="text" name="contact" value="<?= isset($_POST["contact"]) ? htmlentities($_POST["contact"]) : ""?>"
           placeholder="<?= translate("Contact details") ?>">
    <br>
    <input type="text" name="comment" value="<?= isset($_POST["comment"]) ? htmlentities($_POST["comment"]) : ""?>"
           placeholder="<?= translate("Comments") ?>">
    <br>
    <input type="submit" name="checkout" value="<?= translate("Checkout") ?>">
</form>
<a href="index.php"><?= translate("Go to index") ?></a>
</body>
</html>
