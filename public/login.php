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
<?php if (!empty($php_errormsg)): ?>
    <?= $php_errormsg ?>
<?php endif; ?>
<form method="post">
    <input type="text" name="username" <?php if (isset($_POST["username"])): ?>
                                    value="<?= htmlentities($_POST["username"]); ?>"
                                       <?php endif; ?> placeholder="<?= translate("Username") ?>">
    <br>
    <input type="password" name="password" <?php if (isset($_POST["username"])): ?>
                                        value="<?= htmlentities($_POST["password"]); ?>"
                                           <?php endif; ?> placeholder="<?= translate("Password") ?>">
    <br>
    <input type="submit" name="submit" value="<?= translate("Login") ?>">
</form>
</body>
</html>
