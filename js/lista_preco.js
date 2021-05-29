$(function(){
	$('#appendedInputButton').keyup(function(){
		var buscaTexto = $(this).val();
		if(buscaTexto.length >= 3){
			$.ajax({
				method: 'post',
				url: 'sys.php',
				data: {busca: 'sim', texto: buscaTexto},
				dataType: 'json',
				success: function(retorno){
					if(retorno.qtd == 0){
						$('#resultado_busca').html('<tr><td colspan="9">Não encontramos resultados para sua busca</td></tr>');
					}else{
						$('#resultado_busca').html(retorno.dados);
					}
				}
			});
		}
	});

	$('body').on('click', '#resultado_busca a', function(){
		var dadosProduto = $(this).attr('id');
		var splitDados = dadosProduto.split(':');

		$.ajax({
			method: 'post',
			url: 'sys.php',
			data: {add_produto: 'sim', produto: splitDados[0]},
			dataType: 'json',
			success: function(retorno){
				$('tbody#content_retorno').html(retorno.dados);
				$('#count-carrinho').html(retorno.dados);
				//$('#modalSucesso').openModal();
				alert( 'Adicionado com sucesso!' );
			}
		});
	});
	
	
	//inserir produto na tabela
	$('#form_add_item').submit(function(){
		var dados = $( this ).serialize();

		$.ajax({
			type: 'post',
			url: 'sys.php',
			data: dados,
			success: function(data){
				//alert( dados );
				//alert( 'Enviado com sucesso!' );
				$('.modal').modal('hide');//new
				$('#ModalSucesso').modal('show');
				//location.reload();
			}
		});
			
		return false;
	});
	//inserir produto na tabela
	
	
	$('body').on('click', '#remover_item a', function(){
		var dadosProduto = $(this).attr('id');
		var splitDados = dadosProduto.split(':');

		$.ajax({
			method: 'post',
			url: 'sys.php',
			data: {remov_produto: 'sim', produto: dadosProduto},
			dataType: 'json',
			success: function(retorno){
				//$('tbody#content_retorno').html(retorno.dados);
				$('#count-carrinho').html(retorno.dados);
				//$('#modalSucesso').openModal();
				alert( 'Removido com sucesso!' );
				location.reload();
			}
		});
	});
	
	/*Funcionamento Correto*/
	$('#update_item input').focusout( function(){
		var dadosProduto = $(this).attr('id');
		var qtdProduto = $(this).val();

		$.ajax({
			method: 'post',
			url: 'sys.php',
			data: {update_produto: 'sim', produto: dadosProduto, quantidade: qtdProduto},
			dataType: 'json',
			success: function(retorno){
				//$('tbody#content_retorno').html(retorno.dados);
				$('#count-carrinho').html(retorno.dados);
				//$('#modalSucesso').openModal();
				alert( 'Atualizado com sucesso!' );
				location.reload();
			}
		});
	});

	$('#finalizar').click( function(){

		$.ajax({
			method: 'post',
			url: 'sys.php',
			data: {finalizar: 'sim'},
			dataType: 'json',
			success: function(retorno){
				//$('#count-carrinho').html(retorno.dados);
				//$('#modalSucesso').openModal();
				alert( 'Finalizado com sucesso!' );
				location.assign("orcamentos.php?viewer=distribuidor");
			}
		});
	});

	
});