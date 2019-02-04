<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-02-04
 * Time: 11:26 AM
 */
require_once ( "common.php" );


?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
        <body>
            <h1>
                <?= translate ( "Cart" ) ?>
            </h1>
            <table>
            <?php foreach ( $stmt->fetchAll() as $row ): ?>
            <tr>
                <td class="cp_img">
                    <img src="img/<?=$row["id"]?>.jpg" alt="" />
                </td>
                <td class="cp_img">
                    <ul>
                        <li><?= translate ( $row["title"] ) ?></li>
                        <li><?= translate ( $row["description"] ) ?></li>
                        <li><?= translate ( $row["price"] ) ?></li>
                    </ul>
                </td>
                <td class="cp_img">
                    <a href="cart.php?remove&id=<?= $row["id"] ?>" class=""><?= translate( "Remove" ) ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
            </table>
            <br>
            <form method="post" action="order.php">
                <input type="text" name="name" placeholder= "<?= translate ( "Name" ) ?>">
                <br>
                <input type="text" name="contact" placeholder= "<?= translate ( "Contact details" ) ?>">
                <br>
                <input type="text" name="comments" placeholder= "<?= translate ( "Comments" ) ?>">
                <br>
                <input type="submit" name="checkout" value="<?= translate ( "Checkout" ) ?>">
            </form>

            <a href="index.php"> <?= translate ( "Go to index" ) ?></a>
            <a href="login.php"> <?= translate ( "Log In" ) ?></a>
        </body>
</html>
