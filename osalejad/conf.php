<?php
$kasutaja = "bogdan";
$parool = "123456";
$andmebaas = "bogdan";
$servernimi = "localhost";

$yhendus = new mysqli(hostname: $servernimi, username: $kasutaja, password: $parool, database: $andmebaas);

$yhendus->set_charset("utf8");


