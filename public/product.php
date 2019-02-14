<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-01-31
 * Time: 2:10 PM
 */
require_once("common.php");

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    die;
}

if (isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["price"])) {
    if (!empty($_POST["title"]) && !empty($_POST["description"]) && !empty($_POST["price"])) {

        $title = testInput($_POST["title"]);
        $description = testInput($_POST["description"]);
        $price = testInput($_POST["price"]);

        if (!isset($_GET["id"]) && isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
            $unique = uniqid();
            $name = $_FILES["image"]["name"];
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $extensions_arr = array("png", "gif", "jpeg", "jpg");
            if (in_array($image_file_type, $extensions_arr)) {
                try {
                    $stmt = $conn->prepare("INSERT INTO products(title, description, price, image) 
                                    VALUES (:title, :description, :price, :image)");
                    $stmt->execute(array(':title' => $title, ':description' => $description, ':price' => $price,
                        ':image' => $unique . "." . $image_file_type));
                    if (file_exists($target_dir . $name)) {
                        rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                    } else {
                        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $name);
                        rename($target_dir . $name, $target_dir . $unique . "." . $image_file_type);
                    }
                } catch (PDOException $e) {
                    $php_errormsg = sprintf(translate("Error: %s") . $e->getMessage());
                } finally {
                    header("Location: products.php");
                    die;
                }
            } else {
                $php_errormsg = translate("Invalid file format!");
            }
        }

        if (isset($_GET["id"]) && !isset($_FILES["image"]["name"]) && empty($_FILES["image"]["name"])) {
            try {
                $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=? WHERE id=?");
                $stmt->execute([$title, $description, $price, $_GET["id"]]);
            } catch (PDOException $e) {
                $php_errormsg = sprintf(translate("Error: %s"), $e->getMessage());
            }
            finally {
                header("Location: products.php");
                die;
            }
        }
    } else {
        $php_errormsg = translate("Complete all fields!");
    }
}

$product = [];

if (isset($_GET["id"])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->bindValue(1, $_GET["id"], PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $product = $stmt->fetchAll();
    } catch (PDOException $e) {
        $php_errormsg = sprintf(translate("Error: %s"), $e->getMessage());
    }
}

?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    <?= translate("Product") ?>
</h1>

<?php if (!empty($php_errormsg)): ?>
    <?= $php_errormsg ?>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title"
        <?php if (isset($_GET["id"])): ?>
            value="<?= $product[0]["title"] ?>"
        <?php else: ?>
            value="<?= isset($_POST["title"]) ? htmlentities($_POST["title"]) : "" ?>"
        <?php endif; ?>
           placeholder="<?= translate("Title") ?>">
    <br>
    <input type="text" name="description"
        <?php if (isset($_GET["id"])): ?>
            value="<?= $product[0]["description"] ?>"
        <?php else: ?>
            value="<?= isset($_POST["description"]) ? htmlentities($_POST["description"]) : "" ?>"
        <?php endif; ?> placeholder="<?= translate("Description") ?>">
    <br>
    <input type="number" name="price"
        <?php if (isset($_GET["id"])): ?>
            value="<?= $product[0]["price"] ?>"
        <?php else: ?>
            value="<?= isset($_POST["price"]) ? htmlentities($_POST["price"]) : "" ?>"
        <?php endif; ?> placeholder="<?= translate("Price") ?>">
    <br>
    <?php if (!isset($_GET["id"])): ?>
        <input type="file" name="image" accept=".png, .gif, .jpeg, .jpg">
    <?php endif; ?>
    <br>
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="save" value="<?= translate("Save") ?>">
</form>

</body>
</html>
