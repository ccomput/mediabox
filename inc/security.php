<?php
/*if(!$_SERVER['HTTPS']) {
$protocolo = "https://";
header( "Location: ".$protocolo.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']);
}
*/

/**
Limita o numero de SO e Navegadores
**/
$user_agent = $_SERVER['HTTP_USER_AGENT'];

/*if(preg_match('/Android/i', $user_agent)){
	header("Location: mobile/");
}else*/if(preg_match('/Gecko/i', $user_agent) and preg_match('/Windows NT 5.1/i', $user_agent)){
	echo '';
}elseif(preg_match('/Gecko/i', $user_agent) and preg_match('/Windows NT 6.1/i', $user_agent)){
	echo '';
}elseif(preg_match('/Gecko/i', $user_agent) and preg_match('/Windows NT 6.2/i', $user_agent)){
	echo '';
}elseif(preg_match('/Gecko/i', $user_agent) and preg_match('/Windows NT 6.3/i', $user_agent)){
	echo '';
}elseif(preg_match('/Gecko/i', $user_agent) and preg_match('/Windows NT 10.0/i', $user_agent)){
	echo '';
}elseif(preg_match('/MSIE/i', $user_agent) and preg_match('/Windows NT 6.1/i', $user_agent)){
	echo '';
}elseif(preg_match('/MSIE/i', $user_agent) and preg_match('/Windows NT 6.2/i', $user_agent)){
	echo '';
}elseif(preg_match('/MSIE/i', $user_agent) and preg_match('/Windows NT 6.3/i', $user_agent)){
	echo '';
}elseif(preg_match('/MSIE/i', $user_agent) and preg_match('/Windows NT 10.0/i', $user_agent)){
	echo '';
}else{
	echo '';
	/*header("Location: aviso.php");*/
}


/**
 * Protege o banco de dados contra ataques de SQL Injection
 *
 * Remove palavras que podem ser ofensivas à integridade do banco
 * Adiciona barras invertidas a uma string
 *
 * @uses $_REQUEST= _antiSqlInjection($_REQUEST);
 * @uses $_POST = _antiSqlInjection($_POST);
 * @uses $_GET = _antiSqlInjection($_GET);
 *
 * @author Igor Escobar
 * @email blog [at] igorescobar [dot] com
 *
 */
 
function _antiSqlInjection($Target){
    $sanitizeRules = array('OR','FROM','SELECT','INSERT','DELETE','WHERE','DROP TABLE','SHOW TABLES','*','--','=');
    foreach($Target as $key => $value):
        if(is_array($value)): $arraSanitized[$key] = _antiSqlInjection($value);
        else:
            $arraSanitized[$key] = (!get_magic_quotes_gpc()) ? addslashes(str_ireplace($sanitizeRules,"",$value)) : str_ireplace($sanitizeRules,"",$value);
        endif;
    endforeach;
    return $arraSanitized;
}

/*
* Anti Injection
* Verifica e Trata as informações
* Autor: Danilo Iannone - danilowm@gmail.com*/
function anti_injection($obj){
	$obj = preg_replace("/(from|alter table|select|insert|delete|update|where|drop table|show tables)/i", "", $obj);
	$obj = trim($obj);
	$obj = strip_tags($obj);
	if(!get_magic_quotes_gpc()) {
		$obj = addslashes($obj);
		return $obj;
	}
}
 
?>