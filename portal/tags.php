<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/TagsBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$tagsbo = new TagsBO;

$topo_class = 'iteia cat-tags';
$titulopagina = 'Tags';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Tags</span></div>
    <div id="conteudo">
      <h2 class="midia">Tags</h2>
        <p class="caption">Tags são etiquetas com as quais os colaboradores, grupos e autores marcam seus conteúdos para classificá-los da maneira que acharem mais interessante. Você pode buscar uma tag ou selecionar uma das populares para visualizar os conteúdos marcados com a tag escolhida.</p>
      <div id="tags">
        <div class="classificar">Classificar: <a href="/tags.php?ordem=1" class="on">Ordem alfabética</a> | <a href="/tags.php?ordem=2" class="off">Por tamanho</a></div>
        <div id="nuvem">
<?php
echo $tagsbo->getHtmlTagsPopulares(1000, $_GET['ordem']);
?>
		</div>
      </div>
    </div>
<?php
include ('includes/rodape.php');
