<?php
session_start();

$admin_user = "admin";
$admin_password = "TARpv2023";  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $nimi = $_POST['nimi'] ?? '';
    $perekonnanimi = $_POST['perekonnanimi'] ?? '';
    $password = $_POST['password'] ?? '';

    // Проверка пароля
    $correctPassword = 'yourPassword'; // Замените на свой пароль

    if ($password === $correctPassword && !empty($nimi) && !empty($perekonnanimi)) {
        $deleted = kustutaOpilane($nimi, $perekonnanimi);
        echo $deleted ? 'success' : 'error';
    } else {
        echo 'error'; // Возвращаем ошибку, если пароль неверный
    }
    exit;
}

?>

<form method="POST" action="">
    <label for="username">Логин:</label>
    <input type="text" name="username" required><br>
    <label for="password">Пароль:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Войти">
</form>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>
