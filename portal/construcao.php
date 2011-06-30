<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$js_busca = true;
$topo_class = 'denuncie';
$titulopagina = 'Em construção';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Em construção</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Em construção</h2>
        

      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div> </div>
<?php
include ('includes/rodape.php');
