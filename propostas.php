<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_comercial	= 'active';
$propostas		= 'active';

//VARIAVEIS DE TRANSAÇÃO
$like = @$_POST["pesquisa"];

////Pesquisa Avançada
$avancada		= @$_POST["avancada"];
$a_proposta		= @$_POST["proposta"];
$a_situacao		= @$_POST["situacao"];
$a_cliente		= @$_POST["cliente"];
$a_data_ini		= @$_POST["data_ini"];
$a_data_fim		= @$_POST["data_fim"];

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
							Propostas
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
								Propostas
							</li>

							<li class="pull-right search-wrap">
								
								<form action="follow-up.php" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o número da proposta">
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
													
													<div class="control-group ">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls">
															<input id="cliente" name="cliente" type="text" value="" size="16" class="m-ctrl-medium">
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
                                            <form class="cmxform form-horizontal" id="form_follow_search" method="post" target="_blank" action="relatorios/follow_up.php" enctype="multipart/form-data">
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
													
													<div class="control-group ">
														<label for="cliente" class="control-label">Cliente</label>
														<div class="controls">
															<input id="cliente" name="cliente" type="text" value="" size="16" class="m-ctrl-medium">
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
								<a href="form_propostas.php?id=new"><button class="btn btn-warning" style="height:36px; margin-top:-8px;"><i class="icon-plus icon-white"></i> Adicionar</button></a>
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
								<h4><i class="icon-reorder"></i> Propostas</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									<a href="javascript:;" class="icon-remove"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered" id="sample_1">
									<thead>
										<tr>
											<th>Proposta</th>
											<th class="hidden-phone">Cliente</th>
                                            <th class="hidden-phone">Agência</th>
                                            <th class="hidden-phone">Veículo</th>
											<th class="hidden-phone">Campanha</th>
											<th class="hidden-phone">Data</th>
											<th class="hidden-phone" width="200px">Opções</th>
										</tr>
									</thead>
									<tbody>
									<?php
									if($_SESSION['grupo'] == 1){
										$busca = "SELECT ukey, (SELECT fantasia FROM mp_client WHERE ukey = mp_propostas.ukey_client) cliente, (SELECT fantasia FROM mp_agency WHERE ukey = mp_propostas.ukey_agency) agencia, (SELECT fantasia FROM mp_vehicles WHERE ukey = mp_propostas.ukey_vehicles) veiculo, campanha, emissao, timestamp FROM mp_propostas ORDER BY ukey DESC";
									}else{
										$busca = "SELECT ukey, (SELECT fantasia FROM mp_client WHERE ukey = mp_propostas.ukey_client) cliente, (SELECT fantasia FROM mp_agency WHERE ukey = mp_propostas.ukey_agency) agencia, (SELECT fantasia FROM mp_vehicles WHERE ukey = mp_propostas.ukey_vehicles) veiculo, campanha, emissao, timestamp FROM mp_propostas WHERE user_ukey = '".$_SESSION['user_login']."' ORDER BY ukey DESC";
									}
									$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

									while ($monta = mysqli_fetch_array($sql)){
										$ukey		= $monta["ukey"];
										$cliente	= $monta["cliente"];
										$agencia	= $monta["agencia"];
										$veiculo	= $monta["veiculo"];
										$campanha	= $monta["campanha"];
										$data		= date('d/m/Y', strtotime($monta["emissao"]));
										
										if($_SESSION["mod_add_plantel"] == 1){
											$edit = '<a href="form_propostas.php?id='.$ukey.'&editar=sim"><button class="btn btn-small btn-primary"><i class="icon-pencil icon-white"></i> Editar</button></a>';
										}
								
										echo '
										<tr class="odd gradeX">
											<td>'.$ukey.'</td>
											<td class="hidden-phone">'.$cliente.'</td>
											<td class="hidden-phone">'.$agencia.'</td>
											<td class="hidden-phone">'.$veiculo.'</td>
											<td class="hidden-phone">'.$campanha.'</td>
											<td class="hidden-phone">'.$data.'</td>
											<td class="hidden-phone">'.$edit.' <a href="form_propostas.php?id='.$ukey.'&ver=sim"><button class="btn btn-small"><i class="icon-eye-open icon-white"></i> Ver</button></a></td>
										</tr>';
									}
									?>
									</tbody>
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
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page only-->
	<script src="js/dynamic-table.js"></script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>