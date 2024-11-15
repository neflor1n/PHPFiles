<?php
// Загружаем XML файл
$opilased = simplexml_load_file('rruhm.xml');

function otsingOpilaneNimi($paring) {
    global $opilased;
    $paringVastus = array();
    foreach ($opilased->opilane as $opilane) {
        // Поиск по имени ученика
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
        // Поиск по фамилии ученика
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
    <link rel="stylesheet" href="ruhm.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#flip").click(function(){
                $("#panel").slideToggle("slow");
            });
        });
    </script>
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
    <div id="flip"><strong>Otsi opilased</strong></div>
    <div id="panel">
    <!-- Форма для поиска по имени -->
        <form method="post" action="?">
            <label for="otsingNimi">Otsing nime järgi:</label>
            <input type="text" id="otsingNimi" name="otsingNimi" placeholder="Sisesta nimi">
            <input type="submit" value="Otsi">
        </form>

        <br>

        <!-- Форма для поиска по фамилии -->
        <form method="post" action="?">
            <label for="otsingPerekonnanimi">Otsing perekonnanime järgi:</label>
            <input type="text" id="otsingPerekonnanimi" name="otsingPerekonnanimi" placeholder="Sisesta perekonnanimi">
            <input type="submit" value="Otsi">
        </form>
    </div>
    <br>

    <?php
    // Обрабатываем поиск по имени и фамилии
    $paringVastus = array();

    if (!empty($_POST['otsingNimi'])) {
        // Поиск по имени
        $paringVastus = otsingOpilaneNimi($_POST['otsingNimi']);
    } elseif (!empty($_POST['otsingPerekonnanimi'])) {
        // Поиск по фамилии
        $paringVastus = otsingOpilanePerekonnanimi($_POST['otsingPerekonnanimi']);
    }

    // Если найдены пользователи
    if (count($paringVastus) > 0) {
        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>Nimi</th>";
        echo "<th>Perekonnanimi</th>";
        echo "<th>Vanus</th>";
        echo "<th>Hobbi</th>";
        echo "<th>Koduleht</th>";
        echo "</tr>";

        foreach ($paringVastus as $opilane) {
            $koduleht = isset($opilane->koduleht) ? $opilane->koduleht : '';  // Если есть кoдовый сайт
            echo "<tr>";
            echo "<td>" . $opilane->nimi . "</td>";
            echo "<td>" . $opilane->perekonnanimi . "</td>";
            echo "<td>" . $opilane->vanus . "</td>";
            echo "<td>" . $opilane->hobbi . "</td>";
            echo "<td><a href='$koduleht' target='_blank'>Koduleht</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Если нет поискового запроса, показываем всех пользователей
    if (empty($_POST['otsingNimi']) && empty($_POST['otsingPerekonnanimi'])) {
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
    ?>



    </div>




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
                    <td></td>
                    <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>

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
