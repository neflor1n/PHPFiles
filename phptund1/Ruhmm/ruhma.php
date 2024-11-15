<?php
echo $opilased = simplexml_load_file('rruhm.xml');
function otsingOpilaneNimi($paring) {
    global $opilased;
    $paringVastus = array();
    foreach ($opilased->opilane as $opilane) {
        // Проведение поиска по имени ученика
        if (substr(strtolower($opilane->nimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus;
}

function otsingOpilanePerekonnanimi($paring) {
    global $opilased;
    $paringVastus = array();
    foreach ($opilased->opilane as $opilane) {
        // Проведение поиска по имени ученика
        if (substr(strtolower($opilane->perekonnanimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus;
}


?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8" >
    <title>TARpv23 rürma</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        #pealkiri {
            text-align: -webkit-center;
            margin-top: 20px;
            color: #2c3e50;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 20px;
            text-align: -webkit-center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
            font-size: 16px;
        }

        td {
            background-color: white;
            color: #34495e;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #dfe6e9;
        }

        /* Формы */
        form {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
            color: #2c3e50;
        }

        input[type="text"] {
            padding: 10px;
            width: calc(100% - 20px);
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Круги для имен */
        #opilased {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }


        #circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: red;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-align: -webkit-center;
            text-decoration: none;
        }
        #circle a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        #opilased {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .circle:hover {
            background-color: darkred;
        }
    </style>

</head>
<body>
    <h1 id="pealkiri">TARpv23 rühm</h1>

    <div id="opilased">
        <?php
            foreach($opilased-> opilane as $opilane) {
                $nimi = $opilane->nimi;
                $koduleht = $opilane->koduleht;
                echo "<div id='circle'>";
                echo "<a href='$koduleht' target='_blank'>$nimi</a>";
                echo "</div>";
                echo "<br>";
                echo "<br>";
        }
        ?>
    </div>
    <br>
    <br>
    <form method="post" action="?">
        <label for="otsing">Otsing:</label>
        <input type="text" id="otsing" name="otsing" placeholder="Sisesta nimi">
        <input type="submit" value="Otsi">
    </form>
    <br>
    <br>
    <form action="?" method="post">
        <label for="otsing">Otsing:</label>
        <input type="text" id="otsing" name="otsing" placeholder="Sisesta perekonnanimi">
        <input type="submit" value="Otsi"></input>
    </form>

    <br>
    <br>

    <?php
        if (!empty($_POST['otsing'])) {
        $paringVastus = otsingOpilaneNimi($_POST['otsing']);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Nimi</th>";
        echo "<th>Perekonnanimi</th>";
        echo "<th>Vanus</th>";
        echo "<th>Hobbi</th>";
        echo "<th>Koduleht</th>";
        echo "</tr>";

        foreach ($paringVastus as $opilane) {
            $koduleht = $opilane->koduleht;
            echo "<tr>";
            echo "<td>" . $opilane->nimi . "</td>";
            echo "<td>" . $opilane->perekonnanimi . "</td>";
            echo "<td>" . $opilane->vanus . "</td>";
            echo "<td>" . $opilane->hobbi . "</td>";
            echo "<td><a href='$koduleht' target='_blank'>Koduleht</a></td>";
            echo "</tr>";
        }
        echo "</table>";

        } else {
    ?>
    <?php
    if (!empty($_POST['otsing'])) {
        $paringVastus = otsingOpilanePerekonnanimi($_POST['otsing']);
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Nimi</th>";
        echo "<th>Perekonnanimi</th>";
        echo "<th>Vanus</th>";
        echo "<th>Hobbi</th>";
        echo "<th>Koduleht</th>";
        echo "</tr>";

        foreach ($paringVastus as $opilane) {
            $koduleht = $opilane->koduleht;
            echo "<tr>";
            echo "<td>" . $opilane->nimi . "</td>";
            echo "<td>" . $opilane->perekonnanimi . "</td>";
            echo "<td>" . $opilane->vanus . "</td>";
            echo "<td>" . $opilane->hobbi . "</td>";
            echo "<td><a href='$koduleht' target='_blank'>Koduleht</a></td>";
            echo "</tr>";
        }
        echo "</table>";

    } else {
        ?>
    <div id="opilasedTable">
        <table border="1px">
            <tr>
                <th>Nimi</th>
                <th>Perekonnanimi</th>
                <th>Vanus</th>
                <th>Hobbi</th>
            </tr>
            <?php
                foreach ($opilased->opilane as $opilane) {
                    echo "<tr>";
                    echo "<td>" . $opilane->nimi . "</td>";
                    echo "<td>" . $opilane->perekonnanimi . "</td>";
                    echo "<td>" . $opilane->vanus . "</td>";
                    echo "<td>" . $opilane->hobbi . "</td>";
                    echo "</tr>";
                }


            ?>
        </table>

    </div>
        <?php
        }
        }
    ?>

    <div id="salvestamineOpilane">
        <form action="" method="post" name="vorm1">
            <table>
                <tr>
                    <td><label for="nimi">Opilane nimi:</label></td>
                    <td><input type="text" name="nimi" id="nimi"></td>
                </tr>
                <tr>
                    <td><label for="perekonnanimi">Opilane perekonnanimi:</label></td>
                    <td><input type="text" name="perekonnanimi" id="perekonnanimi" required></td>
                </tr>
                <tr>
                    <td><label for="vanus">Opilane vanus:</label></td>
                    <td><input type="text" name="vanus" id="vanus" required></td>
                </tr>
                <tr>
                    <td><label for="hobbi">Opilane hobbi:</label></td>
                    <td><input type="text" name="hobbi" id="hobbi" required></td>
                </tr>
                <tr>
                    <td><label for="koduleht">Opilane koduleht:</label></td>
                    <td><input type="text" name="koduleht" id="koduleht" required></td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
                    <td><input type="submit" name="delete" value="Kustuta"></td>
                </tr>
            </table>
        </form>

        <?php
        if (!empty($_POST['nimi']) && isset($_POST['submit'])) {


                // Загружаем XML-документ
                $xmlDoc = new DOMDocument("1.0", "UTF-8");
                $xmlDoc->preserveWhiteSpace = false;
                if (@$xmlDoc->load('rruhm.xml')) {
                    $xmlDoc->formatOutput = true;

                    // Создание нового элемента <opilane> для нового ученика
                    $xml_opilased = $xmlDoc->createElement("opilane");

                    // Заполнение нового элемента данными из формы
                    foreach ($_POST as $key => $value) {
                        if ($key != 'submit' && $key != 'delete') { // Не обрабатывать кнопки
                            $kirje = $xmlDoc->createElement($key, htmlspecialchars($value)); // Безопасное добавление
                            $xml_opilased->appendChild($kirje);
                        }
                    }

                    // Добавление нового ученика в корневой элемент XML
                    $xmlDoc->documentElement->appendChild($xml_opilased);

                    // Сохранение изменений в файл
                    if ($xmlDoc->save('rruhm.xml')) {
                        echo "<p>Õpilane on edukalt lisatud.</p>";
                    } else {
                        echo "<p>Veebileht ei saanud salvestada muudatusi. Palun proovige hiljem.</p>";
                    }
                } else {
                    echo "<p>XML-faili laadimine ebaõnnestus. Palun kontrollige faili.</p>";
                }

            }

        ?>
    </div>




</body>
</html>


