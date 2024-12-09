<?php
require_once ("conf.php");
global $yhendus;
function kysiKaupadeAndmed($sorttulp, $otsisona) {
    global $yhendus;

    $query = "SELECT kaubad.id, kaubad.nimetus, kaubad.hind, kaubagrupid.grupinimi
              FROM kaubad
              JOIN kaubagrupid ON kaubad.kaubagrupi_id = kaubagrupid.id";

    if (!empty($otsisona)) {
        $query .= " WHERE kaubad.nimetus LIKE ?";
        $otsisona = "%" . $otsisona . "%";
    }

    $query .= " ORDER BY " . $sorttulp;

    $stmt = $yhendus->prepare($query);
    if (!empty($otsisona)) {
        $stmt->bind_param("s", $otsisona);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $kaubad = [];
    while ($row = $result->fetch_object()) {
        $kaubad[] = $row;
    }

    $stmt->close();
    return $kaubad;
}


/**
 * Luuakse HTML select-valik, kus v6etakse v22rtuseks sqllausest tulnud  * esimene tulp ning n2idatakse teise tulba oma.
 */
function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
    global $yhendus;
    $kask=$yhendus->prepare($sqllause);
    $kask->bind_result($id, $sisu);
    $kask->execute();
    $tulemus="<select name='$valikunimi'>";
    while($kask->fetch()){
        $lisand="";
        if($id==$valitudid){$lisand=" selected='selected'";}
        $tulemus.="<option value='$id' $lisand >$sisu</option>";
    }
    $tulemus.="</select>";
    return $tulemus;
}
/*
 function looRippMenyy($sqllause, $valikunimi){
 global $yhendus;
 $kask=$yhendus->prepare($sqllause);
 $kask->bind_result($id, $sisu);
 $kask->execute();
 $tulemus="<select name='$valikunimi'>";
 while($kask->fetch()){
 $tulemus.="<option value='$id'>$sisu</option>";
 }
 $tulemus.="</select>";
 return $tulemus;
 }
*/

function lisaGrupp($grupinimi){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO kaubagrupid (grupinimi)  VALUES (?)");
    $kask->bind_param("s", $grupinimi);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");

}

// Функция для добавления товара
function lisaKaup($nimetus, $kaubagrupi_id, $hind) {
    global $yhendus;

    $nimetus = trim($nimetus);
    $hind = floatval($hind);

    // Проверка, существует ли уже товар с таким же названием
    $query = "SELECT COUNT(*) FROM kaubad WHERE nimetus = ?";
    $stmt = $yhendus->prepare($query);

    if (!$stmt) {
        die("Ошибка при подготовке запроса: " . $yhendus->error);
    }

    $stmt->bind_param("s", $nimetus);
    $stmt->execute();
    $stmt->bind_result($count);

    if (!$stmt->fetch()) {
        die("Ошибка при извлечении результата: " . $stmt->error);
    }

    $stmt->close();

    if ($count > 0) {
        return false;
    }

    $insert_query = "INSERT INTO kaubad (nimetus, kaubagrupi_id, hind) VALUES (?, ?, ?)";
    $stmt = $yhendus->prepare($insert_query);
    $stmt->bind_param("sds", $nimetus, $kaubagrupi_id, $hind);
    $stmt->execute();
    $stmt->close();

    return true;
}



function kustutaKaup($kauba_id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM kaubad WHERE id=?");
    $kask->bind_param("i", $kauba_id);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");

}

function muudaKaup($kauba_id, $nimetus, $kaubagrupi_id, $hind){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE kaubad SET nimetus=?, kaubagrupi_id=?, hind=?  WHERE id=?");

    $kask->bind_param("sidi", $nimetus, $kaubagrupi_id, $hind, $kauba_id);  $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");

}
//---------------
?>
<?php
$pathParts = explode("/", $_SERVER["PHP_SELF"]);
if (array_pop($pathParts) == "abifunktsioonid.php"): ?>
    <pre>
<?php
print_r(kysiKaupadeAndmed("hind", "fass\\aad"));
?>
</pre>
<?php endif ?>


