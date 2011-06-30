<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/ComentariosBO.php');

$comentariobo = new ComentariosBO;
$acao = trim($_GET['acao']);

if ($acao == 'enviar') {
	try {
		$retorno=$comentariobo->inserirComentario($_POST);
		if($retorno!=1){ echo '<div class="aviso sucesso">'.utf8_encode('Seu comentário foi enviado e está aguardando aprovação.').'</div>'; } else { echo utf8_encode('<div class="aviso erro">Não foi possível enviar, por favor tente dentro de instantes.</div>'); } 
	} catch(Exception $e) {
		//echo utf8_encode('<div class="aviso erro">Não foi possível enviar, por favor tente dentro de instantes.</div>');
		echo utf8_encode('<script type="text/javascript">$(\'.aviso a\').click(function() {var target = $(this).attr("href");$(target).focus();return false;});</script><div class="aviso alerta">Por favor, preencha  os campos obrigatórios.<ul>'.implode('</li>', $comentariobo->getCamposErros()).'</ul></div>');
		}
	die;
}
elseif ($acao == 'carregar') {
	$codconteudo = (int)$_GET['cod'];
	$lista_comentarios = $comentariobo->getComentarios($codconteudo);
	$total_coment = count($lista_comentarios);
?>
	<h3 class="mais"><span>Este conte&uacute;do tem</span> <?=$total_coment?> Coment&aacute;rios</h3>
    <p class="msg">Neste espa&ccedil;o n&atilde;o ser&atilde;o permitidos coment&aacute;rios que contenham palavras de baixo cal&atilde;o, publicidade, cal&uacute;nia, inj&uacute;ria, difama&ccedil;&atilde;o ou qualquer conduta que possa ser considerada criminosa. A equipe do portal iTEIA reserva-se no direito de apagar as mensagens.</p>
<?php
	if ($total_coment) {
?>

    <ol>
		<?php
		foreach($lista_comentarios as $key => $comentario):
			$nome_usuario = $comentario['autor'];
			if ($comentario['site'])
				$nome_usuario = '<a href="http://'.$comentario['site'].'" target="_blank">'.$nome_usuario.'</a>';
		?>
        <li<?=((!isset($lista_comentarios[$key + 1])) ? ' class="no-border"' : '')?>>
			<p><strong><?=$nome_usuario?> comentou:</strong><br />
            <small>em <?=htmlentities(date('d.m.Y à\\s H:i', strtotime($comentario['data_cadastro'])));?></small></p>
			<p><?=nl2br($comentario['comentario'])?></p>
        </li>
		<?php endforeach; ?>
    </ol>
<?php
	}
}