<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 3:48 PM
 */
require_once ( 'common.php' );
$items = $_SESSION["cart"];
$place_holders = implode( ',', array_fill(0, count($items), '?' ) );

try {
    $stmt = $conn->prepare( "SELECT * FROM products WHERE id IN ( $place_holders )" );
    $stmt->execute( $items );
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
}
catch( PDOException $e ) {
    echo "Error: " . $e->getMessage();
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
                <a href="" class=""><?= translate( "Remove" )?></a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="index.php"> <?= translate( "Go to index" ) ?></a>
<button type="submit"> <?= translate( "Checkout" ) ?> </button>
</body>
</html>
