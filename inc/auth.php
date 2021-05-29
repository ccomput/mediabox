<?php
require_once "security.php";
// Conexão com o banco de dados
require_once "conect.php";
// Recebemos os dados digitados pelo usuário
$login = addslashes($_POST["login"]);
$senha = base64_encode(addslashes($_POST["senha"]));
     
//VERIFICAMOS USUÁRIO E SENHA COMPARANDO COM OS DADOS DO BANCO MYSQL
$sql = mysql_query("SELECT ukey, nome, login, senha, empresa, grupo, vendedor, cliente FROM kn_user WHERE login = '".$login."' AND senha = '".$senha."'") or die("ERRO NO COMANDO SQL");

//VERIFICAMOS AS LINHAS AFETADAS PELA CONSULTA
$row = mysql_num_rows($sql);
     
//VERIFICAMO SE RETORNOU ALGO
if ($row == 0){
	echo "<script LANGUAGE=\"Javascript\">
		alert(\"Login ou Senha inválidos.\");
		document.location.replace('destroy.php');
		</SCRIPT>";
}else{
//PEGA OS DADOS DO MYSQL E ATRIBUIMOS O VALOR A VARIAVEL
    $id			= mysql_result($sql, 0, "ukey");
	$nome		= mysql_result($sql, 0, "nome");
    $login		= mysql_result($sql, 0, "login");
	$empresa	= mysql_result($sql, 0, "empresa");
	$grupo		= mysql_result($sql, 0, "grupo");
	$vendedor	= mysql_result($sql, 0, "vendedor");
	$cliente	= mysql_result($sql, 0, "cliente");

//INICIALIZAMOS A SESSÃO
    session_start();

//PASSAMOS AS VARIÁVEIS PARA SESSÃO
    $_SESSION["id"]				= $id;
	$_SESSION["nome"]			= $nome;
    $_SESSION["login"]			= $login;
	$_SESSION["empresa"]		= $empresa;
	$_SESSION["grupo"]			= $grupo;
	$_SESSION["cod_vendedor"]	= $vendedor;
	$_SESSION["cod_cliente"]	= $cliente;

//Gravamos o tempo atual em uma sessão, para compararmos depois.
	$_SESSION['tempo_permitido'] = mktime(date('H:i:s'));

//REDIRECIONAMOS PARA A PÁGINA QUE VAI EXIBIR OS DADOS
    header("Location: index.php");
}
?>