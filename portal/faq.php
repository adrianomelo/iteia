<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-faq iteia';
$titulopagina = 'Perguntas frequentes';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Perguntas frequentes</span></div>
    <div id="conteudo">
      <h2 class="midia">Perguntas frequentes</h2>
    </div>

      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>

<?php
include ('includes/rodape.php');
