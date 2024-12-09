<?php
require_once("confZone.php");

global $yhendus;

//lisamine




if(!empty($_REQUEST["uusKonkurss"])) {
    $paring =$yhendus ->prepare("insert into konkurss (KonkursiNimi, LisamisAeg) values (?,NOW())");
    $paring ->bind_param("s", $_REQUEST["uusKonkurss"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

if(isset($_REQUEST["uusKomment"])) {
    $paring = $yhendus->prepare("SELECT Kommentaatid FROM konkurss WHERE Id = ?");
    $paring->bind_param("i", $_REQUEST["uusKomment"]);
    $paring->bind_result($kommentaariSisu);
    $paring->execute();

    // Извлекаем результат и освобождаем его
    $paring->fetch();
    $paring->free_result();  // Освобождаем результаты запроса

    // Разбиваем комментарии на строки и определяем следующий номер
    $kommentaaririd = explode("\n", $kommentaariSisu);
    $järgmineKommentaarNumber = count($kommentaaririd) + 1;

    // Формируем новый комментарий с номером
    $uusKommentaar = $järgmineKommentaarNumber . ") " . $_REQUEST["komment"];


    // Добавляем новый комментарий к существующим
    $uuendatudKommentaarid = $kommentaariSisu . "\n" . $uusKommentaar ;

    // Обновляем комментарии в базе данных
    $paring = $yhendus->prepare("UPDATE konkurss SET Kommentaatid = ? WHERE Id = ?");
    $paring->bind_param("si", $uuendatudKommentaarid, $_REQUEST["uusKomment"]);
    $paring->execute();
    $paring->close();  // Закрываем подготовленный запрос после выполнения

    header("Location: $_SERVER[PHP_SELF]");
}


//tabeli uuendamine +1 punkt
if(isset($_REQUEST['heakonkurss_id'])) {
    $paring=$yhendus-> prepare("update konkurss set Punktid = Punktid +1 where Id = ?");
    $paring->bind_param("i", $_REQUEST['heakonkurss_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");

}

if (isset($_REQUEST['halbkonkurss_id'])) {
    $paring=$yhendus -> prepare("update konkurss set Punktid = Punktid -1 where Id = ?");
    $paring->bind_param("i", $_REQUEST['halbkonkurss_id']);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}


if(isset($_REQUEST["kustuta"])) {
    $paring = $yhendus->prepare("delete from konkurss where id = ?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}



$paring = $yhendus->prepare("select Id, KonkursiNimi, LisamisAeg, Kommentaatid, Punktid, pilt, Avalik from konkurss");
$paring->bind_result($id, $konkursiNimi, $lisaminsAeg, $kommentaatid, $punktid, $pilt,$avalik);
$paring->execute();
?>

    <!DOCTYPE html>
    <html lang="et">
    <head>
        <meta charset="UTF-8">
        <title>TARpv23 jõulu konkursid</title>
        <link rel="stylesheet" href="konkursiStyle.css">
        <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script
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
            <th>Pilt</th>
            <th>Punktid</th>
            <th colspan="2">Kommentaarid</th>
            <th colspan=3>Haldus</th>
        </tr>



        <?php
        $paring->free_result();
        $paring = $yhendus ->prepare("select Id, KonkursiNimi, LisamisAeg, pilt, Kommentaatid, Punktid, Avalik from konkurss where Avalik=1");

        $paring->bind_result($id, $konkursiNimi, $lisaminsAeg, $pilt,$kommentaarid, $punktid, $avalik);
        $paring-> execute();
        while ($paring->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($konkursiNimi) . "</td>";
            //echo "<td><a href='?konkursi_id=$id'>" . htmlspecialchars($konkursiNimi) . "</a></td>";
            echo "<td>" . htmlspecialchars($lisaminsAeg) . "</td>";
            echo "<td width='100px'><img src='$pilt' alt='pilt'></td>";
            echo "<td>" . htmlspecialchars($punktid) . "</td>";

            // Разделение комментариев и добавление кнопок для удаления
            $kommentaaririd = explode("\n", $kommentaarid);
            echo "<td>";
            foreach ($kommentaaririd as $index => $kommentaar) {
                // Для каждого комментария добавляем текст и кнопку удаления
                echo htmlspecialchars($kommentaar) . "<br>";
            }
            echo "</td>";

            echo "<td><form action='?'>
                <input type='hidden' name='uusKomment' value='$id'>
                <input type='text' name='komment' id='komment'>
                <input type='submit' value='Lisa uus kommentaar'>
              </form></td>";

            echo "<td><a href='?heakonkurss_id=$id'><i class='fa-solid fa-plus'></i> Punkt</a></td>";
            echo "<td><a href='?halbkonkurss_id=$id'><i class='fa-solid fa-minus'></i> Punkt</a></td>";

            echo "</tr>";
        }
        ?>
    </table>



    </body>
    </html>
<?php
$yhendus->close();
