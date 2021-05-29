<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_cadastros	= 'active';
$veiculos		= 'active';
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
							Veículos
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="index.php">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="#">Cadastros</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Lista de Veículos
							</li>
							<li class="pull-right search-wrap">
								<form action="#" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<!--<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o código MP">
										<button class="btn" type="submit"><i class="icon-search"></i> </button>-->
										<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorio" title="Relatório"><button class="btn" type="button"><i class="icon-print"></i> </button></a>
									</div>
								</form>
								
								<!-- Modal Relatório -->
                                <div class="modal fade" id="relatorio" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel">Relatório</h4>
											</div>
                                            <form class="cmxform form-horizontal" id="form_status_relatorio" method="post" target="_blank" action="relatorios/veiculos.php" enctype="multipart/form-data">
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
								<a href="form_veiculos.php?id=new"><button class="btn btn-warning" style="height:36px; margin-top:-8px;"><i class="icon-plus icon-white"></i> Adicionar</button></a>
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
								<h4><i class="icon-reorder"></i> Veículos</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									<a href="javascript:;" class="icon-remove"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered" id="sample_1">
									<thead>
										<tr>
											<th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
											<th>Código</th>
											<th>Fantasia</th>
											<th>Razão</th>
											<th>CNPJ</th>
											<th>Situação</th>
											<th class="hidden-phone" width="100px">Opções</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$busca = "SELECT ukey, fantasia, razao, cnpj, ativo FROM mp_vehicles ORDER BY ukey";
									$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

									while ($monta = mysqli_fetch_array($sql)){
										$ukey		= $monta["ukey"];
										$fantasia	= $monta["fantasia"];
										$razao		= $monta["razao"];
										$cnpj		= mask($monta["cnpj"],'##.###.###/####-##');
										$ativo		= $monta["ativo"];
										if($ativo == 1){
											$situacao	= 'ATIVO';
										}else{
											$situacao	= 'INATIVO';
										}
								
										echo '
										<tr class="odd gradeX">
											<td><input type="checkbox" class="checkboxes" value="1" /></td>
											<td>'.$ukey.'</td>
											<td>'.$fantasia.'</td>
											<td>'.$razao.'</td>
											<td>'.$cnpj.'</td>
											<td>'.$situacao.'</td>
											<td class="hidden-phone"><a href="form_veiculos.php?id='.$ukey.'"><button class="btn btn-small btn-primary"><i class="icon-pencil icon-white"></i> </button></a></td>
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