<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-01-31
 * Time: 12:37 PM
 */
require_once ("common.php");

function testInput ( $data ) {
    $data = trim ( $data );
    $data = stripslashes ( $data );
    $data = htmlspecialchars ( $data );
    $data = strip_tags ( $data );
    return $data;
}

function validation () {
    if ( $_SERVER["REQUEST_METHOD"] == "POST" && !empty( $_POST["username"] ) && !empty( $_POST["password"] ) ) {
        $username = testInput ( $_POST["username"] );
        $password = testInput ( $_POST["password"] );
        if ( $username == ADMIN_NAME && $password == ADMIN_PWD ) {
            $_SESSION[ADMIN_NAME] = 1;
            header( "Location: products.php" );
            die;
        }
        else {
            echo "Wrong username or password";
        }
    }
    else {
        echo "Empty field";
    }
}

if ( isset ( $_POST["Submit"] ) ) {
    validation();
}

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            <?= translate ( "Log In" ) ?>
        </h1>
        <form method="post" action="<?= htmlspecialchars( $_SERVER["PHP_SELF"] ) ?>">
            <input type="text" name="username" placeholder= <?= translate ( "Username" ) ?> >
            <br>
            <input type="password" name="password" placeholder= <?= translate ( "Password" ) ?> >
            <br>
            <input type="submit" name="Submit" value= <?= translate ( "Login" ) ?> >
        </form>
    </body>
</html>
