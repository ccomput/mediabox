<?php
$chave_de_acesso	= $_GET['chave'];
$nome_cliente		= $_GET['cliente'];
$pedido_cliente		= $_GET['pc'];
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="pt-br" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="pt-br"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Portal Kraus & Naimer - Nota do Pedido</title>
    <style type="text/css">
	* {margin:0; padding:0}
	body {font-family:'Calibri', Verdana, Geneva, sans-serif;overflow-x:hidden;}
	#header {width:100%; height:30px; background:rgb(74, 139, 194); padding:15px;}
	#info {width:100%; height:80px; background: #FFF; padding:15px; color:#666;}
	</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
	<div id="header">
    	<img src="img/logo.png" alt="Portal" />
	</div>
    <div id="info">
    <?php
    echo '
		<h2>Cliente: '.$nome_cliente.'</h2>
		<h3>Pedido: '.$pedido_cliente.'</h2>
		<h3>Chave de Acesso: '.$chave_de_acesso.'</h2>
		';
	?>
	</div>
	<iframe width="100%" height="720px" name="nota" src="http://www.nfe.fazenda.gov.br/portal/consulta.aspx?tipoConsulta=completa&tipoConteudo=XbSeqxE8pl8=" frameborder=0 ALLOWTRANSPARENCY="true"></iframe>

</body>
</html>