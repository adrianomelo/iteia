function enviarDenuncia() {
    $.post('/denuncieajax.php', $('#form-contato').serialize(), function html(html) { $('#resposta_contato').html(html); limpaCamposContato(); });
	
		if(document.formdenuncie.mensagem.value==''){
		document.formdenuncie.mensagem.className='txt erro';
	} else {
		document.formdenuncie.mensagem.className='txt';
	}
	
    if(document.formdenuncie.nome.value==''){
	document.formdenuncie.nome.className='txt erro';
} else {
    document.formdenuncie.nome.className='txt';	
}
    if(document.formdenuncie.email.value==''){
	document.formdenuncie.email.className='txt erro';
} else {
	document.formdenuncie.email.className='txt';
}
}

function limpaCamposContato() {
    $('#denuncia').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
	
}
