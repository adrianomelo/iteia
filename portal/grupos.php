<?php
include_once('classes/bo/UsuarioBO.php');
$topo_class = 'cat-grupos';
$titulopagina = 'Grupos';
$ativa = 10;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Grupos</span></div>
    <div id="conteudo">
    <?php include('includes/canais.php');?>
      <h2 class="midia">Grupos</h2>
    </div>
<?php
include ('includes/rodape.php');
