<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_comercial	= 'active';
$pedidos		= 'active';

//VARIAVEIS DE EDIÇÃO
if($_GET["ver"] == 'sim'){
	$edit = 'disabled';
}elseif($_SESSION["mod_add_proposta"] == 1){
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
$page	= "Location:pedidos.php?viewer=comercial";

if ($id >= 1 and $id <> "new"){
	$busca = "SELECT ukey, pi, ukey_client, (SELECT fantasia FROM mp_client WHERE ukey = ".$table.".ukey_client) cliente, ukey_agency, (SELECT fantasia FROM mp_agency WHERE ukey = ".$table.".ukey_agency) agencia, ukey_vehicles, (SELECT fantasia FROM mp_vehicles WHERE ukey = ".$table.".ukey_vehicles) veiculo, campanha, ini_veiculacao, fim_veiculacao, ukey_sellers, (SELECT fantasia FROM mp_sellers WHERE ukey = ".$table.".ukey_sellers) vendedor, valor_unit, valor_bruto, sem_comissao, proposta, obs, emissao, lancamento, user_ukey FROM ".$table." WHERE ukey=".$id;
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
	$ini_veic		= $monta["ini_veiculacao"];
	$fim_veic		= $monta["fim_veiculacao"];
	$ukey_sellers	= $monta["ukey_sellers"];
	$vendedor		= $monta["vendedor"];
	$valor_unit		= decimal_br($monta["valor_unit"]);
	$valor_bruto	= decimal_br($monta["valor_bruto"]);
	$sem_comissao	= $monta["sem_comissao"];
	$proposta		= $monta["proposta"];
	$obs			= $monta["obs"];
	$emissao		= $monta["emissao"];
	$lancamento		= $monta["lancamento"];
	$user_ukey		= $monta["user_ukey"];
	//$nascimento	= implode("/",array_reverse(explode("-", substr($monta["nascimento"], 0, 10))));
	
	//FILES
	$buscaFILE 	= "SELECT ukey, ext FROM mp_pedidos_files WHERE ukey = ".$ukey;
	$sqlFILE 	= mysqli_query($con, $buscaFILE) or die("ERROR FILE 001");
	$montaFILE 	= mysqli_fetch_array($sqlFILE);
	$file_ukey	= $montaFILE["ukey"];
	$file_ext	= $montaFILE["ext"];
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){
	
	//$ukey			= $_POST["ukey"];
	$pi				= $_POST["pi"];
	$ukey_client	= $_POST["cliente"];
	$ukey_agency	= $_POST["agencia"];
	$ukey_vehicles	= $_POST["veiculo"];
	$campanha		= $_POST["campanha"];
	$ini_veic		= $_POST["ini_veic"];
	$fim_veic		= $_POST["fim_veic"];
	$ukey_sellers	= $_POST["vendedor"];
	//$valor_unit		= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_unit"]))));
	if($_POST["sem_comissao"] == "1"){
		$valor_unit	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"]))));
	}else{
		$valor_unit	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"])))*0.8);
	}
	$valor_bruto	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"]))));
	$sem_comissao	= $_POST["sem_comissao"];
	$proposta		= $_POST["proposta"];
	$obs			= $_POST["obs"];
	$emissao		= $_POST["emissao"];
	$user_ukey		= $_SESSION["user_login"];
	//$group_ukey	= $_SESSION["grupo"];
	
	//FILE
	$diretorio = "/home1/outbo123/duemidia.outbox360.com.br/temp/";
	@$_FILES['arquivo']['name'] = "PI.pdf";
	move_uploaded_file(@$_FILES['arquivo']['tmp_name'],$diretorio.$_FILES['arquivo']['name']);
	$arquivo = $diretorio.@$_FILES['arquivo']['name'];
	
	if($_FILES['arquivo']["error"] == UPLOAD_ERR_NO_FILE){
		
		$select_insert = "INSERT INTO ".$table." (pi, ukey_client, ukey_agency, ukey_vehicles, campanha, ini_veiculacao, fim_veiculacao, ukey_sellers, valor_unit, valor_bruto, sem_comissao, proposta, obs, emissao, lancamento, user_ukey) VALUES ('".$pi."','".$ukey_client."','".$ukey_agency."','".$ukey_vehicles."','".$campanha."','".$ini_veic."','".$fim_veic."','".$ukey_sellers."','".$valor_unit."','".$valor_bruto."','".$sem_comissao."','".$proposta."','".$obs."','".$emissao."',NOW(),'".$user_ukey."')";
		$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");
		
	}else{
		
		$select_insert = "INSERT INTO ".$table." (pi, ukey_client, ukey_agency, ukey_vehicles, campanha, ini_veiculacao, fim_veiculacao, ukey_sellers, valor_unit, valor_bruto, sem_comissao, proposta, obs, emissao, lancamento, user_ukey) VALUES ('".$pi."','".$ukey_client."','".$ukey_agency."','".$ukey_vehicles."','".$campanha."','".$ini_veic."','".$fim_veic."','".$ukey_sellers."','".$valor_unit."','".$valor_bruto."','".$sem_comissao."','".$proposta."','".$obs."','".$emissao."',NOW(),'".$user_ukey."')";
		$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");
		
		//$select_insert = "INSERT INTO kn_m999 (tamanho, descricao, versao, data, status, referencia, inserido, usuario, file) VALUES ('".$tamanho."','".$descricao."','".$versao."','".$data."','".$status."','".$relacao."','".$inserido."','".$usuario."',LOAD_FILE('".$arquivo."'))";
		//$sql_insert = mysql_query($select_insert) or die("ERRO NO COMANDO INSERIR SQL");
	}
	
	unlink($arquivo);
	//FILE

	header($page);
		
}elseif($update == 1){

	$ukey_pedido	= $_POST["ukey"];
	$pi				= $_POST["pi"];
	$ukey_client	= $_POST["cliente"];
	$ukey_agency	= $_POST["agencia"];
	$ukey_vehicles	= $_POST["veiculo"];
	$campanha		= $_POST["campanha"];
	$ini_veic		= $_POST["ini_veic"];
	$fim_veic		= $_POST["fim_veic"];
	$ukey_sellers	= $_POST["vendedor"];
	//$valor_unit		= decimal_en(str_replace('R$', '', $_POST["valor_unit"]));
	//$valor_unit		= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_unit"]))));
	if($_POST["sem_comissao"] == "1"){
		$valor_unit	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"]))));
	}else{
		$valor_unit	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"])))*0.8);
	}
	$valor_bruto	= trim(str_replace(',','.',str_replace('.','',str_replace('R$','',$_POST["valor_bruto"]))));
	$sem_comissao	= $_POST["sem_comissao"];
	$proposta		= $_POST["proposta"];
	$obs			= $_POST["obs"];
	$emissao		= $_POST["emissao"];
	$user_ukey		= $_SESSION["user_login"];

	$select_update = "UPDATE ".$table." SET pi='".$pi."', ukey_client='".$ukey_client."', ukey_agency='".$ukey_agency."', ukey_vehicles='".$ukey_vehicles."', campanha='".$campanha."', ini_veiculacao='".$ini_veic."', fim_veiculacao='".$fim_veic."', ukey_sellers='".$ukey_sellers."', valor_unit='".$valor_unit."', valor_bruto='".$valor_bruto."', sem_comissao='".$sem_comissao."', proposta='".$proposta."', obs='".$obs."', emissao='".$emissao."', user_ukey='".$user_ukey."' WHERE ukey=".$ukey_pedido."";
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
							<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Pedido
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
								<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Pedido
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
								<h4><i class="icon-reorder"></i> Dados do Pedido</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="#portlet-config" data-toggle="modal" class="config"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="widget-body form">
								<div class="bs-docs-example">
									<form class="cmxform form-horizontal" id="pedido" method="post" action="#" enctype="multipart/form-data">
										<input type="hidden" name="insert" value="<?php if($id == "new"){echo "1";}else{echo "0";} ?>">
										<input type="hidden" name="update" value="<?php if($id == "new"){echo "0";}else{echo "1";} ?>">
										<input type="hidden" name="ukey" value="<?php echo $ukey; ?>">

										
										<div class="row-fluid control-group">
											<div class="span6">
												
												<div class="control-group ">
													<label for="numero_proposta" class="control-label">Código</label>
													<div class="controls">
														<input class="span12 " type="text" value="<?php if(@ukey <> ""){echo "DUE".@$ukey;} ?>" disabled />
													</div>
												</div>
												
											</div>
											<div class="span6">
										
												<div class="control-group ">
													<label for="pi" class="control-label">Pedido de Inserção</label>
													<div class="controls">
														<input class="span12 " type="text" name="pi" id="pi" value="<?php echo @$pi; ?>" <?php echo $edit; ?>/>
													</div>
												</div>
												
											</div>
										</div>
										
										<div class="row-fluid control-group">
											<div class="span6">
										
												<div class="control-group">
													<label for="cliente" class="control-label">Cliente</label>
													<div class="controls controls-row">
														<select class="span12 <?php if($edit == ''){ echo 'chzn-select'; }else{ echo ''; }?>" data-placeholder="Escolha o cliente" id="cliente" name="cliente" tabindex="1" <?php echo $edit; ?> required>
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
												
											</div>
											<div class="span6">
										
												<div class="control-group">
													<label for="agencia" class="control-label">Agência</label>
													<div class="controls controls-row">
														<select class="span12 <?php if($edit == ''){ echo 'chzn-select'; }else{ echo ''; }?>" data-placeholder="Escolha a agência" id="agencia" name="agencia" tabindex="1" <?php echo $edit; ?> required>
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
												
											</div>
										</div>
										
										<div class="row-fluid control-group">
											<div class="span6">
												
												<div class="control-group">
													<label for="veiculo" class="control-label">Veículo</label>
													<div class="controls controls-row">
														<select class="span12 <?php if($edit == ''){ echo 'chzn-select'; }else{ echo ''; }?>" data-placeholder="Escolha o veículo" id="veiculo" name="veiculo" tabindex="1" <?php echo $edit; ?> required>
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
											
											</div>
											<div class="span6">
										
												<div class="control-group">
													<label for="vendedor" class="control-label">Vendedor</label>
													<div class="controls controls-row">
														<select class="span12 <?php if($edit == ''){ echo 'chzn-select'; }else{ echo ''; }?>" data-placeholder="Escolha o vendedor" id="vendedor" name="vendedor" tabindex="1" <?php echo $edit; ?> required>
															<option value="">Selecione</option>
															<?php
															$busca_vendedor = "SELECT ukey, fantasia FROM mp_sellers ORDER BY fantasia ASC";
															$sql_vendedor = mysqli_query($con, $busca_vendedor) or die("ERRO NO COMANDO SQL");
															$row_vendedor = mysqli_num_rows($sql_vendedor);

															if($row_vendedor == 0){
																echo '<option value="">Não há vendedores cadastrados</option>';
															}else{
																while($monta_vendedor = mysqli_fetch_array($sql_vendedor)){
																	$ukey_vendedor		= $monta_vendedor["ukey"];
																	$fantasia_vendedor	= $monta_vendedor["fantasia"];

																	if($ukey_sellers == $ukey_vendedor){
																		echo '<option value="'.$ukey_vendedor.'" selected>'.$fantasia_vendedor.'</option>';
																	}else{
																		echo '<option value="'.$ukey_vendedor.'">'.$fantasia_vendedor.'</option>';
																	}
																}
															}
															?>
														</select>
													</div>
												</div>
												
											</div>
										</div>
										
										<div class="control-group ">
											<label for="campanha" class="control-label">Campanha</label>
											<div class="controls">
												<input class="span12 " id="campanha" name="campanha" type="text" maxlength="80" value="<?php echo @$campanha; ?>" required <?php echo $edit; ?> />
											</div>
										</div>
										
										<div class="row-fluid control-group">
											<div class="span6">
												
												<div class="control-group ">
													<label for="ini_veic" class="control-label">Início da Veículação</label>
													<div class="controls">
														<input class="span12 " type="date" name="ini_veic" id="ini_veic" value="<?php echo @$ini_veic; ?>" <?php echo $edit; ?> />
													</div>
												</div>

											</div>
											<div class="span6">

												<div class="control-group ">
													<label for="fim_veic" class="control-label">Fim da Veículação</label>
													<div class="controls">
														<input class="span12 " type="date" name="fim_veic" id="fim_veic" value="<?php echo @$fim_veic; ?>" <?php echo $edit; ?> />
													</div>
												</div>
												
											</div>
										</div>
										
										<div class="row-fluid control-group">
											<div class="span6">
												
												<div class="control-group ">
													<label for="valor_bruto" class="control-label">Valor Bruto</label>
													<div class="controls">
														<input class="span12 " type="text" name="valor_bruto" id="valor_bruto" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," value="<?php echo @$valor_bruto; ?>" <?php echo $edit; ?> />
													</div>
												</div>
											
											</div>
											<div class="span6">
												
												<div class="control-group ">
													<label for="valor_unit" class="control-label">Valor Liquido</label>
													<div class="controls">
														<!--<input class="span12 " type="text" name="valor_unit" id="valor_unit" data-affixes-stay="true" data-prefix="R$ " data-thousands="." data-decimal="," value="" disabled />-->
														<input class="span12 " type="text" name="valor_unit" id="valor_unit" value="<?php echo @$valor_unit; ?>" <?php echo $edit; ?> disabled />
													</div>
												</div>
												
											</div>
										</div>
										
										
										<div class="row-fluid control-group">
											<div class="span6">
												
												<div class="control-group ">
													<label for="proposta" class="control-label">Proposta Nº</label>
													<div class="controls">
														<input class="span12 " type="text" name="proposta" id="proposta" placeholder="Se houver proposta insira aqui" value="<?php echo @$proposta; ?>" <?php echo $edit; ?> />
													</div>
												</div>
											
											</div>
											<div class="span6">
												
												<div class="control-group ">
													<label for="sem_comissao" class="control-label">Sem Comissão</label>
													<div class="controls">
														<input type="checkbox" class="checkbox" id="sem_comissao" name="sem_comissao" value="1" <?php if(@$sem_comissao == 1){echo 'checked';} ?> />
													</div>
												</div>
												
											</div>
										</div>
										
										
										<div class="control-group ">
											<label for="obs" class="control-label">Observação</label>
											<div class="controls controls-row">
												<input class="span12" id="obs" name="obs" type="text" maxlength="200" value="<?php echo @$obs; ?>" <?php echo $edit; ?> />
											</div>
										</div>
										
										<div class="row-fluid">
											<div class="span3">
										
												<div class="control-group">
													<label for="emissao" class="control-label">Emissão PI</label>
													<div class="controls controls-row">
														<input class="span12" id="emissao" name="emissao" type="date" value="<?php echo @$emissao; ?>" <?php echo $edit; ?>/>
													</div>
												</div>
												
											</div>
											<div class="span3">

												<div class="control-group">
													<label for="lancamento" class="control-label">Data</label>
													<div class="controls controls-row">
														<input class="span12" id="lancamento" name="lancamento" type="date" value="<?php echo @$lancamento; ?>" disabled <?php echo $edit; ?>/>
													</div>
												</div>
												
											</div>
											<div class="span6">
												<div class="control-group ">
													<label for="arquivo" class="control-label">Arquivo</label>
													<div class="controls">
														<input class="span12 " id="arquivo" name="arquivo" type="file" />
													</div>
												</div>
											</div>
										</div>
                                        
										<?php if($_SESSION["mod_add_proposta"] == 1 and $edit == ''){ ?>
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
	<script src="assets/jquery-maskmoney/jquery.maskMoney.js"></script>

	<!--common script for all pages-->
	<script src="js/common-scripts.js"></script>

	<!--script for this page-->
	<script src="js/form-validation-script.js"></script>
	<script src="js/form-component.js"></script>
	<script>
		$(function() {
		    $('#valor_unit').maskMoney();
			$('#valor_bruto').maskMoney();
		})
	</script>
	<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>