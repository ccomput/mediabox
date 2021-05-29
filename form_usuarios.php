<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_config	= ' active';
$usuarios	= ' class="active"';

//*VARIAVEIS DE TRANSAÇÃO	
$id		= @$_GET["id"];
$insert	= @$_POST["insert"];
$update = @$_POST["update"];
$delete	= @$_GET["delete"];
$table	= "mp_user";

if ($id >= 1 and $id <> "new"){
	$busca = "SELECT * FROM ".$table." WHERE ukey=".$id;
	$sql = mysqli_query($con, $busca) or die("ERRO NO COMANDO SQL");

	$monta = mysqli_fetch_array($sql);
		$ukey			= $monta["ukey"];
		$nome_user		= $monta["nome"];
		$apelido		= $monta["apelido"];
		$endereco		= $monta["endereco"];
		$numero			= $monta["numero"];
		$complemento	= $monta["complemento"];
		$cidade			= $monta["cidade"];
		$estado			= $monta["estado"];
		$cep			= $monta["cep"];
		$bairro			= $monta["bairro"];
		$fone			= $monta["fone"];
		$mobile			= $monta["mobile"];
		$mail			= $monta["mail"];
		$login_user		= $monta["login"];
		$senha			= base64_decode($monta["senha"]);
		$grupo			= $monta["grupo"];
		$empresa		= $monta["empresa"];
		$cnpj			= $monta["cnpj"];
		$cpf			= $monta["cpf"];
		$carro			= $monta["ukey_carro"];
		$vendedor		= $monta["vendedor"];
		$unidade		= $monta["ukey_unidades"];
		$zona			= $monta["zona"];
		$desconto		= $monta["desconto"];
		$moeda			= $monta["moeda"];
		
		$cadastro		= $monta["cadastro"];
		$comercial		= $monta["comercial"];
		$add_proposta	= $monta["add_proposta"];
	
		$add_plantel	= $monta["add_plantel"];
		$plantel		= $monta["plantel"];
		$add_certificado= $monta["add_certificado"];
		$certificado	= $monta["certificado"];
		$configura		= $monta["configura"];
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){

	$nome_user		= $_POST["nome"];
	$apelido		= $_POST["apelido"];
	$endereco		= $_POST["endereco"];
	$numero			= $_POST["numero"];
	$complemento	= $_POST["complemento"];
	$cidade			= $_POST["cidade"];
	$estado			= $_POST["estado"];
	$cep			= $_POST["cep"];
	$bairro			= $_POST["bairro"];
	$fone			= $_POST["fone"];
	$mobile			= $_POST["mobile"];
	$mail			= $_POST["mail"];
	$login_user		= $_POST["login"];
	$senha			= base64_encode($_POST["senha"]);
	$grupo			= $_POST["grupo"];
	$empresa		= $_POST["empresa"];
	$cnpj			= $_POST["cnpj"];
	$cpf			= $_POST["cpf"];
	$carro			= $_POST["ukey_carro"];
	$vendedor		= $_POST["vendedor"];
	$unidade		= $_POST["unidade"];
	$zona			= $_POST["zona"];
	$desconto		= $_POST["desconto"];
	$moeda			= $_POST["moeda"];
	
	$cadastro		= $_POST["cadastro"];
	$comercial		= $_POST["comercial"];
	$add_proposta	= $_POST["add_proposta"];
	$add_plantel	= $_POST["add_plantel"];
	$plantel		= $_POST["plantel"];
	$add_certificado= $_POST["add_certificado"];
	$certificado	= $_POST["certificado"];
	$configura		= $_POST["configura"];
	
	$select_insert = "INSERT INTO ".$table." (nome, apelido, endereco, numero, complemento, cidade, estado, cep, bairro, fone, mobile, mail, login, senha, grupo, empresa, cnpj, cpf, ukey_carro, vendedor, ukey_unidades, zona, desconto, moeda, cadastro, comercial, add_proposta, add_plantel, plantel, add_certificado, certificado, configura) VALUES ('".$nome_user."','".$apelido."','".$endereco."','".$numero."','".$complemento."','".$cidade."','".$estado."','".$cep."','".$bairro."','".$fone."','".$mobile."','".$mail."','".$login_user."','".$senha."','".$grupo."','".$empresa."','".$cnpj."','".$cpf."','".$carro."','".$vendedor."','".$unidade."','".$zona."','".$desconto."','".$moeda."','".$cadastro."','".$comercial."','".$add_proposta."','".$add_plantel."','".$plantel."','".$add_certificado."','".$certificado."','".$configura."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header("Location:usuarios.php");
		
}elseif($update == 1){

	$ukey_user		= $_POST["ukey"];
	$nome_user		= $_POST["nome"];
	$apelido		= $_POST["apelido"];
	$endereco		= $_POST["endereco"];
	$numero			= $_POST["numero"];
	$complemento	= $_POST["complemento"];
	$cidade			= $_POST["cidade"];
	$estado			= $_POST["estado"];
	$cep			= $_POST["cep"];
	$bairro			= $_POST["bairro"];
	$fone			= $_POST["fone"];
	$mobile			= $_POST["mobile"];
	$mail			= $_POST["mail"];
	$login_user		= $_POST["login"];
	$senha			= base64_encode($_POST["senha"]);
	$grupo			= $_POST["grupo"];
	$empresa		= $_POST["empresa"];
	$cnpj			= $_POST["cnpj"];
	$cpf			= $_POST["cpf"];
	$carro			= $_POST["carro"];
	$vendedor		= $_POST["vendedor"];
	$unidade		= $_POST["unidade"];
	$zona			= $_POST["zona"];
	$desconto		= $_POST["desconto"];
	$moeda			= $_POST["moeda"];
	
	$cadastro		= $_POST["cadastro"];
	$comercial		= $_POST["comercial"];
	$add_proposta	= $_POST["add_proposta"];
	$add_plantel	= $_POST["add_plantel"];
	$plantel		= $_POST["plantel"];
	$add_certificado= $_POST["add_certificado"];
	$certificado	= $_POST["certificado"];
	$configura		= $_POST["configura"];
	

	$select_update = "UPDATE ".$table." SET nome='".$nome_user."', apelido='".$apelido."', endereco='".$endereco."', numero='".$numero."', complemento='".$complemento."', cidade='".$cidade."', estado='".$estado."', cep='".$cep."', bairro='".$bairro."', fone='".$fone."', mobile='".$mobile."', mail='".$mail."', login='".$login_user."', senha='".$senha."', grupo='".$grupo."', empresa='".$empresa."', cnpj='".$cnpj."', cpf='".$cpf."', ukey_carro='".$carro."', vendedor='".$vendedor."', ukey_unidades='".$unidade."', zona='".$zona."', desconto='".$desconto."', moeda='".$moeda."', cadastro='".$cadastro."', add_proposta='".$add_proposta."', comercial='".$comercial."', add_plantel='".$add_plantel."', plantel='".$plantel."', add_certificado='".$add_certificado."', certificado='".$certificado."', configura='".$configura."' WHERE ukey=".$ukey_user."";
	$sql_update = mysqli_query($con, $select_update) or die("ERRO NO COMANDO EDITAR SQL");
	
	header("Location:usuarios.php");
	
}elseif($delete == 1){

	$select_delete = "DELETE FROM ".$table." WHERE ukey='".$delete."'";
	$sql_delete = mysqli_query($con, $select_delete) or die("ERRO NO COMANDO EXCLUIR SQL");
		
	header("Location:usuarios.php");
	
}else{
	echo "";
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
							Usuários
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="#">Configurações</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Usuários
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
								<h4><i class="icon-reorder"></i> Dados do Usuário</h4>
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
										<li><a data-toggle="tab" href="#empresa">Empresa</a></li>
										<li><a data-toggle="tab" href="#acesso">Acesso</a></li>
									</ul>
									<form class="cmxform form-horizontal" id="usuarios" method="post" action="form_usuarios.php" enctype="multipart/form-data">
										<input type="hidden" name="insert" value="<?php if($id == "new"){echo "1";}else{echo "0";} ?>">
										<input type="hidden" name="update" value="<?php if($id == "new"){echo "0";}else{echo "1";} ?>">
										<input type="hidden" name="ukey" value="<?php echo $ukey; ?>">
										<div class="tab-content" id="myTabContent">
											<div id="geral" class="tab-pane fade in active">
												<!-- BEGIN FORM-->
												<div class="control-group ">
													<label for="nome" class="control-label">Nome*</label>
													<div class="controls">
														<input class="span6 " id="nome" name="nome" type="text" value="<?php echo @$nome_user; ?>" required />
													</div>
												</div>
												
												<div class="control-group ">
													<label for="apelido" class="control-label">Apelido*</label>
													<div class="controls">
														<input class="span6 " id="apelido" name="apelido" type="text" value="<?php echo @$apelido; ?>" required />
													</div>
												</div>
                                
												<div class="control-group ">
													<label for="endereco" class="control-label">Endereço*</label>
													<div class="controls controls-row">
														<input class="span6" id="endereco" name="endereco" type="text" value="<?php echo @$endereco; ?>" required />
													</div>
												</div>
                                                
												<div class="control-group">
													<label for="numero" class="control-label" >Numero*</label>
													<div class="controls controls-row">
														<input class="span6" id="numero" name="numero" type="text" value="<?php echo @$numero; ?>" />
													</div>
												</div>
                                
												<div class="control-group">
													<label for="complemento" class="control-label" >Complemento</label>
													<div class="controls controls-row">
														<input class="span6" id="complemento" name="complemento" type="text" value="<?php echo @$complemento; ?>" />
													</div>
												</div>
                                
												<div class="control-group">
													<label for="bairro" class="control-label" >Bairro</label>
													<div class="controls controls-row">
														<input class="span6" id="bairro" name="bairro" type="text" value="<?php echo @$bairro; ?>" />
													</div>
												</div>
                                
												<div class="control-group">
													<label for="cidade" class="control-label" >Cidade*</label>
													<div class="controls controls-row">
														<input class="span6" id="cidade" name="cidade" type="text" value="<?php echo @$cidade; ?>" required />
													</div>
												</div>
                                
												<div class="control-group ">
													<label for="estado" class="control-label">Estado*</label>
													<div class="controls">
														<select class="input-small m-wrap" id="estado" name="estado">
															<?php if($id >= 1 and $id <> "new"){echo '<option value="'.$estado.'">'.$estado.'</option>';} ?>
															<option value="AC">AC</option>
															<option value="AL">AL</option>
															<option value="AP">AP</option>
															<option value="AM">AM</option>
															<option value="BA">BA</option>
                						                    <option value="CE">CE</option>
															<option value="DF">DF</option>
															<option value="ES">ES</option>
															<option value="GO">GO</option>
															<option value="MA">MA</option>
															<option value="MT">MT</option>
															<option value="MS">MS</option>
															<option value="MG">MG</option>
															<option value="PA">PA</option>
															<option value="PB">PB</option>
															<option value="PR">PR</option>
															<option value="PE">PE</option>
															<option value="PI">PI</option>
															<option value="RJ">RJ</option>
															<option value="RN">RS</option>
															<option value="RO">RO</option>
															<option value="RR">RR</option>
															<option value="SC">SC</option>
															<option value="SP">SP</option>
															<option value="SE">SE</option>
															<option value="TO">TO</option>
														</select>
													</div>
												</div>
                                
												<div class="control-group">
													<label for="cep" class="control-label" >Cep*</label>
													<div class="controls controls-row">
														<input class="span6" id="cep" name="cep" type="text" value="<?php echo @$cep; ?>" data-mask="99999-999" required />
													</div>
												</div>
                                
												<div class="control-group">
													<label for="fone" class="control-label">Fone</label>
													<div class="controls controls-row">
														<input class="span6" id="fone" name="fone" type="text" value="<?php echo @$fone; ?>" data-mask="99 9999-9999" />
													</div>
												</div>
												
												<div class="control-group">
													<label for="mobile" class="control-label">Celular</label>
													<div class="controls controls-row">
														<input class="span6" id="mobile" name="mobile" type="text" value="<?php echo @$mobile; ?>" data-mask="99 99999-9999" />
													</div>
												</div>
                        	        
												<div class="control-group">
													<label for="mail" class="control-label" >E-mail*</label>
													<div class="controls controls-row">
														<input class="span6" id="mail" name="mail" type="text" value="<?php echo @$mail; ?>" required />
													</div>
												</div>
                                
												<div class="control-group">
        		                           			<label for="login" class="control-label" >Login*</label>
													<div class="controls controls-row">
														<input class="span6" id="login" name="login" type="text" value="<?php echo @$login_user; ?>" required />
													</div>
												</div>
                                
												<div class="control-group">
													<label for="senha" class="control-label" >Senha*</label>
													<div class="controls controls-row">
														<input class="span6" id="senha" name="senha" type="password" value="<?php echo @$senha; ?>" required />
													</div>
												</div>
												                                
											</div>

											<div id="empresa" class="tab-pane fade">
												
												<div class="control-group">
													<label for="cnpj" class="control-label" >CNPJ</label>
													<div class="controls controls-row">
														<input class="span6" id="cnpj" name="cnpj" type="text" value="<?php echo @$cnpj; ?>" data-mask="99.999.999/9999-99" />
													</div>
												</div>
                                                
												<div class="control-group">
													<label for="cpf" class="control-label" >CPF</label>
													<div class="controls controls-row">
														<input class="span6" id="cpf" name="cpf" type="text" value="<?php echo @$cpf; ?>" data-mask="999.999.999-99" required />
													</div>
												</div>
												
												<div class="control-group">
													<label for="vendedor" class="control-label">Vendedor</label>
													<div class="controls controls-row">
														<select class="span6" data-placeholder="Escolha o vendedor" id="vendedor" name="vendedor" tabindex="1">
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

																	if($vendedor == $ukey_vendedor){
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
												
												<div class="control-group">
													<label for="unidade" class="control-label">Unidade</label>
													<div class="controls controls-row">
														<select class="span6" data-placeholder="Escolha a unidade" id="unidade" name="unidade" tabindex="1">
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

											<div id="acesso" class="tab-pane fade">
                                            
												<div class="control-group">
													<label for="zona" class="control-label" >Zona</label>
													<div class="controls controls-row">
														<input class="span6" id="zona" name="zona" type="text" value="<?php echo @$zona; ?>" />
													</div>
												</div>
                                                
												<div class="control-group">
													<label for="moeda" class="control-label" >Moeda</label>
													<div class="controls controls-row">
														<select class="input-small m-wrap" id="moeda" name="moeda">
															<?php
															if($moeda == '2'){
																$moeda_euro = 'selected';
															}elseif($moeda == '1'){
																$moeda_dolar = 'selected';
															}else{
																$moeda_real = 'selected';
															}
															?>
															<option value="0" <?php echo $moeda_real; ?> >REAL</option>
															<option value="1" <?php echo $moeda_dolar; ?> >DOLAR</option>
															<option value="2" <?php echo $moeda_euro; ?> >EURO</option>
														</select>
													</div>
												</div>
                                    
                            					<div class="control-group ">
													<label for="grupo" class="control-label">Grupo</label>
													<div class="controls">
														<select class="input-xsmall m-wrap" id="grupo" name="grupo" tabindex="1">
													<?php
													$busca_grupo = "SELECT * FROM mp_group";
													$sql_grupo = mysqli_query($con, $busca_grupo) or die("ERRO NO COMANDO SQL");
					
													if($id >= 1 and $id <> "new"){
														$busca_select = "SELECT * FROM mp_group WHERE ukey=".$grupo." LIMIT 0,1";
														$sql_select = mysqli_query($con, $busca_select) or die("ERRO NO COMANDO SQL");
														$monta_select = mysqli_fetch_array($sql_select);
														$nome_grupo	= $monta_select["grupo"];
													
														echo '<option value="'.$grupo.'">'.$nome_grupo.'</option>';
												
														while($monta_grupo = mysqli_fetch_array($sql_grupo)){
															$ukey_grupo	= $monta_grupo["ukey"];
															$grupos	= $monta_grupo["grupo"];
										
															echo '<option value="'.$ukey_grupo.'">'.$grupos.'</option>';
														}
													}else{
														while($monta_grupo = mysqli_fetch_array($sql_grupo)){
														$ukey_grupo	= $monta_grupo["ukey"];
														$grupos	= $monta_grupo["grupo"];
										
														echo '<option value="'.$ukey_grupo.'">'.$grupos.'</option>';
														}
													}
													?>
												</select>
                                    		</div>
										</div>
                                
                                
										<div class="control-group ">
											<label for="cadastro" class="control-label">Cadastro</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="cadastro" name="cadastro" value="1" <?php if(@$cadastro == 1){echo 'checked';} ?>/>
											</div>
										</div>
										
										<div class="control-group ">
											<label for="comercial" class="control-label">Comercial</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="comercial" name="comercial" value="1" <?php if(@$comercial == 1){echo 'checked';} ?>/>
											</div>
										</div>
												
										<div class="control-group ">
											<label for="add_proposta" class="control-label">Adicionar Proposta</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="add_proposta" name="add_proposta" value="1" <?php if(@$add_proposta == 1){echo 'checked';} ?>/>
											</div>
										</div>
                                                                        
										<div class="control-group ">
											<label for="add_plantel" class="control-label">Adicionar Financeiro</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="add_plantel" name="add_plantel" value="1" <?php if(@$add_plantel == 1){echo 'checked';} ?>/>
											</div>
										</div>
                                
										<div class="control-group ">
											<label for="plantel" class="control-label">Acesso ao Financeiro</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="plantel" name="plantel" value="1" <?php if(@$plantel ==1){echo 'checked';} ?> />
											</div>
										</div>
                                
										<div class="control-group ">
											<label for="add_certificado" class="control-label">Adicionar Certificado</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="add_certificado" name="add_certificado" value="1" <?php if(@$add_certificado == 1){echo 'checked';} ?> />
											</div>
										</div>
                                           
										<div class="control-group ">
											<label for="certificado" class="control-label">Certificado</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="certificado" name="certificado" value="1" <?php if(@$certificado == 1){echo 'checked';} ?> />
											</div>
										</div>
                                
										<div class="control-group ">
											<label for="configura" class="control-label">Configurações</label>
											<div class="controls">
												<input type="checkbox" class="checkbox" id="configura" name="configura" value="1" <?php if(@$configura == 1){echo 'checked';} ?> />
											</div>
										</div>
                                           
									</div>
                            	
                                </div>
                                <div class="form-actions">
									<input class="btn btn-success" type="submit" value="Salvar" />
									<input class="btn" type="reset" value="Cancelar" />
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
	<script type="text/javascript" src="assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
	<script type="text/javascript" src="assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
   <!--datepicker-->
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
	<script src="js/viacep.js"></script>

   <!-- END JAVASCRIPTS -->
   <!--<script>
       $(function () {
           $(" input[type=radio], input[type=checkbox]").uniform();
       });
   </script>-->

</body>
<!-- END BODY -->
</html>