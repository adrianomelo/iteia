<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$js_busca = true;
$topo_class = 'denuncie';
$titulopagina = 'Em constru��o';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Em constru��o</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Em constru��o</h2>
        

      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div> </div>
<?php
include ('includes/rodape.php');
