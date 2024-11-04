<?php
echo "<h1>Möistatus. Euroopa riik</h1>";

$riik = 'Eesti';

echo '<ul>';
echo "<li>Esimene täht riigis on - ".substr($riik, 0, 1);
echo '</ul>';

$text1 = 'Seal on 1.3 millionid inimesed';
$modifiedText = str_replace("s", "*", $text1);

echo '<ul>';
echo "<li>$modifiedText</li>"; 
echo '</ul>';
?>