function recomendar(cod_conteudo, cod_formato, tipo) {
	$('#voto'+tipo).load('/recomendar.php?c='+cod_conteudo+'&f='+cod_formato+'&t='+tipo);
}

function loadComentarios(cod_conteudo) {
    $('#carrega_comentarios').load('/comentarios.php?acao=carregar&cod='+cod_conteudo);
}

function enviarComentario() {
	if(document.formcomentario.comentario.value==''){
		document.formcomentario.comentario.className='txt erro';
	} else {
		document.formcomentario.comentario.className='txt';
	}

	if(document.formcomentario.nome.value==""){
		document.formcomentario.nome.className='txt erro';
	} else {
		document.formcomentario.nome.className='txt';
	}

	if(document.formcomentario.email.value==""){
		document.formcomentario.email.className='txt erro';
	} else {
		document.formcomentario.email.className='txt';
	}

    $.post('/comentarios.php?acao=enviar', $('#formcomentario').serialize(), function html(html) { $('#resposta_comentario').html(html); limpaCamposComentario(); loadComentarios($('#cod1').val()) });

}

function limpaCamposComentario() {
    $('#comentario').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
    $('#seu-site').val('');
}

function carregaImagemGaleria(cod_imagem) {
	//$('#carrega_imagem').load('/galeria.php?cod_imagem='+cod_imagem+'&cod_conteudo='+$('#cod1').val());
	$.get('/galeria.php?cod_imagem='+cod_imagem+'&cod_conteudo='+$('#cod1').val(), function html(html) {
		$('#carrega_imagem').html(html);
	});
}

function limparCss() {
	for (i = 1; i <= total_faixas; i++) {
		$('#faixa_'+i).removeClass('playing');
	}
}

