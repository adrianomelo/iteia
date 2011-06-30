<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codagenda = (int)$_GET['cod'];

include_once("classes/bo/AgendaEdicaoBO.php");
$agendabo = new AgendaEdicaoBO;

if ($codagenda) {
	$agendabo->setDadosCamposEdicao($codagenda);
	$colaborador = $agendabo->getColaboradorConteudo($codagenda);
	$conteudo_relacionado = $agendabo->getConteudoRelacionado($codagenda);
}

$contbo = &$agendabo;

$item_menu = "agenda";
include('includes/topo.php');
?>
    <h2>Eventos</h2>
    <h3 class="titulo">Evento cadastrado</h3>
    <div class="box">
      <div id="exibe_conteudo" class="separador" > <span class="data"><?=$agendabo->getValorCampo('data_inicial').' - '.$agendabo->getValorCampo('hora_inicial')?></span>
        <h3><?=$agendabo->getValorCampo('titulo')?></h3>
        <p><?=nl2br(Util::autoLink($agendabo->getValorCampo('descricao'), 'both', true));?></p>
        <ul>
          <li><strong>Tags:</strong> <?=str_replace(';', ',', $agendabo->getValorCampo('tags'));?></li>
		  <li><strong>Local:</strong> <?=$agendabo->getValorCampo('local')?></li>
          <li><strong>Acesso:</strong> <?=$agendabo->getValorCampo('endereco')?></li>
          <li><strong>Cidade:</strong> <?=$agendabo->getValorCampo('cidade')?></li>
          <li><strong>Site/Hotsite:</strong> <?=$agendabo->getValorCampo('site')?></li>
          <li><strong>Telefone:</strong> <?=$agendabo->getValorCampo('telefone')?></li>
          <li><strong>Valor:</strong> <?=$agendabo->getValorCampo('valor')?></li>
          <li><strong>Data:</strong> <?=$agendabo->getValorCampo('data_inicial')?> at&eacute; <?=$agendabo->getValorCampo('data_final')?></li>
          <li><strong>Hor&aacute;rio:</strong> <?=$agendabo->getValorCampo('hora_inicial')?> &agrave;s <?=$agendabo->getValorCampo('hora_final')?></li>
        </ul>
<?php
	if ($agendabo->getValorCampo('imagem_visualizacao')) {
?>
		<strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
        <img src="exibir_imagem.php?img=<?=$agendabo->getValorCampo('imagem_visualizacao')?>&amp;tipo=1&amp;s=6" width="124" height="124" alt="" />
<?php
	}
?>
      </div>
      <div id="autores" class="separador"><strong>Publicado por:</strong> <a href="<?=ConfigVO::URL_SITE.$colaborador['titulo']?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste colaborador"><?=$colaborador['nome']?></a></div>

      <div class="separador"><strong>Conte&uacute;dos relacionados: </strong>
        <ul>
        <?php foreach ($conteudo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['titulo'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>

      <a href="agenda_edicao.php?cod=<?=$codagenda?>" title="Editar" class="bt">Editar</a>
</div>
    <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <li><a href="conteudo_relacionar.php?cod=<?=$codagenda?>">Relacionar a outros conte&uacute;dos</a></li>
        <?php if ($agendabo->getValorCampo('publicado')): ?>
        	<li><a href="<?=ConfigVO::URL_SITE.$agendabo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>