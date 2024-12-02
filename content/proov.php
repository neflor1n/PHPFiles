<?php
echo "<h1>TERE HOMMIKUST!</h1>";
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
echo strtoupper(string: $text);
echo "<br>";
// koik tahed on vaiksed
echo strtolower(string: $text);
echo "<br>";
// iga sona algab suure tahega
echo ucwords(string: $text);
echo "<br>";
// teksti pikkus
echo "Teksti pikkus - ".strlen(string: $text);
echo "<br>";
echo "Esimesed 9 tahte - ".substr(string: $text, offset: 0, length: 9);
echo "<br>";
$otsing = 'on';
echo "On asukoht lauses on ".strpos($text, $otsing);
echo "<br>";
// eralda esimene sona kuni $otsing
echo substr($text, 0, strpos($text, $otsing));
echo "<br>";
// eralda peale esimest sona, alates 'on'
echo substr($text, strpos($text, $otsing));
echo "<br>";

echo "<h2> Kasutame veebis kasutavaid naidised </h2>";
echo "<br>";
//sõnade arv lauses

echo 'sõnade arv lauses - '.str_word_count($text);
// iseseisevalt - teksti karpimine
echo "<br>";

$text2 = 'Põhitoetus võetakse ära 11.11 kui võlgnevused ei ole parandatud';

/*
$text2 = str_replace('õ', '', $text2);

echo $text2;
*/

//echo trim($text2, "P, t..t, v");
//echo "<br>";
//$text3 = 'A woman should soften but not weaken a man';
//echo trim($text3, "A, a, k..n, w");


$massiivitext = 'Taiendav info opilane kohta';
$sona = str_word_count(string: $massiivitext, format: 1);

print_r($sona);
echo "<br>";
echo "kolmas sona - ".$sona[2];
echo "<br>";
echo "1.taht - ".$massiivitext[0];
echo "<br>";


