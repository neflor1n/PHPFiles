<?php if (isset($_GET['code'])) {die(highlight_file(__FILE__, 1));} ?>
<?php


// Загружаем XML файл с данными об учениках
$opilased = simplexml_load_file('rruhm.xml');

// Функция для поиска ученика по имени
function otsingOpilaneNimi($paring) {
    global $opilased;
    $paringVastus = array(); // Массив для хранения результатов поиска
    // Проходим по каждому ученику и проверяем, начинается ли имя с искомой подстроки
    foreach ($opilased->opilane as $opilane) {
        if (substr(strtolower($opilane->nimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane); // Если совпало, добавляем ученика в результаты
        }
    }
    return $paringVastus; // Возвращаем результаты поиска
}

// Функция для поиска ученика по фамилии
function otsingOpilanePerekonnanimi($paring) {
    global $opilased;
    $paringVastus = array(); // Массив для хранения результатов поиска
    // Проходим по каждому ученику и проверяем, начинается ли фамилия с искомой подстроки
    foreach ($opilased->opilane as $opilane) {
        if (substr(strtolower($opilane->perekonnanimi), 0, strlen($paring)) == strtolower($paring)) {
            array_push($paringVastus, $opilane); // Если совпало, добавляем ученика в результаты
        }
    }
    return $paringVastus; // Возвращаем результаты поиска
}

// Функция для удаления ученика
function kustutaOpilane($nimi, $perekonnanimi) {
    global $opilased;

    // Логируем попытку удаления ученика
    error_log("Удаляем ученика: $nimi $perekonnanimi");

    // Проходим по всем ученикам
    foreach ($opilased->opilane as $key => $opilane) {
        // Проверяем, что имя и фамилия совпадают
        if ((string)$opilane->nimi === $nimi && (string)$opilane->perekonnanimi === $perekonnanimi) {
            unset($opilased->opilane[$key]); // Удаляем ученика

            // Перезаписываем XML файл после удаления
            $result = $opilased->asXML('rruhm.xml');
            if ($result) {
                // Логируем успешное удаление
                error_log("Удаление прошло успешно: $nimi $perekonnanimi");
                return true; // Возвращаем успех
            } else {
                // Логируем ошибку при записи в XML файл
                error_log("Ошибка при записи в XML файл.");
                return false; // Возвращаем ошибку
            }
        }
    }

    // Если ученик не найден, логируем ошибку
    error_log("Не найден ученик: $nimi $perekonnanimi");
    return false; // Возвращаем ошибку
}

// Проверка на запрос удаления через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $nimi = $_POST['nimi'] ?? ''; // Получаем имя ученика
    $perekonnanimi = $_POST['perekonnanimi'] ?? ''; // Получаем фамилию ученика

    // Если имя и фамилия не пустые, пытаемся удалить ученика
    if (!empty($nimi) && !empty($perekonnanimi)) {
        $deleted = kustutaOpilane($nimi, $perekonnanimi); // Удаление ученика
        echo $deleted ? 'success' : 'error'; // Выводим результат удаления
    } else {
        echo 'error'; // Если данные пустые, выводим ошибку
    }
    exit; // Завершаем выполнение скрипта
}


// Обработка формы для добавления ученика
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверяем, что все необходимые поля формы существуют
    if (isset($_POST['nimi'], $_POST['perekonnanimi'], $_POST['vanus'], $_POST['hobbi'], $_POST['koduleht'], $_POST['gender'])) {
        // Получаем данные из формы
        $nimi = $_POST['nimi'];
        $perekonnanimi = $_POST['perekonnanimi'];
        $vanus = $_POST['vanus'];
        $hobbi = $_POST['hobbi'];
        $koduleht = $_POST['koduleht'];
        $gender = $_POST['gender'];

        // Функция для добавления ученика (вызываем с параметрами)
        lisaOpilane($nimi, $perekonnanimi, $vanus, $hobbi, $koduleht, $gender);
    } 
}

// Функция для добавления ученика в XML
function lisaOpilane($nimi, $perekonnanimi, $vanus, $hobbi, $koduleht, $gender) {
    global $opilased;

    // Проверка возраста
    if ($vanus < 14 || $vanus > 70) {
        return "Vanus peab olema vahemikus 14 kuni 70!";
    }

    // Проверка пола
    if ($gender != 'Male' && $gender != 'Female') {
        return "Palun sisesta õige sugu (Male/Female)!";
    }


    // Создаем нового ученика
    $opilane = $opilased->addChild('opilane');
    $opilane->addChild('nimi', $nimi);
    $opilane->addChild('perekonnanimi', $perekonnanimi);
    $opilane->addChild('vanus', $vanus);
    $opilane->addChild('hobbi', $hobbi);
    $opilane->addChild('koduleht', $koduleht);
    $opilane->addChild('gender', $gender);

    // Создаем новый DOMDocument для форматирования
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    // Загружаем XML в DOM
    $xmlString = $opilased->asXML();
    $dom->loadXML($xmlString);

    // Перезаписываем XML файл с форматированием
    if ($dom->save('rruhm.xml')) {
        return true;
    } else {
        return false;
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
            // Функция для открытия/закрытия панели при клике на элемент с классом "flip"
            $(".flip").click(function(){
                $(this).next(".panel").slideToggle("slow"); // Открытие/закрытие следующего элемента с классом "panel"
            });

            // Обработчик клика по кнопке с классом "kustutaBtn" (удаление ученика)
            $(".kustutaBtn").click(function() {
                var nimi = $(this).data("nimi");  // Получаем имя ученика
                var perekonnanimi = $(this).data("perekonnanimi");  // Получаем фамилию ученика

                // Подтверждение удаления
                if (confirm("Kas olete kindel, et soovite selle õpilase kustutada?")) {
                    // Отправляем запрос на сервер для удаления
                    $.ajax({
                        url: window.location.href, // Текущий URL страницы
                        type: 'POST',
                        data: {
                            action: 'delete', // Указываем, что действие - удаление
                            nimi: nimi,
                            perekonnanimi: perekonnanimi
                        },
                        success: function(response) {
                            // Если удаление прошло успешно, удаляем строку с таблицы
                            if (response === "success") {
                                var rowId = `#row-${nimi}-${perekonnanimi.replace(/\s+/g, '-')}`; // Генерация id строки
                                $(rowId).remove(); // Удаляем строку
                                alert('Õpilane kustutatud edukalt!'); // Уведомление об успешном удалении
                            } else {
                                alert('Viga kustutamisel!'); // Уведомление о ошибке при удалении
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Serveri viga!'); // Уведомление о ошибке на сервере
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <h1 id="pealkiri">TARpv23 rühm</h1> <!-- Заголовок страницы -->

    <div id="opilased">
        <!-- Перебор учеников и отображение их в виде кругов -->
        <?php
            foreach ($opilased->opilane as $opilane) {
                $nimi = $opilane->nimi;
                $koduleht = $opilane->koduleht;
                $gender = $opilane->gender;  

                // Присваиваем цвет в зависимости от пола
                $color = ($gender == 'Male') ? '#1c7ade' : 'pink'; // Для мужчин синий, для женщин розовый

                // Создаем элемент с кругом для каждого ученика
                echo "<div id='circle' style='background-color: $color;'>";
                echo "<a href='$koduleht' target='_blank'>$nimi</a>"; // Ссылка на сайт ученика
                echo "</div><br><br>";
            }
        ?>
    </div>

    <br><br>

    <div id="flipid">
        <!-- Панель поиска -->
        <div class="flip"><strong>Otsi opilane</strong></div> <!-- Заголовок поиска -->
        <div class="panel">
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
            <!-- Панель для добавления нового ученика -->
            <div class="flip"><strong>Lisa opilane</strong></div> <!-- Заголовок добавления ученика -->
            <div class="panel">
                <form action="" method="post" name="vorm1">
                    <table>
                        <h3 style="text-align: center;">Lisa opilane</h3> <!-- Заголовок формы -->
                        <!-- Поля формы для ввода данных ученика -->
                        <tr>
                            <td><label for="nimi">Opilane nimi:</label></td>
                            <td><input type="text" placeholder="Sisesta nimi" name="nimi" id="nimi" required></td>
                        </tr>
                        <tr>
                            <td><label for="perekonnanimi">Opilane perekonnanimi:</label></td>
                            <td><input type="text" placeholder="Sisesta perekonnanimi" name="perekonnanimi" id="perekonnanimi" required></td>
                        </tr>
                        <tr>
                            <td><label for="vanus">Opilane vanus:</label></td>
                            <td><input type="number" placeholder="Sisesta vanus" name="vanus" id="vanus" required></td>
                        </tr>
                        <tr>
                            <td><label for="hobbi">Opilane hobbi:</label></td>
                            <td><input type="text" placeholder="Sisesta hobbi" name="hobbi" id="hobbi" required></td>
                        </tr>
                        <tr>
                            <td><label for="koduleht">Opilane koduleht:</label></td>
                            <td><input type="url" placeholder="Sisesta koduleht" name="koduleht" id="koduleht" required></td>
                        </tr>
                        <tr>
                            <td><label for="gender">Opilane gender:</label></td>
                            <td>
                            <input type="radio" name="gender" id="male" value="Male">
                            <label for="male">Male</label>
                            <input type="radio" name="gender" id="female" value="Female">
                            <label for="female">Female</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" name="submit" id="submit" value="Sisesta">
                            </td>
                        </tr>
                    </table>
                    
                </form>
            </div>
        </div>
        <?php
            // Если есть сообщение об ошибке или успехе
            if (isset($result)) {
                echo "<script>showAlert('$result');</script>";
            }
        ?>
    </div>
    

    <br>
        <?php
        // Инициализируем переменную для хранения результатов поиска
    $paringVastus = array();

    // Проверяем, был ли отправлен запрос на поиск по имени
    if (!empty($_POST['otsingNimi'])) {
        // Если запрос на поиск имени не пустой, выполняем поиск по имени
        $paringVastus = otsingOpilaneNimi($_POST['otsingNimi']);
    } 
    // Если запрос на поиск имени пустой, проверяем поиск по фамилии
    elseif (!empty($_POST['otsingPerekonnanimi'])) {
        // Если запрос на поиск фамилии не пустой, выполняем поиск по фамилии
        $paringVastus = otsingOpilanePerekonnanimi($_POST['otsingPerekonnanimi']);
    }

    // Если есть результаты поиска, отображаем таблицу с результатами
    if (count($paringVastus) > 0) {
        // Начинаем создание таблицы с результатами поиска
        echo "<table border='1'>";
        // Заголовки таблицы: Имя, Фамилия, Возраст, Хобби, Сайт и кнопка для удаления
        echo "<tr><th>Nimi</th><th>Perekonnanimi</th><th>Vanus</th><th>Hobbi</th><th>Gender</th><th>Koduleht</th><th>Kustuta</th></tr>";

        // Перебираем всех найденных учеников
        foreach ($paringVastus as $opilane) {
            // Получаем значение из XML для каждого ученика
            $koduleht = $opilane->koduleht ?? ''; // Если нет сайта, то пустое значение
            $nimi = $opilane->nimi;
            $perekonnanimi = $opilane->perekonnanimi;
            
            // Отображаем строку для каждого ученика
            echo "<tr id='row-$nimi-$perekonnanimi'>";
            // Столбцы таблицы: Имя, Фамилия, Возраст, Хобби
            echo "<td>$nimi</td><td>$perekonnanimi</td><td>{$opilane->vanus}</td><td>{$opilane->hobbi}</td><td>{$opilane->gender}</td>";
            // Ссылка на сайт ученика
            echo "<td><a href='$koduleht' target='_blank'>Koduleht</a></td>";
            // Кнопка удаления ученика
            echo "<td><button class='kustutaBtn' data-nimi='$nimi' data-perekonnanimi='$perekonnanimi'>Kustuta</button></td>";
            echo "</tr>";
        }

        // Закрываем таблицу
        echo "</table>";
    }

    // Если поиск не был выполнен, показываем всех учеников без фильтрации
    if (empty($_POST['otsingNimi']) && empty($_POST['otsingPerekonnanimi'])) {
        // Создаем таблицу с полным списком учеников
        echo "<div id='opilasedTable'>
                <table border='1px'>
                    <tr><th>Nimi</th><th>Perekonnanimi</th><th>Vanus</th><th>Hobbi</th><th>Gender</th><th>Koduleht</th></tr>";
        
        // Перебираем всех учеников из исходного XML
        foreach ($opilased->opilane as $opilane) {
            $nimi = $opilane->nimi;
            $perekonnanimi = $opilane->perekonnanimi;

            // Отображаем строку с данными каждого ученика
            echo "<tr><td>$nimi</td><td>$perekonnanimi</td><td>{$opilane->vanus}</td><td>{$opilane->hobbi}</td><td>{$opilane->gender}</td>";
            // Ссылка на сайт ученика
            echo "<td><a href='{$opilane->koduleht}' target='_blank'>Koduleht</a></td></tr>";
        }

        // Закрываем таблицу
        echo "</table></div>";
    }
    ?>







</body>
</html>
