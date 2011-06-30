function buscaComentariosNavegacao(cod, carregar) {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	var mostrar = 10;
	
	if (getcookie('pag_comentario'))
		var mostrar = getcookie('pag_comentario');
	
	$.get("ajax_conteudo.php?get=buscar_comentarios&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&de="+de+"&ate="+ate+"&cod="+cod+"&carregar="+carregar+"&mostrar="+mostrar, function(html) { $("#mostra_resultados_comentarios").html(html); });
}

function buscaComentariosNavegacaoPopUp(cod, carregar) {
	var nome = $("#relacionar_palavrachave_popup").val();
	var por  = $("#relacionar_buscarpor_popup").val();
	var de = $("#relacionar_de_popup").val();
	var ate = $("#relacionar_ate_popup").val();
	
	$.get("ajax_conteudo.php?get=buscar_comentarios&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&de="+de+"&ate="+ate+"&cod="+cod+"&carregar="+carregar+"", function(html) { $("#mostra_resultados_comentarios").html(html); });
}

function mudaPagina(por) {
	$.get("ajax_conteudo.php?get=buscar_comentarios&buscar=1&palavrachave=&buscarpor="+por+"&de=&ate=&cod="+cod+"", function(html) { $("#mostra_resultados_comentarios").html(html); });
}

function submeteAcoesComentario(acao, url) {
	$('#mostra_resultados_comentarios').load(url+"&acao="+acao);
}

function submeteAcoesComentarioMulti(acao, url) {
	var lista_checkboxes = $("input[@name=codcomentario]");
	var lista_marcados = new Array;
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$('#mostra_resultados_comentarios').load(url+"&acao="+acao+"&cod="+cod+"&codcomentario="+lista_marcados.join(","));
}

function carregaComentariosIndex() {
	$('#mostra_resultados_comentarios').load("ajax_conteudo.php?get=mostrar_comentarios_index");
}

function acaoComentario(acao, coditem) {
	$('#mostra_resultados_comentarios').load("ajax_conteudo.php?get=mostrar_comentarios_index&acao="+acao+"&codcomentario="+coditem);
}


