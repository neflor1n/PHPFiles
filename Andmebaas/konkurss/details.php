<?php
require_once("confZone.php");
global $yhendus;



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

    // Добавляем новый комментарий с кнопкой удаления
    $uuendatudKommentaarid = $kommentaariSisu . "\n" . $uusKommentaar . " " . $kustutaMark;

    // Обновляем комментарии в базе данных
    $paring = $yhendus->prepare("UPDATE konkurss SET Kommentaatid = ? WHERE Id = ?");
    $paring->bind_param("si", $uuendatudKommentaarid, $_REQUEST["uusKomment"]);
    $paring->execute();
    $paring->close();

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
        <li><a href="login.php">Admin</a></li>
        <li><a href="KasutajaKonkurs.php">Kasutaja</a></li>
        <li><a href="details.php">Info</a></li>
    </ul>
</nav>

<table>
    <tr>
        <th>KonkursiNimi</th>
    </tr>

<?php
$paring->free_result();
$paring = $yhendus ->prepare("select Id, KonkursiNimi, LisamisAeg, Kommentaatid, Punktid, Avalik from konkurss where Avalik=1");

$paring->bind_result($id, $konkursiNimi, $lisaminsAeg, $kommentaarid, $punktid, $avalik);
$paring-> execute();
while ($paring->fetch()) {
    echo "<tr>";
    echo "<td><a href='?konkursi_id=$id'>" . htmlspecialchars($konkursiNimi) . "</a></td>";
    $kommentaaririd = explode("\n", $kommentaatid);




    echo "</tr>";
}
?>

</table>

<div id="sisu" class="<?php echo isset($_REQUEST["konkursi_id"]) ? 'show' : ''; ?>">
    <?php
    if (isset($_REQUEST["konkursi_id"])) {
        // Запрос для получения информации о конкурсе
        $paring = $yhendus->prepare("SELECT KonkursiNimi, LisamisAeg, Kommentaatid, Punktid FROM konkurss WHERE id = ?");
        $paring->bind_param("i", $_REQUEST["konkursi_id"]);
        $paring->bind_result($konkursiNimi, $lisamisAeg, $Kommentaatid, $Punktid);
        $paring->execute();

        if ($paring->fetch()) {
            echo "<div class='details'>";
            echo "<h2>" . htmlspecialchars($konkursiNimi) . "</h2>";
            echo "<p><strong>Lisamis Aeg:</strong> " . htmlspecialchars($lisamisAeg) . "</p>";
            echo "<p><strong>Kommentaarid:</strong> " . nl2br(htmlspecialchars($Kommentaatid)) . "</p>";
            echo "<p><strong>Punktid:</strong> " . htmlspecialchars($Punktid) . "</p>";
            echo "<td><a href='?heakonkurss_id=$id'><i class='fa-solid fa-plus'></i> Punkt</a></td>";
            echo "<td><a href='?halbkonkurss_id=$id'><i class='fa-solid fa-minus'></i> Punkt</a></td>";
            $kommentaaririd = explode("\n", $kommentaatid);

            echo "<td><form action='?'>
                <input type='hidden' name='uusKomment' value='$id'>
                <input type='text' name='komment' id='komment'>
                <input type='submit' value='Lisa uus kommentaar'>
              </form></td>";

            echo "</div>";
        }

        $paring->close();
    }
    ?>
</div>

</body>
</html>
