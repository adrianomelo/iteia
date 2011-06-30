<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/CanalBO.php');
$canalbo = new CanalBO;
?>
<div id="conheca">
    <h2>Conheça a cultura do nosso país</h2>
    <ul>
<?php
$canais = $canalbo->getCanaisCultura();
$cont = 0;
foreach ($canais as $indice => $canal):
	  if($canal['dados']['nome']){
?>
	<li>
		<span><?=$canal['dados']['nome']?></span>
		<?php if ($canal['dados']['imagem']): ?>
			<a href="/canal.php?cod=<?=$canal['dados']['cod_segmento']?>" title="Ir para página deste canal"><img src="/exibir_imagem.php?img=<?=$canal['dados']['imagem']?>&amp;tipo=1&amp;s=35" alt="Capa do canal" width="140" height="105" /></a>
		<?php else: ?>
			<a href="/canal.php?cod=<?=$canal['dados']['cod_segmento']?>" title="Ir para página deste canal"><img src="/img/padrao/canal.jpg" alt="Capa do canal" width="140" height="105" /></a>
		<?php endif; ?>
	</li>
<?php
}
?>
<?php endforeach; ?>
    <li id="movimentos" class="no-margin-r">Existem muito mais movimentos culturais no Brasil, <a href="/canais" title="Ir para página de canais">comece por aqui</a></li>
    </ul>
</div>
