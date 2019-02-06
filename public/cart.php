<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 3:48 PM
 */
require_once("common.php");

if (isset ($_GET["remove"])) {
    if (isset ($_GET["id"]) && !empty ($_GET["id"])) {
        if (($key = array_search($_GET["id"], $_SESSION["cart"])) !== false) {
            array_splice($_SESSION["cart"], $key, 1);
        } else {
            $err_remove = translate("Failed to delete element");
        }
    }
}

$place_holders = implode(',', array_fill(0, count($_SESSION["cart"]), '?'));

if (!empty ($place_holders)) {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ( $place_holders )");
        $stmt->execute($_SESSION["cart"]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err_select = translate("Error: " . $e->getMessage());
    }
} else {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ( 0 )");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err_select = translate("Error: " . $e->getMessage());
    }
}

if (isset ($_GET["checkout"])) {
    $to = $_POST["contact"];
    $subject = "Test";
    $message1 = '<html><head><link rel="stylesheet" type="text/css" href=""></head><body>';
    $message1 .= '<h1>' . translate("Cart") . '</h1>';
    $message2 = '<table rules="all" style="border-color: #666;" cellpadding="10">';
    foreach ($stmt->fetchAll() as $row) {
        $message2 .= '<tr><td class=""><img src="img/"' . $row["image"] . '".jpg"/></td>';
        $message2 .= '<td class=""><ul><li>' . $row["title"] . '</li><li>' . $row["description"] . '</li><li>' . $row["price"] . '</li></ul></td></tr>';
    }
    $message3 = '</table></body></html>';
    $message = $message1 . $message2 . $message3;
    $headers = "From: <webmaster@example.com>" . "\r\n";
    $headers .= "Cc: myboss@example.com" . "\r\n";
    mail($to, $subject, $message, $headers);
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
<table>
    <?php if (!empty($err_conn_database) && !empty($err_select)) : ?>
        <?= $err_conn_database, $err_select ?>
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
                    <a href="cart.php?remove&id=<?= $row["id"] ?>"><?= translate("Remove") ?></a>
                    <?php if (!empty($err_remove)) : ?>
                        <?= $err_remove ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<br>
<form method="post" action="order.php">
    <input type="text" name="name" placeholder="<?= translate("Name") ?>">
    <br>
    <input type="text" name="contact" placeholder="<?= translate("Contact details") ?>">
    <br>
    <input type="text" name="comment" placeholder="<?= translate("Comments") ?>">
    <br>
    <input type="submit" name="checkout" value="<?= translate("Checkout") ?>">
</form>

<a href="index.php"> <?= translate("Go to index") ?></a>
<a href="login.php"> <?= translate("Log In") ?></a>
</body>
</html>
