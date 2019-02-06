<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-02-04
 * Time: 11:26 AM
 */
require_once("common.php");

if (!empty ($_POST["name"]) && !empty ($_POST["contact"]) && !empty ($_POST["comment"])) {
    $name = testInput($_POST["name"]);
    $contact = testInput($_POST["contact"]);
    $comment = testInput($_POST["comment"]);
} else {
    header("Location: cart.php");
    die;
}

$place_holders = implode(',', array_fill(0, count($_SESSION["cart"]), '?'));

if (!empty ($place_holders)) {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($place_holders)");
        $stmt->execute($_SESSION["cart"]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err_select = translate("Error: " . $e->getMessage());
    }
} else {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id IN (0)");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $err_select = translate("Error: " . $e->getMessage());
    }
}

if (isset ($_POST["checkout"])) {
    $to = $_POST["contact"];
    $subject = "Test";
    $message = '<html><head><link rel="stylesheet" type="text/css" href="https://shop1.local.ro/css/style.css"></head><body>';
    $message .= '<h1>' . translate("Cart") . '</h1>';
    $message .= '<table>';
    foreach ($stmt->fetchAll() as $row) {
        $message .= '<tr><td class="cp_img"><img src="https://shop1.local.ro/img/' . $row["image"] . '" width="600" border="0" style="display: block; /></td>';
        $message .= '<td class="cp_img"><ul><li>' . $row["title"] . '</li><li>' . $row["description"] . '</li><li>' . $row["price"] . '</li></ul></td></tr>';
    }
    $message .= '</table></body></html>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: <webmaster@example.com>" . "\r\n";
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
                <img src="https://shop1.local.ro/img/<?= $row["image"] ?>"/>
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
