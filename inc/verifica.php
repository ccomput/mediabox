<?php
//Inicia a sess�o
session_start();
    	 
//Verifica se h� dados ativos na sess�o
if(empty($_SESSION["user_login"]) || empty($_SESSION["nomes"]) || empty($_SESSION["login"]) || empty($_SESSION["empresa"]) || empty($_SESSION["moeda"]) || empty($_SESSION["grupo"]) || empty($_SESSION["cod_vendedor"]) || empty($_SESSION["cod_cliente"]) || empty($_SESSION["mod_cadastro"]) || empty($_SESSION["mod_comercial"]) || empty($_SESSION["mod_add_proposta"]) || empty($_SESSION["mod_add_plantel"]) || empty($_SESSION["mod_plantel"]) || empty($_SESSION["mod_add_certificado"]) || empty($_SESSION["mod_certificado"]) || empty($_SESSION["mod_configura"])){
	
};

	// pegamos o tempo atual em que estamos:
	//$agora = mktime(date('H:i:s'));
	
	// subtraimos o tempo em que o usu�rio entrou, do tempo atual "a diferen�a � em segundos"
	//$segundos=(is_numeric($_SESSION['tempo_permitido']) and is_numeric($agora)) ? ($agora-$_SESSION['tempo_permitido']):false;
//	
//	//definimos os segundos que o usu�rio dever� ficar logado
	//define('TEMPO_LOGADO',3600);
//	
	//if($segundos > 'TEMPO_LOGADO'){
	//$_SESSION['usuario']='';
//
	//header("Location:destroy.php");
	//}
	
	if($_SESSION["user_login"] == 0 and $_SESSION["nome"] == 0 and $_SESSION["login"] == 0 and $_SESSION["empresa"] == 0 and $_SESSION["grupo"] == 0 and $_SESSION["cod_vendedor"] == 0 and $_SESSION["cod_cliente"] == 0){
		header("Location:destroy.php");
	}

?>