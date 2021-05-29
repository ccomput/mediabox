<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_faturamento= 'active';
$status_pedido	= 'active';

//VARIAVEIS DE EDIÇÃO
if($_SESSION["mod_add_proposta"] == 1){
	$edit = '';
}else{
	$edit = 'disabled';
}

//*VARIAVEIS DE TRANSAÇÃO	
$id		= @$_GET["id"];
$insert	= @$_POST["insert"];
$update = @$_POST["update"];
$delete	= @$_GET["delete"];
$table	= "mp_pedidos";
$page	= "Location:form_status_pedido.php?viewer=faturamento";

if ($id >= 1 and $id <> "new"){
	$busca = "
	SELECT 
		ukey, 
		pi, 
		ukey_client, 
		(SELECT fantasia FROM mp_client WHERE ukey = ".$table.".ukey_client) cliente, 
		ukey_agency, 
		(SELECT fantasia FROM mp_agency WHERE ukey = ".$table.".ukey_agency) agencia, 
		ukey_vehicles, 
		(SELECT fantasia FROM mp_vehicles WHERE ukey = ".$table.".ukey_vehicles) veiculo, 
		campanha, 
		ini_veiculacao, 
		fim_veiculacao, 
		ukey_sellers, 
		(SELECT fantasia FROM mp_sellers WHERE ukey = ".$table.".ukey_sellers) vendedor, 
		valor_unit, 
		valor_bruto, 
		(SELECT comissao FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) comissao,
		(SELECT impostos FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) impostos,
		(SELECT desc_imposto FROM mp_vehicles WHERE ukey = mp_pedidos.ukey_vehicles) desc_imposto,
		proposta, 
		obs, 
		emissao, 
		lancamento, 
		user_ukey, 
		(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status, 
		(SELECT nf_veic FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey AND nf_veic <> '' ORDER BY ukey DESC LIMIT 0,1) nf_veic, 
		(SELECT nf_mp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) nf_mp,
		(SELECT data_nf_mp FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) data_nf_mp 
	FROM ".$table." 
	WHERE ukey=".$id;
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

	$monta = mysqli_fetch_array($sql);

	$ukey			= $monta["ukey"];
	$pi				= $monta["pi"];
	$ukey_client	= $monta["ukey_client"];
	$cliente		= $monta["cliente"];
	$ukey_agency	= $monta["ukey_agency"];
	$agencia		= $monta["agencia"];
	$ukey_vehicles	= $monta["ukey_vehicles"];
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
	$ukey_sellers	= $monta["ukey_sellers"];
	$vendedor		= $monta["vendedor"];
	$valor_unit		= decimal_br($monta["valor_unit"]);
	$valor_bruto	= decimal_br($monta["valor_bruto"]);
	
	/*Formula Comissão IF(desc_imposto = 0, valor_unit * (comissao/100), (valor_unit - (valor_unit*(impostos/100))) * (comissao/100)) comissao,*/
	if($monta["desc_imposto"] == 0){
		$comissao = decimal_br($monta["valor_unit"]*($monta["comissao"]/100));
	}else{
		$comissao = decimal_br(($monta["valor_unit"] - ($monta["valor_unit"]*($monta["impostos"]/100))) * ($monta["comissao"]/100));
	}
	
	$proposta		= $monta["proposta"];
	$obs			= $monta["obs"];

	if($monta["emissao"] == 0){
		$emissao = "-";
	}else{
		$emissao = date('d/m/Y', strtotime($monta["emissao"]));
	}
	
	$lancamento		= $monta["lancamento"];
	$user_ukey		= $monta["user_ukey"];
	$ukey_status	= $monta["ukey_status"];
	$nf_veic		= $monta["nf_veic"];
	$nf_mp			= $monta["nf_mp"];
	
	if($monta["data_nf_mp"] == 0){
		$data_nf_mp = "-";
	}else{
		$data_nf_mp = date('d/m/Y', strtotime($monta["data_nf_mp"]));
	}
	
	if($ukey_status == NULL){
		$status = "LANÇADO";
	}else{
		$busca_ustatus = "SELECT nome FROM mp_status WHERE ukey =".$ukey_status."";
		$sql_ustatus = mysqli_query($con, $busca_ustatus) or die("ERRO NO COMANDO SQL STATUS".$busca_status);
		$monta_ustatus = mysqli_fetch_array($sql_ustatus);
		$name_status = $monta_ustatus["nome"];
		
		if($ukey_status == 9){
			$status = '<button class="btn btn-small btn-danger" type="button"><i class="icon-exclamation icon-white"></i></button> '.$name_status;
		}elseif($ukey_status == 8){
			$status = '<button class="btn btn-small btn-warning" type="button"><i class="icon-exclamation icon-white"></i></button> '.$name_status;
		}elseif($ukey_status == 7){
			$status = '<button class="btn btn-small btn-success" type="button"><i class="icon-info icon-white"></i></button> '.$name_status;
		}elseif($ukey_status == 5){
			$status = '<button class="btn btn-small btn-primary" type="button"><i class="icon-info icon-white"></i></button> '.$name_status;
		}elseif($ukey_status == 4){
			$status = '<button class="btn btn-small " type="button"><i class="icon-info icon-white"></i></button> '.$name_status;
		}else{
			$status = $name_status;
		}
		
	}
	
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){
	
	//$ukey			= $_POST["ukey"];
	/*$ukey_pedidos	= $_POST["pedido"];
	$ukey_status	= $_POST["status"];
	$descricao		= $_POST["descricao"];
	$cobranca		= $_POST["cobranca"];
	$recebido		= $_POST["recebido"];
	$user_ukey		= $_SESSION["user_login"];

	$select_insert = "INSERT INTO mp_status_pedido (ukey_pedidos, ukey_status, descricao, cobranca, recebido, user_ukey) VALUES ('".$ukey_pedidos."','".$ukey_status."','".$descricao."','".$cobranca."','".$recebido."','".$user_ukey."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header($page);*/
		
}elseif($update == 1){

	header($page);
	
}elseif($delete == 1){

	header($page);
	
}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="pt-br" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="pt-br"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<?php include "head.php"; ?>
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
							<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Status Faturamento
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="/">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="/">Financeiro</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Status Faturamento
							</li>
							<li class="pull-right search-wrap">
								<form action="status_pedido.php?viewer=financeiro" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o código DUE">
										<button class="btn" type="submit"><i class="icon-search"></i> </button>
                                        <a data-original-title="" href="#" data-toggle="modal" data-target="#basicModal" title="Pesquisa Avançada"><button class="btn" type="button"><i class="icon-reorder"></i> </button></a>
										<!--<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorio" title="Relatório"><button class="btn" type="button"><i class="icon-print"></i> </button></a>-->
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
                                            <form class="cmxform form-horizontal" id="form_status_pedido" method="post" action="status_pedido.php?viewer=financeiro" enctype="multipart/form-data">
												<div class="modal-body">
												
													<input type="hidden" id="avancada" name="avancada" value="yes">

													
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
														<div class="controls">
															<input class="span12" id="uf" name="uf" type="text" maxlength="2" value="">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
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
								
							</li>
							
							<li class="pull-right">
							<?php
							if($_SESSION["mod_add_plantel"] == 1){ 
								echo '<a data-original-title="" href="#" data-toggle="modal" data-target="#ModalAddStatus" title="Adicionar Status"><button class="btn btn-warning" type="button" style="height:36px; margin-top:-8px;"><i class="icon-plus" style="z-index:2000;"></i> Adicionar</button></a>';
							}
							?>
								<!-- Modal Sucesso -->
								<div class="modal fade" id="ModalSucesso" tabindex="-1" role="dialog" aria-labelledby="ModalSucesso" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-body">
												<h2>Atualizado com Sucesso!</h2>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
											</div>
										</div>
									</div>
								</div>
                                <!-- Modal Sucesso -->
							</li>
							
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN SAMPLE PORTLET-->
						<div class="widget blue">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Dados do Pedido</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body widget-body-preview">
								<!--<div class="row-fluid">
									
									<div class="span12">-->
									
										<div class="row-fluid">
											<div class="span3">
												<h3>Código: DUE<?php echo $ukey; ?></h3>
											</div>
											<div class="span3">
												<h3>PI: <?php echo $pi; ?></h4>
											</div>
											<div class="span3">
												<h3>Status: <?php echo $status; ?></h3>
											</div>
											<div class="span3">
												<h3>NF Veic: <?php echo $nf_veic; ?></h3>
											</div>
										</div>
										
										<div class="row-fluid">
											<div class="span9">
												<h3 class="text-form">Campanha: <?php echo $campanha; ?></h3>
											</div>
											<div class="span3">
												<h3 class="text-form">NFe: <?php echo $nf_mp; echo ' '.$data_nf_mp; ?></h3>
											</div>
										</div>
										
										<div class="row-fluid">
											<div class="span3">
												<h4 class="text-form">Cliente: <?php echo $cliente; ?></h4>
												<h4 class="text-form">Agência: <?php echo $agencia; ?></h4>
											</div>
											<div class="span3">
												<h4 class="text-form">Veículo: <?php echo $veiculo; ?></h4>
												<h4 class="text-form">Vendedor: <?php echo $vendedor; ?></h4>
											</div>
											<div class="span6">
												<div class="row-fluid">
													<div class="span4">
														<h4 class="text-form">Inicio: <?php echo $ini_veic; ?></h4>
														<h4 class="text-form">Valor Bruto: R$<?php echo $valor_bruto; ?></h4>
													</div>
													<div class="span4">
														<h4 class="text-form">Fim: <?php echo $fim_veic; ?></h4>
														<h4 class="text-form">Valor Liquido: R$<?php echo $valor_unit; ?></h4>
													</div>
													<div class="span4">
														<h4 class="text-form">Emissão: <?php echo $emissao; ?></h4>
														<h4 class="text-form">Comissão: R$<?php echo $comissao; ?></h4>
													</div>
												</div>
											</div>
										</div>
										
									<!--</div>
									
								</div>-->
							</div>
						</div>
						<!-- END SAMPLE PORTLET-->
					</div>
				</div>
				
				<div class="row-fluid">	
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE widget-->
						<div class="widget orange">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Registro de Status</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered" id="status_pedido">
									<thead>
										<tr>
											<th>Status</th>
											<th class="hidden-phone">Descrição</th>
											<th class="hidden-phone">NF Veic.</th>
											<th class="hidden-phone">NFe</th>
											<th class="hidden-phone">Usuário</th>
											<th class="hidden-phone">Data</th>
											<th class="hidden-phone">Cobrança</th>
											<th class="hidden-phone">Recebido</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<!-- END EXAMPLE TABLE widget-->
					</div>
					
					<!--Modal-->
					<div class="modal fade" id="ModalAddStatus" tabindex="-1" role="dialog" aria-labelledby="ModalAddStatus" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Adicionar Status</h4>
								</div>
								<form class="cmxform form-horizontal form_addstatus" id="form_status_pedido" method="post" action="#" enctype="multipart/form-data">
									<div class="modal-body">

										<input type="hidden" id="insert" name="insert" value="yes">
										<input type="hidden" name="pedido" value="<?php echo $id; ?>">

										<div class="control-group">
											<label for="status" class="control-label">Status</label>
											<div class="controls controls-row">
												<select class="span12 chzn-select" data-placeholder="Escolha o status" id="status" name="status" tabindex="1" required>
													<option value="">Selecione</option>
													<?php
													$busca_status = "SELECT ukey, nome FROM mp_status ORDER BY ukey ASC";
													$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL");
													$row_status = mysqli_num_rows($sql_status);

													if($row_status == 0){
														echo '<option value="">Não há status cadastrados</option>';
													}else{
														while($monta_status = mysqli_fetch_array($sql_status)){
															$ukey_status	= $monta_status["ukey"];
															$nome_status	= $monta_status["nome"];

															echo '<option value="'.$ukey_status.'">'.$nome_status.'</option>';

														}
													}
													?>
												</select>
											</div>
										</div>

										<div class="control-group">
											<label for="descricao" class="control-label">Interação</label>
											<div class="controls">
												<textarea class="input-xlarge" id="descricao" name="descricao" rows="3"></textarea>
											</div>
										</div>
										
										<div class="control-group ">
											<label for="nf_veic" class="control-label">NF Veículo</label>
											<div class="controls">
												<input class="span12 " type="text" name="nf_veic" id="nf_veic" value="" />
											</div>
										</div>

										<div class="control-group ">
											<label for="nf_mp" class="control-label">NFe</label>
											<div class="controls">
												<input class="span12 " type="text" name="nf_mp" id="nf_mp" value="" />
											</div>
										</div>

										<div class="control-group ">
											<label for="data_nf_mp" class="control-label">Data NFe</label>
											<div class="controls">
												<input class="span12 " type="date" name="data_nf_mp" id="data_nf_mp" value="" />
											</div>
										</div>
										
										<div class="control-group ">
											<label for="cobranca" class="control-label">Cobrança</label>
											<div class="controls">
												<input class="span12 " type="date" name="cobranca" id="cobranca" value="" />
											</div>
										</div>

										<div class="control-group ">
											<label for="recebido" class="control-label">Recebido</label>
											<div class="controls">
												<input class="span12 " type="date" name="recebido" id="recebido" value="" />
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
					
				</div>	
				
				<!-- END PAGE CONTENT-->
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
	<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/js/bootstrap-fileupload.js"></script>
	<script src="assets/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
	<script src="js/jquery.blockui.js"></script>
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="js/excanvas.js"></script>
	<script src="js/respond.js"></script>
	<![endif]-->
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/sorting/date-uk.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/additional-methods.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
	<script type="text/javascript" src="assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
	<script type="text/javascript" src="assets/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
	<script src="assets/fancybox/source/jquery.fancybox.pack.js"></script>
	<script src="assets/jquery-maskmoney/jquery.maskMoney.js"></script>

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="js/form-validation-script.js"></script>
	<script src="js/form-component.js"></script>
	<script>
		$(function() {
		    $('#valor_unit').maskMoney();
		})
	</script>
	<?php
	$ajax = 'ajax.php?target=2&id='.$id;
	?>
    <script>
	$(document).ready(function() {
		$('#status_pedido').DataTable( {
			/*"ajax": "datatable.php"*/
			"ajax": "<?php echo $ajax; ?>",
			"order": [],
			"columnDefs": [
                { "type": "date-uk", targets: 4 }
            ],
			
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
            }
			
		} );
	
	} );
	</script>

    <script type="text/javascript">
	$(document).ready(function(){
		$('.form_addstatus').submit(function(){
			var dados = $( this ).serialize();

			$.ajax({
				type: "POST",
				url: "ajax.php?target=3",
				data: dados,
				success: function( data )
				{
					//alert( dados );
					//alert( 'Enviado com sucesso!' );
					$('.modal').modal('hide');//new
					$('#ModalSucesso').modal('show');
					location.reload();
				}
			});
			
			return false;
		});
	
	});
	</script>
	<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>