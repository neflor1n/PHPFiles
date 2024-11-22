<?php
require_once("conf.php");

global $yhendus;
$paring=$yhendus->prepare("SELECT id, loomanimi, omanik, varv FROM loomad");


?>


<!DOCTYPE html>
<html>
    <head>
        <title>Tabeli sisu mida võetakse andmebaasist</title>
    </head>
    <body>
        <h1>Loomad andmebaasist</h1>
        <?php
            while ($paring->fetch()) {
                echo $id;
                echo ", ";
                echo $loomanimi;
                echo ", ";
                echo $omanik;
                echo ", ";
                echo $varv;
                echo "<br>";
            }
            
        ?>
    </body>
</html>

<?php

$yhendus->close();

?>