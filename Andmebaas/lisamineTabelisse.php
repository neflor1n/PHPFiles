<?php
//require_once("conf.php");
require_once("confZone.php");
global $yhendus;
//kustutamine
if (isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("delete from loomad where id = ?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();

}


//tabeli andmete lisamine
if (isset($_REQUEST["loomanimi"]) && isset($_REQUEST["omanik"]) && !empty($_REQUEST["loomanimi"]) && !empty($_REQUEST["omanik"])) {
    $paring= $yhendus->prepare("insert into loomad(loomanimi, omanik, varv, pilt) values (?, ?, ?, ?)");
    $paring->bind_param("ssss", $_REQUEST["loomanimi"], $_REQUEST["omanik"], $_REQUEST["varv"], $_REQUEST["pilt"]);
    $paring->execute();

}

// tabeli sisu kuvamine
global $yhendus;
$paring=$yhendus->prepare("SELECT id, loomanimi, omanik, varv, pilt FROM loomad");
$paring->bind_result($id, $loomanimi, $omanik, $varv, $pilt);
$paring->execute();

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Tabeli sisu mida võetakse andmebaasist</title>
        <link rel="stylesheet" href="bstyle.css">
    </head>
    <body>
    <h1>Loomad andmebaasist</h1>
    <h2>Uue looma lisamine</h2>
    <form action="?" method="post">
        <label for="loomanimi">Loomanimi</label>
        <input type="text" id="loomanimi" name="loomanimi">

        <label for="omanik">Omanik</label>
        <input type="text" id="omanik" name="omanik">

        <label for="varv">Värv</label>
        <input type="color" id="varv" name="varv">

        <label for="pilt">Pilt</label>
        <textarea name="pilt" id="pilt">Sisesta pildi link</textarea>

        <input type="submit" value="Lisa">
    </form>



    <table>
        <tr>
            <th>ID</th>
            <th>Loomanimi</th>
            <th>Omanik</th>
            <th>Varv</th>
            <th>Pilt</th>
            <th></th>
        </tr>
        <?php
        while ($paring->fetch()) {
            echo "<tr>";

            echo "<td>". htmlspecialchars($id). "</td>";
            if ($varv == 'White' || $varv == 'Gray' || $varv == 'gray' || $varv == 'white' ) {
                echo "<td style='color: $varv' bgcolor='black'>" . htmlspecialchars($loomanimi) . "</td>";
            }
            else {
                echo "<td style='color: $varv;'>". htmlspecialchars($loomanimi). "</td>";

            }
            echo "<td>". htmlspecialchars($omanik). "</td>";
            echo "<td>". htmlspecialchars($varv). "</td>";
            echo "<td><img src='$pilt' alt='pilt' width='150px'></td>";
            echo "<td><a href='?kustuta=$id>'>Kustuta</a></td>";
            echo "</tr>";

        }



        ?>

    </table>

    </body>
    </html>

<?php

$yhendus->close();

?>