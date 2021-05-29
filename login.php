<?php
require "inc/security.php";
require "inc/conect.php";

if(@$_GET["auth"] == "yes"){

	// Recebemos os dados digitados pelo usuário
	$login = anti_injection($_POST["login"]);
	//$login = _antiSqlInjection($_POST["login"]);
	$senha = base64_encode($_POST["senha"]);
	//$senha = base64_encode(_antiSqlInjection($_POST["senha"]));
     
	//VERIFICAMOS USUÁRIO E SENHA COMPARANDO COM OS DADOS DO BANCO MYSQL
	$select = "SELECT * FROM mp_user WHERE login = '".$login."' AND senha = '".$senha."'";
	$sql = mysqli_query($con,$select) or die("ERRO NO COMANDO SQL");

	//VERIFICAMOS AS LINHAS AFETADAS PELA CONSULTA
	$row = mysqli_num_rows($sql);
     
	mysqli_data_seek($sql, 0);
	 
	//VERIFICAMO SE RETORNOU ALGO
	if ($row == 0){
		echo "<script LANGUAGE=\"Javascript\">
		alert(\"Login ou Senha inválidos.\");
		document.location.replace('destroy.php');
		</SCRIPT>";
	}else{
		
		$resultado = mysqli_fetch_assoc($sql);
		
		//PEGA OS DADOS DO MYSQL E ATRIBUIMOS O VALOR A VARIAVEL
    	$id					= $resultado['ukey'];
		$nome				= $resultado["nome"];
	    $login				= $resultado["login"];
		$empresa			= $resultado["empresa"];
		$moeda				= $resultado["moeda"];
		$grupo				= $resultado["grupo"];
		$vendedor			= $resultado["vendedor"];
		$cliente			= $resultado["cliente"];
		$unidade			= $resultado["unidade"];
		
		$cadastro			= $resultado["cadastro"];
		$comercial			= $resultado["comercial"];
		$add_proposta		= $resultado["add_proposta"];
		$add_plantel		= $resultado["add_plantel"];
		$plantel			= $resultado["plantel"];
		$add_certificado	= $resultado["add_certificado"];
		$certificado		= $resultado["certificado"];
		$configura			= $resultado["configura"];

		//INICIALIZAMOS A SESSÃO
		session_start();

		//PASSAMOS AS VARIÁVEIS PARA SESSÃO
		$_SESSION["user_login"]			= $id;
		$_SESSION["nomes"]				= $nome;
		$_SESSION["login"]				= $login;
		$_SESSION["empresa"]			= $empresa;
		$_SESSION["moeda"]				= $moeda;
		$_SESSION["grupo"]				= $grupo;
		$_SESSION["cod_vendedor"]		= $vendedor;
		$_SESSION["cod_cliente"]		= $cliente;
		$_SESSION["cod_unidade"]		= $unidade;
		
		$_SESSION["mod_cadastro"]		= $cadastro;
		$_SESSION["mod_comercial"]		= $comercial;
		$_SESSION["mod_add_proposta"]	= $add_proposta;
		$_SESSION["mod_add_plantel"]	= $add_plantel;
		$_SESSION["mod_plantel"]		= $plantel;
		$_SESSION["mod_add_certificado"]= $add_certificado;
		$_SESSION["mod_certificado"]	= $certificado;
		$_SESSION["mod_configura"]		= $configura;

		//Gravamos o tempo atual em uma sessão, para compararmos depois.
		//$_SESSION['tempo_permitido'] = mktime(date('H:i:s'));

		//REDIRECIONAMOS PARA A PÁGINA QUE VAI EXIBIR OS DADOS
		header("Location: index.php");
	}
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title>DueMidia ERP</title>
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="Wenderson Plácido Brito" name="author" />
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
   <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
   <link href="css/style.css" rel="stylesheet" />
   <link href="css/style-responsive.css" rel="stylesheet" />
   <link href="css/style-default.css" rel="stylesheet" id="style_color" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="lock">
    <div class="lock-header">
        <!-- BEGIN LOGO -->
        <a class="center" id="logo" href="/">
            <img class="center" alt="logo" src="img/duemidia100.png">
        </a>
        <!-- END LOGO -->
    </div>
    <div class="login-wrap">
    	<form action="login.php?auth=yes" method="post">
        <div class="metro single-size blue">
            <div class="locked">
                <i class="icon-lock"></i>
                <span>Login</span>
            </div>
        </div>
        <div class="metro double-size green">
			<div class="input-append lock-input">
				<input type="text" name="login" class="" placeholder="Usuário">
			</div>
        </div>
        <div class="metro double-size yellow">
			<div class="input-append lock-input">
				<input type="password" name="senha" class="" placeholder="Senha">
			</div>
        </div>
        <div class="metro single-size terques login">
			<input type="submit" class="btn login-btn" value="Entrar">
        </div>
        </form>
    </div>
</body>
<!-- END BODY -->
</html>