<?php
require_once('common.php');
session_start();

if(!isset($link_address1)) {
    $link_address1 = 'cart.php';
}


$sql = "SELECT id, title, description, price FROM products";
$result = $conn->query($sql);

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            Produse
        </h1>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php if(!array_search($row["id"], $_SESSION['cart'])): ?>
                            <tr>
                                <td class="cp_img">
                                    <img src="img/<?=$row["id"]?>.jpg"/>
                                </td>
                                <td>
                                    <ul>
                                        <li><?=$row["title"]?></li>
                                        <li><?=$row["description"]?></li>
                                        <li><?=$row["price"]?></li></ul></td>
                                <td>
                                    Add
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
            <?php endif; ?>
            <?php $conn->close(); ?>
                </table>
        <a href=<?=$link_address1?>>Go to cart</a>
    </body>
</html>
