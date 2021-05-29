<?php

/* Get the position data from the POST parameters */
//$lat = $_POST["latitude"];
//$lon = $_POST["longitude"];
//$acc = $_POST["accuracy"];
//$dev = $_POST["deviceid"];

$lat = '-25.363882 ';
$lon = '131.044922 ';
$acc = '804.0 ';
$dev = 'IMEI01';

/* Write the position data to a file for the map script */
if ($lat && $lon && $acc) {
    $fcur = fopen("position.cur", "w");

    $time = time();
    $out = "$time:$lat:$lon:$acc:$dev\n";

    fputs($fcur, $out);
    
    fclose($fcur);
}

?>