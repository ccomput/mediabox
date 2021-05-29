<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
//$id = $_GET["id"];
$formato 	= $_POST["formato"];
$ano	 	= $_POST["ano"];
$cod_veiculo= $_POST["veiculo"];

if($ano == "all"){
	$where = '';
	$ref_ano = '';
}else{
	$where = "AND ano = '".$ano."'";
	$ref_ano = 'Período: '.$ano;
}

if($cod_veiculo == ""){
	$where_veic = "";
	$ref_veic = "";
}else{
	$where_veic = " AND veiculo = '".$cod_veiculo."'";
	$ref_veic = "Veículo: ".$cod_veiculo;
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
				<th colspan="5">VENDAS / COMISSÃO / '.$ano.'</th>
			</tr>
			<tr>
				<th>VEICULO</th>
				<th>VENDA BRUTA</th>
				<th>COMISSÃO</th>
				<th>COMISSÃO RECEBIDA</th>
				<th>COMISSÃO RECEBER</th>
			</tr>';


		/*$busca1 = "
		SELECT
			veiculo,
			SUM(valor_unit) venda_bruta,
			IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
		FROM(
			SELECT
				YEAR(fim_veiculacao) ano,
				valor_unit,
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
				(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
				(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
				(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM `mp_pedidos`
		)dados
		WHERE ukey_status <> '8' AND ukey_status <> '9' ".$where.$where_veic." 
		GROUP BY veiculo
		ORDER BY veiculo";*/
		
		$busca1 = "
		SELECT 
			veiculo,
			SUM(valor_unit) venda_bruta,
			SUM(valor_liquido * (comissao/100)) comissao
		FROM (
			SELECT
				veiculo,
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
			FROM(
				SELECT
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
			)dados
			WHERE ukey_status <> '8' AND ukey_status <> '9' ".$where.$where_veic." 
		)dados1
		GROUP BY veiculo
		ORDER BY veiculo";

		$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
		$num_rows1 = mysqli_num_rows($sql1);


		while($monta1 = mysqli_fetch_array($sql1)){

			//$ano 			= $monta1["ano"];
			$veiculo 		= $monta1["veiculo"];
			$venda_bruta	= inteiro_decimal_br($monta1["venda_bruta"]);
			$comissao		= inteiro_decimal_br($monta1["comissao"]);


			/*$busca2 = "
			SELECT
				IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao_recebida
			FROM(
				SELECT
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
			)dados
			WHERE ukey_status = '7' AND veiculo = '".$veiculo."' ".$where;*/
			
			$busca2 = "
			SELECT 
				SUM(valor_liquido * (comissao/100)) comissao_recebida
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM(
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
				)dados0
				WHERE ukey_status = '7' AND veiculo = '".$veiculo."' ".$where."
			)dados1
			";

			$sql2 = mysqli_query($con, $busca2) or die("ERRO NO COMANDO SQL2");
			//$num_rows2 = mysqli_num_rows($sql2);
			$monta2 = mysqli_fetch_array($sql2);
			$comissao_recebida	= inteiro_decimal_br($monta2["comissao_recebida"]);


			$busca3 = "
			SELECT
				SUM(valor_liquido * (comissao/100)) comissao_receber
			FROM (
				SELECT 
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				
				FROM(
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
				)dados0
				WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND veiculo = '".$veiculo."' ".$where."
			)dados1
			";

			$sql3 = mysqli_query($con, $busca3) or die("ERRO NO COMANDO SQL3");
			$monta3 = mysqli_fetch_array($sql3);
			$comissao_receber	= inteiro_decimal_br($monta3["comissao_receber"]);

			
			$tabela .= '
				<tr>
					<td>'.$veiculo.'</td>
					<td>'.$venda_bruta.'</td>
					<td>'.$comissao.'</td>
					<td>'.$comissao_recebida.'</td>
					<td>'.$comissao_receber.'</td>
				</tr>	
				';
		}

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
	
	//GRUPO//////////////////////////////
	echo '
	<h4>VENDAS / COMISSÃO</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom">VEICULO</th>
				<th class="borda_topbottom">VENDA BRUTA</th>
				<th class="borda_topbottom">COMISSÃO</th>
				<th class="borda_topbottom">COMISSÃO RECEBIDA</th>
				<th class="borda_topbottom">COMISSÃO RECEBER</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1 = "
	SELECT 
		veiculo,
		SUM(valor_unit) venda_bruta,
		SUM(valor_liquido * (comissao/100)) comissao
	FROM (
		SELECT
			veiculo,
			valor_unit,
			comissao,
			IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
		FROM(
			SELECT
				YEAR(fim_veiculacao) ano,
				valor_unit,
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
				(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
				(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
				(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM `mp_pedidos`
		)dados
		WHERE ukey_status <> '8' AND ukey_status <> '9' ".$where.$where_veic." 
	)dados1
	GROUP BY veiculo
	ORDER BY veiculo";

	$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
	$num_rows1 = mysqli_num_rows($sql1);
	
	
	while($monta1 = mysqli_fetch_array($sql1)){
		
		//$ano 			= $monta1["ano"];
		$veiculo 		= $monta1["veiculo"];
		$venda_bruta	= inteiro_decimal_br($monta1["venda_bruta"]);
		$comissao		= inteiro_decimal_br($monta1["comissao"]);
			
		
		$busca2 = "
		SELECT 
			SUM(valor_liquido * (comissao/100)) comissao_recebida
		FROM (
			SELECT
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
			FROM(
				SELECT
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
			)dados0
			WHERE ukey_status = '7' AND veiculo = '".$veiculo."' ".$where."
		)dados1
		";

		$sql2 = mysqli_query($con, $busca2) or die("ERRO NO COMANDO SQL2");
		//$num_rows2 = mysqli_num_rows($sql2);
		$monta2 = mysqli_fetch_array($sql2);
		$comissao_recebida	= inteiro_decimal_br($monta2["comissao_recebida"]);
		
		
		$busca3 = "
		SELECT
			SUM(valor_liquido * (comissao/100)) comissao_receber
		FROM (
			SELECT 
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido

			FROM(
				SELECT
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
			)dados0
			WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND veiculo = '".$veiculo."' ".$where."
		)dados1
		";

		$sql3 = mysqli_query($con, $busca3) or die("ERRO NO COMANDO SQL3");
		$monta3 = mysqli_fetch_array($sql3);
		$comissao_receber	= inteiro_decimal_br($monta3["comissao_receber"]);
		
		echo '
			<tr>
				<td>'.$veiculo.'</td>
				<td class="right">'.$venda_bruta.'</td>
				<td class="right">'.$comissao.'</td>
				<td class="right">'.$comissao_recebida.'</td>
				<td class="right">'.$comissao_receber.'</td>
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
		<h4>RESUMO GERENCIAL POR VEÍCULO</h4>
		'.date('d/m/Y H:m:s').'<br>'.$ref_ano.'<br>
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
		<h4>RESUMO GERENCIAL POR VEÍCULO</h4>
		'.date('d/m/Y H:m:s').'<br>'.$ref_ano.'<br>
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