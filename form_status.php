<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_cadastros	= 'active';
$status			= 'active';

//*VARIAVEIS DE TRANSAÇÃO	
$id		= @$_GET["id"];
$insert	= @$_POST["insert"];
$update = @$_POST["update"];
$delete	= @$_GET["delete"];
$table	= "mp_status";
$page	= "Location:status.php?viewer=cadastros";

if ($id >= 1 and $id <> "new"){
	$busca = "SELECT * FROM ".$table." WHERE ukey=".$id;
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

	$monta = mysqli_fetch_array($sql);

	$ukey		= $monta["ukey"];
	$nome		= $monta["nome"];
	$descricao	= $monta["descricao"];
	$ativo		= $monta["ativo"];
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){

	$nome		= $_POST["nome"];
	$descricao	= $_POST["descricao"];
	$ativo		= $_POST["ativo"];

	$select_insert = "INSERT INTO ".$table." (nome, descricao, ativo) VALUES ('".$nome."','".$descricao."','".$ativo."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header($page);
		
}elseif($update == 1){

	$ukey_status = $_POST["ukey"];
	$nome		= $_POST["nome"];
	$descricao	= $_POST["descricao"];
	$ativo		= $_POST["ativo"];

	$select_update = "UPDATE ".$table." SET nome='".$nome."', descricao='".$descricao."', ativo='".$ativo."' WHERE ukey=".$ukey_status."";
	$sql_update = mysqli_query($con, $select_update) or die("ERRO NO COMANDO EDITAR SQL");
	
	header($page);
	
}elseif($delete == 1){

	$select_delete = "DELETE FROM ".$table." WHERE ukey='".$delete."'";
	$sql_delete = mysqli_query($con, $select_delete) or die("ERRO NO COMANDO EXCLUIR SQL");
		
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
	<!-- Adicionando Javascript -->
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
							<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Status
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="/">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="/">Cadastros</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Status
							</li>
							<li class="pull-right search-wrap">
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
			
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN VALIDATION STATES-->
						<div class="widget green">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Dados do Status</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="#portlet-config" data-toggle="modal" class="config"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="widget-body form">
								<div class="bs-docs-example">
									<ul class="nav nav-tabs" id="myTab">
										<li class="active"><a data-toggle="tab" href="#geral">Geral</a></li>
									</ul>
									<form class="cmxform form-horizontal" id="statusForm" method="post" action="#" enctype="multipart/form-data">
										<input type="hidden" name="insert" value="<?php if($id == "new"){echo "1";}else{echo "0";} ?>">
										<input type="hidden" name="update" value="<?php if($id == "new"){echo "0";}else{echo "1";} ?>">
										<input type="hidden" name="ukey" value="<?php echo $ukey; ?>">
										<div class="tab-content" id="myTabContent">
											<div id="geral" class="tab-pane fade in active">
												<!-- BEGIN FORM-->

												<div class="control-group ">
													<label for="codigo" class="control-label">Código</label>
													<div class="controls">
														<input class="span6 " id="codigo" name="codigo" type="text" value="<?php echo @$ukey; ?>" disabled />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="nome" class="control-label">Nome*</label>
													<div class="controls">
														<input class="span6 " id="nome" name="nome" type="text" value="<?php echo @$nome; ?>" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="descricao" class="control-label">Descrição*</label>
													<div class="controls">
														<input class="span6 " id="descricao" name="descricao" type="text" value="<?php echo @$descricao; ?>" required />
													</div>
												</div>
                                
												<div class="control-group ">
													<label for="ativo" class="control-label">Ativo</label>
													<div class="controls">
														<input type="checkbox" class="checkbox" id="ativo" name="ativo" value="1" <?php if(@$ativo == 1){echo 'checked';} ?> />
													</div>
												</div>

											</div>

										</div>

										<div class="form-actions">
											<input class="btn btn-success" type="submit" value="Salvar" />
											<button class="btn" type="reset" value="Cancelar">Cancelar</button>
										</div>
                            
									</form>
									<!-- END FORM-->
                            
								</div>
							</div>
							<!-- END VALIDATION STATES-->
						</div>
					</div>
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
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/additional-methods.min.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<!--datepicker-->
	<script type="text/javascript" src="assets/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>


	<!--common script for all pages-->
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script src="js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="js/form-validation-script.js"></script>
	<script src="js/form-component.js"></script>
	<script src="js/viacep.js"></script>
	<script src="js/dynamic-table.js"></script>

	<!-- END JAVASCRIPTS -->
	<!--<script>
		$(function () {
			$(" input[type=radio], input[type=checkbox]").uniform();
		});
	</script>-->
</body>
<!-- END BODY -->
</html>