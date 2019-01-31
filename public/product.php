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
            <input type="url" name="image" placeholder="<?= translate ( "Image" ) ?>">
            <input type="button" name="browse" value="<?= translate ( "Browse" ) ?>">
            <br>
            <a href="products.php"><?= translate ( "Products" ) ?></a>
            <input type="button" name="save" value="<?= translate ( "Save" ) ?>">
        </form>
    </body>
</html>
