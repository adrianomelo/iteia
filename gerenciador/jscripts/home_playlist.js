function buscaConteudoHome() {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=4&home=1", function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function removerItemPlayList(cod) {
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=executa_acao&numacao=1&itens=" + cod + "&codplaylist=" + cod_playlist);
}

function adicionarConteudoHome(cod) {
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=adicionar_conteudo_playlist&cod=" + cod + "&codplaylist=" + cod_playlist);
}

function listarConteudoHome() {
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=listar_conteudo_playlist&codplaylist=" + cod_playlist);
}

function homeMudaTempoConteudo(cod, obj) {
	var novotempo = obj.options[obj.selectedIndex].value;
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=mudar_tempo_playlist&cod=" + cod + "&tempo=" + novotempo + "&codplaylist=" + cod_playlist);
}

function executaAcaoHomeSelecionados(num) {
	var lista_checkboxes = $("input[@name=coditem]");
	var lista_marcados = new Array;
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=executa_acao&numacao=" + num + "&itens=" + lista_marcados.join(",") + "&codplaylist=" + cod_playlist);
}
