<?php
require "../inc/conect.php";

/* Get the position data from the POST parameters */
$lat = $_POST["latitude"];
$lon = $_POST["longitude"];
$acc = $_POST["accuracy"];
$dev = $_POST["deviceid"];
$tim = $_POST["time"];

/* Teste */
//$lat = '-23.4863904';
//$lon = '-46.7531683';
//$acc = '804.0';
//$dev = '352167041588881';
//$tim = '2014-05-20 16:00:00';
if($lat <> 0 and $lon <> 0 and $dev <> 0){

if($dev == '353201050642773'){
	$portador = 'Luis';
}elseif($dev == '353201050642880'){
	$portador = 'Tokuji';
}elseif($dev == '353201050642427'){
	$portador = 'Alves';
}elseif($dev == '353201050642104'){
	$portador = 'Rubens';
}elseif($dev == '353201050644126'){
	$portador = 'Robison';
}elseif($dev == '353201050644068'){
	$portador = 'Guilherme';
}elseif($dev == 'Roberto'){
	$portador = 'Roberto';
}

$table = "kn_monitora";

$select_insert = "INSERT INTO ".$table." (lat, lon, acc, dev, tim, portador) VALUES ('".$lat."','".$lon."','".$acc."','".$dev."','".$tim."','".$portador."')";
$sql_insert = mysql_query($select_insert) or die("ERRO NO COMANDO INSERIR SQL");

}else{
	
}
?>