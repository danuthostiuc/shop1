<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 1:00 PM
 */
session_start();
require_once('config.php');

if(!isset($_SESSION['cart'])){
    //If it doesn't, create an empty array.
    $_SESSION['cart'] = array();
}

function translate($text){
    return $text;
}

/*$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/

try {
    $conn = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}
