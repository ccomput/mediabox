<?php
require "../inc/security.php";
require "../inc/conect.php";
require "../inc/verifica.php";
require "../inc/functions.php";

//Variaveis de Transação
$imei	= $_GET['imei'];
$inicio	= $_GET['inicio'];
$fim	= $_GET['fim'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8" />
<title>Kraus & Naimer Portal - Mapa de Localização de Equipe</title>
<link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>
 
<body>
	<div id="header">
    	<img src="../img/logo.png" alt="Portal" />
	</div>
    <div id="info">
    <!--?php
    echo '
		<h2>Cliente: '.$nome_cliente.'</h2>
		<h3>Pedido: '.$pedido_cliente.'</h2>
		<h3>Chave de Acesso: '.$chave_de_acesso.'</h2>
		';
	?>-->
	</div>
    
	<div id="mapa" style="height: 600px; width: 70%; float:left;">
	</div>
    <div id="lista" style="height: 600px; width: 30%; float:left;">
    
	</div>
		<script>
        /**
 		* Created by: http://gustavopaes.net
		* Created on: Nov/2009
		* 
		* Retorna os valores de parâmetros passados via url.
		*
		* @param String Nome da parâmetro.
		
		http://gustavopaes.net/?sessid=NHJI89182JAIS
		var param_sessid = _GET("sessid");
		
		353201050642772
		*/
		function _GET(name){
			var url   = window.location.search.replace("?", "");
			var itens = url.split("&");
			for(n in itens){
				if(itens[n].match(name)){
					return decodeURIComponent(itens[n].replace(name+"=", ""));
				}
			}
			return null;
		}
        </script>
        
		<script src="js/jquery.min.js"></script>
 
        <!-- Maps API Javascript -->
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        
        <!-- Caixa de informação -->
        <script src="js/infobox.js"></script>
		
        <!-- Agrupamento dos marcadores -->
		<script src="js/markerclusterer.js"></script>
 
        <!-- Arquivo de inicialização do mapa -->
		<script src="js/mapa.js"></script>
    </body>
</html>