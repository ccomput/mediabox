<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_relatorios	= 'active';
$relatorios		= 'active';
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
							Relatórios Gerenciais
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="index.php">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="#">Relatórios</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Relatórios Gerenciais
							</li>
							<li class="pull-right search-wrap">
								
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
								<h4><i class="icon-reorder"></i> Relatórios</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									<a href="javascript:;" class="icon-remove"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered" id="sample_1">
									<thead>
										<tr>
											<th>Código</th>
											<th class="hidden-phone">Nome</th>
											<th class="hidden-phone" width="240px">Opções</th>
										</tr>
									</thead>
									<tbody>
										<tr class="odd gradeX">
											<td>001</td>
											<td>Resumo do Faturamento</td>
											<td class="hidden-phone"><a href="relatorios/resumo_faturamento.php?id=" target="_blank"><button class="btn btn-small"><i class="icon-print icon-white"></i> PDF</button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>002</td>
											<td>Faturamento por Etapas e Veículos</td>
											<!--<td class="hidden-phone"><a href="relatorios/resumo_faturamento_veiculo.php?id=" target="_blank"><button class="btn btn-small"><i class="icon-print icon-white"></i> PDF</button></a> </td>-->
											<td class="hidden-phone"><a data-original-title="" href="#" data-toggle="modal" data-target="#resumo_faturamento_veiculo" title="Faturamento por Etapas e Veículos"><button class="btn" type="button"><i class="icon-print icon-white"></i> </button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>003</td>
											<td>Faturamento por Etapas e Clientes</td>
											<!--<td class="hidden-phone"><a href="relatorios/resumo_faturamento_cliente.php?id=" target="_blank"><button class="btn btn-small"><i class="icon-print icon-white"></i> PDF</button></a> </td>-->
											<td class="hidden-phone"><a data-original-title="" href="#" data-toggle="modal" data-target="#resumo_faturamento_cliente" title="Faturamento por Etapas e Clientes"><button class="btn" type="button"><i class="icon-print icon-white"></i> </button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>004</td>
											<td>Faturamento por Etapas e Agências</td>
											<td class="hidden-phone"><a data-original-title="" href="#" data-toggle="modal" data-target="#resumo_faturamento_agencia" title="Faturamento por Etapas e Agências"><button class="btn" type="button"><i class="icon-print icon-white"></i> </button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>005</td>
											<td>Resumo Gerencial</td>
											<td class="hidden-phone"><a href="relatorios/resumo_gerencial.php" target="_blank"><button class="btn btn-small"><i class="icon-print icon-white"></i> PDF</button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>006</td>
											<td>Resumo Gerencial por Veículo</td>
											<td class="hidden-phone"><a data-original-title="" href="#" data-toggle="modal" data-target="#resumo_gerencial_veiculo" title="Resumo Gerencial por Veículo"><button class="btn" type="button"><i class="icon-print icon-white"></i> </button></a> </td>
										</tr>
										<tr class="odd gradeX">
											<td>007</td>
											<td>Resumo Gerencial Mensal por Veículo</td>
											<td class="hidden-phone"><a data-original-title="" href="#" data-toggle="modal" data-target="#resumo_gerencial_veiculo_mes" title="Resumo Gerencial por Veículo"><button class="btn" type="button"><i class="icon-print icon-white"></i> </button></a> </td>
										</tr>
									</tbody>
								</table>
								
								<!-- Modal Faturamento por Etapas e Veículos -->
                                <div class="modal fade" id="resumo_faturamento_veiculo" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Faturamento por Etapas e Veículos</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/resumo_faturamento_veiculo.php" enctype="multipart/form-data">
												<div class="modal-body">
													
													<div class="control-group ">
														<label for="formato" class="control-label">Formato</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o formato" id="formato" name="formato" tabindex="1">
																<option value="pdf">PDF</option>
																<option value="excel">Excel</option>
															</select>
														</div>
													</div>
													
													<!--<div class="control-group ">
														<label for="ano" class="control-label">Ano</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o ano" id="ano" name="ano" tabindex="1">
																<option value="all">Todos</option>
																<option value="2019">2019</option>
																<option value="2018">2018</option>
																<option value="2017">2017</option>
																<option value="2016">2016</option>
															</select>
														</div>
													</div>-->
													
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
													
													<br><br><br><br><br><br>
													
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
								
								
								<!-- Modal Faturamento por Etapas e Clientes -->
                                <div class="modal fade" id="resumo_faturamento_cliente" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Faturamento por Etapas e Clientes</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/resumo_faturamento_cliente.php" enctype="multipart/form-data">
												<div class="modal-body">
													
													<div class="control-group ">
														<label for="formato" class="control-label">Formato</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o formato" id="formato" name="formato" tabindex="1">
																<option value="pdf">PDF</option>
																<option value="excel">Excel</option>
															</select>
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
								
								<!-- Modal Faturamento por Etapas e Agências -->
                                <div class="modal fade" id="resumo_faturamento_agencia" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Faturamento por Etapas e Agências</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/resumo_faturamento_agencia.php" enctype="multipart/form-data">
												<div class="modal-body">
													
													<div class="control-group ">
														<label for="formato" class="control-label">Formato</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o formato" id="formato" name="formato" tabindex="1">
																<option value="pdf">PDF</option>
																<option value="excel">Excel</option>
															</select>
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
								
								
								<!-- Modal Resumo Gerencial por Veículo -->
                                <div class="modal fade" id="resumo_gerencial_veiculo" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Resumo Gerencial por Veículo</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/resumo_gerencial_veiculo.php" enctype="multipart/form-data">
												<div class="modal-body">
													
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
														<label for="ano" class="control-label">Ano</label>
														<div class="controls controls-row">
															<select class="span12 chzn-select" data-placeholder="Escolha o ano" id="ano" name="ano" tabindex="1">
																<option value="all">Todos</option>
																<option value="2019">2019</option>
																<option value="2018">2018</option>
																<option value="2017">2017</option>
																<option value="2016">2016</option>
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
																		/*$ukey_veiculo		= $monta_veiculo["ukey"];*/
																		$fantasia_veiculo	= $monta_veiculo["fantasia"];

																		echo '<option value="'.$fantasia_veiculo.'">'.$fantasia_veiculo.'</option>';
																	}
																}
																?>
															</select>
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
								
								<!-- Modal Resumo Gerencial por Veículo -->
                                <div class="modal fade" id="resumo_gerencial_veiculo_mes" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Resumo Gerencial Mensal por Veículo</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/resumo_gerencial_veiculo_mes.php" enctype="multipart/form-data">
												<div class="modal-body">
													
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
														<label for="ano" class="control-label">Ano</label>
														<div class="controls controls-row">
															<select class="span12 chose-select" data-placeholder="Escolha o ano" id="ano" name="ano" tabindex="1">
																<option value="2019">2019</option>
																<option value="2018">2018</option>
																<option value="2017">2017</option>
																<option value="2016">2016</option>
															</select>
														</div>
													</div>
													
													<div class="control-group ">
														<label for="uf" class="control-label">UF</label>
														<div class="controls controls-row">
															<select class="span12 chosen-select" data-placeholder="Escolha a UF" id="uf" name="uf[]" multiple tabindex="1">
																<option value="DF">DF</option>
																<option value="RJ">RJ</option>
																<option value="SP">SP</option>
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
	
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page only-->
	<script src="js/dynamic-table.js"></script>
	<script>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
    </script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>