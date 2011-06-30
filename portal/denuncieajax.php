<?php
include_once('classes/bo/DenunciaBO.php');
$contatobo = new DenunciaBO;

try {
	$retorno=$contatobo->enviaDenuncia($_POST);
	if($retorno!=1){ echo '<div class="aviso sucesso">'.utf8_encode('Sua den�ncia foi enviada.').'</div>';  } else { echo utf8_encode('<div class="aviso erro">N�o foi poss�vel enviar, por favor tente dentro de instantes.</div>'); } 
} catch(Exception $e) {
	//echo utf8_encode('<div class="aviso erro">N�o foi poss�vel enviar, por favor tente dentro de instantes..</div>');
	echo utf8_encode('<script type="text/javascript">$(\'.aviso a\').click(function() {var target = $(this).attr("href");$(target).focus();return false;});</script><div class="aviso alerta" id="aviso">Por favor, preencha todos os campos obrigat�rios.<ul><li>'.implode('</li><li>', $contatobo->getCamposErros()).'</li></ul></div>');
}
