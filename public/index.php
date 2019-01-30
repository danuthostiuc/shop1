<?php
require_once( 'common.php' );

function addToCart() {
    if (isset($_GET['id']) & !empty($_GET['id'])) {
        $items = $_GET['id'];
        array_push($_SESSION['cart'], $items);
    }
}

if (isset($_GET['add'])) {
    addToCart();
}

if( count( $_SESSION["cart"] ) > 0 ) {
    $place_holders = implode( ',', array_fill( 0, count($_SESSION["cart"] ), '?') );
    echo $place_holders;
    try {
        $stmt = $conn->prepare( "SELECT * FROM products WHERE id NOT IN ( $place_holders )" );
        $stmt->execute($_SESSION["cart"]);
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}else {
    try {
        $stmt = $conn->prepare( "SELECT * FROM products" );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            <?= translate( "Products" ) ?>
        </h1>
        <table>
            <?php foreach ( $stmt->fetchAll() as $row ): ?>
                <tr>
                    <td class="cp_img">
                        <img src="img/<?= $row["id"] ?>.jpg"/>
                    </td>
                    <td class="cp_img">
                        <ul>
                            <li><?= translate( $row["title"] ) ?></li>
                            <li><?= translate( $row["description"] ) ?></li>
                            <li><?= translate( $row["price"] ) ?></li>
                        </ul>
                    </td>
                    <td class="cp_img">
                        <a href="index.php?add&id=<?= $row["id"] ?>" class=""><?= translate( "Add" ) ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="cart.php"> <?= translate( "Go to cart" ) ?></a>
        <?php var_dump( $_SESSION["cart"] ); ?>
    </body>
</html>
