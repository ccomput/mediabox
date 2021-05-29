<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
$id = $_GET["id"];
$formato = $_POST["formato"];

if(isset($formato)){
	
	if($formato == "excel"){

		// Nome do Arquivo do Excel que será gerado
		$arquivo = 'faturamento_por_etapa_e_clientes.xls';

		// Criamos uma tabela HTML com o formato da planilha para excel
		$tabela = '
		<html lang ="pt-br">
			<head>
			<meta charset="utf-8">
			<title>Faturmento por Etapas e Clientes</title>
			</head>
			<body>
			<table border="1">
				<tbody>
					<tr>
						<th colspan="11">VENDAS</th>
					</tr>
					<tr>
						<th></th>
						<th>VEICULAÇÃO</th>
						<th>PENDENTE</th>
						<th>FATURADO</th>
						<th>PREVISÃO</th>
						<th>PAGO</th>
						<th>SEM REPASSE</th>
						<th>PAGO DUE</th>
						<th>BONIFICADO</th>
						<th>CANCELADO</th>
						<th>TOTAL</th>
					</tr>
		';

	}else{

		ob_start();

		echo '
		<table id="table_itens" cellspacing="3" style="width:100%;">
			<tbody>
				<tr>
					<th colspan="11" class="borda_topbottom">VENDAS</th>
				</tr>
				<tr>
					<th class="borda_topbottom"></th>
					<th class="borda_topbottom">VEICULAÇÃO</th>
					<th class="borda_topbottom">PENDENTE</th>
					<th class="borda_topbottom">FATURADO</th>
					<th class="borda_topbottom">PREVISÃO</th>
					<th class="borda_topbottom">PAGO</th>
					<th class="borda_topbottom">SEM REPASSE</th>
					<th class="borda_topbottom">PAGO DUE</th>
					<th class="borda_topbottom">BONIFICADO</th>
					<th class="borda_topbottom">CANCELADO</th>
					<th class="borda_topbottom">TOTAL</th>
				</tr>
		';
		
	}
	
	/*Busca as UF*/
	$busca1 = "
	SELECT 
		uf
	FROM (
		SELECT 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			fim_veiculacao, 
			emissao
		FROM mp_pedidos) dados 
	GROUP BY uf 
	ORDER BY uf";

	$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
	$num_rows1 = mysqli_num_rows($sql1);
	
	
	while($monta1 = mysqli_fetch_array($sql1)){
		
		$uf	= $monta1["uf"];
		
		if($formato == "excel"){
		
			$tabela .= '
			<tr>
				<td colspan="11"><b>'.$uf.'</b></td>
			</tr>
			';
			
		}else{
		
			echo '
			<tr>
				<td colspan="11" class="borda_topbottom">'.$uf.'</td>
			</tr>
			';
			
		}
		
		/*Busca o Veiculo*/
		$buscaAno = "
		SELECT 
			ukey_agency,
			agencia
		FROM (
			SELECT 
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				ukey_agency,
				(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
				emissao
			FROM mp_pedidos) dados 
        WHERE uf = '".$uf."'
		GROUP BY agencia
		ORDER BY agencia";
		$sqlAno = mysqli_query($con, $buscaAno) or die("ERRO NO COMANDO SQL2");
		$num_rowsAno = mysqli_num_rows($sqlAno);
			

		while($montaAno = mysqli_fetch_array($sqlAno)){
			$ukey_agency = $montaAno["ukey_agency"];
			$agencia = $montaAno["agencia"];
			
			if($formato == "excel"){
				$tabela .= '
			<tr>
				<td>'.$agencia.'</td>
				';
				
			}else{

				echo '
			<tr>
				<td>'.$agencia.'</td>
				';
				
			}

			//VALOR
			$buscaValor = "
			SELECT
				(
				SELECT 
					SUM(valor_unit) veiculacao
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '1'
				) veiculacao,

				(
				SELECT 
					SUM(valor_unit) pendente
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '2'
				) pendente,

				(
				SELECT 
					SUM(valor_unit) faturado
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '3'
				) faturado,

				(
				SELECT 
					SUM(valor_unit) previsao
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '4'
				) previsao,

				(
				SELECT 
					SUM(valor_unit) pago
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '5'
				) pago,

				(
				SELECT 
					SUM(valor_unit) sem_repasse
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '6'
				) sem_repasse,

				(
				SELECT 
					SUM(valor_unit) pago_mp
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '7'
				) pago_mp,

				(
				SELECT 
					SUM(valor_unit) bonificado
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '8'
				) bonificado,

				(
				SELECT 
					SUM(valor_unit) cancelado
				FROM(
					SELECT 
					   (SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
					   (SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
					   valor_unit 
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agency."'
					)dados
				WHERE uf = '".$uf."' AND ukey_status = '9'
				) cancelado";
			$sqlValor = mysqli_query($con, $buscaValor) or die("ERRO NO COMANDO SQL Status");
			$num_rowsValor = mysqli_num_rows($sqlValor);

			
			//while($montaValor = mysqli_fetch_array($sqlValor)){
			$montaValor = mysqli_fetch_array($sqlValor);
			
			$veiculacao = $montaValor["veiculacao"];
			$pendente	= $montaValor["pendente"];
			$faturado	= $montaValor["faturado"];
			$previsao	= $montaValor["previsao"];
			$pago		= $montaValor["pago"];
			$sem_repasse = $montaValor["sem_repasse"];
			$pago_mp	= $montaValor["pago_mp"];
			$bonificado = $montaValor["bonificado"];
			$cancelado 	= $montaValor["cancelado"];
			
			$total_ano	= $veiculacao+$pendente+$faturado+$previsao+$pago+$sem_repasse+$pago_mp+$bonificado+$cancelado;
			
			if($formato == "excel"){
				
				$tabela .= '
				<td>'.inteiro_decimal_br($veiculacao).'</td>
				<td>'.inteiro_decimal_br($pendente).'</td>
				<td>'.inteiro_decimal_br($faturado).'</td>
				<td>'.inteiro_decimal_br($previsao).'</td>
				<td>'.inteiro_decimal_br($pago).'</td>
				<td>'.inteiro_decimal_br($sem_repasse).'</td>
				<td>'.inteiro_decimal_br($pago_mp).'</td>
				<td>'.inteiro_decimal_br($bonificado).'</td>
				<td>'.inteiro_decimal_br($cancelado).'</td>
				<td>'.inteiro_decimal_br($total_ano).'</td>
				';
				
			}else{
			
				echo '
				<td class="right">'.inteiro_decimal_br($veiculacao).'</td>
				<td class="right">'.inteiro_decimal_br($pendente).'</td>
				<td class="right">'.inteiro_decimal_br($faturado).'</td>
				<td class="right">'.inteiro_decimal_br($previsao).'</td>
				<td class="right">'.inteiro_decimal_br($pago).'</td>
				<td class="right">'.inteiro_decimal_br($sem_repasse).'</td>
				<td class="right">'.inteiro_decimal_br($pago_mp).'</td>
				<td class="right">'.inteiro_decimal_br($bonificado).'</td>
				<td class="right">'.inteiro_decimal_br($cancelado).'</td>
				<td class="right">'.inteiro_decimal_br($total_ano).'</td>
				';
			
			}
			
			if($formato == "excel"){
				
				$tabela .= '
				</tr>
				';
				
			}else{
			
				echo '
			</tr>
			';
				
			}
		}
	
	}
	
	//fim tabela
	if($formato == "excel"){
		
		$tabela .= '
		<tr>
			<th colspan="11"></th>
		</tr>
		';
		
	}else{
		
		echo '
		</tbody>
	</table>
	';
		
	}
	//fim vendas
	
	if($formato == "excel"){
		
		//ini comissao
		$tabela .= '
		<tr>
			<th colspan="11">COMISSÃO</th>
		</tr>
		<tr>
			<th></th>
			<th>VEICULAÇÃO</th>
			<th>PENDENTE</th>
			<th>FATURADO</th>
			<th>PREVISÃO</th>
			<th>PAGO</th>
			<th>SEM REPASSE</th>
			<th>PAGO MP</th>
			<th>BONIFICADO</th>
			<th>CANCELADO</th>
			<th>TOTAL</th>
		</tr>
		';
		
	}else{
		
		//ini comissao
		echo '
	<br><br>
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<tbody>
			<tr>
				<th colspan="11" class="borda_topbottom">COMISSÃO</th>
			</tr>
			<tr>
				<th class="borda_topbottom"></th>
				<th class="borda_topbottom">VEICULAÇÃO</th>
				<th class="borda_topbottom">PENDENTE</th>
				<th class="borda_topbottom">FATURADO</th>
				<th class="borda_topbottom">PREVISÃO</th>
				<th class="borda_topbottom">PAGO</th>
				<th class="borda_topbottom">SEM REPASSE</th>
				<th class="borda_topbottom">PAGO MP</th>
				<th class="borda_topbottom">BONIFICADO</th>
				<th class="borda_topbottom">CANCELADO</th>
				<th class="borda_topbottom">TOTAL</th>
			</tr>
	';
		
	}
	
	
	$busca1C = "
	SELECT 
		uf
	FROM (
		SELECT 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			fim_veiculacao, 
			emissao
		FROM mp_pedidos) dados 
	GROUP BY uf 
	ORDER BY uf";

	$sql1C = mysqli_query($con, $busca1C) or die("ERRO NO COMANDO SQL1");
	$num_rows1C = mysqli_num_rows($sql1C);
	
	
	while($monta1C = mysqli_fetch_array($sql1C)){
		
		$ufC	= $monta1C["uf"];
		
		if($formato == "excel"){
		
			$tabela .= '
			<tr>
				<td colspan="11"><b>'.$ufC.'</b></td>
			</tr>
			';
			
		}else{
		
			echo '
			<tr>
				<td colspan="11" class="borda_topbottom">'.$ufC.'</td>
			</tr>
			';
			
		}
			
		$buscaAnoC = "
		SELECT 
            ukey_agency,
			agencia
		FROM (
			SELECT 
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				ukey_agency,
				(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
				YEAR(fim_veiculacao) ano, 
				emissao
			FROM mp_pedidos) dados 
        WHERE uf = '".$ufC."'
		GROUP BY agencia
		ORDER BY agencia";
		$sqlAnoC = mysqli_query($con, $buscaAnoC) or die("ERRO NO COMANDO SQL1");
		$num_rowsAnoC = mysqli_num_rows($sqlAnoC);
			

		while($montaAnoC = mysqli_fetch_array($sqlAnoC)){

			$ukey_agencyC = $montaAnoC["ukey_agency"];
			$agenciaC = $montaAnoC["agencia"];
			
			if($formato == "excel"){
		
				$tabela .= '
			<tr>
				<td>'.$agenciaC.'</td>
			';
				
			}else{

				echo '
			<tr>
				<td>'.$agenciaC.'</td>
				';
				
			}

			//VALOR
			$buscaValorC = "
			SELECT
				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '1'
				) veiculacao,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '2'
				) pendente,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '3'
				) faturado,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '4'
				) previsao,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '5'
				) pago,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '6'
				) sem_repasse,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '7'
				) pago_mp,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '8'
				) bonificado,

				(
				SELECT 
					IF(desc_imposto = 0, SUM(valor_unit * (comissao/100)), SUM((valor_unit - (valor_unit*(impostos/100))) * (comissao/100))) comissao
				FROM(
					SELECT 
						(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
						(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
						valor_unit,
						(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
						(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
						(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
					FROM mp_pedidos
					WHERE ukey_agency = '".$ukey_agencyC."'
					)dados
				WHERE uf = '".$ufC."' AND ukey_status = '9'
				) cancelado";
			$sqlValorC = mysqli_query($con, $buscaValorC) or die("ERRO NO COMANDO SQL Status");
			$num_rowsValorC = mysqli_num_rows($sqlValorC);

			
			//while($montaValor = mysqli_fetch_array($sqlValor)){
			$montaValorC = mysqli_fetch_array($sqlValorC);
			
			$veiculacaoC	= $montaValorC["veiculacao"];
			$pendenteC		= $montaValorC["pendente"];
			$faturadoC		= $montaValorC["faturado"];
			$previsaoC		= $montaValorC["previsao"];
			$pagoC			= $montaValorC["pago"];
			$sem_repasseC	= $montaValorC["sem_repasse"];
			$pago_mpC		= $montaValorC["pago_mp"];
			$bonificadoC	= $montaValorC["bonificado"];
			$canceladoC		= $montaValorC["cancelado"];
			
			$total_anoC	= $veiculacaoC+$pendenteC+$faturadoC+$previsaoC+$pagoC+$sem_repasseC+$pago_mpC+$bonificadoC+$canceladoC;
			
			if($formato == "excel"){
		
				$tabela .= '
				<td>'.inteiro_decimal_br($veiculacaoC).'</td>
				<td>'.inteiro_decimal_br($pendenteC).'</td>
				<td>'.inteiro_decimal_br($faturadoC).'</td>
				<td>'.inteiro_decimal_br($previsaoC).'</td>
				<td>'.inteiro_decimal_br($pagoC).'</td>
				<td>'.inteiro_decimal_br($sem_repasseC).'</td>
				<td>'.inteiro_decimal_br($pago_mpC).'</td>
				<td>'.inteiro_decimal_br($bonificadoC).'</td>
				<td>'.inteiro_decimal_br($canceladoC).'</td>
				<td>'.inteiro_decimal_br($total_anoC).'</td>
				';
				
			}else{			
				
				echo '
				<td class="right">'.inteiro_decimal_br($veiculacaoC).'</td>
				<td class="right">'.inteiro_decimal_br($pendenteC).'</td>
				<td class="right">'.inteiro_decimal_br($faturadoC).'</td>
				<td class="right">'.inteiro_decimal_br($previsaoC).'</td>
				<td class="right">'.inteiro_decimal_br($pagoC).'</td>
				<td class="right">'.inteiro_decimal_br($sem_repasseC).'</td>
				<td class="right">'.inteiro_decimal_br($pago_mpC).'</td>
				<td class="right">'.inteiro_decimal_br($bonificadoC).'</td>
				<td class="right">'.inteiro_decimal_br($canceladoC).'</td>
				<td class="right">'.inteiro_decimal_br($total_anoC).'</td>
				';
				
			}
			
			if($formato == "excel"){
				
				$tabela .= '
				</tr>
				';
				
			}else{
				
				echo '
			</tr>
			';
				
			}
		}
	
	}
	//fim comissao
	
	///fim tabela
	if($formato == "excel"){
		
		$tabela .= '
		</tbody>
	</table>
	</html>
	';
		
		// Força o Download do Arquivo Gerado
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename={$arquivo}" );
		header ("Content-Description: PHP Generated Data" );

		echo $tabela;
		
	}else{
		
		echo '
		</tbody>
	</table>
	';
	
	$html = ob_get_clean();


	$header = '
	<div style="float:left;width:110px;">
		<img src="../img/duemidia100.png">
	</div>
	<div style="float:left;width:500px; padding-left:20px;">
		<h4>RESUMO DO FATURAMENTO POR STATUS E AGÊNCIA</h4>
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
		
	}
		

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
	<div style="float:left;width:500px;">
		<h4>RESUMO DO FATURAMENTO POR STATUS E AGÊNCIA</h4>
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
	
}

$name = 'FATURAMENTO POR ETAPAS E AGÊNCIA - '.date('d/m/Y').'.pdf';

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