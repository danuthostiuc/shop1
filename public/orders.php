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
        <link rel="stylesheet" type="text/css" href="<?= CSS_PATH ?>">
    </head>
    <body>
        <h1>
            <?= translate ( "Orders" ) ?>
        </h1>

        <?php foreach ( $_SESSION["orders"] as $it ): ?>
        <?php if ( !$it instanceof order ): ?>
        <?php continue; ?>
        <?php endif; ?>
        <?= $it->displayOrder (); ?>
        <?php endforeach; ?>

        <a href="index.php"> <?= translate ( "Go to index" ) ?></a>
        <a href="login.php"> <?= translate ( "Log In" ) ?></a>
    </body>
</html>
