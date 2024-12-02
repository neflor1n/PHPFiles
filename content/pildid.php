<!-- Pealkiri -->
<h2 style="text-align: -webkit-center">
    Töö pildifailidega
</h2>

<a style='margin: 0 0 0 10px;' href="https://www.metshein.com/unit/php-pildifailidega-ulesanne-14/">Töö pildifailidega</a>
<!-- -->
<div id="pildivaatur">
    <!-- Piltide valimise vorm / Форма для выбора изображения -->
    <form method="post" action="">
        <!-- Langev nimekiri piltide valimiseks / Выпадающий список для выбора изображений -->
        <select name="pildid">
        <option value="">Vali pilt</option>
            <?php 
                // Määrame kataloogi, kus pildid asuvad / Указываем каталог, где находятся изображения
                $kataloog = 'content/img';
                // Avame kataloogi lugemiseks / Открываем каталог для чтения
                $asukoht = opendir($kataloog);
                // Kui kataloogi lugemine on edukas / Если чтение каталога прошло успешно
                while($rida = readdir($asukoht)) {
                    // Kui pilt ei ole kataloogi / Если изображение не является каталогом
                    if($rida != '.' && $rida != '..') {
                        echo "<option value='$rida'>$rida</option>\n";
                    }
                }
            ?>
        </select>
        <input type="submit" value="Vaata">
    </form>
</div>
<?php
if(!empty($_POST['pildid'])) {
    $pilt = $_POST['pildid']; // Pilt, mille kasutaja on valinud (vormist saadud väärtus) / Изображение, выбранное пользователем (значение, полученное из формы)
    $pildi_aadress = 'content/img/' . $pilt; // Täispikk tee valitud pildile / Полный путь к выбранному изображению
    $pildi_andmed = getimagesize($pildi_aadress); // Saame pildi mõõdud ja formaadi / Получаем размеры и формат изображения
        
    // Algse pildi mõõdud / Исходные размеры изображения
    $laius = $pildi_andmed[0];
    $korgus = $pildi_andmed[1];
    $formaat = $pildi_andmed[2];

    // Maksimaalsed lubatud mõõdud / Максимально допустимые размеры
    $max_laius = 120;
    $max_korgus = 90;

    // Arvutame pildi proportsioonide suhte / Вычисляем пропорциональное соотношение изображения
    if($laius <= $max_laius && $korgus <= $max_korgus) {
        $ratio = 1; // Kui pilt sobib, ei muudeta mõõtmeid / Если изображение подходит, размеры не меняются
    } elseif($laius > $korgus) {
        $ratio = $max_laius / $laius; // Kui pilt on laiem kui kõrge, muudame laiust / Если изображение шире, чем выше, меняем ширину
    } else {
        $ratio = $max_korgus / $korgus; // Kui pilt on kõrgem kui lai, muudame kõrgust / Если изображение выше, чем шире, меняем высоту
    }
    
    $pisi_laius = round($laius * $ratio);
    $pisi_korgus = round($korgus * $ratio);
    
    echo '<h3>Originaal pildi andmed</h3>';

    echo "<div id=pildit>";
        echo "Laius: $laius<br>";
        echo "Kõrgus: $korgus<br>";
        echo "Formaat: $formaat<br>";
    echo "</div>";

    echo '<h3>Uue pildi andmed</h3>';

    echo "<div id=pildit>";
        echo "Arvutamse suhe: $ratio <br>";
        echo "Laius: $pisi_laius<br>";
        echo "Kõrgus: $pisi_korgus<br>";
        echo "<img width='$pisi_laius' height='$pisi_korgus' src='$pildi_aadress'><br>";
    echo "</div>";
}
?>
</div>
<br>
<br>

<?php
// Suvaline pilt
$pildid = array_diff(scandir($kataloog), array('.', '..'));
$suvaline_pilt = $pildid[array_rand($pildid)];
echo "<div style='text-align: center; margin-top: 30px;'>";
echo "<h2>Suvaline Pilt</h2>";
echo "<div id='suvaline_pilt'>";
echo "<img src='$kataloog/$suvaline_pilt' alt='Suvaline pilt' style='width: 300px; border: 2px solid #333; border-radius: 5px;'><br>";
//echo "<button id='muudaPiltNupp' onclick='muudaPilt()'>Muuda pilt</button>";
echo "</div>";
echo "<p>Suvaline pilt: $suvaline_pilt</p>";
echo "</div>";

// Pisipiltide veerugudes
echo "<h2 style='margin: 0 0 0 10px;'>Pisipiltide veerugudes</h2>";
echo "<div id='gallery'>";

foreach ($pildid as $pilt) {
    $pildi_aadress = $kataloog . '/' . $pilt;

    echo "<div id='gallery-item'>";
    echo "<a href='$pildi_aadress' target='_blank'><img src='$pildi_aadress' alt='$pilt'></a>";
    echo "</div>";
}

echo "</div>";
?>
