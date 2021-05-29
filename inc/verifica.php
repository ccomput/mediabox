<?php
//Inicia a sessão
session_start();
    	 
//Verifica se há dados ativos na sessão
if(empty($_SESSION["user_login"]) || empty($_SESSION["nomes"]) || empty($_SESSION["login"]) || empty($_SESSION["empresa"]) || empty($_SESSION["moeda"]) || empty($_SESSION["grupo"]) || empty($_SESSION["cod_vendedor"]) || empty($_SESSION["cod_cliente"]) || empty($_SESSION["mod_cadastro"]) || empty($_SESSION["mod_comercial"]) || empty($_SESSION["mod_add_proposta"]) || empty($_SESSION["mod_add_plantel"]) || empty($_SESSION["mod_plantel"]) || empty($_SESSION["mod_add_certificado"]) || empty($_SESSION["mod_certificado"]) || empty($_SESSION["mod_configura"])){
	
};

	// pegamos o tempo atual em que estamos:
	//$agora = mktime(date('H:i:s'));
	
	// subtraimos o tempo em que o usuário entrou, do tempo atual "a diferença é em segundos"
	//$segundos=(is_numeric($_SESSION['tempo_permitido']) and is_numeric($agora)) ? ($agora-$_SESSION['tempo_permitido']):false;
//	
//	//definimos os segundos que o usuário deverá ficar logado
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