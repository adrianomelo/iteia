// funcoes para vinculação de colaboradores

function buscaColaboradoresRelacionamento() {
	var nome = $("#relacionar_palavrachave").val();
	$("#mostra_resultados_colaboradores_relacionamento").load("ajax_conteudo.php?get=buscar_colaborador&buscar=1&tipo=1&palavrachave="+nome+"&buscarpor=nome");
}

function vincularColaborador() {
	$('#mostra_resultados_colaboradores_relacionamento').load('ajax_conteudo.php?get=vincular_colaborador&cod_colaborador='+$("#codusuario").val()+'&cod_conteudo='+$("#codconteudo").val());
	document.location.reload();
}

function buscaColaboradoresRevisao() {
	var nome = $("#relacionar_palavrachave").val();
	$("#mostra_resultados_colaboradores_revisao").load("ajax_conteudo.php?get=buscar_colaborador&buscar=1&tipo=1&palavrachave="+nome+"&buscarpor=nome&revisao=1");
}

function adicionarColaboradorRevisao() {
	$('#mostra_lista_colaboradores_revisao').load('ajax_conteudo.php?get=adicionar_colaborador_revisao&colaboradores='+$('#lista_colaboradores').val());
}

function removerColaboradorRevisao(cod_usuario) {
	$('#mostra_lista_colaboradores_revisao').load('ajax_conteudo.php?get=remover_colaborador_revisao&cod_colaborador='+cod_usuario);
}

function aprovarColaborador(redir, codautor) {
	$('#lightbox').load('ajax_conteudo.php?get=aprovar_colaborador&codautor='+codautor);
	//window.location = 'ajax_conteudo.php?get=redirecionar_conteudo&cod_conteudo='+cod_conteudo;
	//if (redir == 1)
	//	window.location = 'index_lista_publica.php';
	//else if (redir == 2)
		window.location.href = 'index_lista_notificacao.php';
}

function reprovarColaborador() {
	$('#lightbox').load('ajax_conteudo.php?get=reprovar_colaborador&codautor='+$('#codautor').val()+'&comentario='+$('#textarea').val());
	window.location.href = 'index_lista_notificacao.php';
}


function carregarColaboradores() {
	$('#mostra_lista_colaboradores_revisao').load('ajax_conteudo.php?get=carregar_colaboradores_revisao');
}

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
