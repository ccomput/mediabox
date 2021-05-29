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
$a_cobranca		= @$_POST["cobranca"];
$a_cobranca_fim	= @$_POST["cobranca_fim"];

$a_veic_ini		= @$_POST["veic_ini"];
$a_veic_fim		= @$_POST["veic_fim"];


if($a_pi == ""){
	$where_pi = "pi LIKE '%".$a_pi."%'";
}else{
	$where_pi = "pi LIKE '%".$a_pi."%'";
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

$busca = "
SELECT 
	ukey,
	pi, 
	cliente, 
	agencia, 
	veiculo, 
	campanha, 
	ini_veiculacao, 
	fim_veiculacao, 
	vendedor, 
	uf, 
	valor_bruto, 
	valor_unit, 
	IF(desc_imposto = 0, valor_unit * (comissao/100), (valor_unit - (valor_unit*(impostos/100))) * (comissao/100)) comissao,
	emissao, 
	lancamento,
	timestamp, 
	ukey_status, 
	descricao,
	cobranca, 
	nf_veic, 
	nf_mp,
	data_nf_mp,
	recebido,
	data_status,
	data_faturado
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
		lancamento,
		timestamp, 
		(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
		(SELECT descricao FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) descricao, 
		(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca, 
		(SELECT nf_veic FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_veic <> '' ORDER BY ukey DESC LIMIT 0,1) nf_veic, 
		(SELECT nf_mp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_mp <> '' ORDER BY ukey DESC LIMIT 0,1) nf_mp,
		(SELECT data_nf_mp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_mp <> '' ORDER BY ukey DESC LIMIT 0,1) data_nf_mp,
		(SELECT recebido FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND recebido <> '0000-00-00' ORDER BY ukey DESC LIMIT 0,1) recebido,
		(SELECT timestamp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) data_status,
		(SELECT timestamp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND ukey_status = '000003' ORDER BY ukey DESC LIMIT 0,1) data_faturado
	FROM mp_pedidos
) dados WHERE ".$where." ORDER BY ukey DESC
";
$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL1");
$rowcount = mysqli_num_rows($sql);


$busca_total = "
SELECT
	SUM(valor_unit) valor_total,
	SUM(valor_liquido * (comissao/100)) comissao_total
FROM (
	SELECT 
		valor_unit,
		comissao,
		IF(desc_imposto = 0, valor_unit, valor_unit - (valor_unit*(impostos/100))) valor_liquido
	FROM (
		SELECT 
			ukey, 
			pi, 
			ukey_client, 
			ukey_agency, 
			ukey_vehicles, 
			campanha, 
			ini_veiculacao, 
			fim_veiculacao, 
			(SELECT estado FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) uf, 
			valor_unit, 
			(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao, 
			(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
			(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
			emissao, 
			timestamp, 
			(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
			(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca 
		FROM mp_pedidos
	) dados0 WHERE ".$where." ORDER BY ukey DESC
)dados1
";
$sql_total = mysqli_query($con, $busca_total) or die("ERRO NO COMANDO SQL2");
$monta_total = mysqli_fetch_array($sql_total);
$valor_total 	= inteiro_decimal_br($monta_total["valor_total"]);
$comissao_total = inteiro_decimal_br($monta_total["comissao_total"]);



if($a_formato == 'excel'){
	
	// Nome do Arquivo do Excel que será gerado
	$arquivo = 'status_faturamento.xls';
	
	// Criamos uma tabela HTML com o formato da planilha para excel
	$tabela = '
	<html lang ="pt-br">
	<head>
	<meta charset="utf-8">
	<title>XLS</title>
	</head>
	<body>
	<table border="1">
		<tr>
			<th colspan="21">RELAÇÃO DE PI POR STATUS/ETAPA</th>
		</tr>
		<tr>
			<th>Código</th>
			<th>PI</th>
			<th>NF</th>
			<th>NF MP</th>
			<th>Data NF MP</th>
			<th>Cliente</th>
			<th>Agência</th>
			<th>Veículo</th>
			<th>Campanha</th>
			<th>Inicio</th>
			<th>Término</th>
			<th>Executivo</th>
			<th>UF</th>
			<th>V. Bruto</th>
			<th>V. Liquido</th>
			<th>Comissão</th>
			<th>Emissão</th>
			<th>Lançamento</th>
			<th>Status</th>
			<th>Data Status</th>
			<th>Descrição</th>
			<th>Recebido</th>
			<th>Dias</th>
			<th>Data Faturado</th>
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
	$valor_bruto	= $monta["valor_bruto"];
	$valor_unit		= $monta["valor_unit"];
	//$comissao		= $monta["valor_unit"]*($monta["comissao"]/100);
	$comissao		= $monta["comissao"];
	
	if($monta["emissao"] == 0){
		$emissao = "";
	}else{
		$emissao = date('d/m/Y', strtotime($monta["emissao"]));
	}
		
	if($monta["lancamento"] == 0){
		$lancamento = "";
	}else{
		$lancamento = date('d/m/Y', strtotime($monta["lancamento"]));
	}
		
	$vendedor		= $monta["vendedor"];
	$uf				= $monta["uf"];
	$ukey_status	= $monta["ukey_status"];
	$descricao		= str_replace("<br />", "",str_replace("<br>", "", $monta["descricao"]));
	$nf_veic		= $monta["nf_veic"];
	$nf_mp			= $monta["nf_mp"];
	if($monta["data_nf_mp"] == 0){
		$data_nf_mp = "";
	}else{
		$data_nf_mp = date('d/m/Y', strtotime($monta["data_nf_mp"]));
	}
	$recebido		= $monta["recebido"];

	if($ukey_status == NULL){
		$status = "LANÇADO";
	}else{
		$busca_status = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
		$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
		$monta_status = mysqli_fetch_array($sql_status);
		$status = $monta_status["nome"];
	}
	
	$hoje		= date('Y-m-d');
	$data_status= date('Y-m-d',strtotime($monta["data_status"]));
		
	if($ukey_status == NULL){
		$d_status = "";
	}else{
		$d_status = date('d-m-Y',strtotime($monta["data_status"]));
	}
		
	if($monta["data_faturado"] == NULL){
		$d_faturado = '';
	}else{
		$d_faturado = date('d-m-Y',strtotime($monta["data_faturado"]));
	}
	
	// Calcula a diferença em segundos entre as datas
	$diferenca = strtotime($hoje) - strtotime($data_status);

	//Calcula a diferença em dias
	if($ukey_status == NULL){
		$dias = "-";
	}else{
		$dias = floor($diferenca / (60 * 60 * 24));
	}
		
	$tabela .= '
		<tr>
			<td>MP'.$ukey.'</td>
			<td>'.$pi.'</td>
			<td>'.$nf_veic.'</td>
			<td>'.$nf_mp.'</td>
			<td>'.$data_nf_mp.'</td>
			<td>'.$cliente.'</td>
			<td>'.$agencia.'</td>
			<td>'.$veiculo.'</td>
			<td>'.$campanha.'</td>
			<td>'.$ini_veic.'</td>
			<td>'.$fim_veic.'</td>
			<td>'.$vendedor.'</td>
			<td>'.$uf.'</td>
			<td>'.inteiro_decimal_br($valor_bruto).'</td>
			<td>'.inteiro_decimal_br($valor_unit).'</td>
			<td>'.inteiro_decimal_br($comissao).'</td>
			<td>'.$emissao.'</td>
			<td>'.$lancamento.'</td>
			<td>'.$status.'</td>
			<td>'.$d_status.'</td>
			<td>'.$descricao.'</td>
			<td>'.$recebido.'</td>
			<td>'.$dias.'</td>
			<td>'.$d_faturado.'</td>
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
			<th class="borda_topbottom" style="width:4%;">NF</th>
			<th class="borda_topbottom" style="width:4%;">NF DUE</th>
			<th class="borda_topbottom">Cliente</th>
			<th class="borda_topbottom">Agência</th>
			<th class="borda_topbottom">Veículo</th>
			<th class="borda_topbottom">Campanha</th>
			<th class="borda_topbottom">Término</th>
			<th class="borda_topbottom">Vendedor</th>
			<th class="borda_topbottom">UF</th>
			<th class="borda_topbottom">V. Bruto</th>
			<th class="borda_topbottom">V. Liquido</th>
			<th class="borda_topbottom">Comissão</th>
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


	if($monta["fim_veiculacao"] == 0){
		$fim_veic = "";
	}else{
		$fim_veic = date('d/m/Y', strtotime($monta["fim_veiculacao"]));
	}
	$valor_bruto	= $monta["valor_bruto"];
	$valor_unit		= $monta["valor_unit"];
	$comissao		= $monta["comissao"];
	$vendedor		= $monta["vendedor"];
	$uf				= $monta["uf"];
	$ukey_status	= $monta["ukey_status"];
	$nf_veic		= $monta["nf_veic"];
	$nf_mp			= $monta["nf_mp"];


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
			<td>DUE'.$ukey.'</td>
			<td>'.$pi.'</td>
			<td>'.$nf_veic.'</td>
			<td>'.$nf_mp.'</td>
			<td>'.$cliente.'</td>
			<td>'.$agencia.'</td>
			<td>'.$veiculo.'</td>
			<td>'.$campanha.'</td>
			<td>'.$fim_veic.'</td>
			<td>'.$vendedor.'</td>
			<td>'.$uf.'</td>
			<td class="right">'.inteiro_decimal_br($valor_bruto).'</td>
			<td class="right">'.inteiro_decimal_br($valor_unit).'</td>
			<td class="right">'.inteiro_decimal_br($comissao).'</td>
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
<h4 class="right">Comissão Total: '.$comissao_total.'</h4>
';

$html = ob_get_clean();


$header = '
<div style="float:left;width:110px;">
	<img src="../img/duemidia100.png">
</div>
<div style="float:left;width:500px; padding-left:20px;">
	<h4>RELAÇÃO DE PI POR STATUS/ETAPA</h4>
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
		

$name = 'RELAÇÃO DE PI '.date('d/m/Y').'.pdf';

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