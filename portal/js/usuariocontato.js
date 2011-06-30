function enviarContato() {
    $.post('/usuario_contatoajax.php', $('#form-contato').serialize(), function html(html) { $('#resposta_contato').html(html); limpaCamposContato(); });
	if(document.formcontato.mensagem.value==''){
		document.formcontato.mensagem.className='txt erro';
	}
    if(document.formcontato.nome.value==''){
	document.formcontato.nome.className='txt erro';
}
    if(document.formcontato.email.value==''){
	document.formcontato.email.className='txt erro';
}

}

function limpaCamposContato() {
    $('#denuncia').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
}
