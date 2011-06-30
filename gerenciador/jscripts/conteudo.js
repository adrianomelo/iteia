// funcoes de conteudo

// buscar conteudo para relacionamento
function buscaConteudoParaRelacionamento() {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	var evento = $("#relacionar_evento").val();
	
	$('#box').show();

	//$("#mostra_resultados_relacionamento").load("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&relacionamento=1");

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&relacionamento=1"+"&evento="+evento, function html(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function buscaConteudoParaRelacionamentoPopUp() {
	var nome = $("#relacionar_palavrachave_popup").val();
	var por  = 'titulo';
	var tipo = $("#relacionar_formato_popup").val();
	var de = $("#relacionar_de_popup").val();
	var ate = $("#relacionar_ate_popup").val();

	$('#box').show();

	//$("#mostra_resultados_relacionamento").load("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&relacionamento=1");

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&relacionamento=1", function html(html) { $("#mostra_resultados_relacionamento").html(html); });
}

//function navegaConteudo(url, campo, pagina) {
//    $(campo).load(url+"&pagina="+pagina);
//}

function adicionarConteudoRelacionamento(cod_conteudo) {
	$('#mostra_lista_conteudo_relacionado').load('ajax_conteudo.php?get=adicionar_conteudo_relacionado&cod_conteudo='+cod_conteudo);
}

function removerConteudoRelacionamento(codconteudo) {
	$('#mostra_lista_conteudo_relacionado').load('ajax_conteudo.php?get=remover_conteudo_relacionado&cod_conteudo='+codconteudo);
}

//function removerConteudoRelacionamento() {
//	$('#mostra_lista_conteudo_relacionado').load('ajax_conteudo.php?get=remover_conteudo_relacionado&cod_conteudo='+$('#cod_conteudo_relacionado').val());
//}

function carregarConteudoRelacionamento() {
	$('#box').show();
	$('#mostra_lista_conteudo_relacionado').load('ajax_conteudo.php?get=carregar_conteudo_relacionado');
}

function enviarConteudoListaPublica(cod_conteudo) {
	$('#mostra_lista_conteudo_relacionado').load('ajax_conteudo.php?get=enviar_conteudo_listapublica&cod_conteudo='+cod_conteudo);
	$('#lightbox').hide();
	$('#confirmado').show();
}

function aprovarConteudo(redir, cod_conteudo) {
	document.location = 'index_exibir_notificacao.php?cod='+cod_conteudo+'&aprovar=1';
	//$('#lightbox').load('ajax_conteudo.php?get=aprovar_conteudo&cod_conteudo='+cod_conteudo);
	//window.location = 'ajax_conteudo.php?get=redirecionar_conteudo&cod_conteudo='+cod_conteudo;
	//if (redir == 1)
	//	window.location = 'index_lista_publica.php';
	//else if (redir == 2)
	//document.location = 'index_lista_notificacao.php';
}

function reprovarConteudo() {
	$('#lightbox').submit();
	//document.location = 'index_exibir_notificacao.php?cod='+$('#codconteudo').val()+'&comentario='+$('#textarea').val().html()+'&reprovar=1';
	//$('#lightbox').load('ajax_conteudo.php?get=reprovar_conteudo&cod_conteudo='+$('#codconteudo').val()+'&comentario='+$('#textarea').val());
	//document.location = 'index_lista_notificacao.php';
}

function buscaConteudoNavegacao(carregar) {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	var mostrar = 10;
	
	if (getcookie('pag_conteudo'))
		var mostrar = getcookie('pag_conteudo');

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=1&carregar="+carregar+"&mostrar="+mostrar, function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function buscaConteudoNavegacaoPopUp(carregar) {
	var nome = $("#relacionar_palavrachave_popup").val();
	var por  = 'titulo';
	var situacao  = $("#relacionar_situacao_popup").val();
	var tipo = $("#relacionar_formato_popup").val();
	var de = $("#relacionar_de_popup").val();
	var ate = $("#relacionar_ate_popup").val();

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&situacao="+situacao+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=1&carregar="+carregar, function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function buscaNoticiaNavegacao(carregar) {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = 5;
	var situacao  = $("#relacionar_situacao").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	var mostrar = 10;
	
	if (getcookie('pag_noticia'))
		var mostrar = getcookie('pag_noticia');

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&situacao="+situacao+"&de="+de+"&ate="+ate+"&navegacao=2&carregar="+carregar+"&mostrar="+mostrar, function(html) { $("#mostra_resultados_relacionamento").html(html); } );
}

function buscaNoticiaNavegacaoPopUp(carregar) {
	var nome = $("#relacionar_palavrachave_popup").val();
	var por  = $("#relacionar_buscarpor_popup").val();
	var tipo = 5;
	var situacao  = $("#relacionar_situacao_popup").val();
	var de = $("#relacionar_de_popup").val();
	var ate = $("#relacionar_ate_popup").val();

	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&situacao="+situacao+"&de="+de+"&ate="+ate+"&navegacao=2&carregar="+carregar, function(html) { $("#mostra_resultados_relacionamento").html(html); } );
}

function buscaAgendaNavegacao(carregar) {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = 6;
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	var mostrar = 10;
	
	if (getcookie('pag_agenda'))
		var mostrar = getcookie('pag_agenda');

	$("#mostra_resultados_relacionamento").load("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=3&carregar="+carregar+"&mostrar="+mostrar);
}

function buscaAgendaNavegacaoPopUp(carregar) {
	var nome = $("#relacionar_palavrachave_popup").val();
	var por  = $("#relacionar_buscarpor_popup").val();
	var tipo = 6;
	var de = $("#relacionar_de_popup").val();
	var ate = $("#relacionar_ate_popup").val();

	$("#mostra_resultados_relacionamento").load("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=3&carregar="+carregar);
}

function submeteAcoesConteudo(acao, campo, url) {
	var lista_checkboxes = $("input[@name=codconteudo]");
	var lista_marcados = new Array;

	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$.post("ajax_conteudo.php?get=conteudo_relacionado"+"&acao="+acao+"&codconteudo="+lista_marcados.join(","));
	$(campo).load(url+"&acao="+acao+"&codconteudo="+lista_marcados.join(","));
}

function associarGruposConteudo() {
	$.get("ajax_conteudo.php?get=associar_grupos&cod_conteudo="+$("#codconteudo").val()+"&grupos="+$("#lista_grupos").val());
	tb_remove();
	document.location.reload();
}

function showBoxVideo() {
	//$(".display-none").slideToggle("slow");
	$("#ytbox").slideToggle("slow");
	$("#anexo").slideToggle("slow");
}

/*
function getBuscaTag() {
	$("#mostra_resultados_tag").show();
	$.get("ajax_conteudo.php?get=buscar_tag&tag="+$('#tags').val(), function(html) { $("#mostra_resultados_tag").html(html); } );
}

function addTag(tag) {
	var tags_add = $('#hidden_tag').val();
	$('#tags').val(tags_add + tag + '; ');
	$('#hidden_tag').val(tags_add + tag + '; ');
	$("#mostra_resultados_tag").hide();
}
*/
