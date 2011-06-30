<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/ImagemExibicaoBO.php');

$imagembo = new ImagemExibicaoBO;
$codimagem = intval($_GET['cod_imagem']);
$codconteudo = intval($_GET['cod_conteudo']);

$navegacao = $imagembo->getNavegacaoGaleria($codconteudo, $codimagem);
if (count($navegacao)) {
?>
	<div id="visualizador">
		<?php if ($navegacao['atual']['credito']): ?>
		<div id="credito">Cr&eacute;dito: <?=htmlentities($navegacao['atual']['credito'])?></div>
		<?php endif; ?>
		<?php if ($navegacao['anterior']['cod_imagem']): ?>	
		<a href="javascript:;" onclick="javascript:carregaImagemGaleria(<?=$navegacao['anterior']['cod_imagem']?>);" class="anterior">Anterior</a>
		<?php endif; ?>
		<img src="/exibir_imagem.php?img=<?=$navegacao['atual']['imagem']?>&amp;tipo=2&amp;s=32" />
		<?php if ($navegacao['proxima']['cod_imagem']): ?>	
		<a href="javascript:;" onclick="javascript:carregaImagemGaleria(<?=$navegacao['proxima']['cod_imagem']?>);" class="proxima">Próxima</a>
		<?php endif; ?>
		<div id="numeracao">Imagem <?=$navegacao['indice']?> de <?=$navegacao['total']?></div>
		<?php if ($navegacao['atual']['legenda']): ?>	
		<div id="legenda"><?=htmlentities($navegacao['atual']['legenda'])?></div>
		<?php endif; ?>
    </div>
<?php
}
?>