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

if (isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["price"]) && isset($_FILES["image"]["name"]) && !isset($_GET["id"])) {
    if (!empty($_POST["title"]) && !empty($_POST["description"]) && !empty($_POST["price"]) && !empty($_FILES["image"]["name"])) {
        $title = testInput($_POST["title"]);
        $description = testInput($_POST["description"]);
        $price = testInput($_POST["price"]);
        try {
            $stmt = $conn->prepare("INSERT INTO products(title, description, price, image) VALUES (:title, :description, :price, :image)");
            $stmt->execute(array(':title' => $title, ':description' => $description, ':price' => $price, ':image' => $_FILES["image"]["name"]));
        } catch (PDOException $e) {
            $php_errormsg = sprintf(translate("Error: %s") . $e->getMessage());
        } finally {
            header("Location: products.php ");
            die;
        }
    } else {
        $php_errormsg = translate("Complete all fields!");
    }
}

if (isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["price"]) && isset($_FILES["image"]["name"]) && isset($_GET["id"])) {
    if (!empty($_POST["title"]) && !empty($_POST["description"]) && !empty($_POST["price"]) && !empty($_FILES["image"]["name"])) {
        $title = testInput($_POST["title"]);
        $description = testInput($_POST["description"]);
        $price = testInput($_POST["price"]);
        try {
            $stmt = $conn->prepare("UPDATE products SET title=?, description=?, price=?, image=? WHERE id=?");
            $stmt->execute([$title, $description, $price, $_FILES["image"]["name"], $_GET["id"]]);
        } catch (PDOException $e) {
            $php_errormsg = sprintf(translate("Error: %s"), $e->getMessage());
        } finally {
            header("Location: products.php");
            die;
        }
    } else {
        $php_errormsg = translate("Complete all fields!");
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
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="<?= translate("Title") ?>">
    <br>
    <input type="text" name="description" placeholder="<?= translate("Description") ?>">
    <br>
    <input type="number" name="price" placeholder="<?= translate("Price") ?>">
    <br>
    <input type="file" name="image" accept="image/*" value="<?= translate("Browse") ?>">
    <br>
    <a href="products.php"><?= translate("Products") ?></a>
    <input type="submit" name="save" value="<?= translate("Save") ?>">
</form>
<?php if (!empty($php_errormsg)): ?>
    <?= $php_errormsg ?>
<?php endif; ?>
</body>
</html>
