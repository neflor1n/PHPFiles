<?php
require_once('confZone.php');
global $yhendus;

if(isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("delete from osalejad where id = ?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}


if (isset($_REQUEST["nimi"]) && isset($_REQUEST["telefon"])
    && isset($_REQUEST["synniaeg"])
    && !empty($_REQUEST["synniaeg"])
    && !empty($_REQUEST["telefon"])
    && !empty($_REQUEST["nimi"])) {

    $paring = $yhendus->prepare("insert into osalejad(nimi, telefon, pilt,synniaeg) values (?, ?, ?, ?)");
    $paring->bind_param("ssss", $_REQUEST["nimi"], $_REQUEST["telefon"], $_REQUEST["pilt"], $_REQUEST["synniaeg"]);
    $paring->execute();
}


$paring = $yhendus->prepare("SELECT id, nimi, telefon, pilt, synniaeg FROM osalejad");
$paring->bind_result($id, $nimi, $telefon, $pilt, $synniaeg);
$paring->execute();
?>

<!DOCTYPE html>
<html lang="et">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Matkajad</title>
        <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style2.css">
    </head>

    <body>
        <header>
            <h1>Matkajad</h1>
        </header>
        <main>



            <div id="menu">
                <table>
                    <?php
                    while ($paring->fetch()) {
                        echo "<tr>";
                        echo "<td><a href='?osalejad_id=$id'><img src='$pilt' alt='Osaleja pilt' class='thumbnail'></a></td>";
                        echo "</tr>";
                    }
                    $paring->close();
                    ?>
                </table>
            </div>

            <div id="sisu">

                <?php
                if (isset($_REQUEST["osalejad_id"])) {
                    // Новый запрос для конкретного участника
                    $paring = $yhendus->prepare("SELECT nimi, telefon, synniaeg, pilt FROM osalejad WHERE id = ?");
                    $paring->bind_param("i", $_REQUEST["osalejad_id"]);
                    $paring->bind_result($nimi, $telefon, $synniaeg, $pilt);
                    $paring->execute();

                    if ($paring->fetch()) {
                        $today = new DateTime();
                        $birthDate = new DateTime($synniaeg);
                        $vanus = $today->diff($birthDate)->y;

                        echo "<div class='details'>";
                        echo "<h2>" . htmlspecialchars($nimi) . "</h2>";
                        echo "<p><strong>Telefoninumber:</strong> " . htmlspecialchars($telefon) . "</p>";
                        echo "<p><strong>Sünniaeg:</strong> " . htmlspecialchars($synniaeg) . "</p>";
                        echo "<p><strong>Vanus:</strong> " . htmlspecialchars($vanus) . "</p>";
                        echo "<a href='?kustuta=$id' class='delete-btn'><i class='fa-solid fa-trash'></i></a>";
                        echo "</div>";
                    }

                    $paring->close();
                }
                ?>

            </div>
            <form action="?" method="post">
                <label for="nimi">Nimi</label>
                <input type="text" id="nimi" name="nimi" placeholder="Nimi">

                <label for="telefon">Telefon</label>
                <input type="text" id="telefon" name="telefon" placeholder="Näiteks +111111111">

                <label for="pilt">Pilt</label>
                <textarea name="pilt" id="pilt">Sisesta pildi linki</textarea>

                <label for="synniaeg">Sünniaeg</label>
                <input type="date" id="synniaeg" name="synniaeg">

                <input type="submit" value="Lisa">
            </form>

        </main>
    </body>



</html>
