<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-01-31
 * Time: 2:10 PM
 */
require_once ( "common.php" );

if ( !$_SESSION[ADMIN_NAME] ) {
    header( "Location: login.php" );
    die;
}
else {
    header( 'Content-Type: text/html; charset=utf-8' );
}

if ( isset( $_GET["logout"] ) ) {
    $_SESSION[ADMIN_NAME] = 0;
    header( "Location: index.php" );
    die;
}

if ( isset( $_GET["add"] ) ) {
    header( "Location: product.php" );
    die;
}

if ( isset( $_GET["edit"] ) ) {
    header( "Location: product.php" );
    die;
}

function deleteProduct ( $conn ) {
    try {
        $stmt = $conn->prepare( "DELETE FROM products WHERE id=?" );
        $stmt->bindValue( 1, $_GET["id"], PDO::PARAM_INT );
        $stmt->execute();
    }
    catch ( PDOException $e ) {
        echo translate ( "Error: " ) . $e->getMessage();
    }
}

if ( isset( $_GET["delete"] ) ) {
    deleteProduct ( $conn );
    header( "Location: products.php" );
    die;
}

function displayAllProducts ( $conn ) {
    try {
        $stmt = $conn->prepare( "SELECT * FROM products" );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
    }
    catch( PDOException $e ) {
        echo translate ( "Error: " ) . $e->getMessage();
    }
    finally {
        return $stmt;
    }
}

$stmt = displayAllProducts( $conn );

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="<?= CSS_PATH ?>">
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
                    <a href="product.php?edit&id=<?= $row["id"] ?>" class=""><?= translate( "Edit" ) ?></a>
                </td>
                <td class="cp_img">
                    <a href="products.php?delete&id=<?= $row["id"] ?>" class=""><?= translate( "Delete" ) ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
        <a href="product.php?add"> <?= translate( "Add" ) ?></a>
        <a href="index.php?logout"> <?= translate( "Logout" ) ?></a>
    </body>
</html>
