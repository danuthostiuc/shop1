<?php
require_once('common.php');

if(!isset($link_address1)) {
    $link_address1 = 'cart.php';
}

$params = $_SESSION["cart"];

if(count($params) > 0){
    $place_holders = implode(',', array_fill(0, count($params), '?'));
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id NOT IN ($place_holders)");
        $stmt->execute($params);
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}else{
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute($params);
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <h1>
            <?php echo translate("Products") ?>
        </h1>
        <table>
            <?php foreach ($stmt->fetchAll() as $row) { ?>
                <tr>
                    <td class="cp_img">
                        <img src="img/<?=$row["id"]?>.jpg"/>
                    </td>
                    <td class="cp_img">
                        <ul>
                            <li><?=$row["title"]?></li>
                            <li><?=$row["description"]?></li>
                            <li><?=$row["price"]?></li>
                        </ul>
                    </td>
                    <td class="cp_img">
                        <a href="" class=""><?php echo translate("Add")?></a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href=<?=$link_address1?>> <?php echo translate("Go to cart") ?></a>
        <?php
        $conn = null;
        ?>
    </body>
</html>
