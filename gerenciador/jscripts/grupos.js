function adicionarIntegrante() {
	$.get('ajax_conteudo.php?get=adicionar_colaborador_integrantes&nome_integrante='+$('#nome_integrante').val(), function html(html) { $("#mostrar_colaborador_intergrantes").html(html); });
	$('#nome_integrante').val('');
}

function removerAutorIntegrante(codusuario) {
	$.get('ajax_conteudo.php?get=remover_colaborador_integrantes&cod_usuario='+codusuario, function html(html) { $("#mostrar_colaborador_intergrantes").html(html); });
}

function carregarIntegrantes() {
	$('#mostrar_colaborador_intergrantes').load('ajax_conteudo.php?get=carregar_colaborador_integrantes');
}

function definirResponsavelIntegrante(codusuario) {
	$.get('ajax_conteudo.php?get=definir_colaborador_responsavel_integrantes&cod_usuario='+codusuario, function html(html) { $("#mostrar_colaborador_intergrantes").html(html); });
}