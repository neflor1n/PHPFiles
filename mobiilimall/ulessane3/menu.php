<?php

require_once('confZone.php');
global $yhendus;

$anecdote_id = isset($_GET['anecdote']) ? (int)$_GET['anecdote'] : 0;

// Подготовка запроса для получения всех анекдотов
$paring = $yhendus->prepare("SELECT Id, Nimetus FROM anekdoot");
$paring->bind_result($Id, $Nimetus);
$paring->execute();
$paring->store_result(); // Храним результаты

// Вывод меню с анекдотами
echo '
<div class="menu">
    <h3>Naljad</h3>
    <ul>';

echo "<li><a href='index.php'>Kodu</li></a>";
while ($paring->fetch()) {
    echo '<li><a href="?anecdote=' . $Id . '">' . htmlspecialchars($Nimetus) . '</a></li>';
}
echo '<li><a href="?lisaAnekdoot">Добавить анекдот</a>';

// Освобождаем результаты первого запроса
$paring->free_result();

// Теперь выполняем второй запрос, если ID анекдота задано
if ($anecdote_id > 0) {
    // Подготовка запроса для получения анекдота по ID
    $paring = $yhendus->prepare("SELECT Nimetus, Kuupaev, Kirjeldus FROM anekdoot WHERE id = ?");
    $paring->bind_param("i", $anecdote_id);
    $paring->bind_result($Nimetus, $Kuupaev, $Kirjeldus);
    $paring->execute();



    // Освобождаем результаты второго запроса
    $paring->free_result();
}

// Закрываем подготовленный запрос
$paring->close();

echo '</ul></div>';
?>

<style>
    /* Основные стили */
    .menu {
        width: 25%;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .menu h3 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .menu ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu ul li {
        margin: 10px 0;
    }

    .menu ul li a {
        text-decoration: none;
        color: #007bff;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .menu ul li a:hover {
        color: #0056b3;
    }

    /* Адаптивность для экранов менее 768px */
    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .menu {
            width: 75%;
            margin-top: 20px;
        }

        header h1 {
            font-size: 24px;
        }

        .menu h3 {
            font-size: 16px;
        }

        .menu ul li a {
            font-size: 14px;
        }
    }

    @media (max-width: 1000px) {
        header h1 {
            font-size: 20px;
        }

        .menu h3 {
            font-size: 14px;
        }

        .menu ul li a {
            font-size: 12px;
        }

        .menu {
            padding: 10px;
        }
    }

</style>
