<?php
# Informa qual o conjunto de caracteres será usado.
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$user = 'outbo123_duemidi';
$pass = '^Or~*DwRhY=f';
$db   = 'outbo123_duemidia';


//1º passo - Conecta ao servidor MySQL
//mysql_connect("localhost","buzat628_portal","mN$*4r-pg4wu");
$con = mysqli_connect($host, $user, $pass, $db);

//2º passo - Seleciona o Banco de Dados
//mysql_select_db("buzat628_portal");

# UTF8
mysqli_query($con,"SET NAMES 'utf8'");
mysqli_query($con,'SET character_set_connection=utf8');
mysqli_query($con,'SET character_set_client=utf8');
mysqli_query($con,'SET character_set_results=utf8');

# UTF8
//$lang = "pt_BR";
//$codeset = "utf8";  // warning ! not UTF-8 with dash '-' 
//// for windows compatibility (e.g. xampp) : theses 3 lines are useless for linux systems
//putenv('LANG='.$lang.'.'.$codeset);
//putenv('LANGUAGE='.$lang.'.'.$codeset);
//bind_textdomain_codeset('localhost', $codeset); 
//
//// set locale
//bindtextdomain('localhost', ABSPATH.'/locale/');
//setlocale(LC_ALL, $lang.'.'.$codeset);
//textdomain('localhost'); 

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_TIME, 'pt_BR.utf8');
?>