<?php
/**
 * Created by PhpStorm.
 * User: "Danut"
 * Date: 2019-01-31
 * Time: 12:37 PM
 */
require_once("common.php");

if (isset($_SESSION["admin"])) {
    header("Location: products.php");
    die;
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = testInput($_POST["username"]);
        $password = testInput($_POST["password"]);
        if ($username == ADMIN_NAME && $password == ADMIN_PWD) {
            $_SESSION["admin"] = true;
            header("Location: products.php");
            die;
        } else {
            $php_errormsg = translate("Wrong username or password!");
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
    <?= translate("Log In") ?>
</h1>
<form method="post">
    <input type="text" name="username" placeholder="<?= translate("Username") ?>">
    <br>
    <input type="password" name="password" placeholder="<?= translate("Password") ?>">
    <br>
    <input type="submit" name="submit" value="<?= translate("Login") ?>">
    <?php if (!empty($php_errormsg)): ?>
        <?= $php_errormsg ?>
    <?php endif; ?>
</form>
</body>
</html>
