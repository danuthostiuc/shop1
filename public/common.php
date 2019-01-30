<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 1:00 PM
 */
session_start();
require_once( 'config.php' );

if( !isset( $_SESSION["cart"] ) ) {
    $_SESSION["cart"] = array();
}

function translate( $text ) {
    return $text;
}

try {
    $conn = new PDO( "mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD );
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch( PDOException $e ) {
    echo "Connection failed: " . $e->getMessage();
}
