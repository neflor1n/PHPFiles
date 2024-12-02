<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Свежее сообщение</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 30px 0;
            text-align: center;
        }

        /* Контейнер для сообщения */
        .message {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Контейнер для автора */
        .author {
            font-size: 14px;
            color: #777;
            text-align: right;
            margin-top: 20px;
        }

        .author span {
            font-weight: bold;
            color: #333;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }

            .message {
                padding: 15px;
                width: 95%;
            }

        }
    </style>
</head>
<body>

<h1>Свежее сообщение</h1>

<div class="message">
    <?php
    // Чтение сообщения из файла
    if (file_exists("VarskeTeade.txt")) {
        $message = file_get_contents("VarskeTeade.txt");
        echo nl2br($message); // nl2br сохраняет разрывы строк
    } else {
        echo "Сообщение не найдено!";
    }
    ?>
</div>

<div class="author">
    <?php
    // Чтение имени создателя из файла
    if (file_exists("author.txt")) {
        $author = file_get_contents("author.txt");
        echo $author;
    } else {
        echo "Данные о создателе не найдены!";
    }
    ?>
</div>

</body>
</html>
