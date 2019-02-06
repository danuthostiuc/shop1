<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2019-01-28
 * Time: 1:00 PM
 */
session_start();
require_once("config.php");

$GLOBALS["orders"] = [];

if (!isset ($_SESSION[ADMIN_NAME])) {
    $_SESSION[ADMIN_NAME] = 0;
}

if (!isset ($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (!isset ($_SESSION["orders"])) {
    $_SESSION["orders"] = [];
}

function translate($text)
{
    return $text;
}

function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    return $data;
}

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PWD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo translate("Connection failed: ") . $e->getMessage();
}

function fetchCartProducts($conn)
{
    $items = $_SESSION["cart"];
    $place_holders = implode(',', array_fill(0, count($items), '?'));

    if (!empty ($place_holders)) {
        try {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ( $place_holders )");
            $stmt->execute($items);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo translate("Error: ") . $e->getMessage();
        } finally {
            return $stmt;
        }
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ( 0 )");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo translate("Error: ") . $e->getMessage();
        } finally {
            return $stmt;
        }
    }
}


class Order
{

    private $name;
    private $contact;
    private $comment;
    private $stmt;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function setStmt($stmt)
    {
        $this->stmt = $stmt;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getStmt()
    {
        return $this->stmt;
    }

    public function displayOrder()
    {
        $message1 = '<table><tr><th>' . translate("Name") . '</th><th>' . translate("Contact details") . '</th>';
        $message1 .= '<th>' . translate("Comments") . '</th><th colspan="3">' . translate("Products") . '</th></tr>';
        $message1 .= '<tr><td rowspan="' . count($_SESSION["cart"]) . '" class="cp_img">' . translate($this->name) . '</td>';
        $message1 .= '<td rowspan="' . count($_SESSION["cart"]) . '" class="cp_img"> ' . translate($this->contact) . '</td>';
        $message2 = '<td rowspan="' . count($_SESSION["cart"]) . '" class="cp_img">' . translate($this->comment) . '</td>';
        foreach ($this->getStmt()->fetchAll() as $row):
            $message2 .= '<td class="cp_img"><img src="img/' . $row["id"] . '.jpg" alt="' . translate("Image") . '" /></td>';
            $message2 .= '<td class="cp_img"><ul><li>' . translate($row["title"]) . '</li><li>' . translate($row["description"]) . '</li><li>' . translate($row["price"]) . '</li></ul></td>';
            $message2 .= '<td class="cp_img"><a href="cart.php?remove&id=' . $row["id"] . '" class="">' . translate("Remove") . '</a></td></tr>';
        endforeach;
        $message2 .= '</table><br>';
        return $message1 . $message2;
    }
}

/*$collection = new Collection();*/
