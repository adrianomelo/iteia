<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codnoticia = (int)$_GET['cod'];

include_once("classes/bo/NoticiaEdicaoBO.php");
$notbo = new NoticiaEdicaoBO;

if ($codnoticia) {
	$notbo->setDadosCamposEdicao($codnoticia);
	$colaborador = $notbo->getColaboradorConteudo($codnoticia);
	$conteudo_relacionado = $notbo->getConteudoRelacionado($codnoticia);
}

$contbo = &$notbo;

$item_menu = "noticias";
include('includes/topo.php');
?>
    <h2>Conte&uacute;do</h2>
    <h3 class="titulo">Notícia cadastrada</h3>
    <div class="box">
      <div id="exibe_conteudo" class="separador" >
        <h3><?=$notbo->getValorCampo('titulo')?></h3>
		<p><strong><?=$notbo->getValorCampo('subtitulo')?></strong> </p>
        <p><?=nl2br(Util::autoLink($notbo->getValorCampo('texto'), 'both', true));?></p>
<?php
	if ($notbo->getValorCampo('imagem_visualizacao')) {
?>
		<strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
        <img src="exibir_imagem.php?img=<?=$notbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" />
<?php
	}
?>
      </div>
<?php
	if ($notbo->getValorCampo('home')) {
?>
      <div class="separador">
        <h3>Chamada</h3>
        <p><strong><?=$notbo->getValorCampo('home_titulo')?></strong> </p>
        <p><?=nl2br($notbo->getValorCampo('home_resumo'))?></p>
<?php
		if ($notbo->getValorCampo('imagem_home')) {
?>
         <strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
        <img src="exibir_imagem.php?img=<?=$notbo->getValorCampo('imagem_home')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" />
<?php
		}
?>
      </div>
<?php
	}
?>
      <div id="autores" class="separador"><strong>Assinatura:</strong> <?=$notbo->getValorCampo('assinatura')?><br />
      <strong>Tags: </strong> <?=str_replace(';', ',', $notbo->getValorCampo('tags'));?><br />
        <strong>Publicado por:</strong> <a href="<?=ConfigVO::URL_SITE.$colaborador['titulo']?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste colaborador"><?=$colaborador['nome']?></a><br />
      <strong>Data de publica&ccedil;&atilde;o: </strong><?=$notbo->getValorCampo('data').' - '.$notbo->getValorCampo('hora')?></div>

      <div class="separador"><strong>Conte&uacute;dos relacionados: </strong>
        <ul>
        <?php foreach ($conteudo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['titulo'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>

      <a href="noticia_edicao.php?cod=<?=$codnoticia?>" title="Editar" class="bt">Editar</a>
</div>
    <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <li><a href="conteudo_relacionar.php?cod=<?=$codnoticia?>">Relacionar a outros conte&uacute;dos</a></li>
        <?php if ($notbo->getValorCampo('publicado') && ($notbo->getValorCampo('dataoriginal') <= date('Y-m-d H:i:s'))): ?>
        	<li><a href="<?=ConfigVO::URL_SITE.$notbo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>