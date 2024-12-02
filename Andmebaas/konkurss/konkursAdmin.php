<?php
require_once("conf.php");
global $yhendus;

// Логика добавления нового конкурса
if (!empty($_REQUEST["uusKonkurss"])) {
    $paring = $yhendus->prepare("insert into konkurss (KonkursiNimi, LisamisAeg) values (?, NOW())");
    $paring->bind_param("s", $_REQUEST["uusKonkurss"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

// Логика добавления комментария
if (isset($_REQUEST["uusKomment"])) {
    $paring = $yhendus->prepare("SELECT Kommentaatid FROM konkurss WHERE Id = ?");
    $paring->bind_param("i", $_REQUEST["uusKomment"]);
    $paring->bind_result($kommentaariSisu);
    $paring->execute();

    // Извлекаем результат и освобождаем его
    $paring->fetch();
    $paring->free_result();

    // Разбиваем комментарии на строки и определяем следующий номер
    $kommentaaririd = explode("\n", $kommentaariSisu);
    $järgmineKommentaarNumber = count($kommentaaririd) + 1;

    // Формируем новый комментарий с номером
    $uusKommentaar = $järgmineKommentaarNumber . ") " . $_REQUEST["komment"];

    // Формируем ссылку на удаление для нового комментария
    $kustutaMark = "<a href='?kustutaKommentaar=$järgmineKommentaarNumber&id=" . $_REQUEST["uusKomment"] . "'><i class='fa-solid fa-trash'></i></a>";

    // Добавляем новый комментарий с кнопкой удаления
    $uuendatudKommentaarid = $kommentaariSisu . "\n" . $uusKommentaar . " " . $kustutaMark;

    // Обновляем комментарии в базе данных
    $paring = $yhendus->prepare("UPDATE konkurss SET Kommentaatid = ? WHERE Id = ?");
    $paring->bind_param("si", $uuendatudKommentaarid, $_REQUEST["uusKomment"]);
    $paring->execute();
    $paring->close();

    header("Location: $_SERVER[PHP_SELF]");
}

// Логика удаления комментария
if (isset($_REQUEST["kustutaKommentaar"])) {
    $id = $_REQUEST["id"]; // Идентификатор конкурса
    $kommentaarId = $_REQUEST["kustutaKommentaar"]; // Номер комментария для удаления

    // Получаем текущие комментарии
    $paring = $yhendus->prepare("SELECT Kommentaatid FROM konkurss WHERE Id = ?");
    $paring->bind_param("i", $id);
    $paring->bind_result($kommentaariSisu);
    $paring->execute();
    $paring->fetch();
    $paring->free_result();

    // Разбиваем комментарии на строки
    $kommentaaririd = explode("\n", $kommentaariSisu);

    // Удаляем комментарий по номеру
    if (isset($kommentaaririd[$kommentaarId - 1])) {
        unset($kommentaaririd[$kommentaarId - 1]);
    }

    // Обновляем строку комментариев после удаления
    $uuendatudKommentaarid = implode("\n", $kommentaaririd);

    // Обновляем комментарии в базе данных
    $paring = $yhendus->prepare("UPDATE konkurss SET Kommentaatid = ? WHERE Id = ?");
    $paring->bind_param("si", $uuendatudKommentaarid, $id);
    $paring->execute();
    $paring->close();

    header("Location: $_SERVER[PHP_SELF]");
}

// Логика обновления баллов
if (isset($_REQUEST['heakonkurss_id'])) {
    $paring = $yhendus->prepare("update konkurss set Punktid = Punktid + 1 where Id = ?");
    $paring->bind_param("i", $_REQUEST['heakonkurss_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

if (isset($_REQUEST['halbkonkurss_id'])) {
    $paring = $yhendus->prepare("update konkurss set Punktid = Punktid - 1 where Id = ?");
    $paring->bind_param("i", $_REQUEST['halbkonkurss_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

// Логика удаления конкурса
if (isset($_REQUEST["kustuta"])) {
    $paring = $yhendus->prepare("delete from konkurss where id = ?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}


if (isset($_REQUEST["muudaAvalik"])) {
    $paring = $yhendus -> prepare("update konkurss set avalik=1 - avalik where id = ?");
    $paring->bind_param("i", $_REQUEST["muudaAvalik"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

$paring = $yhendus->prepare("select Id, KonkursiNimi, LisamisAeg, Kommentaatid, Punktid, Avalik from konkurss");
$paring->bind_result($id, $konkursiNimi, $lisaminsAeg, $kommentaatid, $punktid, $avalik);
$paring->execute();
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv23 jõulu konkursid</title>
    <link rel="stylesheet" href="konkursiStyle.css">
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>
</head>
<body>
<h1 style="text-align: -webkit-center">TARpv23 jõulu konkursid</h1>

<nav>
    <ul>
        <li><a href="konkurssPunktideLisamine.php">Kodu</a></li>
        <li><a href="KasutajaKonkurs.php">Kasutaja</a></li>
    </ul>
</nav>

<form action="">
    <label for="uusKonkurss">Lisa konkurssi nimi</label>
    <input type="text" id="uusKonkurss" name="uusKonkurss" placeholder="Konkurssi nimi">
    <input type="submit" value="Lisa">
</form>
<br>
<br>

<table>
    <tr>
        <th>KonkursiNimi</th>
        <th>LisamisAeg</th>
        <th>Punktid</th>
        <th colspan="2">Kommentaarid</th>
        <th colspan=4>Haldus</th>
    </tr>

    <?php
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($konkursiNimi) . "</td>";
        echo "<td>" . htmlspecialchars($lisaminsAeg) . "</td>";
        echo "<td>" . htmlspecialchars($punktid) . "</td>";

        // Разделение комментариев и добавление кнопок для удаления
        $kommentaaririd = explode("\n", $kommentaatid);
        echo "<td>";
        foreach ($kommentaaririd as $index => $kommentaar) {
            // Для каждого комментария добавляем текст и кнопку удаления
            echo htmlspecialchars($kommentaar) . " <a href='?kustutaKommentaar=" . ($index + 1) . "&id=" . $id . "'><i class='fa-solid fa-trash'></i></a><br>";
        }
        echo "</td>";

        echo "<td><form action='?'>
                <input type='hidden' name='uusKomment' value='$id'>
                <input type='text' name='komment' id='komment'>
                <input type='submit' value='Добавить комментарий'>
              </form></td>";

        echo "<td><a href='?heakonkurss_id=$id'><i class='fa-solid fa-plus'></i> Punkt</a></td>";
        echo "<td><a href='?halbkonkurss_id=$id'><i class='fa-solid fa-minus'></i> Punkt</a></td>";
        echo "<td><a href='?kustuta=$id'><i class='fa-regular fa-trash-can'></i></a>";
        echo "</tr>";
    }
    ?>

</table>
</body>
</html>
<?php
$yhendus->close();
?>
