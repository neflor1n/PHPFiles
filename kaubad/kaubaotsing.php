<?php
require("abifunktsioonid.php");
$sorttulp = "nimetus";
$otsisona = "";
if (isset($_REQUEST["sort"])) {
    $sorttulp = $_REQUEST["sort"];
}
if (isset($_REQUEST["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}
$kaubad = kysiKaupadeAndmed($sorttulp, $otsisona);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        form {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"], select {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
        }
        a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form action="kaubaotsing.php">
    <h2>Otsi kaupu</h2>
    <input type="text" name="otsisona" placeholder="Sisesta otsisõna" value="<?= htmlspecialchars($otsisona) ?>" />
    <input type="submit" value="Otsi" />
    <table>
        <tr>
            <th><a href="kaubaotsing.php?sort=nimetus">Nimetus</a></th>
            <th><a href="kaubaotsing.php?sort=grupinimi">Kaubagrupp</a></th>
            <th><a href="kaubaotsing.php?sort=hind">Hind</a></th>
        </tr>
        <?php foreach ($kaubad as $kaup): ?>
            <tr>
                <td><?= $kaup->nimetus ?></td>
                <td><?= $kaup->grupinimi ?></td>
                <td><?= $kaup->hind ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

</body>
</html>
