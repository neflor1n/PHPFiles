<?php
echo "<h1 style='text-align: center;'>Mõistatus. Euroopa riik</h1>";

$riik = 'Eesti';

// -------------------------------------- E --------------------------------------

echo '<ul>';
echo "<li style='position: relative; left: 300px;'>Esimene täht riigis on - ".substr($riik, 0, 1). '</li>';
echo '</ul>';

// -------------------------------------- E --------------------------------------

$text = 'Riigist sai esimene riik maailmas, mis võttis kasutusele elektroonilised valimised';
$words = explode(' ', $text);
$words[2] = substr($words[2], 1);
$newText = implode(' ', $words);


echo '<ul>';
echo '<li style="position: relative; left: 300px;">'.$newText.'</li>';
echo '</ul>';

// -------------------------------------- S --------------------------------------

$text1 = 'Seal on 1.3 millionid inimesed';
$modifiedText = str_replace("s", "*", $text1);


echo '<ul>';
echo "<li style='position: relative; left: 300px;'>$modifiedText</li>"; 
echo '</ul>';


// -------------------------------------- T --------------------------------------

$text2 = '400 korda rohkem meteoriidikraatreid pindalaühiku kohta kui Maa keskmine';
$words2 = explode(' ', $text2);
$words2[3] = str_replace('t', '', $words2[3]);
$newText2 = implode(' ', $words2);

echo '<ul>';
echo '<li style="position: relative; left: 300px;">'.$newText2.'</li>';
echo '</ul>';

// -------------------------------------- I --------------------------------------

$text3 = 'Metsad hõivavad 52% pindalast';
$words3 = explode(' ', $text3);
$words3[1] = str_replace('i', '', $words3[1]);
$newText3 = implode(' ', $words3);

echo '<ul>';
echo '<li style="position: relative; left: 300px;">'.$newText3.'</li>';
echo '</ul>';

