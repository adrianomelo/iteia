<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-regulamento iteia';
$titulopagina = 'Regulamento';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Regulamento</span></div>
    <div id="conteudo">
      <h2 class="midia">Regulamento</h2>
    </div>
<?php
include ('includes/rodape.php');
