<?php
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";

//Parametro de link ativo
$sub_financeiro = 'active';
$plano_contas	= 'active';

//*VARIAVEIS DE TRANSAÇÃO	
$id		= @$_GET["id"];
$insert	= @$_POST["insert"];
$update = @$_POST["update"];
$delete	= @$_GET["delete"];
$table	= "mp_client";

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
	$contato1		= $monta["contato1"];
	$contato2		= $monta["contato2"];
	$observacao		= $monta["observacao"];
	$ativo			= $monta["ativo"];
	
}

//*INSERIR DADOS/////////////////////////////////////////////////////
if ($insert == 1){

	/*$fantasia		= $_POST["fantasia"];

	$select_insert = "INSERT INTO ".$table." (fantasia, razao, endereco, numero, complemento, bairro, cidade, estado, pais, cep, cnpj, ie, fone1, fone2, mail, contato1, contato2, observacao, ativo) VALUES ('".$fantasia."','".$razao."','".$endereco."','".$numero."','".$complemento."','".$bairro."','".$cidade."','".$estado."','".$pais."','".$cep."','".$cnpj."','".$ie."','".$fone1."','".$fone2."','".$mail."','".$contato1."','".$contato2."','".$observacao."','".$ativo."')";
	$sql_insert = mysqli_query($con, $select_insert) or die("ERRO NO COMANDO INSERIR SQL");

	header("Location:clientes.php?viewer=clientes");*/
		
}elseif($update == 1){

	/*$ukey_cliente	= $_POST["ukey"];
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
	$contato1		= $_POST["contato1"];
	$contato2		= $_POST["contato2"];
	$observacao		= $_POST["observacao"];
	$ativo			= $_POST["ativo"];

	$select_update = "UPDATE ".$table." SET fantasia='".$fantasia."', razao='".$razao."', endereco='".$endereco."', numero='".$numero."', complemento='".$complemento."', bairro='".$bairro."', cidade='".$cidade."', estado='".$estado."', pais='".$pais."', cep='".$cep."', cnpj='".$cnpj."', ie='".$ie."', fone1='".$fone1."', fone2='".$fone2."', mail='".$mail."', contato1='".$contato1."', contato2='".$contato2."', observacao='".$observacao."', ativo='".$ativo."' WHERE ukey=".$ukey_cliente."";
	$sql_update = mysqli_query($con, $select_update) or die("ERRO NO COMANDO EDITAR SQL");
	
	header("Location:clientes.php?viewer=clientes");*/
	
}elseif($delete == 1){

	/*$select_delete = "DELETE FROM ".$table." WHERE ukey='".$delete."'";
	$sql_delete = mysqli_query($con, $select_delete) or die("ERRO NO COMANDO EXCLUIR SQL");
		
	header("Location:clientes.php?viewer=clientes");*/
	
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
							Plano de Contas
						</h3>
						<ul class="breadcrumb">
							<li>
								<a href="/">Painel</a>
								<span class="divider">/</span>
							</li>
							<li>
								<a href="/">Financeiro</a>
								<span class="divider">/</span>
							</li>
							<li class="active">
								Plano de Contas
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
						
						<div class="widget widget-tabs blue">
							<div class="widget-title">
								<h4><i class="icon-reorder"></i> Plano de Contas</h4>
							</div>
							<div class="widget-body">
								<div class="tabbable ">
									<ul class="nav nav-tabs">
										<li class=""><a href="#widget_tab4" data-toggle="tab">Transferências</a></li>
										<li class=""><a href="#widget_tab4" data-toggle="tab">Bancos</a></li>
										<li class=""><a href="#widget_tab3" data-toggle="tab">Centros de Custo</a></li>
										<li class=""><a href="#widget_tab2" data-toggle="tab">Despesas</a></li>
										<li class="active"><a href="#widget_tab1" data-toggle="tab">Receitas</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="widget_tab1">
											<h4>Receitas</h4>
											<p>
                                            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
											</p>
										</div>
										<div class="tab-pane" id="widget_tab2">
											<h4>Despesas</h4>
											<p>
                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
											</p>
											<p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
											</p>
										</div>
										<div class="tab-pane" id="widget_tab3">
											<h4>Centros de Custo</h4>
											<p>
                                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
											</p>
											<p>
                                            The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
											</p>
										</div>
									</div>
								</div>
							</div>
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