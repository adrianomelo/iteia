function addEmailLista() {
	$.get('ajax/newsletter_listas_acoes.kmf?acao=adicionar&emails='+$('#slct').val(), function html(html) { $('#slct2').html(html); } );
}

function remEmailLista() {
	$.get('ajax/newsletter_listas_acoes.kmf?acao=remover&emails='+$('#slct2').val(), function html(html) { $('#slct2').html(html); } );
}

function carregaLista() {
	$('#slct2').load('ajax/newsletter_listas_acoes.kmf');
}

function addListaUsuario() {
	$.get('ajax_newsletter_usuarios_acoes.kmf?acao=adicionar&lista='+$('#select5').val(), function html(html) { $('#mostra_lista_usuario').html(html); } );
}

function removerListaUsuario(cod) {
	$.get('ajax_newsletter_usuarios_acoes.kmf?acao=remover&cod='+cod, function html(html) { $('#mostra_lista_usuario').html(html); } );
}

function carregaListaUsuario() {
	$('#mostra_lista_usuario').load('ajax_newsletter_usuarios_acoes.kmf');
}

function addListaParaEnvio() {
	$('#area5').val($('#area5').val() + '['+ $('#select5').val()+'],');
}




function buscaConteudoHome() {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=4&home=1", function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function removerItemPlayList(cod) {
	$("#mostra_selecionadas_homeconteudo").load("newsletter_ajax.php?get=executa_acao&numacao=1&itens=" + cod + "&codnewsletter=" + cod_newsletter);
}

function adicionarConteudoHome(cod) {
	$("#mostra_selecionadas_homeconteudo").load("newsletter_ajax.php?get=adicionar_conteudo_newsletter&cod=" + cod + "&codnewsletter=" + cod_newsletter);
}

function listarConteudoNewsletter() {
	$("#mostra_selecionadas_homeconteudo").load("newsletter_ajax.php?get=listar_conteudo_newsletter&codnewsletter=" + cod_newsletter);
}

function definirDestaque(cod) {
	if (cod) {
		$("#destaque").val(cod);
		$.get("newsletter_ajax.php?get=definir_destaque&coditem="+cod);
		listarConteudoNewsletter();
	}
}

function acaoNewsletter(num) {
	$('#acao').val(num);
	document.getElementById('lightbox_form').submit();
}

function executaAcaoHomeSelecionados(num) {
	var lista_checkboxes = $("input[@name=coditem]");
	var lista_marcados = new Array;
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$("#mostra_selecionadas_homeconteudo").load("newsletter_ajax.php?get=executa_acao&numacao=" + num + "&itens=" + lista_marcados.join(",") + "&codnewsletter=" + cod_newsletter);
}
