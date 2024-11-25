<?php
require_once("conf.php");
//require_once("confZone.php");
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






$paring=$yhendus->prepare("SELECT id, nimi, telefon, pilt, synniaeg FROM osalejad");
$paring->bind_result($id, $nimi, $telefon, $pilt, $synniaeg);
$paring->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabeli sisu mida võetakse andmebaasist</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>

</head>
<body>
<h1>Osalejad andmebaasist</h1>


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

<table>
    <tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Telefon</th>
        <th>Sünniaeg</th>
        <th>Vanus</th>
        <th>Pilt</th>

        <th></th>
    </tr>
    <?php
    while($paring->fetch()) {
        $today = new DateTime();
        $birthDate = new DateTime($synniaeg);
        $vanus = $today->diff($birthDate)->y;

        echo "<tr>";
        echo "<td>".htmlspecialchars($id)."</td>";
        echo "<td>".htmlspecialchars($nimi)."</td>";
        echo "<td>".htmlspecialchars($telefon)."</td>";
        echo "<td>".htmlspecialchars($synniaeg)."</td>";
        echo "<td>".htmlspecialchars($vanus)." aastat vana"."</td>";
        echo "<td><img src='$pilt' alt='pilt' width='150px'></td>";

        echo "<td>";
        echo "<a href='?kustuta=$id'><i class='fa-solid fa-delete-left' style='padding-right: 10px;'></i></a>";
        echo "<a href='osalejadEdit.php?edit=$id'><i class='fa-solid fa-pen-to-square'></i></a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
</body>

<footer>
    Bogdan Sergachev TARpv23 &copy;<br>
    <a href="https://bogdansergachev23.thkit.ee/wp/rus/php-%d0%b1%d0%b0%d0%b7%d0%b0-%d0%b4%d0%b0%d0%bd%d0%bd%d1%8b%d1%85/">Wordpress</a>
</footer>
</html>
