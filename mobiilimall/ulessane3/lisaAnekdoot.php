<?php
require_once("confZone.php");
global $yhendus;

if (isset($_REQUEST["nimetus"]) && isset($_REQUEST["kuupaev"]) && isset($_REQUEST["kirjeldus"])
    && !empty($_REQUEST["nimetus"]) && !empty($_REQUEST["kuupaev"]) && !empty($_REQUEST["kirjeldus"])) {

    // Подготовка и выполнение запроса для добавления анекдота
    $paring = $yhendus->prepare("INSERT INTO anekdoot (Nimetus, Kuupaev, Kirjeldus) VALUES (?, ?, ?)");
    $paring->bind_param("sss", $_REQUEST["nimetus"], $_REQUEST["kuupaev"], $_REQUEST["kirjeldus"]);
    $paring->execute();
    $paring->close();

    echo "<p>Anekdoot edukalt lisatud!</p>";
}
?>

<link rel="stylesheet" href="anecdoteStyle.css">

<form action="?" method="post">
    <label for="nimetus">Nalja pealkiri</label>
    <input type="text" id="nimetus" name="nimetus" placeholder="Nalja pealkiri" required>

    <label for="kuupaev">Kuupäev</label>
    <input type="date" id="kuupaev" name="kuupaev" required>

    <label for="kirjeldus">Nalja kirjeldus</label>
    <textarea id="kirjeldus" name="kirjeldus" placeholder="Nalja kirjeldus" required></textarea>

    <input type="submit" value="Lisa anekdoot">
    <br>




</form>

