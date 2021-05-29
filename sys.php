<?php
session_start();

	if(isset($_POST['busca']) && $_POST['busca'] == 'sim'){
		include_once "connect_list.php";
		$textoBusca = strip_tags($_POST['texto']);
		//$buscar = $pdo->prepare("SELECT ukey, codigo, tipo, descricao, opcionais, tamanho, tensao, corrente, code, fator, valor_fator FROM `kn_lista_preco` WHERE `codigo` LIKE '%".$textoBusca."%' OR `descricao` LIKE '%".$textoBusca."%' ORDER BY codigo ASC");
		$buscar = $pdo->prepare("SELECT ukey, codigo, tipo, descricao, opcionais, tamanho, corrente, code, fator, (SELECT fator FROM `kn_fator` WHERE codigo = kn_lista_preco.fator) valor_fator FROM `kn_lista_preco` WHERE `codigo` LIKE '%".$textoBusca."%' OR `descricao` LIKE '%".$textoBusca."%' ORDER BY codigo ASC");
		$buscar->execute();

		$retorno = array();
		$retorno['dados'] = '';
		$retorno['qtd'] = $buscar->rowCount();

		
		if($retorno['qtd'] >= 0){
			while($conteudo = $buscar->fetchObject()){
				
				$ukey		= $conteudo->ukey;
				$codigo		= $conteudo->codigo;
				$tipo		= $conteudo->tipo;
				$descricao	= $conteudo->descricao;
				$opcionais	= $conteudo->opcionais;
				$tamanho	= $conteudo->tamanho;
				$corrente	= $conteudo->corrente;
				$code		= $conteudo->code;
				$fator		= $conteudo->valor_fator;
				
				if(substr($tipo,0,2) == 'KG'){
					$dolar	= 0.37496;
					$euro	= 0.281199;
				}else{
					$dolar	= 0.418482;
					$euro	= 0.313839;
				}
				
				if(preg_match('/.EG/i', $codigo) or preg_match('/.ER/i', $codigo)){
					
					//Tipos de Desconto Baseado no Tipo
					$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
					$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
					$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
					$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
					$desc65 = array("C32", "C42", "C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
					$desc66 = array("CB28");
					$desc70 = array("C26C", "CB28C", "C32C", "C43");
					
				}elseif(preg_match('/.E/i', $codigo)){
					
					//Tipos de Desconto Baseado no Tipo
					$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
					$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
					$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
					$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
					$desc65 = array("C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
					$desc66 = array("CB28");
					$desc70 = array("C26C", "CB28C", "C32", "C32C", "C42", "C43");
					
				}

				/* Desconto por Tipo de Chave*/
				if(in_array($tipo, $desc45)){
					$desconto_tipo = 0.55;
				}elseif(in_array($tipo, $desc55)){
					$desconto_tipo = 0.45;
				}elseif(in_array($tipo, $desc60)){
					$desconto_tipo = 0.40;
				}elseif(in_array($tipo, $desc64)){
					$desconto_tipo = 0.36;
				}elseif(in_array($tipo, $desc65)){
					$desconto_tipo = 0.35;
				}elseif(in_array($tipo, $desc66)){
					$desconto_tipo = 0.34;
				}elseif(in_array($tipo, $desc70)){
					$desconto_tipo = 0.30;
				}else{
					$desconto_tipo = 0.55;
				}
				
				/*Moeda
				0 - REAL
				1 - DOLAR
				2 - EURO
				*/
				$moeda = $_SESSION["moeda"];
				
				if($moeda == 1){
					$preco = $code*$fator*$dolar;
				}elseif($moeda == 2){
					$preco = $code*$fator*$euro;
				}else{
					$preco = $code*$fator*$desconto_tipo;
				}
				
				
				/*Retorno*/
				$retorno['dados'] .= '<tr>
											<td class="hidden-phone">'.$codigo.'</td>
											<td class="hidden-phone">'.$tipo.'</td>
											<td class="hidden-phone">'.utf8_encode($descricao).'</td>
											<td class="hidden-phone">'.$opcionais.'</td>
											<td class="hidden-phone">'.$tamanho.'</td>
											<td class="hidden-phone">'.$corrente.'</td>
											<td class="hidden-phone right">'.number_format($preco,2,',','.').'</td>
											<td class="hidden-phone"><a href="#" id="'.$ukey.'"><button class="btn btn-success"><i class="icon-shopping-cart"></i></button></a></td>
                        	    		</tr>';
			}
		}

		echo json_encode($retorno);
	}

	/*Adicionar Novo Produto*/
	if($_POST['novo_produto'] == 'yes'){
		include_once "connect_list.php";
		//$retorno = array();
		//$retorno['dados'] = '';
		$codigo = $_POST['codigo'];
		$tipo = $_POST['tipo'];
		$descricao = $_POST['descricao'];
		$opcionais = $_POST['opcionais'];
		$tamanho = $_POST['tamanho'];
		$corrente = $_POST['corrente'];
		$code = $_POST['code'];
		$fator = $_POST['fator'];
		
		$novoProduto = $pdo->prepare("INSERT INTO `kn_lista_preco` (codigo, tipo, descricao, opcionais, tamanho, corrente, code, fator) VALUES ('".$codigo."', '".$tipo."', '".$descricao."', '".$opcionais."', '".$tamanho."', '".$corrente."', '".$code."', '".$fator."')");
		$novoProduto->execute();
		
	}

	/*Adicionar Produto*/
	if(isset($_POST['add_produto'])){
		include_once "connect_list.php";
		$retorno = array();
		$retorno['dados'] = '';
		
		$produtoId = (int)$_POST['produto'];
		if(isset($_SESSION['carrinho'][$produtoId])){
			$_SESSION['carrinho'][$produtoId] += 1;
		}else{
			$_SESSION['carrinho'][$produtoId] = 1;
		}
		$total = 0;
		
		$retorno['dados'] .= '<li class="dropdown" id="header_notification_bar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-shopping-cart"></i>
								<span class="badge badge-warning">'.count($_SESSION['carrinho']).'</span>
							</a>
							<ul class="dropdown-menu extended notification">
								<li>
									<p>Você possui '.count($_SESSION['carrinho']).' itens no carrinho</p>
								</li>
							';
		
		foreach($_SESSION['carrinho'] as $idProd => $qtd){
			$pegaProduto = $pdo->prepare("SELECT ukey, codigo, tipo, descricao, opcionais, tamanho, corrente, code, fator, (SELECT fator FROM `kn_fator` WHERE codigo = kn_lista_preco.fator) valor_fator FROM `kn_lista_preco` WHERE `ukey` = '".$idProd."'");
			$pegaProduto->execute(array($idProd));
			$dadosProduto = $pegaProduto->fetchObject();
			
			$ukey 	= $dadosProduto->ukey;
			$codigo = $dadosProduto->codigo;
			$tipo 	= $dadosProduto->tipo;
			$code	= $dadosProduto->code;
			$fator	= $dadosProduto->valor_fator;
			
			if(substr($tipo,0,2) == 'KG'){
				$dolar	= 0.37496;
				$euro	= 0.281199;
			}else{
				$dolar	= 0.418482;
				$euro	= 0.313839;
			}
			
			if(preg_match('/.EG/i', $codigo) or preg_match('/.ER/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C32", "C42", "C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32C", "C43");
				
			}elseif(preg_match('/.E/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32", "C32C", "C42", "C43");
				
			}
			
			
			/* Desconto por Tipo de Chave*/
			if(in_array($tipo, $desc45)){
				$desconto_tipo = 0.55;
			}elseif(in_array($tipo, $desc55)){
				$desconto_tipo = 0.45;
			}elseif(in_array($tipo, $desc60)){
				$desconto_tipo = 0.40;
			}elseif(in_array($tipo, $desc64)){
				$desconto_tipo = 0.36;
			}elseif(in_array($tipo, $desc65)){
				$desconto_tipo = 0.35;
			}elseif(in_array($tipo, $desc66)){
				$desconto_tipo = 0.34;
			}elseif(in_array($tipo, $desc70)){
				$desconto_tipo = 0.30;
			}else{
				$desconto_tipo = 0.55;
			}
			
			/*Moeda
			0 - REAL
			1 - DOLAR
			2 - EURO
			*/
			$moeda = $_SESSION["moeda"];
			
			if($moeda == 1){
				$preco = $code*$fator*$dolar;
			}elseif($moeda == 2){
				$preco = $code*$fator*$euro;
			}else{
				$preco = $code*$fator*$desconto_tipo;
			}
			
			$subTotal = (number_format($preco,2, '.', '')*$qtd);
			$total += $subTotal;
			
			$retorno['dados'] .= '<li><a href="#" style="display:table; width:91%;"><div style="float:left; width:70%">'.$codigo.'</div><div style="float:left; width:30%; text-align:right;">'.$qtd.'</div></a></li>';
			
		}
		$retorno['dados'] .= '<li><p>Total: R$ '.number_format($total, 2, ',','.').'</p></li>
		<li><a href="carrinho.php?viewer=distribuidor">Ir para o carrinho <i class="icon-shopping-cart"></i></a></li>
		</ul></li>';
		echo json_encode($retorno);
	}
	
	
	/*Update Produto*/
	if(isset($_POST['update_produto'])){
		include_once "connect_list.php";
		$retorno = array();
		$retorno['dados'] = '';
		
		$produtoId = (int)$_POST['produto'];
		$produtoQtd = $_POST['quantidade'];
		if(isset($_SESSION['carrinho'][$produtoId])){
			$_SESSION['carrinho'][$produtoId] = $produtoQtd;
			//$_SESSION['carrinho'][$produtoId] += 1;
		}
		
		$total = 0;
		
		$retorno['dados'] .= '<li class="dropdown" id="header_notification_bar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-shopping-cart"></i>
								<span class="badge badge-warning">'.count($_SESSION['carrinho']).'</span>
							</a>
							<ul class="dropdown-menu extended notification">
								<li>
									<p>Você possui '.count($_SESSION['carrinho']).' itens no carrinho</p>
								</li>
							';
		
		foreach($_SESSION['carrinho'] as $idProd => $qtd){
			$pegaProduto = $pdo->prepare("SELECT ukey, codigo, tipo, descricao, opcionais, tamanho, corrente, code, fator, (SELECT fator FROM `kn_fator` WHERE codigo = kn_lista_preco.fator) valor_fator FROM `kn_lista_preco` WHERE `ukey` = '".$idProd."'");
			$pegaProduto->execute(array($idProd));
			$dadosProduto = $pegaProduto->fetchObject();
			
			$ukey 	= $dadosProduto->ukey;
			$codigo = $dadosProduto->codigo;
			$tipo 	= $dadosProduto->tipo;
			$code	= $dadosProduto->code;
			$fator	= $dadosProduto->valor_fator;
			
			if(substr($tipo,0,2) == 'KG'){
				$dolar	= 0.37496;
				$euro	= 0.281199;
			}else{
				$dolar	= 0.418482;
				$euro	= 0.313839;
			}
			
			if(preg_match('/.EG/i', $codigo) or preg_match('/.ER/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C32", "C42", "C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32C", "C43");
				
			}elseif(preg_match('/.E/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32", "C32C", "C42", "C43");
				
			}

			
			/* Desconto por Tipo de Chave*/
			if(in_array($tipo, $desc45)){
				$desconto_tipo = 0.55;
			}elseif(in_array($tipo, $desc55)){
				$desconto_tipo = 0.45;
			}elseif(in_array($tipo, $desc60)){
				$desconto_tipo = 0.40;
			}elseif(in_array($tipo, $desc64)){
				$desconto_tipo = 0.36;
			}elseif(in_array($tipo, $desc65)){
				$desconto_tipo = 0.35;
			}elseif(in_array($tipo, $desc66)){
				$desconto_tipo = 0.34;
			}elseif(in_array($tipo, $desc70)){
				$desconto_tipo = 0.30;
			}else{
				$desconto_tipo = 0.55;
			}

			
			/*Moeda
			0 - REAL
			1 - DOLAR
			2 - EURO
			*/
			$moeda = $_SESSION["moeda"];
			
			if($moeda == 1){
				$preco = $code*$fator*$dolar;
			}elseif($moeda == 2){
				$preco = $code*$fator*$euro;
			}else{
				$preco = $code*$fator*$desconto_tipo;
			}
			
			$subTotal = (number_format($preco,2, '.', '')*$qtd);
			$total += $subTotal;
			
			$retorno['dados'] .= '<li><a href="#" style="display:table; width:91%;"><div style="float:left; width:70%">'.$codigo.'</div><div style="float:left; width:30%; text-align:right;">'.$qtd.'</div></a></li>';
			
		}
		$retorno['dados'] .= '<li><p>Total: R$ '.number_format($total, 2, ',','.').'</p></li>
		<li><a href="carrinho.php?viewer=distribuidor">Ir para o carrinho <i class="icon-shopping-cart"></i></a></li>
		</ul></li>';
		echo json_encode($retorno);
	}
	
	

	/*Remove Produto*/
	if(isset($_POST['remov_produto']) && $_POST['remov_produto'] == 'sim'){
		include_once "connect_list.php";
		$retorno = array();
		$retorno['dados'] = '';
		
		$produtoId = (int)$_POST['produto'];
		if(isset($_SESSION['carrinho'][$produtoId])){
			unset($_SESSION['carrinho'][$produtoId]);
		}
		$total = 0;
		
		$retorno['dados'] .= '
		<li class="dropdown" id="header_notification_bar">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="icon-shopping-cart"></i>
				<span class="badge badge-warning">'.count($_SESSION['carrinho']).'</span>
			</a>
			<ul class="dropdown-menu extended notification">
				<li>
					<p>Você possui '.count($_SESSION['carrinho']).' itens no carrinho</p>
				</li>
		';
		
		foreach($_SESSION['carrinho'] as $idProd => $qtd){
			$pegaProduto = $pdo->prepare("SELECT ukey, codigo, tipo, descricao, opcionais, tamanho, corrente, code, fator, (SELECT fator FROM `kn_fator` WHERE codigo = kn_lista_preco.fator) valor_fator FROM `kn_lista_preco` WHERE `ukey` = '".$idProd."'");
			$pegaProduto->execute(array($idProd));
			$dadosProduto = $pegaProduto->fetchObject();
			
			$ukey 	= $dadosProduto->ukey;
			$codigo = $dadosProduto->codigo;
			$tipo 	= $dadosProduto->tipo;
			$code	= $dadosProduto->code;
			$fator	= $dadosProduto->valor_fator;
			
			if(substr($tipo,0,2) == 'KG'){
				$dolar	= 0.37496;
				$euro	= 0.281199;
			}else{
				$dolar	= 0.418482;
				$euro	= 0.313839;
			}
			
			if(preg_match('/.EG/i', $codigo) or preg_match('/.ER/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C32", "C42", "C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32C", "C43");
				
			}elseif(preg_match('/.E/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32", "C32C", "C42", "C43");
				
			}

			
			/* Desconto por Tipo de Chave*/
			if(in_array($tipo, $desc45)){
				$desconto_tipo = 0.55;
			}elseif(in_array($tipo, $desc55)){
				$desconto_tipo = 0.45;
			}elseif(in_array($tipo, $desc60)){
				$desconto_tipo = 0.40;
			}elseif(in_array($tipo, $desc64)){
				$desconto_tipo = 0.36;
			}elseif(in_array($tipo, $desc65)){
				$desconto_tipo = 0.35;
			}elseif(in_array($tipo, $desc66)){
				$desconto_tipo = 0.34;
			}elseif(in_array($tipo, $desc70)){
				$desconto_tipo = 0.30;
			}else{
				$desconto_tipo = 0.55;
			}

			
			/*Moeda
			0 - REAL
			1 - DOLAR
			2 - EURO
			*/
			$moeda = $_SESSION["moeda"];
			
			if($moeda == 1){
				$preco = $code*$fator*$dolar;
			}elseif($moeda == 2){
				$preco = $code*$fator*$euro;
			}else{
				$preco = $code*$fator*$desconto_tipo;
			}
			
			$subTotal = (number_format($preco,2, '.', '')*$qtd);
			$total += $subTotal;
			
			$retorno['dados'] .= '<li><a href="#" style="display:table; width:91%;"><div style="float:left; width:70%">'.$codigo.'</div><div style="float:left; width:30%; text-align:right;">'.$qtd.'</div></a></li>';
			
		}
		$retorno['dados'] .= '<li><p>Total: R$ '.number_format($total, 2, ',','.').'</p></li>
		<li><a href="carrinho.php?viewer=distribuidor">Ir para o carrinho <i class="icon-shopping-cart"></i></a></li>
		</ul></li>';
		echo json_encode($retorno);
	}
	
	/*Finalizar**********/
	if(isset($_POST['finalizar']) && $_POST['finalizar'] == 'sim'){
		include_once "connect_list.php";
		$retorno = array();
		$retorno['dados'] = '';
		
		$geraOrcamento = $pdo->prepare("INSERT INTO `kn_orcamentos` (user_ukey) VALUES (".$_SESSION['user_login'].")");
		$geraOrcamento->execute();
		//$ukey_orcamento = $geraOrcamento->lastInsertId();
		
		$pegaOrcamento = $pdo->prepare("SELECT ukey FROM `kn_orcamentos` WHERE user_ukey = '".$_SESSION['user_login']."' ORDER BY ukey DESC LIMIT 1");
		$pegaOrcamento->execute(array());
		$dadosOrcamento = $pegaOrcamento->fetchObject();
		$ukey_orcamento = $dadosOrcamento->ukey;
		
		$total = 0;
		
		foreach($_SESSION['carrinho'] as $idProd => $qtd){
			$pegaProduto = $pdo->prepare("SELECT ukey, codigo, tipo, code, (SELECT fator FROM `kn_fator` WHERE codigo = kn_lista_preco.fator) valor_fator FROM `kn_lista_preco` WHERE `ukey` = '".$idProd."'");
			$pegaProduto->execute(array($idProd));
			$dadosProduto = $pegaProduto->fetchObject();
			
			$ukey 	= $dadosProduto->ukey;
			$codigo = $dadosProduto->codigo;
			$tipo 	= $dadosProduto->tipo;
			$code	= $dadosProduto->code;
			$fator	= $dadosProduto->valor_fator;
			
			if(substr($tipo,0,2) == 'KG'){
				$dolar	= 0.37496;
				$euro	= 0.281199;
			}else{
				$dolar	= 0.418482;
				$euro	= 0.313839;
			}
			
			if(preg_match('/.EG/i', $codigo) or preg_match('/.ER/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C32", "C42", "C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32C", "C43");
				
			}elseif(preg_match('/.E/i', $codigo)){
				
				//Tipos de Desconto Baseado no Tipo
				$desc45 = array("CA4", "CB8", "CA10", "CA20", "KG250", "KG251", "KG252", "KG315", "KG316", "KG317");
				$desc55 = array("CB8B", "CA10B", "CA20B", "C315", "KG20A", "KG32A", "KG41", "KG64");
				$desc60 = array("KG125", "KG126", "KG127", "KG160", "KG161", "KG162");
				$desc64 = array("C26", "KG20B", "KG32B", "KG41B", "KG64B", "KG80", "KG100", "KG105", "A11", "A25");
				$desc65 = array("C80", "C125", "KG20", "KG20A", "KG20B", "KG32", "KG32A", "KG32B", "KG41", "KG161", "KG41B", "KG64", "KG64B", "KG80", "KG80C", "KG100", "KG100C", "KG105", "KG105C");
				$desc66 = array("CB28");
				$desc70 = array("C26C", "CB28C", "C32", "C32C", "C42", "C43");
				
			}

			
			/* Desconto por Tipo de Chave*/
			if(in_array($tipo, $desc45)){
				$desconto_tipo = 0.55;
			}elseif(in_array($tipo, $desc55)){
				$desconto_tipo = 0.45;
			}elseif(in_array($tipo, $desc60)){
				$desconto_tipo = 0.40;
			}elseif(in_array($tipo, $desc64)){
				$desconto_tipo = 0.36;
			}elseif(in_array($tipo, $desc65)){
				$desconto_tipo = 0.35;
			}elseif(in_array($tipo, $desc66)){
				$desconto_tipo = 0.34;
			}elseif(in_array($tipo, $desc70)){
				$desconto_tipo = 0.30;
			}else{
				$desconto_tipo = 0.55;
			}
	
			
			/*Moeda
			0 - REAL	1 - DOLAR	2 - EURO
			*/
			$moeda = $_SESSION["moeda"];
			
			if($moeda == 1){
				$preco = $code*$fator*$dolar;
			}elseif($moeda == 2){
				$preco = $code*$fator*$euro;
			}else{
				$preco = $code*$fator*$desconto_tipo;
			}
			
			$unitario = number_format($preco,2, '.', '');
			$subTotal = number_format($preco,2, '.', '')*$qtd;
			$total += $subTotal;
			
			//$retorno['dados'] .= "<li>".$ukey_orcamento.", ".$ukey.", ".$codigo.", ".$qtd.", ".$subTotal."</li>";
			
			$geraItem = $pdo->prepare("INSERT INTO `kn_orcamentos_itens` (ukey_orcamentos, ukey_lista_preco, codigo, quantidade, preco, total) VALUES ('".$ukey_orcamento."', '".$ukey."', '".$codigo."', '".$qtd."', '".$unitario."', '".$subTotal."')");
			$geraItem->execute();
			
		}
		
		$atuaOrcamento = $pdo->prepare("UPDATE `kn_orcamentos` SET total='".$total."' WHERE `user_ukey` = '".$_SESSION['user_login']."' AND `ukey` ='".$ukey_orcamento."'");
		$atuaOrcamento->execute();
		
		unset($_SESSION['carrinho']);
		$retorno['dados'] .= '';
		echo json_encode($retorno);
	}
?>