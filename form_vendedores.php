<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_cadastros	= 'active';
$vendedores		= 'active';

//*VARIAVEIS DE TRANSAÇÃO	
$id		= @$_GET["id"];
$insert	= @$_POST["insert"];
$update = @$_POST["update"];
$delete	= @$_GET["delete"];
$table	= "mp_sellers";
$page	= "Location:vendedores.php?viewer=cadastros";

if ($id >= 1 and $id <> "new"){
	$busca = "SELECT * FROM ".$table." WHERE ukey=".$id;
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

	$monta = mysqli_fetch_array($sql);

	$ukey			= $monta["ukey"];
	$fantasia		= $monta["fantasia"];
	$razao			= $monta["razao"];
	$endereco		= $monta["endereco"];
	$numero			= $monta["numero"];
	$complemento	= $monta["complemento"];
	$bairro			= $monta["bairro"];
	$cidade			= $monta["cidade"];
	$estado			= $monta["estado"];
	$pais			= $monta["pais"];
	$cep			= $monta["cep"];
	$cnpj			= mask($monta["cnpj"],'##.###.###/####-##');
	$ie				= $monta["ie"];
	$fone1			= mask($monta["fone1"],'## ####-####');
	$fone2			= mask($monta["fone2"],'## ####-####');
	$mail			= $monta["mail"];
	$observacao		= $monta["observacao"];
	$ativo			= $monta["ativo"];
	$admissao		= $monta["admissao"];
	$demissao		= $monta["demissao"];
	$unidade		= $monta["ukey_unidades"];
	$responsavel	= $monta["responsavel"];
	$cpf			= mask($monta["cpf"],'###.###.###-##');
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){

	$fantasia		= $_POST["fantasia"];
	$razao			= $_POST["razao"];
	$endereco		= $_POST["endereco"];
	$numero			= $_POST["numero"];
	$complemento	= $_POST["complemento"];
	$bairro			= $_POST["bairro"];
	$cidade			= $_POST["cidade"];
	$estado			= $_POST["estado"];
	$pais			= $_POST["pais"];
	$cep			= $_POST["cep"];
	$cnpj			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["cnpj"] ));
	$ie				= $_POST["ie"];
	$fone1			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["fone1"] ));
	$fone2			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["fone2"] ));
	$mail			= $_POST["mail"];
	$observacao		= $_POST["observacao"];
	$ativo			= $_POST["ativo"];
	$admissao		= $_POST["admissao"];
	$demissao		= $_POST["demissao"];
	$unidade		= $_POST["unidade"];
	$responsavel	= $_POST["responsavel"];
	$cpf			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["cpf"] ));

	$select_insert = "INSERT INTO ".$table." (fantasia, razao, endereco, numero, complemento, bairro, cidade, estado, pais, cep, cnpj, ie, fone1, fone2, mail, observacao, ativo, admissao, demissao, ukey_unidades, responsavel, cpf) VALUES ('".$fantasia."','".$razao."','".$endereco."','".$numero."','".$complemento."','".$bairro."','".$cidade."','".$estado."','".$pais."','".$cep."','".$cnpj."','".$ie."','".$fone1."','".$fone2."','".$mail."','".$observacao."','".$ativo."','".$admissao."','".$demissao."','".$unidade."','".$responsavel."','".$cpf."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header($page);
		
}elseif($update == 1){

	$ukey_vendedor	= $_POST["ukey"];
	$fantasia		= $_POST["fantasia"];
	$razao			= $_POST["razao"];
	$endereco		= $_POST["endereco"];
	$numero			= $_POST["numero"];
	$complemento	= $_POST["complemento"];
	$bairro			= $_POST["bairro"];
	$cidade			= $_POST["cidade"];
	$estado			= $_POST["estado"];
	$pais			= $_POST["pais"];
	$cep			= $_POST["cep"];
	$cnpj			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["cnpj"] ));
	$ie				= $_POST["ie"];
	$fone1			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["fone1"] ));
	$fone2			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["fone2"] ));
	$mail			= $_POST["mail"];
	$observacao		= $_POST["observacao"];
	$ativo			= $_POST["ativo"];
	$admissao		= $_POST["admissao"];
	$demissao		= $_POST["demissao"];
	$unidade		= $_POST["unidade"];
	$responsavel	= $_POST["responsavel"];
	$cpf			= str_replace("-", "", preg_replace( array( '/[ ]/' , '/[^A-Za-z0-9\-]/' ) , array( '' , '' ) , $_POST["cpf"] ));

	$select_update = "UPDATE ".$table." SET fantasia='".$fantasia."', razao='".$razao."', endereco='".$endereco."', numero='".$numero."', complemento='".$complemento."', bairro='".$bairro."', cidade='".$cidade."', estado='".$estado."', pais='".$pais."', cep='".$cep."', cnpj='".$cnpj."', ie='".$ie."', fone1='".$fone1."', fone2='".$fone2."', mail='".$mail."', observacao='".$observacao."', ativo='".$ativo."', admissao='".$admissao."', demissao='".$demissao."', ukey_unidades='".$unidade."', responsavel='".$responsavel."', cpf='".$cpf."' WHERE ukey='".$ukey_vendedor."'";
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
							<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Vendedor
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
								<?php if($id <> "new"){echo 'Editar';}else{ echo 'Adicionar'; } ?> Vendedor
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
								<h4><i class="icon-reorder"></i> Dados do Vendedor</h4>
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
										<li class=""><a data-toggle="tab" href="#contato">Dados de Contato</a></li>
										<li class=""><a data-toggle="tab" href="#contrato">Contrato</a></li>
										<li class=""><a data-toggle="tab" href="#comissoes">Veículos e Comissões</a></li>
									</ul>
									<form class="cmxform form-horizontal" id="vendedoresForm" method="post" action="#" enctype="multipart/form-data">
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
													<label for="cnpj" class="control-label">CNPJ*</label>
													<div class="controls">
														<input class="span6 " id="cnpj" name="cnpj" type="text" value="<?php echo @$cnpj; ?>" data-mask="99.999.999/9999-99" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="ie" class="control-label">IE</label>
													<div class="controls">
														<input class="span6 " id="ie" name="ie" type="text" value="<?php echo @$ie; ?>" />
													</div>
												</div>
                                
												<div class="control-group ">
													<label for="fantasia" class="control-label">Fantasia</label>
													<div class="controls controls-row">
														<input class="span6" id="fantasia" name="fantasia" type="text" value="<?php echo @$fantasia; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="razao" class="control-label">Razão Social*</label>
													<div class="controls controls-row">
														<input class="span6" id="razao" name="razao" type="text" value="<?php echo @$razao; ?>" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="cep" class="control-label">CEP*</label>
													<div class="controls controls-row">
														<input class="span6" id="cep" name="cep" type="text" value="<?php echo @$cep; ?>" data-mask="99999999" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="endereco" class="control-label">Endereço</label>
													<div class="controls controls-row">
														<input class="span6" id="endereco" name="endereco" type="text" value="<?php echo @$endereco; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="numero" class="control-label">Número</label>
													<div class="controls controls-row">
														<input class="span6" id="numero" name="numero" type="text" value="<?php echo @$numero; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="complemento" class="control-label">Complemento</label>
													<div class="controls controls-row">
														<input class="span6" id="complemento" name="complemento" type="text" value="<?php echo @$complemento; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="bairro" class="control-label">Bairro</label>
													<div class="controls controls-row">
														<input class="span6" id="bairro" name="bairro" type="text" value="<?php echo @$bairro; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="cidade" class="control-label">Cidade</label>
													<div class="controls controls-row">
														<input class="span6" id="cidade" name="cidade" type="text" value="<?php echo @$cidade; ?>" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="estado" class="control-label">UF*</label>
													<div class="controls controls-row">
														<input class="span6" id="estado" name="estado" type="text" value="<?php echo @$estado; ?>" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="ativo" class="control-label">Ativo</label>
													<div class="controls">
														<input type="checkbox" class="checkbox" id="ativo" name="ativo" value="1" <?php if(@$ativo == 1){echo 'checked';} ?> />
													</div>
												</div>


											</div>

											<div id="contato" class="tab-pane fade">
												
												<div class="control-group ">
													<label for="responsavel" class="control-label">Responsável</label>
													<div class="controls">
														<input class="span6 " id="responsavel" name="responsavel" type="text" value="<?php echo @$responsavel; ?>"  />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="cpf" class="control-label">CPF</label>
													<div class="controls">
														<input class="span6 " id="cpf" name="cpf" type="text" value="<?php echo @$cpf; ?>" data-mask="999.999.999-99" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="fone1" class="control-label">Telefone*</label>
													<div class="controls controls-row">
														<input class="span6" id="fone1" name="fone1" type="text" value="<?php echo @$fone1; ?>" data-mask="99 9999-9999" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="fone2" class="control-label">Celular</label>
													<div class="controls controls-row">
														<input class="span6" id="fone2" name="fone2" type="text" value="<?php echo @$fone2; ?>" data-mask="99 99999-9999" />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="mail" class="control-label">E-mail*</label>
													<div class="controls controls-row">
														<input class="span6" id="mail" name="mail" type="text" value="<?php echo @$mail; ?>" />
													</div>
												</div>
																				
												<div class="control-group ">
													<label for="observacao" class="control-label">Observação</label>
													<div class="controls">
														<textarea name="observacao" class="span6 " rows="3"><?php echo @$bservacao; ?></textarea>
													</div>
												</div>
												
											</div>

											<div id="contrato" class="tab-pane fade">

												<div class="control-group ">
													<label for="admissao" class="control-label">Admissão</label>
													<div class="controls controls-row">
														<input class="span6" id="admissao" name="admissao" type="date" value="<?php echo @$admissao; ?>" />
													</div>
												</div>

												<div class="control-group ">
													<label for="demissao" class="control-label">Demissão</label>
													<div class="controls controls-row">
														<input class="span6" id="demissao" name="demissao" type="date" value="<?php echo @$demissao; ?>" />
													</div>
												</div>
												
												<div class="control-group">
													<label for="unidade" class="control-label">Unidade</label>
													<div class="controls controls-row">
														<select class="span6" data-placeholder="Escolha a unidade" id="unidade" name="unidade" tabindex="1" required>
															<option value="">Selecione</option>
															<?php
															$busca_unidade = "SELECT ukey, sigla FROM mp_unidades ORDER BY sigla ASC";
															$sql_unidade = mysqli_query($con, $busca_unidade) or die("ERRO NO COMANDO SQL");
															$row_unidade = mysqli_num_rows($sql_unidade);

															if($row_unidade == 0){
																echo '<option value="">Não há unidades cadastradas</option>';
															}else{
																while($monta_unidade = mysqli_fetch_array($sql_unidade)){
																	$ukey_unidade	= $monta_unidade["ukey"];
																	$sigla_unidade	= $monta_unidade["sigla"];

																	if($unidade == $ukey_unidade){
																		echo '<option value="'.$ukey_unidade.'" selected>'.$sigla_unidade.'</option>';
																	}else{
																		echo '<option value="'.$ukey_unidade.'">'.$sigla_unidade.'</option>';
																	}
																}
															}
															?>
														</select>
													</div>
												</div>

											</div>

											<div id="comissoes" class="tab-pane fade">
												
												<div class="row-fluid">
													<div class="span12">
														<!-- BEGIN EXAMPLE TABLE widget-->
														<div class="widget orange">
															<div class="widget-title">
																<h4><i class="icon-reorder"></i> Veículos e Comissões</h4>
																<span class="tools">
																	<a class="btn btn-warning" href="form_vendedores_comissao.php?id=new&vendedor=<?php echo $ukey; ?>"><i class="icon-plus icon-white"></i> Adicionar</a>
																</span>
															</div>
															<div class="widget-body">
																<table class="table table-striped table-bordered" id="sample_1">
																	<thead>
																		<tr>
																			<th>Veículo</th>
																			<th>Comissão</th>
																			<th class="hidden-phone" width="50px">Opções</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php
																	if($id <> "new"){
																		$buscaComissao = "SELECT ukey, ukey_sellers, ukey_vehicles, (SELECT fantasia FROM mp_vehicles WHERE ukey = ukey_vehicles) fantasia, comissao FROM mp_sellers_comissao WHERE ukey_sellers = ".$id." ORDER BY ukey";
																		$sqlComissao = mysqli_query($con, $buscaComissao) or die("ERRO NO COMANDO SQL");

																		while ($montaComissao = mysqli_fetch_array($sqlComissao)){
																			$ukey_comissao		= $montaComissao["ukey"];
																			$vendedores			= $montaComissao["ukey_sellers"];
																			$veiculo			= $montaComissao["fantasia"];
																			$comissao			= number_format($montaComissao["comissao"], 2, ',', '.')."%";

																			echo '
																			<tr class="odd gradeX">
																				<td>'.$veiculo.'</td>
																				<td>'.$comissao.'</td>
																				<td class="hidden-phone"><a class="btn btn-small btn-primary" href="form_vendedores_comissao.php?id='.$ukey_comissao.'&vendedor='.$vendedores.'"><i class="icon-pencil icon-white"></i></a></td>
																			</tr>';
																		}
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