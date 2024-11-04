<?php
echo "TERE HOMMIKUST!";
echo "<br>";
$muutuja = 'PHP in skriptikeel';
echo "<strong>";
echo $muutuja;
echo "</strong>";
echo "<br>";
echo "<h2> Tekstifunktsioonid</h2>";
$text ='Esmaspaev on 4.november';

echo $text;
echo "<br>";
// koik tahed on suured
echo strtoupper($text);
echo "<br>";
// koik tahed on vaiksed
echo strtolower($text);
echo "<br>";
// iga sona algab suure tahega
echo ucwords($text);
echo "<br>";
// teksti pikkus
echo "Teksti pikkus - ".strlen($text);
echo "<br>";

echo "Esimesed 9 tahte - ".substr($text, 0, 9);