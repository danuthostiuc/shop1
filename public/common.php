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
if(!isset($link_address1)) {
    $link_address1 = 'cart.php';
}

$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, description, price FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "<table>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if(!array_search($row["id"], $_SESSION['cart'])) {
            echo "<tr><td><img src=\"\"/></td><td><ul><li>" . $row["title"] . "</li><li>" . $row["description"] . "</li><li>" . $row["price"] . "</li></ul></td><td>Add</td></tr>";
        }
    }
    echo "</table>";
} else {
    echo "0 results";
}

echo "<a href='".$link_address1."'>Go to cart</a>";

$conn->close();
