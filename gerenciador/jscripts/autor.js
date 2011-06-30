// funcoes para vinculação de autores

// buscar autores
function buscaAutoresRelacionamento() {
	var nome = $("#relacionar_palavrachave").val();
	$("#mostra_resultados_autores_relacionamento").load("ajax_conteudo.php?get=buscar_autor&buscar=1&tipo=2&palavrachave="+nome+"&buscarpor=nome");
}

//function navegaConteudo(url, campo, pagina) {
//    $(campo).load(url+"&pagina="+pagina);
//}

function adicionarAutor() {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=adicionar_autor&autores='+$('#lista_autores').val());
}

function removerAutor(cod_autor) {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=remover_autor&cod_autor='+cod_autor);
}

function carregarAutores() {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=carregar_autores');
}

function mudaCampoLogin() {
	if ($('#tipo_autor').val() == 1)
		$('#box_login').hide();
	else
		$('#box_login').show();
}

function aprovarAutor(redir, codautor) {
	$('#lightbox').load('ajax_conteudo.php?get=aprovar_autor&codautor='+codautor);
	//window.location = 'ajax_conteudo.php?get=redirecionar_conteudo&cod_conteudo='+cod_conteudo;
	//if (redir == 1)
	//	window.location = 'index_lista_publica.php';
	//else if (redir == 2)
		window.location.href = 'index_lista_notificacao.php';
}

function reprovarAutor() {
	$('#lightbox').load('ajax_conteudo.php?get=reprovar_autor&codautor='+$('#codautor').val()+'&comentario='+$('#textarea').val());
	window.location.href = 'index_lista_notificacao.php';
}

function submeteUnificacao() {
	var lista_checkboxes = $("input[@id=codusuario]");
	var lista_marcados = new Array;
	var total = "";
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)) {
			lista_marcados.push(item.value);
			total++;
		}
	}

	if (total != 2)
		alert('Selecione 2 registros do tipo Autor ou Wiki');
	else if (($('#tipoautor_' + lista_marcados[0]).val() == 1) || ($('#tipoautor_' + lista_marcados[1]).val() == 1))
		alert('Selecione apenas registros do tipo Autor ou Wiki');
	else
		window.location.href = 'cadastro_autores_unificar.php?cod1='+lista_marcados[0]+'&cod2='+lista_marcados[1];
}



