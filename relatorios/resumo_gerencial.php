<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
$id = $_GET["id"];

//if(isset($id)){
if($_SESSION["mod_certificado"] == 1){

	ob_start();
	
	//GRUPO//////////////////////////////
	echo '
	<h4>VENDAS / COMISSÃO / GRUPO</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom">ANO</th>
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
		ano,
		SUM(valor_unit) venda_bruta,
		SUM(comissao) comissao,
		SUM(valor_liquido) valor_liquido
	FROM (
		SELECT
			ano,
			valor_unit,
			(valor_liquido * (comissao/100)) comissao,
			valor_liquido
		FROM (
			SELECT
				ukey,
				ano,
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido,
				uf, 
				ukey_status
			FROM (
				SELECT
					ukey,
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				)dados0
			WHERE ukey_status <> '8' AND ukey_status <> '9'
			)dados1
		)dados2
	GROUP BY ano";

	$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
	$num_rows1 = mysqli_num_rows($sql1);
	
	
	while($monta1 = mysqli_fetch_array($sql1)){
		
		$ano 			= $monta1["ano"];
		$venda_bruta	= inteiro_decimal_br($monta1["venda_bruta"]);
		$comissao		= inteiro_decimal_br($monta1["comissao"]);
			
		
		$busca2 = "
		SELECT
			SUM(comissao) comissao_recebida
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status = '7' AND ano = '".$ano."'
				)dados1
			)dados2
		";

		$sql2 = mysqli_query($con, $busca2) or die("ERRO NO COMANDO SQL2");
		//$num_rows2 = mysqli_num_rows($sql2);
		$monta2 = mysqli_fetch_array($sql2);
		$comissao_recebida	= inteiro_decimal_br($monta2["comissao_recebida"]);
		
		
		$busca3 = "
		SELECT
			SUM(comissao) comissao_receber
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND ano = '".$ano."'
				)dados1
			)dados2
		";

		$sql3 = mysqli_query($con, $busca3) or die("ERRO NO COMANDO SQL3");
		$monta3 = mysqli_fetch_array($sql3);
		$comissao_receber	= inteiro_decimal_br($monta3["comissao_receber"]);
		
		echo '
			<tr>
				<td>'.$ano.'</td>
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
	
	
	//DF/////////////////////////////////////
	echo '<br>
	<h4>VENDAS / COMISSÃO / DF</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom">ANO</th>
				<th class="borda_topbottom">VENDA BRUTA</th>
				<th class="borda_topbottom">COMISSÃO</th>
				<th class="borda_topbottom">COMISSÃO RECEBIDA</th>
				<th class="borda_topbottom">COMISSÃO RECEBER</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1df = "
	SELECT
		ano,
		SUM(valor_unit) venda_bruta,
		SUM(comissao) comissao,
		SUM(valor_liquido) valor_liquido
	FROM (
		SELECT
			ano,
			valor_unit,
			(valor_liquido * (comissao/100)) comissao,
			valor_liquido
		FROM (
			SELECT
				ukey,
				ano,
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido,
				uf, 
				ukey_status
			FROM (
				SELECT
					ukey,
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				)dados0
			WHERE ukey_status <> '8' AND ukey_status <> '9' AND uf = 'DF'
			)dados1
		)dados2
	GROUP BY ano
	";

	$sql1df = mysqli_query($con, $busca1df) or die("ERRO NO COMANDO SQL1");
	$num_rows1df = mysqli_num_rows($sql1df);
	
	
	while($monta1df = mysqli_fetch_array($sql1df)){
		
		$anodf 			= $monta1df["ano"];
		$venda_brutadf	= inteiro_decimal_br($monta1df["venda_bruta"]);
		$comissaodf		= inteiro_decimal_br($monta1df["comissao"]);
			
		
		$busca2df = "
		SELECT
			SUM(comissao) comissao_recebida
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status = '7' AND ano = '".$anodf."' AND uf = 'DF'
				)dados1
			)dados2
		";

		$sql2df = mysqli_query($con, $busca2df) or die("ERRO NO COMANDO SQL2");
		$monta2df = mysqli_fetch_array($sql2df);
		$comissao_recebidadf	= inteiro_decimal_br($monta2df["comissao_recebida"]);
		
		
		$busca3df = "
		SELECT
			SUM(comissao) comissao_receber
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND ano = '".$anodf."' AND uf = 'DF'
				)dados1
			)dados2
		";

		$sql3df = mysqli_query($con, $busca3df) or die("ERRO NO COMANDO SQL3");
		$monta3df = mysqli_fetch_array($sql3df);
		$comissao_receberdf	= inteiro_decimal_br($monta3df["comissao_receber"]);
		
		echo '
			<tr>
				<td>'.$anodf.'</td>
				<td class="right">'.$venda_brutadf.'</td>
				<td class="right">'.$comissaodf.'</td>
				<td class="right">'.$comissao_recebidadf.'</td>
				<td class="right">'.$comissao_receberdf.'</td>
			</tr>	
			';
	}
	
	echo '
		</tbody>
	</table>
	';
	//fim tabela
	
	
	//RJ/////////////////////////////////////
	echo '<br>
	<h4>VENDAS / COMISSÃO / RJ</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom">ANO</th>
				<th class="borda_topbottom">VENDA BRUTA</th>
				<th class="borda_topbottom">COMISSÃO</th>
				<th class="borda_topbottom">COMISSÃO RECEBIDA</th>
				<th class="borda_topbottom">COMISSÃO RECEBER</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1rj = "
	SELECT
		ano,
		SUM(valor_unit) venda_bruta,
		SUM(comissao) comissao,
		SUM(valor_liquido) valor_liquido
	FROM (
		SELECT
			ano,
			valor_unit,
			(valor_liquido * (comissao/100)) comissao,
			valor_liquido
		FROM (
			SELECT
				ukey,
				ano,
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido,
				uf, 
				ukey_status
			FROM (
				SELECT
					ukey,
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				)dados0
			WHERE ukey_status <> '8' AND ukey_status <> '9' AND uf = 'RJ'
			)dados1
		)dados2
	GROUP BY ano
	";

	$sql1rj = mysqli_query($con, $busca1rj) or die("ERRO NO COMANDO SQL1");
	$num_rows1rj = mysqli_num_rows($sql1rj);
	
	
	while($monta1rj = mysqli_fetch_array($sql1rj)){
		
		$anorj 			= $monta1rj["ano"];
		$venda_brutarj	= inteiro_decimal_br($monta1rj["venda_bruta"]);
		$comissaorj		= inteiro_decimal_br($monta1rj["comissao"]);
			
		
		$busca2rj = "
		SELECT
			SUM(comissao) comissao_recebida
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status = '7' AND ano = '".$anorj."' AND uf = 'RJ'
				)dados1
			)dados2
		";

		$sql2rj = mysqli_query($con, $busca2rj) or die("ERRO NO COMANDO SQL2");
		$monta2rj = mysqli_fetch_array($sql2rj);
		$comissao_recebidarj	= inteiro_decimal_br($monta2rj["comissao_recebida"]);
		
		
		$busca3rj = "
		SELECT
			SUM(comissao) comissao_receber
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND ano = '".$anorj."' AND uf = 'RJ'
				)dados1
			)dados2
		";

		$sql3rj = mysqli_query($con, $busca3rj) or die("ERRO NO COMANDO SQL3");
		$monta3rj = mysqli_fetch_array($sql3rj);
		$comissao_receberrj	= inteiro_decimal_br($monta3rj["comissao_receber"]);
		
		echo '
			<tr>
				<td>'.$anorj.'</td>
				<td class="right">'.$venda_brutarj.'</td>
				<td class="right">'.$comissaorj.'</td>
				<td class="right">'.$comissao_recebidarj.'</td>
				<td class="right">'.$comissao_receberrj.'</td>
			</tr>	
			';
	}
	
	echo '
		</tbody>
	</table>
	';
	//fim tabela
	
	
	//SP/////////////////////////////////////
	echo '<br>
	<h4>VENDAS / COMISSÃO / SP</h4>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<th class="borda_topbottom">ANO</th>
				<th class="borda_topbottom">VENDA BRUTA</th>
				<th class="borda_topbottom">COMISSÃO</th>
				<th class="borda_topbottom">COMISSÃO RECEBIDA</th>
				<th class="borda_topbottom">COMISSÃO RECEBER</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	$busca1sp = "
	SELECT
		ano,
		SUM(valor_unit) venda_bruta,
		SUM(comissao) comissao,
		SUM(valor_liquido) valor_liquido
	FROM (
		SELECT
			ano,
			valor_unit,
			(valor_liquido * (comissao/100)) comissao,
			valor_liquido
		FROM (
			SELECT
				ukey,
				ano,
				valor_unit,
				comissao,
				IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido,
				uf, 
				ukey_status
			FROM (
				SELECT
					ukey,
					YEAR(fim_veiculacao) ano,
					valor_unit,
					(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
					(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
					(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
					(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM `mp_pedidos`
				)dados0
			WHERE ukey_status <> '8' AND ukey_status <> '9' AND uf = 'SP'
			)dados1
		)dados2
	GROUP BY ano
	";

	$sql1sp = mysqli_query($con, $busca1sp) or die("ERRO NO COMANDO SQL1");
	$num_rows1sp = mysqli_num_rows($sql1sp);
	
	
	while($monta1sp = mysqli_fetch_array($sql1sp)){
		
		$anosp 			= $monta1sp["ano"];
		$venda_brutasp	= inteiro_decimal_br($monta1sp["venda_bruta"]);
		$comissaosp		= inteiro_decimal_br($monta1sp["comissao"]);
			
		
		$busca2sp = "
		SELECT
			SUM(comissao) comissao_recebida
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status = '7' AND ano = '".$anosp."' AND uf = 'SP'
				)dados1
			)dados2
		";

		$sql2sp = mysqli_query($con, $busca2sp) or die("ERRO NO COMANDO SQL2");
		$monta2sp = mysqli_fetch_array($sql2sp);
		$comissao_recebidasp	= inteiro_decimal_br($monta2sp["comissao_recebida"]);
		
		
		$busca3sp = "
		SELECT
			SUM(comissao) comissao_receber
		FROM (
			SELECT
				(valor_liquido * (comissao/100)) comissao
			FROM (
				SELECT
					valor_unit,
					comissao,
					IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
				FROM (
					SELECT
						YEAR(fim_veiculacao) ano,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf,
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
					FROM `mp_pedidos`
					)dados0
				WHERE ukey_status <> '7' AND ukey_status <> '8' AND ukey_status <> '9' AND ano = '".$anosp."' AND uf = 'SP'
				)dados1
			)dados2
		";

		$sql3sp = mysqli_query($con, $busca3sp) or die("ERRO NO COMANDO SQL3");
		$monta3sp = mysqli_fetch_array($sql3sp);
		$comissao_recebersp	= inteiro_decimal_br($monta3sp["comissao_receber"]);
		
		echo '
			<tr>
				<td>'.$anosp.'</td>
				<td class="right">'.$venda_brutasp.'</td>
				<td class="right">'.$comissaosp.'</td>
				<td class="right">'.$comissao_recebidasp.'</td>
				<td class="right">'.$comissao_recebersp.'</td>
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
		<h4>RESUMO GERENCIAL</h4>
		'.date('d/m/Y H:m:s').'
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
		<h4>RESUMO GERENCIAL</h4>
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


?>