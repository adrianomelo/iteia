<strong>Situa&ccedil;&atilde;o:</strong>
<div class="box box-<?php if ((int)$contbo->getValorCampo('publicado') == 0) echo 'alerta'; elseif ((int)$contbo->getValorCampo('publicado') == 2) echo 'erro'; else echo 'dica';
?>">
	<h3><?=Util::iif(!(int)$contbo->getValorCampo('publicado'), 'Aguardando aprovação', Util::iif((int)$contbo->getValorCampo('publicado') == 1, 'Conte&uacute;do publicado.', 'Este conteúdo não foi aprovado.'));?></h3>
<?php if ($contbo->getValorCampo('publicado') == 0): // se o conteudo ainda não foi publicado
	if ($lista_colaborador_aprovacao):
?>
		<p>Esta obra foi enviada para aprovação dos <strong>colaboradores</strong></p>
<?php else: ?>
		<p>Esta obra foi enviada para <strong>lista pública de aprovação</strong></p>
<?php
	endif;
	if ($_SESSION['logado_dados']['nivel'] == 2): // autor
?>
	<!--
<p>Para ser publicado, esse conte&uacute;do ainda precisa d&aacute; autoriza&ccedil;&atilde;o de um  dos colaboradores que fazem parte do portal Pernambuco Na&ccedil;&atilde;o Cultural. </p>
      <p>1. Solicite a autoriza&ccedil;&atilde;o de um <a href="conteudo_enviar_colaboradores.php?cod=<?=$contbo->getValorCampo('codconteudo');?>"><strong>colaborador</strong></a> espec&iacute;fico. ou <br />
      2. Solicite  a autoriza&ccedil;&atilde;o de qualquer colaborador, enviando esse conte&uacute;do para a  <a href="conteudo_confirmar_lista_publica.php?height=100&amp;width=305&amp;modal=true&amp;cod=<?=$contbo->getValorCampo('codconteudo');?>" class="thickbox"><strong>Lista p&uacute;blica de autoriza&ccedil;&otilde;es</strong></a>.</p>
-->
<?php endif;?>
<?php if ($_SESSION['logado_dados']['nivel'] >= 5): // colab ?>
      <!--
Para que ele seja publicado <a href="conteudo_adicionar_autores.php?cod=<?=$contbo->getValorCampo('codconteudo');?>"><strong>adicione um autor</strong> </a><br />
-->
<?php
	endif;
elseif ((int)$contbo->getValorCampo('publicado') == 1):
?>
<p>Essa obra foi aprovada por <strong><?=$nome_colaborador_aprovacao;?></strong> <a href="<?=ConfigVO::URL_SITE.$contbo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">(Veja aqui)</a><br />
      </p>
<?php
endif;
if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): // se for admin pode vincular a um colaborador ?>
      <!--
Est&aacute; publicando para um colaborador? <a href="conteudo_colaborador_vincular.php?height=360&amp;width=305&amp;cod=<?=$contbo->getValorCampo('codconteudo');?>" title="Vincular a um colaborador"  class="thickbox"><strong>Vincular a um colaborador</strong></a>
-->
<?php endif; ?>
</div>