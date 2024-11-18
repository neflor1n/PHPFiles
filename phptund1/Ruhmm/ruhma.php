<?php
$opilased = simplexml_load_file('rruhm.xml');

// Функция для поиска ученика по имени
function otsingOpilaneNimi($paring) {
    global $opilased;
    $paringVastus = array();
    foreach ($opilased->opilane as $opilane) {
        if (substr(strtolower($opilane->nimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus;
}

// Функция для поиска ученика по фамилии
function otsingOpilanePerekonnanimi($paring) {
    global $opilased;
    $paringVastus = array();
    foreach ($opilased->opilane as $opilane) {
        if (substr(strtolower($opilane->perekonnanimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane);
        }
    }
    return $paringVastus;
}

// Функция для удаления ученика
function kustutaOpilane($nimi, $perekonnanimi) {
    global $opilased;

    error_log("Удаляем ученика: $nimi $perekonnanimi");

    // Проходим через всех учеников
    foreach ($opilased->opilane as $key => $opilane) {
        // Проверяем, что имя и фамилия совпадают
        if ((string)$opilane->nimi === $nimi && (string)$opilane->perekonnanimi === $perekonnanimi) {
            unset($opilased->opilane[$key]); // Удаляем ученика

            // Перезаписываем XML файл после удаления
            if ($opilased->asXML('rruhm.xml')) {
                error_log("Удаление прошло успешно: $nimi $perekonnanimi");
                return true;
            } else {
                error_log("Ошибка при записи в XML файл.");
                return false;
            }
        }
    }

    error_log("Не найден ученик: $nimi $perekonnanimi");
    return false; // Если ученик не найден
}


// Проверка на запрос удаления
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $nimi = $_POST['nimi'] ?? '';
    $perekonnanimi = $_POST['perekonnanimi'] ?? '';

    if (!empty($nimi) && !empty($perekonnanimi)) {
        $deleted = kustutaOpilane($nimi, $perekonnanimi);
        echo $deleted ? 'success' : 'error';
    } else {
        echo 'error';
    }
    exit;
}

// Функция для добавления ученика
function lisaOpilane($nimi, $perekonnanimi, $vanus, $hobbi, $koduleht) {
    global $opilased;

    // Создаем нового ученика
    $opilane = $opilased->addChild('opilane');
    $opilane->addChild('nimi', $nimi);
    $opilane->addChild('perekonnanimi', $perekonnanimi);
    $opilane->addChild('vanus', $vanus);
    $opilane->addChild('hobbi', $hobbi);
    $opilane->addChild('koduleht', $koduleht);

    // Перезаписываем XML файл
    if ($opilased->asXML('rruhm.xml')) {
        return true;
    } else {
        return false;
    }
}

// Обрабатываем добавление ученика
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nimi']) && isset($_POST['perekonnanimi']) && isset($_POST['vanus']) && isset($_POST['hobbi']) && isset($_POST['koduleht'])) {
    $nimi = $_POST['nimi'];
    $perekonnanimi = $_POST['perekonnanimi'];
    $vanus = $_POST['vanus'];
    $hobbi = $_POST['hobbi'];
    $koduleht = $_POST['koduleht'];

    $added = lisaOpilane($nimi, $perekonnanimi, $vanus, $hobbi, $koduleht);
    if ($added) {
        echo 'Õpilane lisatud edukalt!';
    } else {
        echo 'Viga lisamisel!';
    }
}
?>


<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>TARpv23 rürma</title>
    <link rel="stylesheet" href="ruhm.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#flip").click(function(){
                $("#panel").slideToggle("slow");
            });
        
            
            $(".kustutaBtn").click(function() {
                var nimi = $(this).data("nimi");
                var perekonnanimi = $(this).data("perekonnanimi");

                if (confirm("Kas olete kindel, et soovite selle õpilase kustutada?")) {
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            nimi: nimi,
                            perekonnanimi: perekonnanimi
                        },
                        success: function(response) {
                            if (response === "success") {
                                var rowId = `#row-${nimi}-${perekonnanimi.replace(/\s+/g, '-')}`;
                                $(rowId).remove();
                                alert('Õpilane kustutatud edukalt!');
                            } else {
                                alert('Viga kustutamisel!');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Serveri viga!');
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <h1 id="pealkiri">TARpv23 rühm</h1>
    
    <div id="opilased">
        <?php
            foreach ($opilased->opilane as $opilane) {
                $nimi = $opilane->nimi;
                $koduleht = $opilane->koduleht;
                echo "<div id='circle'>";
                echo "<a href='$koduleht' target='_blank'>$nimi</a>";
                echo "</div><br><br>";
            }
        ?>
    </div>
    
    <br><br>
    
    <div id="flipid">
        <div id="flip"><strong>Otsi opilane</strong></div>
        <div id="panel">
            <!-- Форма поиска по имени -->
            <form method="post" action="?">
                <label for="otsingNimi">Otsing nime järgi:</label>
                <input type="text" id="otsingNimi" name="otsingNimi" placeholder="Sisesta nimi">
                <input type="submit" value="Otsi">
            </form>
            <br>

            <!-- Форма поиска по фамилии -->
            <form method="post" action="?">
                <label for="otsingPerekonnanimi">Otsing perekonnanime järgi:</label>
                <input type="text" id="otsingPerekonnanimi" name="otsingPerekonnanimi" placeholder="Sisesta perekonnanimi">
                <input type="submit" value="Otsi">
            </form>
        </div>

        <br><br>
        
        <div id="salvestamineOpilane">
            <div id="flip"><strong>Lisa opilane</strong></div>
            <div id="panel" style="display: none;"> <!-- Панель скрыта по умолчанию -->
                <form action="" method="post" name="vorm1">
                    <table>
                        <h3 style="text-align: center;">Lisa opilane</h3>
                        <tr>
                            <td><label for="nimi">Opilane nimi:</label></td>
                            <td><input type="text" name="nimi" id="nimi" required></td>
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
            </div>
        </div>
    </div>
    <br>

    <?php
    $paringVastus = array();

    if (!empty($_POST['otsingNimi'])) {
        $paringVastus = otsingOpilaneNimi($_POST['otsingNimi']);
    } elseif (!empty($_POST['otsingPerekonnanimi'])) {
        $paringVastus = otsingOpilanePerekonnanimi($_POST['otsingPerekonnanimi']);
    }

    // Отображаем результаты поиска
    if (count($paringVastus) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Nimi</th><th>Perekonnanimi</th><th>Vanus</th><th>Hobbi</th><th>Koduleht</th><th>Kustuta</th></tr>";
        foreach ($paringVastus as $opilane) {
            $koduleht = $opilane->koduleht ?? '';
            $nimi = $opilane->nimi;
            $perekonnanimi = $opilane->perekonnanimi;
            echo "<tr id='row-$nimi-$perekonnanimi'>";
            echo "<td>$nimi</td><td>$perekonnanimi</td><td>{$opilane->vanus}</td><td>{$opilane->hobbi}</td>";
            echo "<td><a href='$koduleht' target='_blank'>Koduleht</a></td>";
            echo "<td><button class='kustutaBtn' data-nimi='$nimi' data-perekonnanimi='$perekonnanimi'>Kustuta</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // Если нет поиска, показываем всех учеников
    if (empty($_POST['otsingNimi']) && empty($_POST['otsingPerekonnanimi'])) {
        echo "<div id='opilasedTable'>
                <table border='1px'>
                    <tr><th>Nimi</th><th>Perekonnanimi</th><th>Vanus</th><th>Hobbi</th><th>Koduleht</th></tr>";
        foreach ($opilased->opilane as $opilane) {
            $nimi = $opilane->nimi;
            $perekonnanimi = $opilane->perekonnanimi;
            echo "<tr><td>$nimi</td><td>$perekonnanimi</td><td>{$opilane->vanus}</td><td>{$opilane->hobbi}</td>";
            echo "<td><a href='{$opilane->koduleht}' target='_blank'>Koduleht</a></td></tr>";
        }
        echo "</table></div>";
    }
    ?>







</body>
</html>
