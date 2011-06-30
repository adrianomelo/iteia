<?php
include_once('classes/bo/UsuarioContatoBO.php');
$contatobo = new UsuarioContatoBO;

try {
	$retorno=$contatobo->enviaContato($_POST);
	if($retorno!=1){ echo '<div class="aviso sucesso">'.utf8_encode('Sua mensagem foi enviada.').'</div>'; } else {echo utf8_encode('<div class="aviso erro">Não foi possível enviar, por favor tente dentro de instantes.</div>'); } } catch(Exception $e) {
	
	echo utf8_encode('<script type="text/javascript">$(\'.aviso a\').click(function() {var target = $(this).attr("href");$(target).focus();return false;});</script><div class="aviso alerta">Por favor, preencha todos os campos obrigatórios.<ul><li>'.implode('</li><li>', $contatobo->getCamposErros()).'</li></ul></div>');
}
