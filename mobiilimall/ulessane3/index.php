<?php
require_once('confZone.php');
global $yhendus;

if(isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("delete from anekdoot where Id = ?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}

$anecdote_id = isset($_GET['anecdote']) ? (int)$_GET['anecdote'] : 0;

?>

<link rel="stylesheet" href="anecdoteStyle.css">
<script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>

<?php include('header.php'); ?>


<div class="content-wrapper">
    <?php include('menu.php'); ?>

    <div class="main-content">
        <h2>Programmeerija Anekdoodid</h2>

        <?php


        if ($anecdote_id > 0) {
            // Подготовка запроса для получения анекдота по ID
            $paring = $yhendus->prepare("SELECt Id, Nimetus, Kuupaev, Kirjeldus FROM anekdoot WHERE id = ?");
            $paring->bind_param("i", $anecdote_id);
            $paring->bind_result($Id, $Nimetus, $Kuupaev, $Kirjeldus);

            $paring->execute();
            $paring->store_result(); // Храним результаты
            if(isset($_REQUEST["kustuta"])) {
                $kask = $yhendus->prepare("delete from anekdoot where Id = ?");
                $kask->bind_param("i", $_REQUEST["kustuta"]);
                $kask->execute();
            }

            if ($paring->fetch()) {
                echo "<h2>$Nimetus</h2>";
                echo "<p><strong>Kuupäev:</strong> $Kuupaev</p>";
                echo "<p>$Kirjeldus</p>";
                echo "<a href='?kustuta=$Id' class='delete-btn'><i class='fa-solid fa-trash'></i></a>";

            } else {
                echo "<p>Nalja ei leitud.</p>";
            }

            // Освобождаем результаты первого запроса
            $paring->free_result();
            $paring->close();
        } elseif (isset($_GET['lisaAnekdoot'])) {
            $file_name = "lisaAnekdoot.php";
            if (file_exists($file_name)) {
                include($file_name);

            } else {
                echo "<p>Fail ei otsi</p>";
            }
        } else {
            echo "<p>Valige loendist nali.</p>";
        }
        ?>
    </div>
</div>







<?php include('footer.php'); ?>
