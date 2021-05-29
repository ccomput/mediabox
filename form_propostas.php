<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_comercial	= 'active';
$propostas		= 'active';

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
$table	= "mp_propostas";
$page	= "Location:propostas.php?viewer=comercial";

if ($id >= 1 and $id <> "new"){
	$busca = "SELECT ukey, ukey_client, (SELECT fantasia FROM mp_client WHERE ukey = mp_propostas.ukey_client) cliente, ukey_agency, (SELECT fantasia FROM mp_agency WHERE ukey = mp_propostas.ukey_agency) agencia, ukey_vehicles, (SELECT fantasia FROM mp_vehicles WHERE ukey = mp_propostas.ukey_vehicles) veiculo, campanha, proposta, obs, validade, user_ukey, timestamp FROM ".$table." WHERE ukey=".$id;
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

	$monta = mysqli_fetch_array($sql);

	$ukey			= $monta["ukey"];
	$ukey_client	= $monta["ukey_client"];
	$cliente		= $monta["cliente"];
	$ukey_agency	= $monta["ukey_agency"];
	$agencia		= $monta["agencia"];
	$ukey_vehicles	= $monta["ukey_vehicles"];
	$veiculo		= $monta["veiculo"];
	$campanha		= $monta["campanha"];
	$proposta		= $monta["proposta"];
	$obs			= $monta["obs"];
	$validade		= $monta["validade"];
	$user_ukey		= $monta["user_ukey"];
	$timestamp		= $monta["timestamp"];
	//$nascimento	= implode("/",array_reverse(explode("-", substr($monta["nascimento"], 0, 10))));
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){
	
	//$ukey			= $_POST["ukey"];
	$ukey_client	= $_POST["cliente"];
	$ukey_agency	= $_POST["agencia"];
	$ukey_vehicles	= $_POST["veiculo"];
	$campanha		= $_POST["campanha"];
	$proposta		= $_POST["proposta"];
	$obs			= $_POST["obs"];
	$validade		= $_POST["validade"];
	$user_ukey		= $_SESSION["user_login"];
	//$group_ukey	= $_SESSION["grupo"];

	$select_insert = "INSERT INTO ".$table." (ukey_client, ukey_agency, ukey_vehicles, campanha, proposta, obs, validade, emissao, user_ukey) VALUES ('".$ukey_client."','".$ukey_agency."','".$ukey_vehicles."','".$campanha."','".$proposta."','".$obs."','".$validade."',NOW(),'".$user_ukey."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header($page);
		
}elseif($update == 1){

	$ukey_proposta	= $_POST["ukey"];
	$ukey_client	= $_POST["cliente"];
	$ukey_agency	= $_POST["agencia"];
	$ukey_vehicles	= $_POST["veiculo"];
	$campanha		= $_POST["campanha"];
	$proposta		= $_POST["proposta"];
	$obs			= $_POST["obs"];
	$validade		= $_POST["validade"];
	$user_ukey		= $_SESSION["user_login"];

	$select_update = "UPDATE ".$table." SET ukey_client='".$ukey_client."', ukey_agency='".$ukey_agency."', ukey_vehicles='".$ukey_vehicles."', campanha='".$campanha."', proposta='".$proposta."', obs='".$obs."', validade='".$validade."', user_ukey='".$user_ukey."' WHERE ukey=".$ukey_proposta."";
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
							<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Proposta
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="/">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="/">Comercial</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Proposta
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
								<h4><i class="icon-reorder"></i> Dados da Proposta</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="#portlet-config" data-toggle="modal" class="config"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="widget-body form">
								<div class="bs-docs-example">
									<form class="cmxform form-horizontal" id="proposta" method="post" action="#" enctype="multipart/form-data">
										<input type="hidden" name="insert" value="<?php if($id == "new"){echo "1";}else{echo "0";} ?>">
										<input type="hidden" name="update" value="<?php if($id == "new"){echo "0";}else{echo "1";} ?>">
										<input type="hidden" name="ukey" value="<?php echo $ukey; ?>">

										<div class="control-group ">
											<label for="numero_proposta" class="control-label">Proposta</label>
											<div class="controls">
												<input class="span12 " type="text" value="<?php echo @$ukey; ?>" disabled />
											</div>
										</div>
										
										<div class="control-group">
											<label for="cliente" class="control-label">Cliente</label>
											<div class="controls controls-row">
												<select class="span12 chzn-select" data-placeholder="Escolha o cliente" id="cliente" name="cliente" tabindex="1" <?php echo $edit; ?> required>
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
															
															if($ukey_client == $ukey_cliente){
																echo '<option value="'.$ukey_cliente.'" selected>'.$fantasia_cliente.'</option>';
															}else{
																echo '<option value="'.$ukey_cliente.'">'.$fantasia_cliente.'</option>';
															}
														}
													}
													?>
												</select>
											</div>
										</div>
										
										<div class="control-group">
											<label for="agencia" class="control-label">Agência</label>
											<div class="controls controls-row">
												<select class="span12 chzn-select" data-placeholder="Escolha a agência" id="agencia" name="agencia" tabindex="1" <?php echo $edit; ?> required>
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
															
															if($ukey_agency == $ukey_agencia){
																echo '<option value="'.$ukey_agencia.'" selected>'.$fantasia_agencia.'</option>';
															}else{
																echo '<option value="'.$ukey_agencia.'">'.$fantasia_agencia.'</option>';
															}
														}
													}
													?>
												</select>
											</div>
										</div>
										
										<div class="control-group">
											<label for="veiculo" class="control-label">Veículo</label>
											<div class="controls controls-row">
												<select class="span12 chzn-select" data-placeholder="Escolha o veículo" id="veiculo" name="veiculo" tabindex="1" <?php echo $edit; ?> required>
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
															
															if($ukey_vehicles == $ukey_veiculo){
																echo '<option value="'.$ukey_veiculo.'" selected>'.$fantasia_veiculo.'</option>';
															}else{
																echo '<option value="'.$ukey_veiculo.'">'.$fantasia_veiculo.'</option>';
															}
														}
													}
													?>
												</select>
											</div>
										</div>
										
										<div class="control-group ">
											<label for="campanha" class="control-label">Campanha</label>
											<div class="controls">
												<input class="span12 " id="campanha" name="campanha" type="text" maxlength="80" value="<?php echo @$campanha; ?>" required <?php echo $edit; ?> />
											</div>
										</div>
										
										<div class="control-group">
											<label for="proposta" class="control-label">Proposta</label>
											<div class="controls">
												<textarea class="span12 wysihtmleditor5" name="proposta" rows="5"><?php echo @$proposta; ?></textarea>
											</div>
										</div>
										
										<div class="control-group ">
											<label for="obs" class="control-label">Observação</label>
											<div class="controls controls-row">
												<input class="span12" id="obs" name="obs" type="text" maxlength="200" value="<?php echo @$obs; ?>" <?php echo $edit; ?> />
											</div>
										</div>
                                                
										<div class="control-group">
											<label for="validade" class="control-label">Validade</label>
											<div class="controls controls-row">
												<input class="span6" id="validade" name="validade" type="date" value="<?php echo @$validade; ?>" <?php echo $edit; ?> />
											</div>
										</div>
                                        
										<?php if($_SESSION["mod_add_proposta"] == 1){ ?>
										<div class="form-actions">
											<input class="btn btn-success" type="submit" value="Salvar" />
											<button class="btn" type="reset" value="Cancelar">Cancelar</button>
										</div>
                                        <?php }; ?>
                            
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

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="js/form-validation-script.js"></script>
	<script src="js/form-component.js"></script>
	<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>