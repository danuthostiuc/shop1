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

$object = new order();
$object->setName ( $name );
$object->setContact ( $contact );
$object->setComment ( $comment );
$object->setStmt ( fetchCartProducts ( $conn ) );
array_push( $_SESSION["orders"], $object );

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?= CSS_PATH ?>">
    </head>
        <body>
            <h1>
                <?= translate ( "Order" ) ?>
            </h1>

            <?= $object->displayOrder () ?>

            <a href="index.php"> <?= translate ( "Go to index" ) ?></a>
            <a href="login.php"> <?= translate ( "Log In" ) ?></a>
        </body>
</html>
