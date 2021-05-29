<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_faturamento= 'active';
$status_pedido	= 'active';

//VARIAVEIS DE TRANSAÇÃO
$like = @$_POST["pesquisa"];

////Pesquisa Avançada
$avancada		= @$_POST["avancada"];
$a_pi			= @$_POST["pi"];
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


/*BUSCA TOTAL*/
if($avancada == "yes"){
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
		
}elseif($_SESSION['grupo'] == 1){
	if($like == ""){
		$where = "ukey_status <> '7' OR ukey_status IS NULL";
	}else{
		$where = "ukey = '".$like."'";
	}
	
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
}else{
	if($like == ""){
		$where = "ukey_status <> '7' OR ukey_status IS NULL";
	}else{
		$where = "ukey = '".$like."'";
	}
	
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
}
$sql_total = mysqli_query($con, $busca_total) or die("ERRO NO COMANDO SQL2");
$monta_total = mysqli_fetch_array($sql_total);
$valor_total 	= decimal_br($monta_total["valor_total"]);
$comissao_total = decimal_br($monta_total["comissao_total"]);

/*BUSCA TOTAL*/

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="pt-br" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="pt-br"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<?php include "head.php"; ?>
	<style>
		div.dataTables_wrapper {width: 100%;margin: 0 auto;}
	</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<?php include "preloader.php"; ?>
	<!-- BEGIN HEADER -->
	<div id="header" class="navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<?php include "topnavigationbar.php"; ?>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->
	<div id="container" class="row-fluid">
		<!-- BEGIN SIDEBAR -->
		<div class="sidebar-scroll">
			<div id="sidebar" class="nav-collapse collapse">
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
				<div class="navbar-inverse">
					<form class="navbar-search visible-phone">
						<input type="text" class="search-query" placeholder="Search" />
					</form>
				</div>
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
				<!-- BEGIN SIDEBAR MENU -->
				<?php include "sidebar.php"; ?>
				<!-- END SIDEBAR MENU -->
			</div>
		</div>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->  
		<div id="main-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->   
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
							Status do Faturamento
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="index.php">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="#">Financeiro</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Status do Faturamento
							</li>

							<li class="pull-right search-wrap">
								
								<form action="#" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o código DUE">
										<button class="btn" type="submit"><i class="icon-search"></i> </button>
                                        <a data-original-title="" href="#" data-toggle="modal" data-target="#basicModal" title="Pesquisa Avançada"><button class="btn" type="button"><i class="icon-filter"></i> </button></a>
										<?php if($_SESSION["mod_configura"] == 1){ ?>
										<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorios" title="Relatórios"><button class="btn" type="button"><i class="icon-file-text"></i> </button></a>
										<?php } ?>
										<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorio" title="Relatório"><button class="btn" type="button"><i class="icon-print"></i> </button></a>
									</div>
								</form>
								
								<!--Modal-->
                                <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Pesquisa Avançada</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_pedido" method="post" action="#" enctype="multipart/form-data">
												<div class="modal-body">
												
													<input type="hidden" id="avancada" name="avancada" value="yes">

													<div class="control-group ">
														<label for="situacao" class="control-label">Situação</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha a situação" id="situacao" name="situacao" tabindex="1">
																<option value="open">Aberto</option>
																<option value="close">Fechado</option>
																<option value="all">Todos</option>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="statuspi" class="control-label">Status PI</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o status" id="statuspi" name="statuspi[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_status = "SELECT ukey, nome FROM mp_status ORDER BY ukey ASC";
																$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL");
																$row_status = mysqli_num_rows($sql_status);

																if($row_status == 0){
																	echo '<option value="">Não há status cadastrados</option>';
																}else{
																	while($monta_status = mysqli_fetch_array($sql_status)){
																		$ukey_status = $monta_status["ukey"];
																		$nome_status = $monta_status["nome"];

																		echo '<option value="'.$ukey_status.'">'.$nome_status.'</option>';

																	}
																}
																?>
															</select>
														</div>
													</div>
                                                    
													<div class="control-group">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o cliente" id="cliente" name="cliente[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_cliente = "SELECT ukey, fantasia FROM mp_client ORDER BY fantasia ASC";
																$sql_cliente = mysqli_query($con, $busca_cliente) or die("ERRO NO COMANDO SQL");
																$row_cliente = mysqli_num_rows($sql_cliente);

																if($row_cliente == 0){
																	echo '<option value="">Não há clientes cadastrados</option>';
																}else{
																	while($monta_cliente = mysqli_fetch_array($sql_cliente)){
																		$ukey_cliente		= $monta_cliente["ukey"];
																		$fantasia_cliente	= $monta_cliente["fantasia"];

																		echo '<option value="'.$ukey_cliente.'">'.$fantasia_cliente.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="agencia" class="control-label">Agência</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha a agência" id="agencia" name="agencia[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_agencia = "SELECT ukey, fantasia FROM mp_agency ORDER BY fantasia ASC";
																$sql_agencia = mysqli_query($con, $busca_agencia) or die("ERRO NO COMANDO SQL");
																$row_agencia = mysqli_num_rows($sql_agencia);

																if($row_agencia == 0){
																	echo '<option value="">Não há agências cadastradas</option>';
																}else{
																	while($monta_agencia = mysqli_fetch_array($sql_agencia)){
																		$ukey_agencia		= $monta_agencia["ukey"];
																		$fantasia_agencia	= $monta_agencia["fantasia"];

																		echo '<option value="'.$ukey_agencia.'">'.$fantasia_agencia.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="veiculo" class="control-label">Veículo</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o veículo" id="veiculo" name="veiculo[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_veiculo = "SELECT ukey, fantasia FROM mp_vehicles ORDER BY fantasia ASC";
																$sql_veiculo = mysqli_query($con, $busca_veiculo) or die("ERRO NO COMANDO SQL");
																$row_veiculo = mysqli_num_rows($sql_veiculo);

																if($row_veiculo == 0){
																	echo '<option value="">Não há veículos cadastrados</option>';
																}else{
																	while($monta_veiculo = mysqli_fetch_array($sql_veiculo)){
																		$ukey_veiculo		= $monta_veiculo["ukey"];
																		$fantasia_veiculo	= $monta_veiculo["fantasia"];

																		echo '<option value="'.$ukey_veiculo.'">'.$fantasia_veiculo.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="uf" class="control-label">UF</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o Estado" id="uf" name="uf" tabindex="1">
																<option value="">Selecione</option>
																<option value="DF">DF</option>
																<option value="RJ">RJ</option>
																<option value="SP">SP</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="cobranca" class="control-label">Cobrança</label>
														<div class="controls">
															<input id="cobranca" name="cobranca" type="date" value="" size="12" class="m-ctrl-medium data"> e 
															<input id="cobranca_fim" name="cobranca_fim" type="date" value="" size="12" class="m-ctrl-medium data">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="veic_ini" class="control-label">Veículação</label>
														<div class="controls">
															<input id="veic_ini" name="veic_ini" type="date" value="" size="12" class="m-ctrl-medium data"> e 
															<input id="veic_fim" name="veic_fim" type="date" value="" size="12" class="m-ctrl-medium data">
														</div>
													</div>
                                                    
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													<button type="submit" class="btn btn-primary">Salvar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
                                <!--Modal-->
								
								<!--Modal Relatórios-->
                                <div class="modal fade" id="relatorios" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Relatórios</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_pedido" method="post" action="#" enctype="multipart/form-data">
												<div class="modal-body">
												
													<input type="hidden" id="avancada" name="avancada" value="yes">

													
													<div class="control-group">
														<label for="tipo" class="control-label">Tipo</label>
														<div class="controls controls-row">
															<select class="span12" data-placeholder="" id="tipo" name="tipo" tabindex="1">
																<option value="">Selecione</option>
																<option value="0">Resumo Comissão</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="situacao" class="control-label">Situação</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha a situação" id="situacao" name="situacao" tabindex="1">
																<option value="open">Aberto</option>
																<option value="close">Fechado</option>
																<option value="all">Todos</option>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="statuspi" class="control-label">Status PI</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o status" id="statuspi" name="statuspi" tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_status = "SELECT ukey, nome FROM mp_status ORDER BY ukey ASC";
																$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL");
																$row_status = mysqli_num_rows($sql_status);

																if($row_status == 0){
																	echo '<option value="">Não há status cadastrados</option>';
																}else{
																	while($monta_status = mysqli_fetch_array($sql_status)){
																		$ukey_status = $monta_status["ukey"];
																		$nome_status = $monta_status["nome"];

																		echo '<option value="'.$ukey_status.'">'.$nome_status.'</option>';

																	}
																}
																?>
															</select>
														</div>
													</div>
                                                    
													<div class="control-group">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o cliente" id="cliente" name="cliente" tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_cliente = "SELECT ukey, fantasia FROM mp_client ORDER BY fantasia ASC";
																$sql_cliente = mysqli_query($con, $busca_cliente) or die("ERRO NO COMANDO SQL");
																$row_cliente = mysqli_num_rows($sql_cliente);

																if($row_cliente == 0){
																	echo '<option value="">Não há clientes cadastrados</option>';
																}else{
																	while($monta_cliente = mysqli_fetch_array($sql_cliente)){
																		$ukey_cliente		= $monta_cliente["ukey"];
																		$fantasia_cliente	= $monta_cliente["fantasia"];

																		echo '<option value="'.$ukey_cliente.'">'.$fantasia_cliente.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="agencia" class="control-label">Agência</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha a agência" id="agencia" name="agencia" tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_agencia = "SELECT ukey, fantasia FROM mp_agency ORDER BY fantasia ASC";
																$sql_agencia = mysqli_query($con, $busca_agencia) or die("ERRO NO COMANDO SQL");
																$row_agencia = mysqli_num_rows($sql_agencia);

																if($row_agencia == 0){
																	echo '<option value="">Não há agências cadastradas</option>';
																}else{
																	while($monta_agencia = mysqli_fetch_array($sql_agencia)){
																		$ukey_agencia		= $monta_agencia["ukey"];
																		$fantasia_agencia	= $monta_agencia["fantasia"];

																		echo '<option value="'.$ukey_agencia.'">'.$fantasia_agencia.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="veiculo" class="control-label">Veículo</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o veículo" id="veiculo" name="veiculo" tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_veiculo = "SELECT ukey, fantasia FROM mp_vehicles ORDER BY fantasia ASC";
																$sql_veiculo = mysqli_query($con, $busca_veiculo) or die("ERRO NO COMANDO SQL");
																$row_veiculo = mysqli_num_rows($sql_veiculo);

																if($row_veiculo == 0){
																	echo '<option value="">Não há veículos cadastrados</option>';
																}else{
																	while($monta_veiculo = mysqli_fetch_array($sql_veiculo)){
																		$ukey_veiculo		= $monta_veiculo["ukey"];
																		$fantasia_veiculo	= $monta_veiculo["fantasia"];

																		echo '<option value="'.$ukey_veiculo.'">'.$fantasia_veiculo.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="uf" class="control-label">UF</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o Estado" id="uf" name="uf" tabindex="1">
																<option value="">Selecione</option>
																<option value="DF">DF</option>
																<option value="RJ">RJ</option>
																<option value="SP">SP</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="cobranca" class="control-label">Cobrança</label>
														<div class="controls">
															<input id="cobranca" name="cobranca" type="date" value="" size="12" class="m-ctrl-medium data"> e 
															<input id="cobranca_fim" name="cobranca_fim" type="date" value="" size="12" class="m-ctrl-medium data">
														</div>
													</div>
                                                    
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													<button type="submit" class="btn btn-primary">Salvar</button>
												</div>
											</form>
										</div>
									</div>
								</div>
                                <!--Modal-->
								
								<!-- Modal Relatório -->
                                <div class="modal fade" id="relatorio" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Relatório</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/status.php" enctype="multipart/form-data">
												<div class="modal-body">
													<input type="hidden" id="avancada" name="avancada" value="yes">
													
													<div class="control-group ">
														<label for="formato" class="control-label">Formato</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o formato" id="formato" name="formato" tabindex="1">
																<option value="pdf">PDF</option>
																<option value="excel">Excel</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="situacao" class="control-label">Situação</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha a situação" id="situacao" name="situacao" tabindex="1">
																<option value="open">Aberto</option>
																<option value="close">Fechado</option>
																<option value="all">Todos</option>
															</select>
														</div>
													</div>
													
                                                    <div class="control-group">
														<label for="statuspi" class="control-label">Status PI</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o status" id="statuspi" name="statuspi[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_status = "SELECT ukey, nome FROM mp_status ORDER BY ukey ASC";
																$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL");
																$row_status = mysqli_num_rows($sql_status);

																if($row_status == 0){
																	echo '<option value="">Não há status cadastrados</option>';
																}else{
																	while($monta_status = mysqli_fetch_array($sql_status)){
																		$ukey_status = $monta_status["ukey"];
																		$nome_status = $monta_status["nome"];

																		echo '<option value="'.$ukey_status.'">'.$nome_status.'</option>';

																	}
																}
																?>
															</select>
														</div>
													</div>
                                                    
													<div class="control-group">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o cliente" id="cliente" name="cliente[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_cliente = "SELECT ukey, fantasia FROM mp_client ORDER BY fantasia ASC";
																$sql_cliente = mysqli_query($con, $busca_cliente) or die("ERRO NO COMANDO SQL");
																$row_cliente = mysqli_num_rows($sql_cliente);

																if($row_cliente == 0){
																	echo '<option value="">Não há clientes cadastrados</option>';
																}else{
																	while($monta_cliente = mysqli_fetch_array($sql_cliente)){
																		$ukey_cliente		= $monta_cliente["ukey"];
																		$fantasia_cliente	= $monta_cliente["fantasia"];

																		echo '<option value="'.$ukey_cliente.'">'.$fantasia_cliente.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="agencia" class="control-label">Agência</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha a agência" id="agencia" name="agencia[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_agencia = "SELECT ukey, fantasia FROM mp_agency ORDER BY fantasia ASC";
																$sql_agencia = mysqli_query($con, $busca_agencia) or die("ERRO NO COMANDO SQL");
																$row_agencia = mysqli_num_rows($sql_agencia);

																if($row_agencia == 0){
																	echo '<option value="">Não há agências cadastradas</option>';
																}else{
																	while($monta_agencia = mysqli_fetch_array($sql_agencia)){
																		$ukey_agencia		= $monta_agencia["ukey"];
																		$fantasia_agencia	= $monta_agencia["fantasia"];

																		echo '<option value="'.$ukey_agencia.'">'.$fantasia_agencia.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group">
														<label for="veiculo" class="control-label">Veículo</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o veículo" id="veiculo" name="veiculo[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
																$busca_veiculo = "SELECT ukey, fantasia FROM mp_vehicles ORDER BY fantasia ASC";
																$sql_veiculo = mysqli_query($con, $busca_veiculo) or die("ERRO NO COMANDO SQL");
																$row_veiculo = mysqli_num_rows($sql_veiculo);

																if($row_veiculo == 0){
																	echo '<option value="">Não há veículos cadastrados</option>';
																}else{
																	while($monta_veiculo = mysqli_fetch_array($sql_veiculo)){
																		$ukey_veiculo		= $monta_veiculo["ukey"];
																		$fantasia_veiculo	= $monta_veiculo["fantasia"];

																		echo '<option value="'.$ukey_veiculo.'">'.$fantasia_veiculo.'</option>';
																	}
																}
																?>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="uf" class="control-label">UF</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o Estado" id="uf" name="uf" tabindex="1">
																<option value="">Selecione</option>
																<option value="DF">DF</option>
																<option value="RJ">RJ</option>
																<option value="SP">SP</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="cobranca" class="control-label">Cobrança</label>
														<div class="controls">
															<input id="cobranca" name="cobranca" type="date" value="" size="12" class="m-ctrl-medium data"> e 
															<input id="cobranca_fim" name="cobranca_fim" type="date" value="" size="12" class="m-ctrl-medium data">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="veic_ini" class="control-label">Veículação</label>
														<div class="controls">
															<input id="veic_ini" name="veic_ini" type="date" value="" size="12" class="m-ctrl-medium data"> e 
															<input id="veic_fim" name="veic_fim" type="date" value="" size="12" class="m-ctrl-medium data">
														</div>
													</div>
                                                    
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													<button type="submit" class="btn btn-primary">OK</button>
												</div>
											</form>
										</div>
									</div>
								</div>
                                <!-- Modal Relatório -->
								
							</li>
							<li class="pull-right">
								
							</li>
							
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN ADVANCED TABLE widget-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE widget-->
						<div class="widget orange">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Pedidos</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered display nowrap" id="status_pedido">
									<thead>
										<tr>
											<th>Código</th>
											<th>PI</th>
											<th>Cliente</th>
                                            <th>Agência</th>
                                            <th>Veículo</th>
											<th>Campanha</th>
											<th width="136px">Período</th>
											<!--<th>Fim</th>-->
											<th>Vendedor</th>
											<th class="right">Bruto</th>
											<th class="right">Liquido</th>
											<!--<th class="right">Comissão</th>-->
											<th>NF</th>
											<!--<th>NF DUE</th>-->
											<th>Cobrança</th>
											<th width="96px">Status</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<!-- END EXAMPLE TABLE widget-->
					</div>
				</div>
				<!-- END ADVANCED TABLE widget-->
				
				<!-- BEGIN ADVANCED TABLE widget-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE widget-->
						<div class="widget blue">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Totais do Filtro</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body">
								<div class="row-fluid">
									<div class="span4">
										<h3>Liquido</h3>
										<h3>Comissão</h3>
									</div>
									<div class="span4">
										
									</div>
									<div class="span4 text-right">
										<h3><?php echo $valor_total; ?></h3>
										<h3><?php echo $comissao_total; ?></h3>
									</div>
								</div>
							</div>
						</div>
						<!-- END EXAMPLE TABLE widget-->
					</div>
				</div>
				<!-- END ADVANCED TABLE widget-->
				
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->  
	</div>
	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->
	<?php include "footer.php"; ?>
	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<script src="js/jquery-1.8.3.min.js"></script>
	<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/jquery.blockui.js"></script>
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="js/excanvas.js"></script>
	<script src="js/respond.js"></script>
	<![endif]-->
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<!--<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>-->
	<!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>-->
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<!--<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/sorting/date-uk.js"></script>-->
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/date-uk.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page only-->
	<script>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
    </script>
	<!--<script src="js/form-validation-script.js"></script>-->
	<!--<script src="js/form-component.js"></script>-->
	<!--<script src="js/dynamic-table.js"></script>-->
	<?php
	$ajax = 'ajax.php?target=0&pesquisa='.$like.'&pi='.$a_pi.'&avancada='.$avancada.'&situacao='.$a_situacao.'&statuspi='.$a_status.'&cliente='.$a_cliente.'&agencia='.$a_agencia.'&veiculo='.$a_veiculo.'&uf='.$a_uf.'&cobranca='.$a_cobranca.'&cobranca_fim='.$a_cobranca_fim.'&veic_ini='.$a_veic_ini.'&veic_fim='.$a_veic_fim;
	?>
    <script>
	$(document).ready(function() {
		$('#status_pedido').DataTable( {
			/*"scrollX": true,*/
			"ajax": "<?php echo $ajax; ?>",
			"order": [],
			"columnDefs": [
                { "type": "date-uk", targets: 10 }
            ],
			
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
            }
			
		} );
	
	} );
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>