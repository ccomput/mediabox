<?php
require "inc/conect.php";

$esquema = @$_GET["m999"];

$buscasql = "SELECT * FROM kn_m999 WHERE ukey = ".$esquema;
$sql = mysql_query($buscasql) or die("ERRO NO COMANDO SQL");

$montaBox = mysql_fetch_array($sql);
$codigo = $montaBox["tamanho"]."M999/".$montaBox["ukey"];
$pdf = $montaBox["file"];

header('Content-type: application/pdf;');
header('Content-Disposition: inline; filename="'.$codigo.'.pdf";');
echo $pdf;
exit; 

?>	