<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_comercial	= 'active';
$pedidos		= 'active';

//VARIAVEIS DE TRANSAÇÃO
$like = @$_POST["pesquisa"];

////Pesquisa Avançada
$avancada		= @$_POST["avancada"];
$a_proposta		= @$_POST["proposta"];
$a_situacao		= @$_POST["situacao"];
if(!empty($_POST["cliente"])){
	$a_cliente = implode(',',$_POST["cliente"]);	
}
if(!empty($_POST["agencia"])){
	$a_agencia = implode(',',$_POST["agencia"]);	
}
if(!empty($_POST["veiculo"])){
	$a_veiculo = implode(',',$_POST["veiculo"]);	
}
$a_data_ini		= @$_POST["data_ini"];
$a_data_fim		= @$_POST["data_fim"];
$a_pi			= @$_POST["pi"];
$a_codigo		= @$_POST["codigo"];

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
							Pedidos
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="index.php">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="#">Comercial</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Pedidos
							</li>

							<li class="pull-right search-wrap">
								
								<form action="pedidos.php" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o número do pedido">
										<button class="btn" type="submit"><i class="icon-search"></i> </button>
                                        <a data-original-title="" href="#" data-toggle="modal" data-target="#basicModal" title="Pesquisa Avançada"><button class="btn" type="button"><i class="icon-filter"></i> </button></a>
										<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorio" title="Relatório"><button class="btn" type="button"><i class="icon-print"></i> </button></a>
									</div>
								</form>
								
								<?php
								//Consultas de Pesquisa e Relatórios
								
								//Status
								$busca_status = "SELECT ukey, nome FROM mp_status ORDER BY ukey ASC";
								$sql_status = mysqli_query($con, $busca_status) or die("ERRO NO COMANDO SQL");
								$row_status = mysqli_num_rows($sql_status);
								
								//Busca Cliente
								$busca_cliente = "SELECT ukey, fantasia FROM mp_client ORDER BY fantasia ASC";
								$sql_cliente = mysqli_query($con, $busca_cliente) or die("ERRO NO COMANDO SQL");
								$row_cliente = mysqli_num_rows($sql_cliente);
								
								//Agencia
								$busca_agencia = "SELECT ukey, fantasia FROM mp_agency ORDER BY fantasia ASC";
								$sql_agencia = mysqli_query($con, $busca_agencia) or die("ERRO NO COMANDO SQL");
								$row_agencia = mysqli_num_rows($sql_agencia);
								
								//Veículo
								$busca_veiculo = "SELECT ukey, fantasia FROM mp_vehicles ORDER BY fantasia ASC";
								$sql_veiculo = mysqli_query($con, $busca_veiculo) or die("ERRO NO COMANDO SQL");
								$row_veiculo = mysqli_num_rows($sql_veiculo);
								?>
								
								<!--Modal-->
                                <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Pesquisa Avançada</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_follow_search" method="post" action="#" enctype="multipart/form-data">
												<div class="modal-body">
												
													<input type="hidden" id="avancada" name="avancada" value="yes">

													<div class="control-group">
														<label for="situacao" class="control-label">Status</label>
														<div class="controls">
															<select class="span6 " id="situacao" name="situacao" data-placeholder="Escolha a situação">
																<option value="open">Aberto</option>
                                                                <option value="close">Fechado</option>
                                                                <option value="both">Ambos</option>
															</select>
														</div>
													</div>
                                                    
													<div class="control-group ">
														<label for="data_ini" class="control-label">Data Inicial</label>
														<div class="controls">
															<input id="data_ini" name="data_ini" type="date" value="" size="16" class="m-ctrl-medium">
														</div>
													</div>
                                                    <div class="control-group ">
														<label for="data_fim" class="control-label">Data Final</label>
														<div class="controls">
															<input id="data_fim" name="data_fim" type="date" value="" size="16" class="m-ctrl-medium">
														</div>
													</div>
													
													<div class="control-group">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha o cliente" id="cliente" name="cliente[]" multiple tabindex="1">
																<option value="">Selecione</option>
																<?php
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
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
														</div>
													</div>
													
													<div class="control-group ">
														<label for="codigo" class="control-label">Cód. DUE</label>
														<div class="controls">
															<input class="span12" id="codigo" name="codigo" type="text" value="">
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
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/pedidos.php" enctype="multipart/form-data">
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
														<label for="pi" class="control-label">PI</label>
														<div class="controls">
															<input class="span12" id="pi" name="pi" type="text" value="">
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
								<?php if($_SESSION["mod_add_proposta"] == 1){ ?>
								<a href="form_pedidos.php?id=new"><button class="btn btn-warning" style="height:36px; margin-top:-8px;"><i class="icon-plus icon-white"></i> Adicionar</button></a>
								<?php } ?>
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
									<a href="javascript:;" class="icon-remove"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered" id="pedidos">
									<thead>
										<tr>
											<th>Código</th>
											<th>PI</th>
											<th>Emissão</th>
											<th class="hidden-phone">Cliente</th>
                                            <th class="hidden-phone">Agência</th>
                                            <th class="hidden-phone">Veículo</th>
											<th class="hidden-phone">Campanha</th>
											<th class="hidden-phone">Inicio</th>
											<th class="hidden-phone">Fim</th>
											<th class="hidden-phone">Valor Liq.</th>
											<th class="hidden-phone">Vendedor</th>
											<th class="hidden-phone">Status</th>
											<th class="hidden-phone" width="100px">Opções</th>
										</tr>
									</thead>
								</table>
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
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/sorting/date-uk.js"></script>
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
	<!--<script src="js/dynamic-table.js"></script>-->
	<?php
	$ajax = 'ajax.php?target=1&pesquisa='.$like.'&perdido='.$perdido.'&avancada='.$avancada.'&situacao='.$a_situacao.'&cliente='.$a_cliente.'&agencia='.$a_agencia.'&veiculo='.$a_veiculo.'&veic_ini='.$a_data_ini.'&veic_fim='.$a_data_fim.'&pi='.$a_pi.'&codigo='.$a_codigo;
	?>
    <script>
	$(document).ready(function() {
		$('#pedidos').DataTable( {
			/*"ajax": "datatable.php"*/
			/*"scrollX": true,*/
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
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>