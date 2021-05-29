<?php
require "../../../inc/conect.php";
require "../../../inc/verifica.php";
require "../../../inc/functions.php";

//Parametro de link ativo
$sub_faturamento= 'active';
$cobrancas		= 'active';

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

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="pt-br" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="pt-br"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<?php include "../../../head.php"; ?>
	<style>
		div.dataTables_wrapper {width: 100%;margin: 0 auto;}
	</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<?php include "../../../preloader.php"; ?>
	<!-- BEGIN HEADER -->
	<div id="header" class="navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<?php include "../../../topnavigationbar.php"; ?>
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
				<?php include "../../../sidebar.php"; ?>
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
							Cobranças
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="../../../index.php">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="#">Faturamento</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Cobranças
							</li>

							<li class="pull-right search-wrap">
								
								<form action="#" class="hidden-phone" method="post">
									<div class="input-append search-input-area">
										<input class="" id="appendedInputButton" type="text" name="pesquisa" placeholder="Insira o código DUE">
										<button class="btn" type="submit"><i class="icon-search"></i> </button>
                                        <!--<a data-original-title="" href="#" data-toggle="modal" data-target="#basicModal" title="Pesquisa Avançada"><button class="btn" type="button"><i class="icon-filter"></i> </button></a>
										<a data-original-title="" href="#" data-toggle="modal" data-target="#relatorio" title="Relatório"><button class="btn" type="button"><i class="icon-print"></i> </button></a>-->
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
								<h4><i class="icon-reorder"></i> Cobranças do Dia</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered display nowrap" id="cobranca_dia">
									<thead>
										<tr>
											<th>Código</th>
											<th>PI</th>
											<th>Cliente</th>
                                            <th>Agência</th>
                                            <th>Veículo</th>
											<th>Campanha</th>
											<th>Inicio</th>
											<th>Fim</th>
											<th>Vendedor</th>
											<th class="right">Bruto</th>
											<th class="right">Liquido</th>
											<th>Cobrança</th>
											<th>Data Status</th>
											<th>Dias</th>
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
								<h4><i class="icon-reorder"></i> Cobranças do Mês</h4>
								<span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
								</span>
							</div>
							<div class="widget-body">
								<table class="table table-striped table-bordered display nowrap" id="cobranca_mes">
									<thead>
										<tr>
											<th>Código</th>
											<th>PI</th>
											<th>Cliente</th>
                                            <th>Agência</th>
                                            <th>Veículo</th>
											<th>Campanha</th>
											<th>Inicio</th>
											<th>Fim</th>
											<th>Vendedor</th>
											<th class="right">Bruto</th>
											<th class="right">Liquido</th>
											<th>Cobrança</th>
											<th>Data Status</th>
											<th>Dias</th>
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
				
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->  
	</div>
	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->
	<?php include "../../../footer.php"; ?>
	<!-- END FOOTER -->

	<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<script src="../../../js/jquery-1.8.3.min.js"></script>
	<script src="../../../js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="../../../assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="../../../js/jquery.blockui.js"></script>
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="js/excanvas.js"></script>
	<script src="js/respond.js"></script>
	<![endif]-->
	<script type="text/javascript" src="../../../assets/uniform/jquery.uniform.min.js"></script>
	<!--<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>-->
	<!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>-->
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<!--<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/sorting/date-uk.js"></script>-->
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/date-uk.js"></script>
	<script type="text/javascript" src="../../../assets/data-tables/DT_bootstrap.js"></script>
	
	<script type="text/javascript" src="../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>

	<!--common script for all pages-->
	<script src="../../../js/common-scripts.js"></script>

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
	$ajax1 = '/ajax.php?target=4&pesquisa='.$like;
	$ajax2 = '/ajax.php?target=5&pesquisa='.$like;
	?>
    <script>
	$(document).ready(function() {
		$('#cobranca_dia').DataTable( {
			//"scrollX": true,
			"ajax": "<?php echo $ajax1; ?>",
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
	<script>
	$(document).ready(function() {
		$('#cobranca_mes').DataTable( {
			//"scrollX": true,
			"ajax": "<?php echo $ajax2; ?>",
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