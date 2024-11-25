<?php
require("conf.php");

global $yhendus;
//kustutamine
if (isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("delete from loomad where id = ?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();

}

if (isset($_REQUEST["uusloom"]) && isset($_REQUEST["omanik"]) && !empty($_REQUEST["loomanimi"]) && !empty($_REQUEST["omanik"])) {
    $paring= $yhendus->prepare("insert into loomad(loomanimi, omanik, varv, pilt) values (?, ?, ?, ?)");
    $paring->bind_param("ssss", $_REQUEST["loomanimi"], $_REQUEST["omanik"], $_REQUEST["varv"], $_REQUEST["pilt"]);
    $paring->execute();

}

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <title>Loomad 1 kaupa</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            gap: 20px;
        }

        #meny {
            width: 300px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        #meny ul {
            list-style-type: none;
            padding-left: 0;
        }

        #meny li {
            margin-bottom: 15px;
        }

        #meny a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.2em;
            transition: color 0.3s ease;
        }

        #meny a:hover {
            color: #0056b3;
        }

        #sisu {
            flex-grow: 1;
            max-width: 800px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #sisu div {
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #sisu div img {
            border-radius: 8px;
            margin-top: 15px;
        }

        #sisu a {
            color: #e74c3c;
            font-size: 1.5em;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #sisu a:hover {
            color: #c0392b;
        }


    </style>
</head>
    <body>
        <h1>Loomad 1 kaupa</h1>
        <div id="meny">
            <ul>
                <?php
                    // loomade nimed andmebaasist
                    global $yhendus;
                    $paring=$yhendus->prepare("SELECT id, loomanimi, omanik, varv, pilt FROM loomad");
                    $paring->bind_result($id, $loomanimi, $omanik, $varv, $pilt);
                    $paring->execute();

                    while($paring->fetch()) {
                        echo "<li><a href='?looma_id=$id'>".$loomanimi."</a></li>";
                    }


                ?>
            </ul>
            <?php
                echo "<a href='?lisamine=jah'>Lisa uus loom...</a>";
            ?>
        </div>




        <div id="sisu">
            <?php
                //kui klick looma nimele, siis näitame looma info
                if (isset($_REQUEST["looma_id"])){
                    $paring = $yhendus->prepare("SELECT id, loomanimi, omanik, varv, pilt FROM loomad WHERE id = ?");
                    $paring->bind_result($id, $loomanimi, $omanik, $varv, $pilt);
                    $paring->bind_param("i", $_REQUEST["looma_id"]);
                    $paring->execute();
                    //näitame ühe kaupa
                    if($paring->fetch()) {
                        echo "<div>Loomanimi: ".$loomanimi;
                        echo "<br>Omanik: ".$omanik;
                        echo "<br>Tõig: ".$varv;
                        echo "<br><img src='$pilt' width='150px' alt='pilt'>";
                        echo "<a href='?kustuta=$id'><i class='fa-solid fa-delete-left' style='padding-right: 10px; margin-left: 10px'></i></a>";
                        echo "</div>";
                    }
                }
            ?>
        </div>



        <?php
        //lisamisvorm, mis avatakse kui vajutatud lisa
            if(isset($_REQUEST["lisamine"])) {
        ?>
            <form action="?" method="post">
                <input type="hidden" value="jah" name="uusloom">
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
        <?php
            }
            ?>


    </body>
</html>