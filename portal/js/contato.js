function enviarContato() {
   if(document.formcontato.mensagem.value==''){
		document.formcontato.mensagem.className='txt erro';
	} else {
		document.formcontato.mensagem.className='txt';
	}
    if(document.formcontato.nome.value==''){
	document.formcontato.nome.className='txt erro';
} else {
		document.formcontato.nome.className='txt';
}
    if(document.formcontato.email.value==''){
	document.formcontato.email.className='txt erro';
} else {
	document.formcontato.email.className='txt';
}
   
   $.post('/contatoajax.php', $('#form-contato').serialize(), function html(html) { $('#resposta_contato').html(html); limpaCamposContato(); });
	
	

}

function limpaCamposContato() {
    $('#sua-mensagem').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
}
