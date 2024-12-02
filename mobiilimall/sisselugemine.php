<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunniplaani leht</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Esmaspäev</h1>
<ol>
    <li>Matemaatika</li>
    <li>Ajalugu</li>
    <li>Laulmine</li>
</ol>
<?php
// Вставляем текст уведомления
if (file_exists("teade.txt")) {
    $teade = file_get_contents("teade.txt");
    echo "<div class='notification'>$teade</div>";
}
?>
</body>
</html>