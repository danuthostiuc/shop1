<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 1:00 PM
 */
session_start();
require_once( 'config.php' );

if ( !isset( $_SESSION[ADMIN_NAME] ) ) {
    $_SESSION[ADMIN_NAME] = 0;
}

if ( !isset( $_SESSION["cart"] ) ) {
    $_SESSION["cart"] = array();
}

function translate( $text ) {
    return $text;
}

function testInput ( $data ) {
    $data = trim ( $data );
    $data = stripslashes ( $data );
    $data = strip_tags ( $data );
    return $data;
}

try {
    $conn = new PDO( "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PWD );
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch( PDOException $e ) {
    echo "Connection failed: " . $e->getMessage();
}
