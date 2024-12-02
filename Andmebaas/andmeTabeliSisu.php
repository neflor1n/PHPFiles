<?php
//require_once("conf.php");
require_once("confZone.php");



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


        <table>
            <tr>
                <th>ID</th>
                <th>Loomanimi</th>
                <th>Omanik</th>
                <th>Varv</th>
                <th>Pilt</th>
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
                    echo "</tr>";
                }



            ?>
        </table>
    </body>
</html>

<?php

$yhendus->close();

?>