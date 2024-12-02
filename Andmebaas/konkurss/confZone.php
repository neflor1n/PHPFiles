<?php
$kasutaja = "d132041_bogdansergachev";
$parool = "Bg251020070309";
$andmebaas = "d132041_baasphp";
$servernimi = "d132041.mysql.zonevs.eu";

$yhendus = new mysqli(hostname: $servernimi, username: $kasutaja, password: $parool, database: $andmebaas);

$yhendus->set_charset("utf8");


