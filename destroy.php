<?php
	session_start();

    //Elimina os dados da sessão
    !isset($_SESSION["user_login"]);
    !isset($_SESSION["nome"]);
    !isset($_SESSION["login"]);
    !isset($_SESSION["empresa"]);
    !isset($_SESSION["grupo"]);
    !isset($_SESSION["cod_vendedor"]);
    !isset($_SESSION["cod_cliente"]);
	!isset($_SESSION["cod_unidade"]);
	!isset($_SESSION["mod_cadastro"]);
	!isset($_SESSION["mod_comercial"]);
	!isset($_SESSION["mod_add_proposta"]);
	!isset($_SESSION["mod_add_plantel"]);
	!isset($_SESSION["mod_plantel"]);
	!isset($_SESSION["mod_add_certificado"]);
	!isset($_SESSION["mod_certificado"]);
	!isset($_SESSION["mod_configura"]);
	
     
    //Encerra a sessão
    session_destroy();
    header("Location:login.php");
?>