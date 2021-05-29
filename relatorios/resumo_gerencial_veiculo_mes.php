<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
//$id = $_GET["id"];
$formato 	= $_POST["formato"];
$ano	 	= $_POST["ano"];
//$cod_veiculo= $_POST["veiculo"];

if(!empty($_POST["uf"])){
	$a_uf = implode("','",$_POST["uf"]);	
}
if(!empty($_POST["veiculo"])){
	$a_veiculo = implode(',',$_POST["veiculo"]);	
}

/*if($ano == "all"){
	$where = '';
	$ref_ano = '';
}else{
	$where = "AND ano = '".$ano."'";
	$ref_ano = 'Período: '.$ano;
}*/

/*if($a_veiculo == ""){
	$where_veic = "";
	$ref_veic = "";
}else{
	$where_veic = " AND ukey_vehicles = '".$cod_veiculo."'";
	$ref_veic = "";
}*/

//LOGICA DE TRANSAÇÃO
if($a_uf == ""){
	$where_uf = "";
	$ref_uf	= "Todas";
}else{
	$where_uf = " AND uf IN ('".$a_uf."')";
	$ref_uf	= str_replace("','", ', ', $a_uf);
}

if($a_veiculo == ""){
	$where_veic = "";
	$ref_veiculo = "Todos";
}else{
	$where_veic = " AND ukey_vehicles IN (".$a_veiculo.")";
	$ref_veiculo = $a_veiculo;
}


if($formato == "excel"){
	
	if($_SESSION["mod_certificado"] == 1){
		
		// Nome do Arquivo do Excel que será gerado
		$arquivo = 'resumo_gerencial_veiculo.xls';
		
		// Criamos uma tabela HTML com o formato da planilha para excel
		$tabela = '
		<html lang ="pt-br">
		<head>
		<meta charset="utf-8">
		<title>Resumo Gerencial Veículo</title>
		</head>
		<body>
		<table border="1">
			<tr>
				<th colspan="14">VENDAS / '.$ano.'</th>
			</tr>
			<tr>
				<th>VEICULO</th>
				<th>JAN</th>
				<th>FEV</th>
				<th>MAR</th>
				<th>ABR</th>
				<th>MAI</th>
				<th>JUN</th>
				<th>JUL</th>
				<th>AGO</th>
				<th>SET</th>
				<th>OUT</th>
				<th>NOV</th>
				<th>DEZ</th>
				<th>VENDA BRUTA</th>
			</tr>';


		$busca1v = "
		SELECT
			veiculo,
			ukey_vehicles ,
			SUM(valor_unit) venda_bruta,
			IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
		FROM(
			SELECT
				valor_unit,
				ukey_vehicles,
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
				(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
				(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
				(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM `mp_pedidos`
			WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'".$where_veic."
		) dados
		WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
		GROUP BY veiculo
		ORDER BY veiculo";

		$sql1v = mysqli_query($con, $busca1v) or die("ERRO NO COMANDO SQL1");
		$num_rows1v = mysqli_num_rows($sql1v);


		while($monta1v = mysqli_fetch_array($sql1v)){

			$veiculov 		= $monta1v["veiculo"];
			$ukey_vehiclesv	= $monta1v["ukey_vehicles"];
			$venda_brutav	= inteiro_decimal_br($monta1v["venda_bruta"]);
			$comissaov		= inteiro_decimal_br($monta1v["comissao"]);


			$busca2v = "
			SELECT 
				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-01-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jan,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-02-01' AND '".$ano."-02-28' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)fev,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-03-01' AND '".$ano."-03-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)mar,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-04-01' AND '".$ano."-04-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)abr,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-05-01' AND '".$ano."-05-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)mai,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-06-01' AND '".$ano."-06-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jun,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-07-01' AND '".$ano."-07-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jul,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-08-01' AND '".$ano."-08-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)ago,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-09-01' AND '".$ano."-09-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)`set`,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-10-01' AND '".$ano."-10-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)`out`,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-11-01' AND '".$ano."-11-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)nov,

				(SELECT
					SUM(valor_unit) valor_unit
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-12-01' AND '".$ano."-12-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)dez
			";

			$sql2v = mysqli_query($con, $busca2v) or die("ERRO NO COMANDO SQL2");
			//$num_rows2 = mysqli_num_rows($sql2);
			$monta2v = mysqli_fetch_array($sql2v);
			$janv	= inteiro_decimal_br($monta2v["jan"]);
			$fevv	= inteiro_decimal_br($monta2v["fev"]);
			$marv	= inteiro_decimal_br($monta2v["mar"]);
			$abrv	= inteiro_decimal_br($monta2v["abr"]);
			$maiv	= inteiro_decimal_br($monta2v["mai"]);
			$junv	= inteiro_decimal_br($monta2v["jun"]);
			$julv	= inteiro_decimal_br($monta2v["jul"]);
			$agov	= inteiro_decimal_br($monta2v["ago"]);
			$setv	= inteiro_decimal_br($monta2v["set"]);
			$outv	= inteiro_decimal_br($monta2v["out"]);
			$novv	= inteiro_decimal_br($monta2v["nov"]);
			$dezv	= inteiro_decimal_br($monta2v["dez"]);

			
			$tabela .= '
				<tr>
					<td>'.$veiculov.'</td>
					<td>'.$janv.'</td>
					<td>'.$fevv.'</td>
					<td>'.$marv.'</td>
					<td>'.$abrv.'</td>
					<td>'.$maiv.'</td>
					<td>'.$junv.'</td>
					<td>'.$julv.'</td>
					<td>'.$agov.'</td>
					<td>'.$setv.'</td>
					<td>'.$outv.'</td>
					<td>'.$novv.'</td>
					<td>'.$dezv.'</td>
					<td>'.$venda_brutav.'</td>
				</tr>	
				';
		}
		
///COMISSÃO
			$tabela .= '
			<tr>
				<th colspan="14">COMISSÃO / '.$ano.'</th>
			</tr>
			<tr>
				<th>VEICULO</th>
				<th>JAN</th>
				<th>FEV</th>
				<th>MAR</th>
				<th>ABR</th>
				<th>MAI</th>
				<th>JUN</th>
				<th>JUL</th>
				<th>AGO</th>
				<th>SET</th>
				<th>OUT</th>
				<th>NOV</th>
				<th>DEZ</th>
				<th>COMISSÃO</th>
			</tr>';

		
		$busca1 = "
		SELECT
			veiculo,
			ukey_vehicles ,
			SUM(valor_unit) venda_bruta,
			IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
		FROM(
			SELECT
				valor_unit,
				ukey_vehicles,
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
				(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
				(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
				(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM `mp_pedidos`
			WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'".$where_veic."
		) dados
		WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
		GROUP BY veiculo
		ORDER BY veiculo";

		$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
		$num_rows1 = mysqli_num_rows($sql1);


		while($monta1 = mysqli_fetch_array($sql1)){

			$veiculo 		= $monta1["veiculo"];
			$ukey_vehicles	= $monta1["ukey_vehicles"];
			$venda_bruta	= inteiro_decimal_br($monta1["venda_bruta"]);
			$comissao		= inteiro_decimal_br($monta1["comissao"]);


			$busca2 = "
			SELECT 
				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-01-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jan,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-02-01' AND '".$ano."-02-28' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)fev,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-03-01' AND '".$ano."-03-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)mar,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-04-01' AND '".$ano."-04-30' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)abr,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-05-01' AND '".$ano."-05-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)mai,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-06-01' AND '".$ano."-06-30' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jun,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-07-01' AND '".$ano."-07-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)jul,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-08-01' AND '".$ano."-08-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)ago,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-09-01' AND '".$ano."-09-30' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)`set`,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-10-01' AND '".$ano."-10-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)`out`,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-11-01' AND '".$ano."-11-30' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)nov,

				(SELECT
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM (
					SELECT
						valor_unit,
						ukey_vehicles,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					WHERE fim_veiculacao BETWEEN '".$ano."-12-01' AND '".$ano."-12-31' AND ukey_vehicles = '".$ukey_vehicles."'
					) dadojan
				WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
				)dez
			";

			$sql2 = mysqli_query($con, $busca2) or die("ERRO NO COMANDO SQL2");
			//$num_rows2 = mysqli_num_rows($sql2);
			$monta2 = mysqli_fetch_array($sql2);
			$jan	= inteiro_decimal_br($monta2["jan"]);
			$fev	= inteiro_decimal_br($monta2["fev"]);
			$mar	= inteiro_decimal_br($monta2["mar"]);
			$abr	= inteiro_decimal_br($monta2["abr"]);
			$mai	= inteiro_decimal_br($monta2["mai"]);
			$jun	= inteiro_decimal_br($monta2["jun"]);
			$jul	= inteiro_decimal_br($monta2["jul"]);
			$ago	= inteiro_decimal_br($monta2["ago"]);
			$set	= inteiro_decimal_br($monta2["set"]);
			$out	= inteiro_decimal_br($monta2["out"]);
			$nov	= inteiro_decimal_br($monta2["nov"]);
			$dez	= inteiro_decimal_br($monta2["dez"]);

			
			$tabela .= '
				<tr>
					<td>'.$veiculo.'</td>
					<td>'.$jan.'</td>
					<td>'.$fev.'</td>
					<td>'.$mar.'</td>
					<td>'.$abr.'</td>
					<td>'.$mai.'</td>
					<td>'.$jun.'</td>
					<td>'.$jul.'</td>
					<td>'.$ago.'</td>
					<td>'.$set.'</td>
					<td>'.$out.'</td>
					<td>'.$nov.'</td>
					<td>'.$dez.'</td>
					<td>'.$comissao.'</td>
				</tr>	
				';
		}

		
///

		$tabela .= '
		</table>
	</body>
	</html>';
		
	// Força o Download do Arquivo Gerado
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename={$arquivo}" );
	header ("Content-Description: PHP Generated Data" );
	
	echo $tabela;
	}

}else{
	

//if(isset($id)){
if($_SESSION["mod_certificado"] == 1){

	ob_start();
	
	//VENDA//////////////////////////////
	echo '
	<h4>VENDAS</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom" style="width:15%;">VEICULO</th>
				<th class="borda_topbottom">JAN</th>
				<th class="borda_topbottom">FEV</th>
				<th class="borda_topbottom">MAR</th>
				<th class="borda_topbottom">ABR</th>
				<th class="borda_topbottom">MAI</th>
				<th class="borda_topbottom">JUN</th>
				<th class="borda_topbottom">JUL</th>
				<th class="borda_topbottom">AGO</th>
				<th class="borda_topbottom">SET</th>
				<th class="borda_topbottom">OUT</th>
				<th class="borda_topbottom">NOV</th>
				<th class="borda_topbottom">DEZ</th>
				<th class="borda_topbottom">VENDA</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1v = "
	SELECT
		veiculo,
		ukey_vehicles ,
		SUM(valor_unit) venda_bruta,
		IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
	FROM(
		SELECT
			valor_unit,
			ukey_vehicles,
			(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
		FROM `mp_pedidos`
		WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'".$where_veic."
	) dados
	WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
	GROUP BY veiculo
	ORDER BY veiculo";

	$sql1v = mysqli_query($con, $busca1v) or die("ERRO NO COMANDO SQL1");
	$num_rows1v = mysqli_num_rows($sql1v);
	
	
	while($monta1v = mysqli_fetch_array($sql1v)){
		
		$veiculov 		= $monta1v["veiculo"];
		$ukey_vehiclesv	= $monta1v["ukey_vehicles"];
		$venda_brutav	= inteiro_decimal_br($monta1v["venda_bruta"]);
		$comissaov		= inteiro_decimal_br($monta1v["comissao"]);
			
		
		$busca2v = "
		SELECT 
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-01-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jan,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-02-01' AND '".$ano."-02-28' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)fev,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-03-01' AND '".$ano."-03-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)mar,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-04-01' AND '".$ano."-04-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)abr,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-05-01' AND '".$ano."-05-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)mai,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-06-01' AND '".$ano."-06-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jun,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-07-01' AND '".$ano."-07-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jul,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-08-01' AND '".$ano."-08-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)ago,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-09-01' AND '".$ano."-09-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)`set`,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-10-01' AND '".$ano."-10-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)`out`,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-11-01' AND '".$ano."-11-30' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)nov,
			
			(SELECT
				SUM(valor_unit) valor_unit
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-12-01' AND '".$ano."-12-31' AND ukey_vehicles = '".$ukey_vehiclesv."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)dez
		";

		$sql2v = mysqli_query($con, $busca2v) or die("ERRO NO COMANDO SQL2");
		//$num_rows2 = mysqli_num_rows($sql2);
		$monta2v = mysqli_fetch_array($sql2v);
		$janv	= inteiro_decimal_br($monta2v["jan"]);
		$fevv	= inteiro_decimal_br($monta2v["fev"]);
		$marv	= inteiro_decimal_br($monta2v["mar"]);
		$abrv	= inteiro_decimal_br($monta2v["abr"]);
		$maiv	= inteiro_decimal_br($monta2v["mai"]);
		$junv	= inteiro_decimal_br($monta2v["jun"]);
		$julv	= inteiro_decimal_br($monta2v["jul"]);
		$agov	= inteiro_decimal_br($monta2v["ago"]);
		$setv	= inteiro_decimal_br($monta2v["set"]);
		$outv	= inteiro_decimal_br($monta2v["out"]);
		$novv	= inteiro_decimal_br($monta2v["nov"]);
		$dezv	= inteiro_decimal_br($monta2v["dez"]);
		
		
		echo '
			<tr>
				<td>'.$veiculov.'</td>
				<td class="right">'.$janv.'</td>
				<td class="right">'.$fevv.'</td>
				<td class="right">'.$marv.'</td>
				<td class="right">'.$abrv.'</td>
				<td class="right">'.$maiv.'</td>
				<td class="right">'.$junv.'</td>
				<td class="right">'.$julv.'</td>
				<td class="right">'.$agov.'</td>
				<td class="right">'.$setv.'</td>
				<td class="right">'.$outv.'</td>
				<td class="right">'.$novv.'</td>
				<td class="right">'.$dezv.'</td>
				<td class="right">'.$venda_brutav.'</td>
			</tr>	
			';
	}
	
	echo '
		</tbody>
	</table>
	';
	//fim tabela
	
	//COMISSÃO//////////////////////////////
	echo '
	<h4>COMISSÃO</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom" style="width:15%;">VEICULO</th>
				<th class="borda_topbottom">JAN</th>
				<th class="borda_topbottom">FEV</th>
				<th class="borda_topbottom">MAR</th>
				<th class="borda_topbottom">ABR</th>
				<th class="borda_topbottom">MAI</th>
				<th class="borda_topbottom">JUN</th>
				<th class="borda_topbottom">JUL</th>
				<th class="borda_topbottom">AGO</th>
				<th class="borda_topbottom">SET</th>
				<th class="borda_topbottom">OUT</th>
				<th class="borda_topbottom">NOV</th>
				<th class="borda_topbottom">DEZ</th>
				<th class="borda_topbottom">COMISSÃO</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1 = "
	SELECT
		veiculo,
		ukey_vehicles ,
		SUM(valor_unit) venda_bruta,
		IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
	FROM(
		SELECT
			valor_unit,
			ukey_vehicles,
			(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
		FROM `mp_pedidos`
		WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'".$where_veic."
	) dados
	WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
	GROUP BY veiculo
	ORDER BY veiculo";

	$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
	$num_rows1 = mysqli_num_rows($sql1);
	
	
	while($monta1 = mysqli_fetch_array($sql1)){
		
		$veiculo 		= $monta1["veiculo"];
		$ukey_vehicles	= $monta1["ukey_vehicles"];
		$venda_bruta	= inteiro_decimal_br($monta1["venda_bruta"]);
		$comissao		= inteiro_decimal_br($monta1["comissao"]);
			
		
		$busca2 = "
		SELECT 
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-01-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jan,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-02-01' AND '".$ano."-02-28' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)fev,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-03-01' AND '".$ano."-03-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)mar,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-04-01' AND '".$ano."-04-30' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)abr,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-05-01' AND '".$ano."-05-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)mai,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-06-01' AND '".$ano."-06-30' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jun,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-07-01' AND '".$ano."-07-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)jul,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-08-01' AND '".$ano."-08-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)ago,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-09-01' AND '".$ano."-09-30' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)`set`,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-10-01' AND '".$ano."-10-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)`out`,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-11-01' AND '".$ano."-11-30' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)nov,
			
			(SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
			FROM (
				SELECT
					valor_unit,
					ukey_vehicles,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				WHERE fim_veiculacao BETWEEN '".$ano."-12-01' AND '".$ano."-12-31' AND ukey_vehicles = '".$ukey_vehicles."'
				) dadojan
			WHERE ukey_status <> '8' AND ukey_status <> '9'".$where_uf."
			)dez
		";

		$sql2 = mysqli_query($con, $busca2) or die("ERRO NO COMANDO SQL2");
		//$num_rows2 = mysqli_num_rows($sql2);
		$monta2 = mysqli_fetch_array($sql2);
		$jan	= inteiro_decimal_br($monta2["jan"]);
		$fev	= inteiro_decimal_br($monta2["fev"]);
		$mar	= inteiro_decimal_br($monta2["mar"]);
		$abr	= inteiro_decimal_br($monta2["abr"]);
		$mai	= inteiro_decimal_br($monta2["mai"]);
		$jun	= inteiro_decimal_br($monta2["jun"]);
		$jul	= inteiro_decimal_br($monta2["jul"]);
		$ago	= inteiro_decimal_br($monta2["ago"]);
		$set	= inteiro_decimal_br($monta2["set"]);
		$out	= inteiro_decimal_br($monta2["out"]);
		$nov	= inteiro_decimal_br($monta2["nov"]);
		$dez	= inteiro_decimal_br($monta2["dez"]);
		
		
		echo '
			<tr>
				<td>'.$veiculo.'</td>
				<td class="right">'.$jan.'</td>
				<td class="right">'.$fev.'</td>
				<td class="right">'.$mar.'</td>
				<td class="right">'.$abr.'</td>
				<td class="right">'.$mai.'</td>
				<td class="right">'.$jun.'</td>
				<td class="right">'.$jul.'</td>
				<td class="right">'.$ago.'</td>
				<td class="right">'.$set.'</td>
				<td class="right">'.$out.'</td>
				<td class="right">'.$nov.'</td>
				<td class="right">'.$dez.'</td>
				<td class="right">'.$comissao.'</td>
			</tr>	
			';
	}
	
	echo '
		</tbody>
	</table>
	';
	//fim tabela
	
	

	
	$html = ob_get_clean();

	
	$header = '
	<div style="float:left;width:110px;">
		<img src="../img/duemidia100.png">
	</div>
	<div style="float:left;width:500px; padding-left:20px;">
		<h4>RESUMO GERENCIAL MENSAL POR VEÍCULO</h4>
		'.date('d/m/Y H:m:s').'<br>Referente a: '.$ano.'<br>
		Praça: '.$ref_uf.'<br>
		Veículos: '.$ref_veiculo.'
	</div>
	';

	$footer = '
	<div style="font-weight:bold;font-size:8pt;text-align:center;">

	</div>
	<div style="font-weight:bold;font-size:7pt;text-align:center;">
	
	</div>
	<div style="font-weight:bold;font-size:7pt;text-align:center;">

	</div>
	';
		

}else{
	ob_start();
	echo '
	<h1>Indisponível</h1>
	';
	$html = ob_get_clean();
	
	$header = '
	<div style="float:left;width:110px;">
		<img src="../img/duemidia100.png">
	</div>
	<div style="float:left;width:500px; padding-left:20px;">
		<h4>RESUMO GERENCIAL MENSAL POR VEÍCULO</h4>
		'.date('d/m/Y H:m:s').'
	</div>
	';
	
	$footer = '
	<div style="font-weight:bold;font-size:7pt;text-align:center;">
		
	</div>
	<div style="font-weight:bold;font-size:7pt;text-align:center;">
	</div>
	';
	
}

$name = 'RESUMO FATURAMENTO_'.date('d/m/Y').'.pdf';

//==============================================================
//==============================================================
//==============================================================

require_once '../vendor/autoload.php';

//$mpdf=new mPDF(); 
//$mpdf=new mPDF('utf-8', 'A4-L');
//$mpdf=new mPDF('','A4-L','','',15,15,30,25,9,9); 
$mpdf=new mPDF('','A4-L','','',9,9,35,25,9,7); 

// LOAD a stylesheet
$stylesheet = file_get_contents('style_mediaplus.css');
$mpdf->WriteHTML($stylesheet,1);// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->SetHTMLHeader($header);

$mpdf->defaultfooterfontsize = 10; /* in pts */
$mpdf->defaultfooterfontstyle = B; /* blank, B, I, or BI */
$mpdf->defaultfooterline = 1; /* 1 to include line below header/above footer */
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($html);

$mpdf->Output($name,'I');
exit;

//==============================================================
//==============================================================
//==============================================================

}
	
?>