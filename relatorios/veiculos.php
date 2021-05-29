<?php
require_once "../inc/conect.php";
require "../inc/verifica.php";
require "functions.php";

//*VARIAVEIS DE TRANSAÇÃO	
//$id = $_GET["id"];
$formato 	= $_POST["formato"];

//BUSCA
$busca1 = "
SELECT
	ukey,
	fantasia,
	cnpj,
	estado,
	fone1,
	contato1,
	ativo,
	meio,
	comissao,
	impostos,
	desc_imposto,
	prazo
FROM `mp_vehicles`
ORDER BY fantasia ASC
";

$sql1 = mysqli_query($con, $busca1) or die("ERRO NO COMANDO SQL1");
$num_rows1 = mysqli_num_rows($sql1);


if($formato == "excel"){
	
	if($_SESSION["mod_certificado"] == 1){
		
		// Nome do Arquivo do Excel que será gerado
		$arquivo = 'relacao_de_veiculos.xls';
		
		// Criamos uma tabela HTML com o formato da planilha para excel
		$tabela = '
		<html lang ="pt-br">
		<head>
		<meta charset="utf-8">
		<title>Relação de Veículos</title>
		</head>
		<body>
		<table border="1">
			<tr>
				<th colspan="5">Relação de Veículos</th>
			</tr>
			<tr>
				<th>VEICULO</th>
				<th>CÓDIGO</th>
				<th>CNPJ</th>
				<th>UF</th>
				<th>TELEFONE</th>
				<th>CONTATO</th>
				<th>SITUAÇÃO</th>
				<th>MEIO</th>
				<th>COMISSÃO(%)</th>
				<th>IMPOSTOS(%)</th>
				<th>DESCONTA</th>
				<th>PRAZO(DIAS)</th>
			</tr>';

		while($monta1 = mysqli_fetch_array($sql1)){

			$veiculo 	= $monta1["fantasia"];
			$codigo 	= $monta1["ukey"];
			$cnpj 		= formatarCnpj($monta1["cnpj"]);
			$uf 		= $monta1["estado"];
			$fone1 		= $monta1["fone1"];
			$contato1 	= $monta1["contato1"];
			$ativo 		= $monta1["ativo"];
			if($ativo == "1"){
				$situacao = "Sim";
			}else{
				$situacao = "Não";
			}
			$meio 		= $monta1["meio"];
			$comissao	= inteiro_decimal_br($monta1["comissao"]);
			$impostos	= inteiro_decimal_br($monta1["impostos"]);
			$desc_imposto	= $monta1["desc_imposto"];
			if($desc_imposto == "1"){
				$imposto = "Sim";
			}else{
				$imposto = "Não";
			}
			$prazo		= $monta1["prazo"];


			$tabela .= '
				<tr>
					<td>'.$veiculo.'</td>
					<td>'.$codigo.'</td>
					<td>'.$cnpj.'</td>
					<td>'.$uf.'</td>
					<td>'.$fone1.'</td>
					<td>'.$contato1.'</td>
					<td>'.$situacao.'</td>
					<td>'.$meio.'</td>
					<td>'.$comissao.'</td>
					<td>'.$impostos.'</td>
					<td>'.$imposto.'</td>
					<td>'.$prazo.'</td>
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
	<table id="table_itens" cellspacing="3" style="width:100%;">
		<thead>
			<tr>
				<td class="borda_topbottom"><b>VEICULO</b></td>
				<td class="borda_topbottom"><b>CÓDIGO</b></td>
				<td class="borda_topbottom"><b>CNPJ</b></td>
				<td class="borda_topbottom"><b>UF</b></td>
				<th class="borda_topbottom">TELEFONE</th>
				<th class="borda_topbottom">CONTATO</th>
				<th class="borda_topbottom">SITUAÇÃO</th>
				<th class="borda_topbottom">MEIO</th>
				<th class="borda_topbottom">COMISSÃO(%)</th>
				<th class="borda_topbottom">IMPOSTOS(%)</th>
				<th class="borda_topbottom">DESCONTA</th>
				<th class="borda_topbottom">PRAZO(DIAS)</th>
			</tr>
		</thead>
		<tbody>
	';
	
	
	while($monta1 = mysqli_fetch_array($sql1)){

		$veiculo 	= $monta1["fantasia"];
		$codigo 	= $monta1["ukey"];
		$cnpj 		= formatarCnpj($monta1["cnpj"]);
		$uf 		= $monta1["estado"];
		$fone1 		= $monta1["fone1"];
		$contato1 	= $monta1["contato1"];
		$ativo 		= $monta1["ativo"];
		if($ativo == "1"){
			$situacao = "Sim";
		}else{
			$situacao = "Não";
		}
		$meio 		= $monta1["meio"];
		$comissao	= inteiro_decimal_br($monta1["comissao"]);
		$impostos	= inteiro_decimal_br($monta1["impostos"]);
		$desc_imposto	= $monta1["desc_imposto"];
		if($desc_imposto == "1"){
			$imposto = "Sim";
		}else{
			$imposto = "Não";
		}
		$prazo		= $monta1["prazo"];
			

		echo '
		<tr>
			<td>'.$veiculo.'</td>
			<td>'.$codigo.'</td>
			<td>'.$cnpj.'</td>
			<td>'.$uf.'</td>
			<td>'.$fone1.'</td>
			<td>'.$contato1.'</td>
			<td>'.$situacao.'</td>
			<td>'.$meio.'</td>
			<td class="right">'.$comissao.'</td>
			<td class="right">'.$impostos.'</td>
			<td>'.$imposto.'</td>
			<td class="right">'.$prazo.'</td>
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
	<div style="float:left;width:100px;">
		<img src="../img/mediaplus.png">
	</div>
	<div style="float:left;width:500px;">
		<h4>RELAÇÃO DE VEÍCULOS</h4>
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
		<h4>RELAÇÃO DE VEÍCULOS</h4>
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

$name = 'RELACAO_DE_VEICULOS'.date('d/m/Y').'.pdf';

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