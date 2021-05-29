<?php
function inverte_data($data,$separador){
	$nova_data = implode("".$separador."",array_reverse(explode("".$separador."",$data)));
echo $nova_data;
}

function decimal_br($numero){
    $numero = number_format($numero, 2, ',', '.'); 
    return $numero;
}

function decimal_en($numero){
    $numero = number_format($numero, 2, '.', ''); 
    return $numero;
}

function mask($val, $mask){
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++){
		if($mask[$i] == '#'){
			if(isset($val[$k]))
			$maskared .= $val[$k++];
		}else{
			if(isset($mask[$i]))
			$maskared .= $mask[$i];
		}
	 }
	 return $maskared;
}

//VARIAVEIS
$date 	= date("Y-m-d");
$day	= date("d");
$month	= date("m");
$year	= date("Y");
?>