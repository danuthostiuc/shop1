<?php
require_once('common.php');
session_start();

if(!isset($link_address1)) {
    $link_address1 = 'cart.php';
}

//$result = $conn->query("SELECT * FROM products WHERE id NOT IN ($in)");
var_dump($_SESSION["cart"]);
$params = $_SESSION["cart"];
$place_holders = implode(',', array_fill(0, count($params), '?'));
var_dump($place_holders);
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute($params);
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
var_dump($result);
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            <?php echo translate("Products") ?>
        </h1>
        <table>
            <?php foreach ($stmt->fetchAll() as $row) { ?>
                <tr>
                    <td class="cp_img">
                        <img src="img/<?=$row["id"]?>.jpg"/>
                    </td>
                    <td class="cp_img">
                        <ul>
                            <li><?=$row["title"]?></li>
                            <li><?=$row["description"]?></li>
                            <li><?=$row["price"]?></li>
                        </ul>
                    </td>
                    <td class="cp_img">
                        <a href="" class=""><?php echo translate("Add")?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href=<?=$link_address1?>> <?php echo translate("Go to cart") ?></a>
        <?php
        var_dump($_SESSION["cart"]);
        ?>
    </body>
</html>
