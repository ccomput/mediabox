<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];


if (preg_match('/Windows NT 5.1/i', $user_agent)){
	$aviso = '
	<h3>Para utilizar nosso portal utilize este navegador.</h3>
	<p>Clique no logo para realizar o download caso não possua instalado.</p>';
	$firefox = '<a href="https://www.mozilla.org/pt-BR/firefox/fx/" target="_blank" title="Download Mozilla Firefox"><img src="images/firefox_logo.png" /></a>';
	$ie = '';
}elseif(preg_match('/Windows NT 6.1/i', $user_agent)){
	$aviso = '
	<h3>Para utilizar nosso portal utilize um desses navegadores.</h3>
	<p>Clique no logo para realizar o download caso não possua instalado.</p>';
	$firefox = '<a href="https://www.mozilla.org/pt-BR/firefox/fx/" target="_blank" title="Download Mozilla Firefox"><img src="images/firefox_logo.png" /></a>';
	$ie = '<a href="http://windows.microsoft.com/pt-br/internet-explorer/ie-10-worldwide-languages" target="_blank" title="Download Internet Explorer"><img src="images/ie_logo.png" /></a>';
}elseif(preg_match('/Windows NT 6.2/i', $user_agent)){
	$aviso = '
	<h3>Para utilizar nosso portal utilize um desses navegadores.</h3>
	<p>Clique no logo para realizar o download caso não possua instalado.</p>';
	$firefox = '<a href="https://www.mozilla.org/pt-BR/firefox/fx/" target="_blank" title="Download Mozilla Firefox"><img src="images/firefox_logo.png" /></a>';
	$ie = '<a href="http://windows.microsoft.com/pt-br/internet-explorer/ie-10-worldwide-languages" target="_blank" title="Download Internet Explorer"><img src="images/ie_logo.png" /></a>';
}else{
	$aviso = '<h3>Seu sistema operacional não é compatível</h3>
	<p>Por favor, utilize o Windows XP, 7 ou 8.</p>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Portal Kraus & Naimer</title>
<link href='http://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
<style>
* {padding:0; margin:0;}
body {background:url(img/page-background.png) repeat-x; font-family:'Muli', Calibri, Verdana, Geneva, sans-serif; color:#666;}
a:hover {opacity:0.7;}
a img {border:none;}
h3 {padding-top:40px; padding-bottom:6px;}
p {color:#999;}
#center{position:absolute; left:50%; top:50%; margin-left:-400px; margin-top:-300px; width:800px; height:600px; text-align:center;}
</style>
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body>
	<div id="center">
		<img src="img/portal_kn_logo.png" width="400" /><br />
		<!--<php echo @$aviso; echo @$firefox; echo @$ie;?>-->
        <h3>Nosso Portal encontra-se em manutenção.</h3>
        <p>Tente novamente mais tarde.</p>
    </div>
</body>
</html>