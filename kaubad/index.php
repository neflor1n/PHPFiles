<?php
require("abifunktsioonid.php");
global $yhendus;
// Обработка запросов на добавление, изменение, удаление товаров и групп
if (isset($_REQUEST["grupilisamine"])) {
    lisaGrupp($_REQUEST["uuegrupinimi"]);
    header("Location: index.php");
    exit();
}
if (isset($_REQUEST["kaubalisamine"])) {
    $nimetus = $_REQUEST["nimetus"];
    $kaubagrupi_id = $_REQUEST["kaubagrupi_id"];
    $hind = $_REQUEST["hind"];

    $result = lisaKaup($nimetus, $kaubagrupi_id, $hind, $yhendus);

    if ($result) {
        header("Location: index.php");
        exit();
    } else {
        // Если товар существует, выводим сообщение
        echo "<script>alert('Selline kaup on juba olemas!');</script>";
    }
}


if (isset($_REQUEST["kustutusid"])) {
    kustutaKaup($_REQUEST["kustutusid"]);
}
if (isset($_REQUEST["muutmine"])) {
    // Обработка данных, которые были изменены
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
    header("Location: index.php"); // Переход на главную страницу после изменения
    exit();
}

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
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kauba haldus</title>
    <script src="https://kit.fontawesome.com/34392d1db2.js" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Форма поиска -->
<form action="index.php" method="GET" class="search-bar">
    <input type="text" name="otsisona" placeholder="Sisesta otsisõna" value="<?= htmlspecialchars($otsisona) ?>" />
    <input type="submit" value="Otsi" />
</form>

<!-- Таблица товаров -->
<h2>Kaubad</h2>
<table class="products-table">
    <thead>
    <tr>
        <th><a href="index.php?sort=nimetus">Nimetus</a></th>
        <th><a href="index.php?sort=grupinimi">Kaubagrupp</a></th>
        <th><a href="index.php?sort=hind">Hind (€)</a></th>
        <th>Tegevused</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($kaubad as $kaup): ?>
        <tr>
            <td><?= htmlspecialchars($kaup->nimetus) ?></td>
            <td><?= htmlspecialchars($kaup->grupinimi) ?></td>
            <td><?= htmlspecialchars($kaup->hind) ?></td>
            <td>
                <a href="index.php?kustutusid=<?= $kaup->id ?>" id="uuenda" onclick="return confirm('Kas ikka soovid kustutada?')"><i class="fa-solid fa-trash"></i></a>
                <a href="index.php?muutmisid=<?= $kaup->id ?>" id="kustuta"><i class="fa-solid fa-pen-to-square"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<!-- Форма изменения товара -->
<?php if (isset($_REQUEST["muutmisid"])): ?>
    <?php
    $kaup_id = $_REQUEST["muutmisid"];
    $kaup_to_edit = null;
    foreach ($kaubad as $kaup) {
        if ($kaup->id == $kaup_id) {
            $kaup_to_edit = $kaup;
            break;
        }
    }
    ?>
    <?php if ($kaup_to_edit): ?>
        <h2>Muuda kauba andmed</h2>
        <form action="index.php" method="POST">
            <input type="hidden" name="muudetudid" value="<?= $kaup_to_edit->id ?>" />
            <div class="form-group">
                <label for="nimetus">Nimetus:</label>
                <input type="text" name="nimetus" value="<?= htmlspecialchars($kaup_to_edit->nimetus) ?>" required />
            </div>
            <div class="form-group">
                <label for="kaubagrupi_id">Kaubagrupp:</label>
                <?= looRippMenyy(
                    "SELECT id, grupinimi FROM kaubagrupid",
                    "kaubagrupi_id",
                    isset($kaup_to_edit->kaubagrupi_id) ? $kaup_to_edit->kaubagrupi_id : null
                ) ?>
            </div>

            <div class="form-group">
                <label for="hind">Hind:</label>
                <input type="text" name="hind" value="<?= htmlspecialchars($kaup_to_edit->hind) ?>" required />
            </div>
            <input type="submit" name="muutmine" value="Muuda" class="button" />
        </form>
    <?php endif; ?>
<?php endif; ?>


<!-- Форма добавления товара -->
<h2>Kauba lisamine</h2>
<form action="index.php" method="POST">
    <div class="form-group">
        <label for="nimetus">Nimetus:</label>
        <input type="text" name="nimetus" required />
    </div>
    <div class="form-group">
        <label for="kaubagrupi_id">Kaubagrupp:</label>
        <?= looRippMenyy("SELECT id, grupinimi FROM kaubagrupid", "kaubagrupi_id") ?>
    </div>
    <div class="form-group">
        <label for="hind">Hind:</label>
        <input type="text" name="hind" required />
    </div>
    <input type="submit" name="kaubalisamine" value="Lisa kaup" class="button" />
</form>

<!-- Форма добавления группы -->
<h2>Grupi lisamine</h2>
<form action="index.php" method="POST">
    <div class="form-group">
        <label for="uuegrupinimi">Grupi nimi:</label>
        <input type="text" name="uuegrupinimi" required />
    </div>
    <input type="submit" name="grupilisamine" value="Lisa grupp" class="button" />
</form>



</body>
</html>