<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-02-04
 * Time: 11:26 AM
 */
require_once ( "common.php" );

function orderValidation () {
    if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty ( $_POST["name"] ) && !empty ( $_POST["contact"] ) && !empty ( $_POST["comment"] ) ) {
        $name = ( empty ( $_POST["name"] ) ) ? "default" : testInput ( $_POST["name"] );
        $contact = ( empty ( $_POST["contact"] ) ) ? "default" : testInput ( $_POST["contact"] );
        $comment = ( empty ( $_POST["comment"] ) ) ? "default" : testInput ( $_POST["comment"] );
        return array ( $name, $contact, $comment );
    }
    else {
        echo translate ( "Empty field/fields" );
        header ( "Location: cart.php" );
        die;
    }
}

if ( isset ( $_POST["checkout"] ) ) {
    list ( $name, $contact, $comment ) = orderValidation();
}

$stmt = fetchCartProducts ( $conn );

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?= CSS_PATH ?>">
    </head>
        <body>
            <h1>
                <?= translate ( "Order" ) ?>
            </h1>
            <table>
                <tr>
                    <th> <?= translate ( "Name" ) ?> </th>
                    <th> <?= translate ( "Contact details" ) ?> </th>
                    <th> <?= translate ( "Comments" ) ?> </th>
                    <th colspan="3"> <?= translate ( "Products" ) ?> </th>
                </tr>
                <tr>
                    <td rowspan="<?= count( $_SESSION["cart"] ) ?>" class="cp_img">
                        <?= translate ( $name ) ?>
                    </td>
                    <td rowspan="<?= count( $_SESSION["cart"] ) ?>" class="cp_img">
                        <?= translate ( $contact ) ?>
                    </td>
                    <td rowspan="<?= count( $_SESSION["cart"] ) ?>" class="cp_img">
                        <?= translate ( $comment ) ?>
                    </td>
                    <?php foreach ( $stmt->fetchAll() as $row ): ?>
                        <td class="cp_img">
                            <img src="img/<?=$row["id"]?>.jpg" alt="<?= translate ( "Image" ) ?>" />
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

            <a href="index.php"> <?= translate ( "Go to index" ) ?></a>
            <a href="login.php"> <?= translate ( "Log In" ) ?></a>
        </body>
</html>
