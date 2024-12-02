<?php
require_once("conf.php");
global $yhendus;

if (isset($_REQUEST["login"])) {
    if ($_REQUEST["login"] == "root" && $_REQUEST["password"] == "12341") {
        header("Location: konkursAdmin.php");
        exit;
    }
}

$paring = $yhendus->prepare("SELECT Id, Username, Passwordd FROM users");
$paring->bind_result($Id, $Username, $Password);
$paring->execute();

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="loginStyle.css">
</head>
<body>
<h1 style="text-align: -webkit-center">Login</h1>
<form action="?" method="POST">
    <label for="username">Username</label>
    <input type="text" name="login" id="username" required>
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <input type="submit" value="OK">
</form>
</body>
</html>
