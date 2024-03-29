<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
$id = $_GET["id"];

if(isset($id)){

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
		
			echo '
			<tr>
				<td colspan="11" class="borda_topbottom">'.$uf.'</td>
			</tr>
			';
			
		$buscaAno = "
		SELECT 
            ano
		FROM (
			SELECT 
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				YEAR(fim_veiculacao) ano, 
				emissao
			FROM mp_pedidos) dados 
        WHERE uf = '".$uf."'
		GROUP BY ano
		ORDER BY ano";
		$sqlAno = mysqli_query($con, $buscaAno) or die("ERRO NO COMANDO SQL1");
		$num_rowsAno = mysqli_num_rows($sqlAno);
			

		while($montaAno = mysqli_fetch_array($sqlAno)){

			$ano = $montaAno["ano"];

			echo '
			<tr>
				<td>'.$ano.'</td>
				';

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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
					WHERE fim_veiculacao BETWEEN '".$ano."-01-01' AND '".$ano."-12-31'
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
			
			
			echo '
			</tr>
			';
		}
	
	}
	
	//fim tabela
	echo '
		</tbody>
	</table>
	';
	//fim vendas
	
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
				<th class="borda_topbottom">PAGO DUE</th>
				<th class="borda_topbottom">BONIFICADO</th>
				<th class="borda_topbottom">CANCELADO</th>
				<th class="borda_topbottom">TOTAL</th>
			</tr>
	';
	
	
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
		
			echo '
			<tr>
				<td colspan="11" class="borda_topbottom">'.$ufC.'</td>
			</tr>
			';
			
		$buscaAnoC = "
		SELECT 
            ano
		FROM (
			SELECT 
				(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
				YEAR(fim_veiculacao) ano, 
				emissao
			FROM mp_pedidos) dados 
        WHERE uf = '".$ufC."'
		GROUP BY ano
		ORDER BY ano";
		$sqlAnoC = mysqli_query($con, $buscaAnoC) or die("ERRO NO COMANDO SQL1");
		$num_rowsAnoC = mysqli_num_rows($sqlAnoC);
			

		while($montaAnoC = mysqli_fetch_array($sqlAnoC)){

			$anoC = $montaAnoC["ano"];

			echo '
			<tr>
				<td>'.$anoC.'</td>
				';

			//VALOR
			$buscaValorC = "
			SELECT
			
				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '1'
						)dados1
					)dados2
				) veiculacao,
				
				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '2'
						)dados1
					)dados2
				) pendente,
				
				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '3'
						)dados1
					)dados2
				) faturado,
				
				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '4'
						)dados1
					)dados2
				) previsao,
				
				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '5'
						)dados1
					)dados2
				) pago,

				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '6'
						)dados1
					)dados2
				) sem_repasse,

				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '7'
						)dados1
					)dados2
				) pago_mp,

				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '8'
						)dados1
					)dados2
				) bonificado,

				(
					SELECT
						SUM(comissao) comissao
					FROM (
						SELECT
							(valor_liquido * (comissao/100)) comissao
						FROM(
							SELECT 
								comissao,
								IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
							FROM(
								SELECT 
									(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
									valor_unit,
									(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
									(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
									(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto
								FROM mp_pedidos
								WHERE fim_veiculacao BETWEEN '".$anoC."-01-01' AND '".$anoC."-12-31'
							)dados0
							WHERE uf = '".$ufC."' AND ukey_status = '9'
						)dados1
					)dados2
				) cancelado
				";
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
			
			echo '
			</tr>
			';
		}
	
	}
	//fim comissao
	
	///fim tabela
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
		<h4>RESUMO DO FATURAMENTO POR STATUS</h4>
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
		<h4>RESUMO DO FATURAMENTO POR STATUS</h4>
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