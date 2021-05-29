<?php
require "../../inc/conect.php";
require "../../inc/security.php";
require "../../inc/verifica.php";

// Informa o navegador que o conteúdo do arquivo é do tipo JSON
header('Content-Type: application/json');

if($_GET['imei'] <> 0){
	//$SQL = "SELECT ukey Id, lat Latitude, lon Longitude, portador Descricao, DATE_FORMAT(time,'%d/%m/%Y %H:%i:%s') Data FROM kn_monitora WHERE dev =".$_GET['imei']." AND date(time) >= '".$_GET['inicio']."' AND date(time) <= '".$_GET['fim']."'";
	$SQL = "SELECT ukey Id, lat Latitude, lon Longitude, portador Descricao, DATE_FORMAT(time,'%d/%m/%Y %H:%i:%s') Data 
	FROM kn_monitora 
	WHERE dev =".$_GET['imei']." AND (date(time) BETWEEN '".$_GET['inicio']."' AND '".$_GET['fim']."') AND (time(time) BETWEEN '07:00:00' AND '17:00:00')";
	$table = mysql_query($SQL) or die(mysql_error());
}else{
	//$SQL = "SELECT ukey Id, lat Latitude, lon Longitude, portador Descricao, DATE_FORMAT(time,'%d/%m/%Y %H:%i:%s') Data FROM kn_monitora WHERE ('".$_GET['inicio']."' <= date(time) AND date(time) <= '".$_GET['fim']."')";
	$SQL = "SELECT ukey Id, lat Latitude, lon Longitude, portador Descricao, DATE_FORMAT(time,'%d/%m/%Y %H:%i:%s') Data FROM kn_monitora WHERE (date(time) BETWEEN '".$_GET['inicio']."' AND '".$_GET['fim']."') AND (time(time) BETWEEN '07:00:00' AND '17:00:00')";
	$table = mysql_query($SQL) or die(mysql_error());
}
 
while ($row = mysql_fetch_array($table)){
	$i=0;
	foreach($row as $key => $value){
		if (is_string($key)){
			$fields[mysql_field_name($table,$i++)] = $value;
		}
	}
	$json_result [ ] = $fields;
}
 
$JSON = json_encode($json_result);
 
print_r($JSON);
 
?>