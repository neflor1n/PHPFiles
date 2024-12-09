<?php
$kasutaja = "bogdan";
$parool = "123456";
$andmebaas = "bogdan";
$servernimi = "localhost";

$yhendus = new mysqli($servernimi, $kasutaja, $parool, $andmebaas);

$yhendus->set_charset("utf8");


