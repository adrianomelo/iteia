function buscaConteudoHome() {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();
	
	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=4&home=1", function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function adicionarConteudoHome(cod) {
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=adicionar_conteudo_home&cod=" + cod + "&paginahome=" + pagina_home + "&posicao=" + posicao_home);
}

function homeMudaTempoConteudo(cod, obj) {
	var novotempo = obj.options[obj.selectedIndex].value;
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=mudar_tempo&cod=" + cod + "&tempo=" + novotempo + "&paginahome=" + pagina_home + "&posicao=" + posicao_home);
}

function listarConteudoHome() {
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=listar_conteudo_home&paginahome=" + pagina_home + "&posicao=" + posicao_home);
}

function executaAcaoHomeSelecionados(num) {
	var lista_checkboxes = $("input[@name=coditem]");
	var lista_marcados = new Array;
	var novasecao = $("#select4").val();
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=executa_acao&numacao=" + num + "&itens=" + lista_marcados.join(",") + "&paginahome=" + pagina_home + "&posicao=" + posicao_home + "&novasecao=" + novasecao);
}

function executaAcaoHomeConteudoUsuarioSelecionado(num) {
	var lista_checkboxes = $("input[@name=coditem]");
	var lista_marcados = new Array;
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}

	var cod_usuario = $("#codusuario").val();
	var tipousuario = $("#tipousuario").val();
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=executa_acao_conteudo&numacao=" + num + "&itens=" + lista_marcados.join(",") + "&tipousuario=" + tipousuario + "&codusuario=" + cod_usuario);
}

function ordenarItens() {
	if ($("#ordem1").is(":checked"))
		$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=ordena_data" + "&paginahome=" + pagina_home + "&posicao=" + posicao_home);
}

function buscaUsuariosVincular() {
	var tipo = $("#vincular_tipo").val();
	var nome = $("#vincular_palavrachave").val();
	$("#mostra_resultados_usuarios").load("home_ajax.php?get=buscar_usuario&buscar=1&tipo="+tipo+"&palavrachave="+nome+"&buscarpor=nome");
}

function selecionarUsuario(tipo) {
	switch (tipo) {
		case 1: tiponome = 'Colaborador'; break;
		case 2: tiponome = 'Autor'; break;
		case 3: tiponome = 'Grupo'; break;
	}
	
	$("#codusuario").val($("#codusuario_sec").val());
	$("#tipousuario").val(tipo);
	
	$("#publicado_por").html('<p>Este conte&uacute;do ser&aacute; publicado pelo ' + tiponome + ': <strong>'+ $("#codusuario_sec option:selected").text() + '</strong></p>');
	$("#add").show();
	$("#mostra_resultados_relacionamento").hide();
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=exibir_dadoshome_usuario&tipousuario=" + tipo + "&codusuario=" + $("#codusuario_sec").val());
	$("#adicionar").show();
	tb_remove();
}

function buscaConteudoHomeUsuario() {
	var nome = $("#relacionar_palavrachave").val();
	var por  = $("#relacionar_buscarpor").val();
	var tipo = $("#relacionar_formato").val();
	var de = $("#relacionar_de").val();
	var ate = $("#relacionar_ate").val();

	$("#mostra_resultados_relacionamento").show();
	$.get("ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave="+nome+"&buscarpor="+por+"&formato="+tipo+"&de="+de+"&ate="+ate+"&navegacao=5", function(html) { $("#mostra_resultados_relacionamento").html(html); });
}

function adicionarConteudoHomeUsuario(cod) {
	var cod_usuario = $("#codusuario").val();
	var tipousuario = $("#tipousuario").val();
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=adicionar_conteudo&cod=" + cod + "&tipousuario=" + tipousuario + "&codusuario=" + cod_usuario);
}

function homeMudaTempoConteudoUsuario(cod, obj) {
	var novotempo = obj.options[obj.selectedIndex].value;
	var cod_usuario = $("#codusuario").val();
	var tipousuario = $("#tipousuario").val();
	$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=mudar_tempo_usuario&cod=" + cod + "&tempo=" + novotempo + "&tipousuario=" + tipousuario + "&codusuario=" + cod_usuario);
}

function ordenarItensUsuario() {
	var cod_usuario = $("#codusuario").val();
	var tipousuario = $("#tipousuario").val();
	
	if ($("#ordem1").is(":checked"))
		$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=ordena_data_usuario" + "&paginahome=" + pagina_home + "&posicao=" + posicao_home + "&tipousuario=" + tipousuario + "&codusuario=" + cod_usuario);
}

function salvaListaHomeDefinitiva() {
	var lista_checkboxes = $("input[@name=coditem]");
	var lista_marcados = new Array;
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		lista_marcados.push(item.value);
	}

	$.get("home_ajax.php?get=executa_acao_salvarconteudo&itens=" + lista_marcados.join(","));
	$("#botao_salvar").hide();
}

function apagaItensIniciais() {
	$.get("home_ajax.php?get=executa_acao_apagarconteudoinicial");
}
