<?php
require_once("confZone.php");

global $yhendus;

$nimi = $telefon = $pilt = $synniaeg = ""; 

// Проверяем, был ли передан параметр edit
if (isset($_GET["edit"])) {
    $id = $_GET["edit"];  // Получаем ID участника

    // Запрашиваем данные участника по ID
    $kask = $yhendus->prepare("SELECT nimi, telefon, pilt, synniaeg FROM osalejad WHERE id = ?");
    $kask->bind_param("i", $id);
    $kask->execute();
    $kask->bind_result($nimi, $telefon, $pilt, $synniaeg);
    $kask->fetch();

    // Освобождаем результат, чтобы избежать ошибки при запросе
    $kask->free_result();

    // Если форма была отправлена (POST), обновляем данные участника
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        // Получаем новые значения из формы
        $nimi = $_POST['nimi'];
        $telefon = $_POST['telefon'];
        $pilt = $_POST['pilt'];
        $synniaeg = $_POST['synniaeg'];

        $updateQuery = $yhendus->prepare("UPDATE osalejad SET nimi = ?, telefon = ?, pilt = ?, synniaeg = ? WHERE id = ?");
        $updateQuery->bind_param("ssssi", $nimi, $telefon, $pilt, $synniaeg, $id);
        $updateQuery->execute();

        header("Location: andmeOsalejad.php");
        exit;
    }
} else {
    echo "Участник не выбран для редактирования.";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Uuenda andmed</title>
        <link rel="stylesheet" href="ostyle.css">
    </head>
    <body>
        <h1>Uuenda andmed</h1>
        <form action="?edit=<?= htmlspecialchars($id) ?>" method="post">
            <label for="nimi">Nimi</label>
            <input type="text" id="nimi" name="nimi" value="<?= htmlspecialchars($nimi) ?>" placeholder="Nimi">

            <label for="telefon">Telefon</label>
            <input type="text" id="telefon" name="telefon" value="<?= htmlspecialchars($telefon) ?>" placeholder="Näiteks +111111111">

            <label for="pilt">Pilt</label>
            <textarea name="pilt" id="pilt"><?= htmlspecialchars($pilt) ?></textarea>

            <label for="synniaeg">Sünniaeg</label>
            <input type="date" id="synniaeg" name="synniaeg" value="<?= htmlspecialchars($synniaeg) ?>">

            <input type="submit" value="Uuenda">
        </form>
    </body>
</html>
