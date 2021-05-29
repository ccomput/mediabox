<?php
require "inc/verifica.php";
require "inc/functions.php";


//Parametro de transação
/* TARGET: 
/ 0 - Status Pedido 
/ 1 - Pedidos 
/ 2 - Status Pedido
/ 3 - Inserir Status Pedido
/ 4 - Cobranças do Dia
/ 5 - Cobranças do Mês
*/
$target = $_GET['target'];
$id		= @$_GET['id'];

$like			= @$_GET['pesquisa'];
$avancada		= @$_GET['avancada'];
$data_ini		= @$_GET['data_ini'];
$data_fim		= @$_GET['data_fim'];

//Avançada
$a_pi			= @$_GET["pi"];
$a_situacao		= @$_GET["situacao"];
$a_status		= @$_GET["statuspi"];
$a_cliente		= @$_GET["cliente"];
$a_agencia		= @$_GET["agencia"];
$a_veiculo		= @$_GET["veiculo"];
$a_uf			= @$_GET["uf"];
$a_cobranca		= @$_GET["cobranca"];
$a_cobranca_fim	= @$_GET["cobranca_fim"];
$a_veic_ini		= @$_GET["veic_ini"];
$a_veic_fim		= @$_GET["veic_fim"];
$a_codigo		= @$_GET["codigo"];

//TASKS
if($target == '5'){
	
	//COBRANÇAS DO MÊS
	include_once "inc/conect.php";
	
	//PESQUISA
	if($like == ""){
		$where = "WHERE (ukey_status <> '7' OR ukey_status IS NULL) AND MONTH(cobranca) = '".$month."' AND YEAR(cobranca) = '".$year."'";
	}else{
		$where = "WHERE ukey = '".$like."' AND MONTH(cobranca) = '".$month."' AND YEAR(cobranca) = '".$year."'";
	}
	
	//BUSCA
	$busca = "
	SELECT 
		ukey, pi, cliente, agencia, veiculo, campanha, emissao, ini_veiculacao, fim_veiculacao, vendedor, valor_bruto, valor_unit, timestamp, ukey_status, cobranca, data_status
	FROM (
		SELECT 
			ukey, 
			pi, 
			(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
			(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
			(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
			campanha, 
			ini_veiculacao, 
			fim_veiculacao, 
			(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			valor_bruto, 
			valor_unit, 
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			emissao, 
			timestamp, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
			(SELECT nf_veic FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_veic <> '' ORDER BY ukey DESC LIMIT 0,1) nf_veic, 
			(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca,
			(SELECT timestamp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) data_status 
		FROM mp_pedidos 
	) dados ".$where." ORDER BY data_status DESC";
	$sql = mysqli_query($con, $busca) or die("ERROR COBRANCA 001");
	$rowcount = mysqli_num_rows($sql);
	
	/*JSON*/
	$i = 0;
	echo '
	{
		"data": [
	';
	
	//DADOS
	while($monta = mysqli_fetch_array($sql)){
		$i++;
		
		$ukey			= $monta["ukey"];
		$pi				= $monta["pi"];
		$cliente		= $monta["cliente"];
		$agencia		= $monta["agencia"];
		$veiculo		= $monta["veiculo"];
		$campanha		= $monta["campanha"];
		if($monta["ini_veiculacao"] == 0){
			$ini_veic = "-";
		}else{
			$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
		}
		if($monta["fim_veiculacao"] == 0){
			$fim_veic = "-";
		}else{
			$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
		}
		$vendedor		= $monta["vendedor"];
		$valor_bruto	= decimal_br($monta["valor_bruto"]);
		$valor_unit		= decimal_br($monta["valor_unit"]);
		$ukey_status	= $monta["ukey_status"];
		if($monta["cobranca"] == 0){
			$cobranca = "-";
		}else{
			$cobranca = date('d/m/Y', strtotime($monta["cobranca"]));
		}
		$data_status = date('d/m/Y', strtotime($monta["data_status"]));
		
		//CALCULO DE DIAS DO ULTIMO STATUS
		$diferenca = strtotime($date) - strtotime($monta["data_status"]);
		$dias = floor($diferenca/(60*60*24));
		
		if($dias <= 7){
			$status_dias = '<button class=\"btn btn-small\" type=\"button\">'.$dias.'</button>';
		}elseif($dias <= 15){
			$status_dias = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$dias.'</button>';
		}elseif($dias > 15){
			$status_dias = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$dias.'</button>';
		}
		
		if($ukey_status == NULL){
			$status = '<button class=\"btn btn-small\" type=\"button\">LANÇADO</button>';
		}else{
			$busca_status = "SELECT nome FROM mp_status WHERE ukey = ".$ukey_status."";
			$sql_status = mysqli_query($con, $busca_status) or die("ERROR STATUS 001");
			$monta_status = mysqli_fetch_array($sql_status);
			$name_status = $monta_status["nome"];
			
			if($name_status == "VEICULAÇÃO"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PENDENTE"){
				$status = '<button class=\"btn btn-small btn-primary\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "FATURADO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PREVISÃO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO VEIC."){
				$status = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "SEM REPASSE"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO DUE"){
				$status = '<button class=\"btn btn-small btn-success\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "BONIFICADO"){
				$status = '<button class=\"btn btn-small btn-inverse\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "CANCELADO"){
				$status = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$name_status.'</button>';
			}else{
				$status = $name_status;
			}
		}
		
		//BUTTONS
		if($_SESSION["mod_add_plantel"] == 1){
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&editar=sim\">'.$status.'</a>';
		}else{
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&ver=sim\">'.$status.'</a>';
		}
		$link = $edit;
		
		/*JSON*/
		if($i == $rowcount){
			print '
			[
				"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$cobranca.'",
				"'.$data_status.'",
				"'.$status_dias.'",
				"'.$link.'"
			]
			';
		}else{
			print '
			[
	    		"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$cobranca.'",
				"'.$data_status.'",
				"'.$status_dias.'",
				"'.$link.'"
			],
			';
		}
	}

	/*JSON*/
	echo '
	]
	}
	';
	
}elseif($target == '4'){
	
	//COBRANÇAS DO DIA
	include_once "inc/conect.php";
	
	//PESQUISA
	if($like == ""){
		$where = "WHERE (ukey_status <> '7' OR ukey_status IS NULL) AND cobranca = '".$date."'";
	}else{
		$where = "WHERE ukey = '".$like."' AND cobranca = '".$date."'";
	}
	
	//BUSCA
	$busca = "
	SELECT 
		ukey, pi, cliente, agencia, veiculo, campanha, emissao, ini_veiculacao, fim_veiculacao, vendedor, valor_bruto, valor_unit, timestamp, ukey_status, cobranca, data_status
	FROM (
		SELECT 
			ukey, 
			pi, 
			(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
			(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
			(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
			campanha, 
			ini_veiculacao, 
			fim_veiculacao, 
			(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			valor_bruto, 
			valor_unit, 
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			emissao, 
			timestamp, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
			(SELECT nf_veic FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_veic <> '' ORDER BY ukey DESC LIMIT 0,1) nf_veic, 
			(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca,
			(SELECT timestamp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) data_status 
		FROM mp_pedidos 
	) dados ".$where." ORDER BY ukey DESC";
	$sql = mysqli_query($con, $busca) or die("ERROR COBRANCA 001");
	$rowcount = mysqli_num_rows($sql);
	
	/*JSON*/
	$i = 0;
	echo '
	{
		"data": [
	';
	
	//DADOS
	while($monta = mysqli_fetch_array($sql)){
		$i++;
		
		$ukey			= $monta["ukey"];
		$pi				= $monta["pi"];
		$cliente		= $monta["cliente"];
		$agencia		= $monta["agencia"];
		$veiculo		= $monta["veiculo"];
		$campanha		= $monta["campanha"];
		if($monta["ini_veiculacao"] == 0){
			$ini_veic = "-";
		}else{
			$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
		}
		if($monta["fim_veiculacao"] == 0){
			$fim_veic = "-";
		}else{
			$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
		}
		$vendedor		= $monta["vendedor"];
		$valor_bruto	= decimal_br($monta["valor_bruto"]);
		$valor_unit		= decimal_br($monta["valor_unit"]);
		$ukey_status	= $monta["ukey_status"];
		if($monta["cobranca"] == 0){
			$cobranca = "-";
		}else{
			$cobranca = date('d/m/Y', strtotime($monta["cobranca"]));
		}
		$data_status = date('d/m/Y', strtotime($monta["data_status"]));
		
		//CALCULO DE DIAS DO ULTIMO STATUS
		$diferenca = strtotime($date) - strtotime($monta["data_status"]);
		$dias = floor($diferenca/(60*60*24));
		
		if($dias <= 7){
			$status_dias = '<button class=\"btn btn-small\" type=\"button\">'.$dias.'</button>';
		}elseif($dias <= 15){
			$status_dias = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$dias.'</button>';
		}elseif($dias > 15){
			$status_dias = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$dias.'</button>';
		}
		
		if($ukey_status == NULL){
			$status = '<button class=\"btn btn-small\" type=\"button\">LANÇADO</button>';
		}else{
			$busca_status = "SELECT nome FROM mp_status WHERE ukey = ".$ukey_status."";
			$sql_status = mysqli_query($con, $busca_status) or die("ERROR STATUS 001");
			$monta_status = mysqli_fetch_array($sql_status);
			$name_status = $monta_status["nome"];
			
			if($name_status == "VEICULAÇÃO"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PENDENTE"){
				$status = '<button class=\"btn btn-small btn-primary\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "FATURADO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PREVISÃO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO VEIC."){
				$status = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "SEM REPASSE"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO MP"){
				$status = '<button class=\"btn btn-small btn-success\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "BONIFICADO"){
				$status = '<button class=\"btn btn-small btn-inverse\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "CANCELADO"){
				$status = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$name_status.'</button>';
			}else{
				$status = $name_status;
			}
		}
		
		//BUTTONS
		if($_SESSION["mod_add_plantel"] == 1){
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&editar=sim\">'.$status.'</a>';
		}else{
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&ver=sim\">'.$status.'</a>';
		}
		$link = $edit;
		
		/*JSON*/
		if($i == $rowcount){
			print '
			[
				"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$cobranca.'",
				"'.$data_status.'",
				"'.$status_dias.'",
				"'.$link.'"
			]
			';
		}else{
			print '
			[
	    		"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$cobranca.'",
				"'.$data_status.'",
				"'.$status_dias.'",
				"'.$link.'"
			],
			';
		}
	}

	/*JSON*/
	echo '
	]
	}
	';
	
}elseif($target == '0'){
	
	//STATUS PEDIDO
	include_once "inc/conect.php";
	
	if($avancada == "yes"){
		
		
		if($a_pi == ""){
			$where_pi = "WHERE pi LIKE '%".$a_pi."%'";
		}else{
			$where_pi = "WHERE pi LIKE '%".$a_pi."%'";
		}
		
		if($a_situacao == "open"){

			if($a_status == ""){
				$where_status = "AND ukey_status <> '7'";
			}else{
				$where_status = "AND ukey_status IN (".$a_status.") AND ukey_status <> '7'";
			}

		}elseif($a_situacao == "close"){

			if($a_status == ""){
				$where_status = "AND ukey_status = '7'";
			}else{
				$where_status = "AND ukey_status = '7'";
			}

		}else{

			if($a_status == ""){
				$where_status = "";
			}else{
				$where_status = "AND ukey_status IN (".$a_status.")";
			}

		}
		
		if($a_cliente == ""){
			$where_cliente = "";
		}else{
			$where_cliente = "AND ukey_client IN (".$a_cliente.")";
		}
		
		if($a_agencia == ""){
			$where_agencia = "";
		}else{
			$where_agencia = "AND ukey_agency IN (".$a_agencia.")";
		}
		
		if($a_veiculo == ""){
			$where_veiculo = "";
		}else{
			$where_veiculo = "AND ukey_vehicles IN (".$a_veiculo.")";
		}
		
		if($a_uf == ""){
			$where_uf = "";
		}else{
			$where_uf = "AND uf = '".$a_uf."'";
		}
		
		if($a_cobranca_fim == "" and $a_cobranca == ""){
			$where_cobranca = "";
		}elseif($a_cobranca_fim == "" and $a_cobranca <> ""){
			$where_cobranca = "AND cobranca = '".$a_cobranca."'";
		}elseif($a_cobranca_fim <> "" and $a_cobranca <> ""){
			$where_cobranca = "AND cobranca BETWEEN '".$a_cobranca."' AND '".$a_cobranca_fim."'";
		}
		
		if($a_veic_fim == "" and $a_veic_ini == ""){
			$where_veiculacao = "";
		}elseif($a_veic_fim == "" and $a_veic_ini <> ""){
			$where_veiculacao = "AND ini_veiculacao = '".$a_veic_ini."'";
		}elseif($a_veic_fim <> "" and $a_veic_ini <> ""){
			$where_veiculacao = "AND ini_veiculacao BETWEEN '".$a_veic_ini."' AND '".$a_veic_fim."'";
		}

		$where = $where_pi.$where_status.$where_cliente.$where_agencia.$where_veiculo.$where_uf.$where_cobranca.$where_veiculacao;
		
	}elseif($_SESSION['grupo'] == 1){
		
		if($like == ""){
			$where = "WHERE ukey_status <> '7' OR ukey_status IS NULL";
		}else{
			$where = "WHERE ukey = '".$like."'";
		}
	}else{
		if($like == ""){
			$where = "WHERE ukey_status <> '7' OR ukey_status IS NULL";
		}else{
			$where = "WHERE ukey = '".$like."'";
		}
	}
	
	$busca = "
	SELECT 
		ukey, pi, cliente, agencia, veiculo, campanha, emissao, ini_veiculacao, fim_veiculacao, vendedor, uf, valor_bruto, valor_unit, 
		IF(desc_imposto = 0, valor_unit * (comissao/100), (valor_unit - (valor_unit*(impostos/100))) * (comissao/100)) comissao,
		timestamp, ukey_status, nf_veic, nf_mp, cobranca
	FROM (
		SELECT 
			ukey, 
			pi, 
			ukey_client,
			(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
			ukey_agency,
			(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
			ukey_vehicles, 
			(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
			campanha, 
			ini_veiculacao, 
			fim_veiculacao, 
			(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			valor_bruto,
			valor_unit, 
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			emissao, 
			timestamp, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
			(SELECT nf_veic FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_veic <> '' ORDER BY ukey DESC LIMIT 0,1) nf_veic, 
			(SELECT nf_mp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_mp <> '' ORDER BY ukey DESC LIMIT 0,1) nf_mp, 
			(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca 
		FROM mp_pedidos 
	) dados ".$where." ORDER BY ukey DESC";
	
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");
	$rowcount = mysqli_num_rows($sql);
	
	/*JSON*/
	$i = 0;
	echo '
	{
		"data": [
	';

	while($monta = mysqli_fetch_array($sql)){
		$i++;
		
		$ukey			= $monta["ukey"];
		$pi				= $monta["pi"];
		$cliente		= $monta["cliente"];
		$agencia		= $monta["agencia"];
		$veiculo		= $monta["veiculo"];
		$campanha		= $monta["campanha"];
		if($monta["emissao"] == 0){
			$emissao = "-";
		}else{
			$emissao = date('d/m/Y', strtotime($monta["emissao"]));
		}
		
		if($monta["ini_veiculacao"] == 0){
			$ini_veic = "-";
		}else{
			$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
		}
		
		if($monta["fim_veiculacao"] == 0){
			$fim_veic = "-";
		}else{
			$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
		}
		
		$vendedor		= $monta["vendedor"];
		$valor_bruto	= decimal_br($monta["valor_bruto"]);
		$valor_unit		= decimal_br($monta["valor_unit"]);
		$comissao		= decimal_br($monta["comissao"]);
		$ukey_status	= $monta["ukey_status"];
		$nf_veic		= $monta["nf_veic"];
		$nf_mp			= $monta["nf_mp"];
		
		if($monta["cobranca"] == 0){
			$cobranca = "-";
		}else{
			$cobranca = date('d/m/Y', strtotime($monta["cobranca"]));
		}
		
		
		if($ukey_status == NULL){
			$status = '<button class=\"btn btn-small\" type=\"button\">LANÇADO</button>';
		}else{
			$busca_status = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
			$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
			$monta_status = mysqli_fetch_array($sql_status);
			$name_status = $monta_status["nome"];
			
			if($name_status == "VEICULAÇÃO"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PENDENTE"){
				$status = '<button class=\"btn btn-small btn-primary\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "FATURADO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PREVISÃO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO VEIC."){
				$status = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "SEM REPASSE"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO MP"){
				$status = '<button class=\"btn btn-small btn-success\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "BONIFICADO"){
				$status = '<button class=\"btn btn-small btn-inverse\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "CANCELADO"){
				$status = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$name_status.'</button>';
			}else{
				$status = $name_status;
			}
		}
		
		//BUTTONS
		if($_SESSION["mod_add_plantel"] == 1){
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&editar=sim\">'.$status.'</a>';
		}else{
			$edit = '<a href=\"\/form_status_pedido.php?id='.$ukey.'&ver=sim\">'.$status.'</a>';
		}
		$link = $edit;
		
		
		/*JSON*/
		if($i == $rowcount){
			print '
			[
				"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.' - '.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$nf_veic.'",
				"'.$cobranca.'",
				"'.$link.'"
			]
			';
		}else{
			print '
			[
	    		"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.' - '.$fim_veic.'",
				"'.$vendedor.'",
				"'.$valor_bruto.'",
				"'.$valor_unit.'",
				"'.$nf_veic.'",
				"'.$cobranca.'",
				"'.$link.'"
			],
			';
		}
		
	}
	
	/*JSON*/
	echo '
	]
	}
	';
	
	
}elseif($target == '1'){
	
	// PEDIDOS
	include_once "inc/conect.php";
	
	$busca_gerencia = "
	SELECT gerencia
	FROM mp_group 
	WHERE ukey = '".$_SESSION['grupo']."'
	";
	$sql_gerencia = mysqli_query($con, $busca_gerencia) or die("ERRO NO COMANDO SQL");
	$monta_gerencia = mysqli_fetch_array($sql_gerencia);
	$gerencia	= $monta_gerencia["gerencia"];
	
	if($avancada == "yes"){
		
		if($a_situacao == "open"){

			if($a_status == ""){
				$where_status = "ukey_status <> '7' ";
			}else{
				$where_status = "ukey_status IN (".$a_status.") AND ukey_status <> '7' ";
			}

		}elseif($a_situacao == "close"){
			if($a_status == ""){
				$where_status = "ukey_status = '7' ";
			}else{
				$where_status = "ukey_status = '7' ";
			}
		}elseif($a_situacao == "both"){
			if($a_status == ""){
				$where_status = "ukey_status >= '0' ";
			}else{
				$where_status = "ukey_status >= '0' AND ukey_status IN (".$a_status.") ";
			}
		}else{
			if($a_status == ""){
				$where_status = "";
			}else{
				$where_status = "ukey_status IN (".$a_status.") ";
			}
		}
		
		if($a_cliente == ""){
			$where_cliente = "";
		}else{
			$where_cliente = "AND ukey_client IN (".$a_cliente.")";
		}
		
		if($a_agencia == ""){
			$where_agencia = "";
		}else{
			$where_agencia = "AND ukey_agency IN (".$a_agencia.")";
		}
		
		if($a_veiculo == ""){
			$where_veiculo = "";
		}else{
			$where_veiculo = "AND ukey_vehicles IN (".$a_veiculo.")";
		}
		
		if($a_veic_fim == "" and $a_veic_ini == ""){
			$where_veiculacao = "";
		}elseif($a_veic_fim == "" and $a_veic_ini <> ""){
			$where_veiculacao = "AND ini_veiculacao = '".$a_veic_ini."'";
		}elseif($a_veic_fim <> "" and $a_veic_ini <> ""){
			$where_veiculacao = "AND ini_veiculacao BETWEEN '".$a_veic_ini."' AND '".$a_veic_fim."'";
		}
		
		if($a_pi == ""){
			$where_pi = "";
		}else{
			$where_pi = "AND pi LIKE '%".$a_pi."%'";
		}
		
		if($a_codigo == ""){
			$where_codigo = "";
		}else{
			$where_codigo = "AND ukey LIKE '%".$a_codigo."%'";
		}
	
		$where = $where_status.$where_cliente.$where_agencia.$where_veiculo.$where_veiculacao.$where_pi.$where_codigo;
	
		if($gerencia == 1){
			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				cliente, 
				ukey_agency, 
				agencia, 
				ukey_vehicles, 
				veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				ukey_status
			FROM (
				SELECT 
					ukey, 
					pi, 
					ukey_client, 
					(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
					ukey_agency, 
					(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
					ukey_vehicles, 
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
					campanha, 
					ini_veiculacao, 
					fim_veiculacao, 
					(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
					(SELECT ukey_unidades FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) unidade, 
					valor_unit, 
					emissao, 
					timestamp, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM mp_pedidos
				) DADOS
			WHERE ".$where."
			ORDER BY ukey DESC
			";

		}elseif($_SESSION['grupo'] == 1){

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				cliente, 
				ukey_agency, 
				agencia, 
				ukey_vehicles, 
				veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				ukey_status
			FROM (
				SELECT 
					ukey, 
					pi, 
					ukey_client, 
					(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
					ukey_agency, 
					(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
					ukey_vehicles, 
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
					campanha, 
					ini_veiculacao, 
					fim_veiculacao, 
					(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
					valor_unit, 
					emissao, 
					timestamp, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM mp_pedidos 
			) DADOS
			WHERE ".$where."
			ORDER BY ukey DESC
			";


		}elseif($_SESSION['grupo'] == 3){

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				cliente, 
				ukey_agency, 
				agencia, 
				ukey_vehicles, 
				veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				ukey_status
			FROM (
				SELECT 
					ukey, 
					pi, 
					ukey_client, 
					(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
					ukey_agency, 
					(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
					ukey_vehicles, 
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
					campanha, 
					ini_veiculacao, 
					fim_veiculacao, 
					(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
					valor_unit, 
					emissao, 
					timestamp, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM mp_pedidos 
			) DADOS
			WHERE ".$where."
			ORDER BY ukey DESC
			";


		}else{

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				cliente, 
				ukey_agency, 
				agencia, 
				ukey_vehicles, 
				veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				ukey_status
			FROM (
				SELECT 
					ukey, 
					pi, 
					ukey_client, 
					(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
					ukey_agency, 
					(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
					ukey_vehicles, 
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
					campanha, 
					ini_veiculacao, 
					fim_veiculacao, 
					(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
					valor_unit, 
					emissao, 
					timestamp, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM mp_pedidos 
			) DADOS
			WHERE ".$where."
			ORDER BY ukey DESC
			";

		}
		
	}else{
		
		if($gerencia == 1){
			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				cliente, 
				ukey_agency, 
				agencia, 
				ukey_vehicles, 
				veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				ukey_status
			FROM (
				SELECT 
					ukey, 
					pi, 
					ukey_client, 
					(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
					ukey_agency, 
					(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
					ukey_vehicles, 
					(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
					campanha, 
					ini_veiculacao, 
					fim_veiculacao, 
					(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
					(SELECT ukey_unidades FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) unidade, 
					valor_unit, 
					emissao, 
					timestamp, 
					(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
				FROM mp_pedidos
				) DADOS
			WHERE unidade = '".$_SESSION["cod_unidade"]."'
			ORDER BY ukey DESC
			";

		}elseif($_SESSION['grupo'] == 1){

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
				ukey_agency, 
				(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
				ukey_vehicles, 
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM mp_pedidos ORDER BY ukey DESC
			";


		}elseif($_SESSION['grupo'] == 3){

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
				ukey_agency, 
				(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
				ukey_vehicles, 
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM mp_pedidos ORDER BY ukey DESC
			";


		}else{

			$busca = "
			SELECT 
				ukey, 
				pi, 
				ukey_client, 
				(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
				ukey_agency, 
				(SELECT fantasia FROM mp_agency WHERE ukey = mp_pedidos.ukey_agency) agencia, 
				ukey_vehicles, 
				(SELECT fantasia FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) veiculo, 
				campanha, 
				ini_veiculacao, 
				fim_veiculacao, 
				(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
				valor_unit, 
				emissao, 
				timestamp, 
				(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status
			FROM mp_pedidos 
			WHERE user_ukey = '".$_SESSION['user_login']."' OR ukey_sellers = '".$_SESSION['cod_vendedor']."'
			ORDER BY ukey DESC
			";

		}
		
	}
	
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");
	$rowcount = mysqli_num_rows($sql);
	
	/*JSON*/
	$i = 0;
	echo '
	{
		"data": [
	';

	while($monta = mysqli_fetch_array($sql)){
		$i++;
		
		$ukey			= $monta["ukey"];
		$pi				= $monta["pi"];
		$cliente		= $monta["cliente"];
		$agencia		= $monta["agencia"];
		$veiculo		= $monta["veiculo"];
		$campanha		= $monta["campanha"];
		
		if($monta["emissao"] == 0){
			$emissao = "-";
		}else{
			$emissao = date('d/m/Y', strtotime($monta["emissao"]));
		}
		
		if($monta["ini_veiculacao"] == 0){
			$ini_veic = "-";
		}else{
			$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
		}
		
		if($monta["fim_veiculacao"] == 0){
			$fim_veic = "-";
		}else{
			$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
		}
		
		$vendedor		= $monta["vendedor"];
		$valor_unit		= decimal_br($monta["valor_unit"]);
		$ukey_status	= $monta["ukey_status"];
		
		if($ukey_status == NULL){
			$status = "LANÇADO";
		}else{
			$busca_status = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
			$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
			$monta_status = mysqli_fetch_array($sql_status);
			$name_status = $monta_status["nome"];
			
			if($name_status == "VEICULAÇÃO"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PENDENTE"){
				$status = '<button class=\"btn btn-small btn-primary\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "FATURADO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PREVISÃO"){
				$status = '<button class=\"btn btn-small btn-info\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO VEIC."){
				$status = '<button class=\"btn btn-small btn-warning\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "SEM REPASSE"){
				$status = '<button class=\"btn btn-small\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "PAGO MP"){
				$status = '<button class=\"btn btn-small btn-success\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "BONIFICADO"){
				$status = '<button class=\"btn btn-small btn-inverse\" type=\"button\">'.$name_status.'</button>';
			}elseif($name_status == "CANCELADO"){
				$status = '<button class=\"btn btn-small btn-danger\" type=\"button\">'.$name_status.'</button>';
			}else{
				$status = $name_status;
			}
		}

		
		if($_SESSION["mod_add_proposta"] == 1){
			
			if($_SESSION["grupo"] == '1' or $_SESSION["grupo"] == '3'){
				$edit = '<a href=\"form_pedidos.php?id='.$ukey.'&editar=sim\"><button class=\"btn btn-small btn-primary\"><i class=\"icon-pencil icon-white\"></i> </button></a>';
			}elseif($_SESSION["grupo"] == '2' and $ukey_status == '3'){
				$edit = '';
			}elseif($ukey_status == '7'){
				$edit = '';
			}else{
				$edit = '<a href=\"form_pedidos.php?id='.$ukey.'&editar=sim\"><button class=\"btn btn-small btn-primary\"><i class=\"icon-pencil icon-white\"></i> </button></a>';
			}
			
		}else{
			$edit = '';
		}

		$link = $edit.' <a href=\"form_pedidos.php?id='.$ukey.'&ver=sim\"><button class=\"btn btn-small\"><i class=\"icon-eye-open icon-white\"></i> </button></a>';
		
		
		/*JSON*/
		if($i == $rowcount){
			print '
			[
				"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$emissao.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$valor_unit.'",
				"'.$vendedor.'",
				"'.$status.'",
				"'.$link.'"
			]
			';
		}else{
			print '
			[
	    		"DUE'.$ukey.'",
				"'.$pi.'",
				"'.$emissao.'",
				"'.$cliente.'",
				"'.$agencia.'",
				"'.$veiculo.'",
				"'.$campanha.'",
				"'.$ini_veic.'",
				"'.$fim_veic.'",
				"'.$valor_unit.'",
				"'.$vendedor.'",
				"'.$status.'",
				"'.$link.'"
			],
			';
		}
		
	}
	
	/*JSON*/
	echo '
	]
	}
	';
	
	
}elseif($target == '2'){
	
	include_once "inc/conect.php";
	
	$busca = "SELECT (SELECT nome FROM mp_status WHERE ukey = mp_status_pedido.ukey_status) status, descricao, nf_veic, nf_mp, cobranca, recebido, (SELECT apelido FROM mp_user WHERE ukey = mp_status_pedido.ukey_user) usuario, timestamp FROM mp_status_pedido WHERE ukey_pedidos = '".$id."' ORDER BY ukey DESC" ;

	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");
	$rowcount = mysqli_num_rows($sql);
	
	/*JSON*/
	$i = 0;
	echo '
	{
		"data": [
	';
	

	while($monta = mysqli_fetch_array($sql)){
		$i++;
		
		$status			= $monta["status"];
		$descricao		= trim(preg_replace("/\r?\n/","<br>", $monta["descricao"]));
		$nf_veic		= $monta["nf_veic"];
		$nf_mp			= $monta["nf_mp"];
		$usuario		= $monta["usuario"];
		//$timestamp		= date('d/m/Y H:i:s', strtotime($monta["timestamp"]));
		if($monta["timestamp"] == 0){
			$timestamp	= "";
		}else{
			$timestamp	= date('d/m/Y', strtotime($monta["timestamp"]));
		}
		
		if($monta["cobranca"] == 0){
			$cobranca = "-";
		}else{
			$cobranca = date('d/m/Y', strtotime($monta["cobranca"]));
		}
		
		if($monta["recebido"] == 0){
			$recebido = "-";
		}else{
			$recebido = date('d/m/Y', strtotime($monta["recebido"]));
		}

		
		/*JSON*/
		if($i == $rowcount){
			print '
			[
				"'.$status.'",
				"'.$descricao.'",
				"'.$nf_veic.'",
				"'.$nf_mp.'",
				"'.$usuario.'",
				"'.$timestamp.'",
				"'.$cobranca.'",
				"'.$recebido.'"
			]
			';
		}else{
			print '
			[
	    		"'.$status.'",
				"'.$descricao.'",
				"'.$nf_veic.'",
				"'.$nf_mp.'",
				"'.$usuario.'",
				"'.$timestamp.'",
				"'.$cobranca.'",
				"'.$recebido.'"
			],
			';
		}
		
	}
	
	/*JSON*/
	echo '
	]
	}
	';
	
	
}elseif($target == '3'){
	
	if($_POST["insert"] == "yes"){
		
		include_once "inc/conect.php";
		
		$ukey_pedidos	= $_POST["pedido"];
		$ukey_status	= $_POST["status"];
		$descricao		= trim(preg_replace("/\r?\n/","<br>",$_POST["descricao"]));
		$nf_veic		= $_POST["nf_veic"];
		$nf_mp			= $_POST["nf_mp"];
		$data_nf_mp		= $_POST["data_nf_mp"];
		$cobranca		= $_POST["cobranca"];
		$recebido		= $_POST["recebido"];
		$ukey_user		= $_SESSION["user_login"];

		$select_insert = "INSERT INTO mp_status_pedido (ukey_pedidos, ukey_status, descricao, nf_veic, nf_mp, cobranca, recebido, ukey_user, data_nf_mp) VALUES ('".$ukey_pedidos."','".$ukey_status."','".$descricao."','".$nf_veic."','".$nf_mp."','".$cobranca."','".$recebido."','".$ukey_user."','".$data_nf_mp."')";
		$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");
	}
	
}elseif($target == '4'){
	
	if(isset($_SESSION['user_login'])){
		
		include_once "connect_list.php";
		
		if($_SESSION["mod_vendas"] == '1'){
			
			if($like <> ''){
				$where = "WHERE ukey LIKE '%".$like."%'";
			}elseif($avancada == 'yes'){
				$where = "WHERE data BETWEEN '".$data_ini." 00:00:00' AND '".$data_fim." 23:59:59'";
			}else{
				$where = "";
			}
			
			$query = "SELECT ukey, 
			data, 
			total, 
			user_ukey, 
			(SELECT empresa FROM `kn_user` WHERE ukey = kn_orcamentos.user_ukey) empresa 
			FROM `kn_orcamentos` ".$where." 
			ORDER BY ukey DESC 
			LIMIT 0,300";
			$pegaOrcamento = $pdo->query($query);
			
		}else{
			
			if($like <> ''){
				$where = "AND ukey LIKE '%".$like."%'";
			}elseif($avancada == 'yes'){
				//$where = "WHERE data >= '".$data_ini." 00:00:00' AND data <= '".$data_fim." 00:00:00'";
				$where = "AND data BETWEEN '".$data_ini." 00:00:00' AND '".$data_fim." 23:59:59'";
			}else{
				$where = "";
			}
			
			$query = "SELECT ukey, 
			data, 
			total, 
			user_ukey, 
			(SELECT empresa FROM `kn_user` WHERE ukey = kn_orcamentos.user_ukey) empresa 
			FROM `kn_orcamentos` 
			WHERE `user_ukey` = '".$_SESSION['user_login']."' ".$where." 
			ORDER BY ukey DESC 
			LIMIT 0,300";
			$pegaOrcamento = $pdo->query($query);
		}
		$count = $pegaOrcamento->rowCount();
	
		$i = 0;
		echo '
		{
			"data": [
		';
	
		foreach ($pegaOrcamento as $orcamento){
			$i++;
			$ukey_orcamento = $orcamento['ukey'];
			//$data_orcamento = $orcamento['data'];
			$data_orcamento = date("d/m/Y", strtotime($orcamento['data']));
			$empresa = $orcamento['empresa'];
			$total_orcamento = 'R$ '.number_format($orcamento['total'], 2, ',', '.');
			
			if($_SESSION["mod_vendas"] == '1'){
				$link = '<a href=\"meu_orcamento.php?orcamento='.$ukey_orcamento.'&viewer=vendas\" ><button class=\"btn btn-small btn-primary\"><i class=\"icon-eye-open icon-white\"></i> Ver</button></a> <a href=\"relatorios/orcamento.php?orcamento='.$ukey_orcamento.'\" target=\"_brank\" ><button class=\"btn btn-small\"><i class=\"icon-print icon-white\"></i> PDF</button></a>';
			}else{
				$link = '<a href=\"meu_orcamento.php?orcamento='.$ukey_orcamento.'&viewer=distribuidor\" ><button class=\"btn btn-small btn-primary\"><i class=\"icon-eye-open icon-white\"></i> Ver</button></a> <a href=\"relatorios/orcamento.php?orcamento='.$ukey_orcamento.'\" target=\"_brank\" ><button class=\"btn btn-small\"><i class=\"icon-print icon-white\"></i> PDF</button></a>';
			}
		 
		 	if($_SESSION["mod_vendas"] == '1'){
				//Vendas
				if($i == $count){
					print '
					[
						"'.$ukey_orcamento.'",
						"'.$data_orcamento.'",
						"'.$empresa.'",
						"'.$total_orcamento.'",
						"'.$link.'"
					]
					';
				}else{
					print '
					[
						"'.$ukey_orcamento.'",
						"'.$data_orcamento.'",
						"'.$empresa.'",
						"'.$total_orcamento.'",
						"'.$link.'"
					],
					';
				}
				
			}else{
				//Normal
				if($i == $count){
					print '
					[
						"'.$ukey_orcamento.'",
						"'.$data_orcamento.'",
						"'.$total_orcamento.'",
						"'.$link.'"
					]
					';
				}else{
					print '
					[
						"'.$ukey_orcamento.'",
						"'.$data_orcamento.'",
						"'.$total_orcamento.'",
						"'.$link.'"
					],
					';
				}

			}
		 
		}
		echo '
		]
		}
		';
	}

}elseif($target == '5'){
	include_once "connect_list.php";
	$pegaItens = $pdo->query("SELECT 
		ukey, 
		ukey_orcamentos,
		ukey_lista_preco,
		(SELECT descricao FROM kn_lista_preco WHERE ukey = kn_orcamentos_itens.ukey_lista_preco) descricao,
		codigo,
		quantidade,
		preco,
		total,
		DATE_FORMAT(data, '%d/%m/%Y %H:%i:%s') data
	FROM kn_orcamentos_itens
	WHERE ukey_orcamentos = '".$id_orcamento."'");
	$count = $pegaItens->rowCount();
	
	$i = 0;
	echo '
	{
		"data": [
	';
	
	foreach ($pegaItens as $itens){
		$i++;
		
		$ukey_item	= $itens["ukey"];
		$codigo		= $itens["codigo"];
		$descricao	= utf8_encode($itens["descricao"]);
		$quantidade	= $itens["quantidade"];
		$preco		= number_format($itens["preco"], 2, ',', '.');
		$subtotal	= number_format($itens["total"], 2, ',', '.');
		
		
		if($i == $count){
			print '
			[
				"'.$codigo.'",
				"'.$descricao.'",
				"15",
				"'.$quantidade.'",
				"'.$preco.'",
				"'.$subtotal.'"
			]
			';
		}else{
			print '
			[
				"'.$codigo.'",
				"'.$descricao.'",
				"15",
				"'.$quantidade.'",
				"'.$preco.'",
				"'.$subtotal.'"
			],
			';
		}
		 
	}
	
	echo '
	]
	}
	';
}
?>