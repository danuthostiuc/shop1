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

if (isset ($_POST["add"])) {
    if (isset ($_POST["title"]) && isset ($_POST["description"]) && isset ($_POST["price"]) && isset ($_POST["browse"]) &&
        !empty ($_POST["title"]) && !empty ($_POST["description"]) && !empty ($_POST["price"]) && !empty ($_POST["browse"])) {
        $title = testInput($_POST["title"]);
        $description = testInput($_POST["description"]);
        $price = testInput($_POST["price"]);
        try {
            $stmt = $conn->prepare("INSERT INTO products( title, description, price) VALUES ( :title, :description, :price )");
            $stmt->execute(array(':title' => $title, ':description' => $description, ':price' => $price));
        } catch (PDOException $e) {
            echo translate("Error: ") . $e->getMessage();
        } finally {
            header("Location: products.php ");
        }
    } else {
        $php_errormsg = translate("Empty field/fields");
    }
}

if (isset ($_POST["id"])) {
    if (isset ($_POST["title"]) && isset ($_POST["description"]) && isset ($_POST["price"]) && isset ($_POST["browse"]) &&
        !empty ($_POST["title"]) && !empty ($_POST["description"]) && !empty ($_POST["price"]) && !empty ($_POST["browse"])) {
        $title = testInput($_POST["title"]);
        $description = testInput($_POST["description"]);
        $price = testInput($_POST["price"]);
        try {
            $sql = "UPDATE products SET title=?, description=?, price=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$title, $description, $price, $_SESSION["id"]]);
        } catch (PDOException $e) {
            echo translate("Error: ") . $e->getMessage();
        } finally {
            header("Location: products.php");
        }
        die;
    } else {
        $php_errormsg = translate("Empty field/fields");
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
<?php if (isset ($_GET["add"])): ?>
    <form method="post" action=" <?= htmlspecialchars($_SERVER["PHP_SELF"]) ?> ">
        <input type="text" name="title" placeholder="<?= translate("Title") ?>">
        <br>
        <input type="text" name="description" placeholder="<?= translate("Description") ?>">
        <br>
        <input type="number" name="price" placeholder="<?= translate("Price") ?>">
        <br>
        <input type="file" name="browse" accept="image/jpeg" value="<?= translate("Browse") ?>">
        <br>
        <a href="products.php"><?= translate("Products") ?></a>
        <input type="submit" name="add" value="<?= translate("Save") ?>">
    </form>
<?php elseif (isset ($_GET["edit"])): ?>
    <?php $_SESSION["id"] = !isset ($_SESSION["id"]) ? $_GET["id"] : "default" ?>
    <form method="post" action=" <?= htmlspecialchars($_SERVER["PHP_SELF"]) ?> ">
        <input type="text" name="title" placeholder="<?= translate("Title") ?>">
        <br>
        <input type="text" name="description" placeholder="<?= translate("Description") ?>">
        <br>
        <input type="number" name="price" placeholder="<?= translate("Price") ?>">
        <br>
        <input type="file" name="browse" accept="image/jpeg" value="<?= translate("Browse") ?>">
        <br>
        <a href="products.php"><?= translate("Products") ?></a>
        <input type="submit" name="edit" value="<?= translate("Save") ?>">
    </form>
<?php endif; ?>
</body>
</html>
