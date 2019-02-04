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

function fetchCartProducts ( $conn ) {
    $items = $_SESSION["cart"];
    $place_holders = implode ( ',', array_fill (0, count($items), '?' ) );

    if( !empty ( $place_holders ) ) {
        try {
            $stmt = $conn->prepare( "SELECT * FROM products WHERE id IN ( $place_holders )" );
            $stmt->execute( $items );
            $stmt->setFetchMode( PDO::FETCH_ASSOC );
        } catch ( PDOException $e ) {
            echo translate ( "Error: " ) . $e->getMessage();
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
