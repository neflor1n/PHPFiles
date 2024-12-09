<?php
require("abifunktsioonid.php");

if (isset($_REQUEST["grupilisamine"])) {
    lisaGrupp($_REQUEST["uuegrupinimi"]);
    header("Location: kaubahaldus.php");
    exit();
}
if (!empty($_REQUEST["kaubalisamine"]) && isset($_REQUEST["kaubalisamine"])) {
    lisaKaup($_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
    header("Location: kaubahaldus.php");
    exit();
}
if (isset($_REQUEST["kustutusid"])) {
    kustutaKaup($_REQUEST["kustutusid"]);
}
if (isset($_REQUEST["muutmine"])) {
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
}

$kaubad = kysiKaupadeAndmed();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        #lisamis{
            color: #333;
            font-size: 1.5em;
            margin-bottom: 15px;
            margin-left: 100px;
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
        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<form action="kaubahaldus.php">
    <h2 id="lisamis">Kauba lisamine</h2>
    <dl>
        <dt>Nimetus:</dt>
        <dd><input type="text" name="nimetus" /></dd>
        <dt>Kaubagrupp:</dt>
        <dd>
            <?php echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupi_id"); ?>
        </dd>
        <dt>Hind:</dt>
        <dd><input type="text" name="hind" /></dd>
    </dl>
    <input type="submit" name="kaubalisamine" value="Lisa kaup" />
    <h2 id="lisamis">Grupi lisamine</h2>
    <input type="text" name="uuegrupinimi" />
    <input type="submit" name="grupilisamine" value="Lisa grupp" />
</form>

<form action="kaubahaldus.php">
    <h2>Kaupade loetelu</h2>
    <table>
        <tr>
            <th>Haldus</th>
            <th>Nimetus</th>
            <th>Kaubagrupp</th>
            <th>Hind</th>
        </tr>
        <?php foreach ($kaubad as $kaup): ?>
            <tr>
                <?php if (isset($_REQUEST["muutmisid"]) && intval($_REQUEST["muutmisid"]) == $kaup->id): ?>
                    <td>
                        <input type="submit" name="muutmine" value="Muuda" />
                        <input type="submit" name="katkestus" value="Katkesta" />
                        <input type="hidden" name="muudetudid" value="<?= $kaup->id ?>" />
                    </td>
                    <td><input type="text" name="nimetus" value="<?= $kaup->nimetus ?>" /></td>
                    <td><?php echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupi_id", $kaup->kaubagrupi_id); ?></td>
                    <td><input type="text" name="hind" value="<?= $kaup->hind ?>" /></td>
                <?php else: ?>
                    <td>
                        <a href="kaubahaldus.php?kustutusid=<?= $kaup->id ?>" onclick="return confirm('Kas ikka soovid kustutada?')"><i class='fa-regular fa-trash-can'></i></a>
                        <a href="kaubahaldus.php?muutmisid=<?= $kaup->id ?>">m</a>
                    </td>
                    <td><?= $kaup->nimetus ?></td>
                    <td><?= $kaup->grupinimi ?></td>
                    <td><?= $kaup->hind ?></td>
                <?php endif ?>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

</body>
</html>
