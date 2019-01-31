<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 3:48 PM
 */
require_once ( 'common.php' );

function removeFromCart() {
    if ( isset( $_GET["id"] ) && !empty( $_GET["id"] ) ) {
        if ( ($key = array_search( $_GET["id"], $_SESSION["cart"] ) ) !== false ) {
            array_splice( $_SESSION["cart"], $key, 1 );
        } else {
            echo translate ( "Failed to delete element" );
        }
    }
}

if ( isset( $_GET["remove"] ) ) {
    removeFromCart();
}

function displayCartProducts ( $conn ) {
    $items = $_SESSION["cart"];
    $place_holders = implode( ',', array_fill(0, count($items), '?' ) );

    if( !empty ( $place_holders ) ) {
        try {
            $stmt = $conn->prepare( "SELECT * FROM products WHERE id IN ( $place_holders )" );
            $stmt->execute( $items );
            $stmt->setFetchMode( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            echo translate( "Error: " ) . $e->getMessage();
        } finally {
            return $stmt;
        }
    }
    else {
        try {
            $stmt = $conn->prepare( "SELECT * FROM products WHERE id IN ( 0 )" );
            $stmt->execute();
            $stmt->setFetchMode( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            echo translate( "Error: " ) . $e->getMessage();
        } finally {
            return $stmt;
        }
    }
}

$stmt = displayCartProducts( $conn );

function sendEmail( $stmt ) {
    $to = SHOP_EMAIL;
    $subject = "Test";
    $message1 = '<html><head><link rel="stylesheet" type="text/css" href=""></head><body>';
    $message1 .= '<h1>' . translate( "Cart" ) . '</h1>';
    $message2 = '<table rules="all" style="border-color: #666;" cellpadding="10">';
    foreach ( $stmt->fetchAll() as $row ):
        $message2 .= '<tr><td class=""><img src="img/"' . $row["id"] . '".jpg"/></td>';
        $message2 .= '<td class=""><ul><li>' . translate( $row["title"] ) . '</li><li>' . translate( $row["description"] ) . '</li><li>' . translate( $row["price"] ) . '</li></ul></td>';
        $message2 .= '<td class=""><a href="cart.php?remove&id=' . $row["id"] . 'class="">' . translate( "Remove" ) . '</a></td></tr>';
    endforeach;
    $message3 = '</table></body></html>';
    $message = $message1 . $message2 . $message3;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: <webmaster@example.com>' . "\r\n";
    $headers .= 'Cc: myboss@example.com' . "\r\n";
    mail( $to, $subject, $message, $headers );
}

if ( isset( $_GET["checkout"] ) ) {
    sendEmail ( $stmt );
}

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
        <body>
            <h1>
                <?= translate( "Cart" ) ?>
            </h1>
            <table>
            <?php foreach ( $stmt->fetchAll() as $row ): ?>
            <tr>
                <td class="cp_img">
                    <img src="img/<?=$row["id"]?>.jpg"/>
                </td>
                <td class="cp_img">
                    <ul>
                        <li><?= translate( $row["title"] ) ?></li>
                        <li><?= translate( $row["description"] ) ?></li>
                        <li><?= translate( $row["price"] ) ?></li>
                    </ul>
                </td>
                <td class="cp_img">
                    <a href="cart.php?remove&id=<?= $row["id"] ?>" class=""><?= translate( "Remove" )?></a>
                </td>
            </tr>
            <?php endforeach; ?>
            </table>
            <a href="index.php"> <?= translate( "Go to index" ) ?> </a>
            <a href="cart.php?checkout"> <?= translate( "Checkout" ) ?> </a>
            <a href="login.php"> <?= translate( "Log In" ) ?></a>
        </body>
</html>
