<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
$id				= @$_POST['id'];

$like			= @$_POST['pesquisa'];
$avancada		= @$_POST['avancada'];
$data_ini		= @$_POST['data_ini'];
$data_fim		= @$_POST['data_fim'];

//MediaPlus
$a_pi			= @$_POST["pi"];
$a_formato		= @$_POST["formato"];
$a_situacao		= @$_POST["situacao"];
if(!empty($_POST["statuspi"])){
	$a_status = implode(',',$_POST["statuspi"]);	
}
if(!empty($_POST["cliente"])){
	$a_cliente = implode(',',$_POST["cliente"]);	
}
if(!empty($_POST["agencia"])){
	$a_agencia = implode(',',$_POST["agencia"]);	
}
if(!empty($_POST["veiculo"])){
	$a_veiculo = implode(',',$_POST["veiculo"]);	
}
$a_uf			= @$_POST["uf"];
$a_veic_ini		= @$_POST["veic_ini"];
$a_veic_fim		= @$_POST["veic_fim"];


if($a_pi == ""){
	$where_pi = "pi LIKE '%".$a_pi."%'";
}else{
	$where_pi = "pi LIKE '%".$a_pi."%'";
}


if($a_situacao == "open"){
	
	if($a_status == ""){
		$where_status = " AND ukey_status <> '7'";
	}else{
		$where_status = " AND ukey_status IN (".$a_status.") AND ukey_status <> '7'";
	}
	
}elseif($a_situacao == "close"){
	
	if($a_status == ""){
		$where_status = " AND ukey_status = '7'";
	}else{
		$where_status = " AND ukey_status = '7'";
	}
	
}else{
	
	if($a_status == ""){
		$where_status = "";
	}else{
		$where_status = " AND ukey_status IN (".$a_status.")";
	}
	
}


if($a_cliente == ""){
	$where_cliente = "";
}else{
	$where_cliente = " AND ukey_client IN (".$a_cliente.")";
}

if($a_agencia == ""){
	$where_agencia = "";
}else{
	$where_agencia = " AND ukey_agency IN (".$a_agencia.")";
}

if($a_veiculo == ""){
	$where_veiculo = "";
}else{
	$where_veiculo = " AND ukey_vehicles IN (".$a_veiculo.")";
}

if($a_uf == ""){
	$where_uf = "";
}else{
	$where_uf = " AND uf = '".$a_uf."'";
}

if($a_veic_fim == "" and $a_veic_ini == ""){
	$where_veiculacao = "";
}elseif($a_veic_fim == "" and $a_veic_ini <> ""){
	$where_veiculacao = " AND ini_veiculacao = '".$a_veic_ini."'";
}elseif($a_veic_fim <> "" and $a_veic_ini <> ""){
	$where_veiculacao = " AND ini_veiculacao BETWEEN '".$a_veic_ini."' AND '".$a_veic_fim."'";
}

$where = $where_pi.$where_status.$where_cliente.$where_agencia.$where_veiculo.$where_uf.$where_veiculacao;


//Novo
$busca_gerencia = "
SELECT gerencia
FROM mp_group 
WHERE ukey = '".$_SESSION['grupo']."'
";
$sql_gerencia = mysqli_query($con, $busca_gerencia) or die("ERRO NO COMANDO SQL");
$monta_gerencia = mysqli_fetch_array($sql_gerencia);
$gerencia	= $monta_gerencia["gerencia"];
	
	
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
	WHERE unidade = '".$_SESSION["cod_unidade"]."' AND ".$where."
	ORDER BY ukey DESC
	";
	
	$busca_total = "
	SELECT
		SUM(valor_unit) valor_total
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
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0, 1) ukey_status 
		FROM mp_pedidos
	) dados0 
	WHERE unidade = '".$_SESSION["cod_unidade"]."' AND ".$where." 
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
		)DADOS
	WHERE ".$where."
	ORDER BY ukey DESC
	";
	
	$busca_total = "
	SELECT
		SUM(valor_unit) valor_total
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
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0, 1) ukey_status 
		FROM mp_pedidos
	) dados0 
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
		)DADOS
	WHERE ".$where."
	ORDER BY ukey DESC
	";
	
	$busca_total = "
	SELECT
		SUM(valor_unit) valor_total
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
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0, 1) ukey_status 
		FROM mp_pedidos
	) dados0 
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
		WHERE user_ukey = '".$_SESSION['user_login']."' OR ukey_sellers = '".$_SESSION['cod_vendedor']."'
		)DADOS
	WHERE ".$where." 
	ORDER BY ukey DESC
	";
	
	$busca_total = "
	SELECT
		SUM(valor_unit) valor_total
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
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0, 1) ukey_status 
		FROM mp_pedidos
		WHERE user_ukey = '".$_SESSION['user_login']."' OR ukey_sellers = '".$_SESSION['cod_vendedor']."'
	) dados0 
	WHERE ".$where." 
	ORDER BY ukey DESC
	";
	
}
$sql = mysqli_query($con, $busca) or die("ERRO");
$rowcount = mysqli_num_rows($sql);
//Novo


$sql_total = mysqli_query($con, $busca_total) or die("ERRO 2");
$monta_total = mysqli_fetch_array($sql_total);
$valor_total 	= inteiro_decimal_br($monta_total["valor_total"]);


if($a_formato == 'excel'){
	
	// Nome do Arquivo do Excel que será gerado
	$arquivo = 'RELACAO_DE_PEDIDOS.xls';
	
	// Criamos uma tabela HTML com o formato da planilha para excel
	$tabela = '
	<html lang ="pt-br">
	<head>
	<meta charset="utf-8">
	<title>RELAÇÃO DE PEDIDOS</title>
	</head>
	<body>
	<table border="1">
		<tr>
			<th colspan="12">RELAÇÃO DE PEDIDOS</th>
		</tr>
		<tr>
			<th>Código</th>
			<th>PI</th>
			<th>Emissão</th>
			<th>Cliente</th>
			<th>Agência</th>
			<th>Veículo</th>
			<th>Campanha</th>
			<th>Inicio</th>
			<th>Término</th>
			<th>Valor</th>
			<th>Executivo</th>
			<th>Status</th>
		</tr>';
	
	
	while($monta = mysqli_fetch_array($sql)){

	$ukey			= $monta["ukey"];
	$pi				= $monta["pi"];
	$cliente		= $monta["cliente"];
	$agencia		= $monta["agencia"];
	$veiculo		= $monta["veiculo"];
	$campanha		= $monta["campanha"];
	if($monta["ini_veiculacao"] == 0){
		$ini_veic = "";
	}else{
		$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
	}
	if($monta["fim_veiculacao"] == 0){
		$fim_veic = "";
	}else{
		$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
	}
	$valor_unit		= $monta["valor_unit"];
	
	if($monta["emissao"] == 0){
		$emissao = "";
	}else{
		$emissao = date('d/m/Y', strtotime($monta["emissao"]));
	}
		
	$vendedor		= $monta["vendedor"];
	$ukey_status	= $monta["ukey_status"];

	if($ukey_status == NULL){
		$status = "LANÇADO";
	}else{
		$busca_status = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
		$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
		$monta_status = mysqli_fetch_array($sql_status);
		$status = $monta_status["nome"];
	}
	
	$tabela .= '
		<tr>
			<td>MP'.$ukey.'</td>
			<td>'.$pi.'</td>
			<td>'.$emissao.'</td>
			<td>'.$cliente.'</td>
			<td>'.$agencia.'</td>
			<td>'.$veiculo.'</td>
			<td>'.$campanha.'</td>
			<td>'.$ini_veic.'</td>
			<td>'.$fim_veic.'</td>
			<td>'.inteiro_decimal_br($valor_unit).'</td>
			<td>'.$vendedor.'</td>
			<td>'.$status.'</td>
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

}else{


ob_start();

echo '
<table id="table_itens" cellspacing="3" style="width:100%;">
	<thead>
		<tr>
			<th class="borda_topbottom">Código</th>
			<th class="borda_topbottom">PI</th>
			<th class="borda_topbottom">Emissão</th>
			<th class="borda_topbottom">Cliente</th>
			<th class="borda_topbottom">Agência</th>
			<th class="borda_topbottom">Veículo</th>
			<th class="borda_topbottom">Campanha</th>
			<th class="borda_topbottom">Inicio</th>
			<th class="borda_topbottom">Término</th>
			<th class="borda_topbottom">Valor</th>
			<th class="borda_topbottom">Executivo</th>
			<th class="borda_topbottom">Status</th>
		</tr>
	</thead>
	<tbody>
';

while($monta = mysqli_fetch_array($sql)){

	$ukey			= $monta["ukey"];
	$pi				= $monta["pi"];
	$cliente		= $monta["cliente"];
	$agencia		= $monta["agencia"];
	$veiculo		= $monta["veiculo"];
	$campanha		= $monta["campanha"];
	if($monta["ini_veiculacao"] == 0){
		$ini_veic = "";
	}else{
		$ini_veic = date('d/m/Y', strtotime($monta["ini_veiculacao"]));
	}
	if($monta["fim_veiculacao"] == 0){
		$fim_veic = "";
	}else{
		$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
	}
	$valor_unit		= $monta["valor_unit"];
	
	if($monta["emissao"] == 0){
		$emissao = "";
	}else{
		$emissao = date('d/m/Y', strtotime($monta["emissao"]));
	}
		
	$vendedor		= $monta["vendedor"];
	$ukey_status	= $monta["ukey_status"];

	if($ukey_status == NULL){
		$status = "LANÇADO";
	}else{
		$busca_status = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
		$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
		$monta_status = mysqli_fetch_array($sql_status);
		$status = $monta_status["nome"];
	}
	
	echo '
		<tr>
			<td>MP'.$ukey.'</td>
			<td>'.$pi.'</td>
			<td>'.$emissao.'</td>
			<td>'.$cliente.'</td>
			<td>'.$agencia.'</td>
			<td>'.$veiculo.'</td>
			<td>'.$campanha.'</td>
			<td>'.$ini_veic.'</td>
			<td>'.$fim_veic.'</td>
			<td class="right">'.inteiro_decimal_br($valor_unit).'</td>
			<td>'.$vendedor.'</td>
			<td>'.$status.'</td>
		</tr>
	';
	
}

echo '	
	</tbody>
</table>
';

echo '
<h4 class="right">Valor Total: '.$valor_total.'</h4>
';

$html = ob_get_clean();


$header = '
<div style="float:left;width:110px;">
	<img src="../img/duemidia100.png">
</div>
<div style="float:left;width:500px; padding-left:20px;">
	<h4>RELAÇÃO DE PEDIDOS</h4>
	'.date('d/m/Y H:m:s').'
</div>
';

$footer = '
<div style="font-weight:bold;font-size:8pt;text-align:center;">

</div>
<div style="font-weight:bold;font-size:7pt;text-align:center;">
</div>
<div style="font-weight:bold;font-size:7pt;text-align:right;">
	{PAGENO}
</div>
';
		

$name = 'RELAÇÃO DE PEDIDOS '.date('d/m/Y').'.pdf';

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