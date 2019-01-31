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

function saveProduct ( $conn ) {
    $title = testInput ( $_POST["title"] );
    $description = testInput ( $_POST["description"] );
    $price = testInput ( $_POST["price"] );
    try {
        $stmt = $conn->prepare( "INSERT INTO products( title, description, price) VALUES ( :title, :description, :price )" );
        $stmt->execute( array ( ':title' => $title, ':description' => $description, ':price' => $price ) );
    }
    catch ( PDOException $e ) {
        echo translate ( "Error: " ) . $e->getMessage();
    }
}

if ( isset ( $_POST["save"] ) ) {
    if ( isset ( $_POST["title"] ) && isset ( $_POST["description"] ) && isset ( $_POST["price"] ) && isset ( $_POST["browse"] ) &&
        !empty ( $_POST["title"] ) && !empty ( $_POST["description"] ) && !empty ( $_POST["price"] ) && !empty ( $_POST["browse"] ) ) {
        saveProduct ( $conn );
    }
    else {
        echo translate ( "Empty field/fields" );
    }
}

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            <?= translate( "Product" ) ?>
        </h1>
        <form method="post" action=" <?= htmlspecialchars ( $_SERVER["PHP_SELF"] ) ?> ">
            <input type="text" name="title" placeholder= "<?= translate ( "Title" ) ?>" >
            <br>
            <input type="text" name="description" placeholder="<?= translate ( "Description" ) ?>">
            <br>
            <input type="number" name="price" placeholder="<?= translate ( "Price" ) ?>">
            <br>
            <input type="file" name="browse" accept="image/jpeg" value="<?= translate ( "Browse" ) ?>">
            <br>
            <a href="products.php"><?= translate ( "Products" ) ?></a>
            <input type="submit" name="save" value="<?= translate ( "Save" ) ?>">
        </form>
    </body>
</html>
