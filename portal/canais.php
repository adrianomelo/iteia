<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/CanalBO.php');
$canalbo = new CanalBO;

$topo_class = 'cat-canais';
$titulopagina = 'Canais';
$ativa = 1;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Canais</span></div>
    <div id="conteudo">
      <h2 class="midia">Canais</h2>
      <div id="recentes">
        <h3 class="mais"><span>Canais</span> Mais Ativos</h3>
        <div class="conteudo-lista">
<?php
$temcount = 1;
$colspan = 5;
$canais = $canalbo->getCanaisAtivos();
$i=0;

foreach ($canais as $indice => $canal) {

	if($canal['dados']['cod_segmento']){
	
	$temul = false;
	if ($temcount == 1)
		echo '<ul>';
	echo '<li'.($temcount == 1 ? ' class="no-border"':'').'>';
	
	echo '<div class="capa">';
	if ($canal['dados']['imagem'])
		echo '<a href="/canal.php?cod='.$canal['dados']['cod_segmento'].'" title="Ir para página deste canal"><img src="/exibir_imagem.php?img='.$canal['dados']['imagem'].'&amp;tipo=1&amp;s=35" alt="Capa do canal" width="140" height="105" /></a>';
	echo '</div>';
		
	echo '<h1><a href="/canal.php?cod='.$canal['dados']['cod_segmento'].'" title="Ir para página deste canal">'.$canal['dados']['nome'].'</a></h1>';
    //echo '<a href="/busca_resultado.php?buscar=1&amp;novabusca=1&amp;canal='.$canal['dados']['cod_segmento'].'&amp;audios=1&amp;videos=1&amp;textos=1&amp;imagens=1" title="Listar todo conteúdo deste canal" class="info">'.$canal['total'].' conteúdos</a>';
	
	echo '<a href="/busca_action.php?buscar=1&amp;canal='.$canal['dados']['cod_segmento'].'&amp;formatos=2,3,4,5" title="Listar todo conteúdo deste canal" class="info">'.$canal['total'].' conteúdos</a>';
	
	echo '</li>';

	if ($temcount == $colspan):
    	$temcount -= $colspan;
		echo '</ul>';
	if (isset($canais[$indice + 1]))
		echo '<div class="hr separador3px"><hr /></div>';
		$temul = true;
	endif;
	$temcount++;
}

}
if (!$temul) echo '</ul>';


?>
          <!--<div class="todos"><a href="/busca_resultado.php?buscar=1&amp;novabusca=1&amp;canais=1&amp;can=1&amp;td=1" title="Listar canais"><strong>Ver todos</strong></a></div>-->
		  <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=8" title="Listar canais"><strong>Ver todos</strong></a></div>
        </div>
      </div>
	</div>
<?php
include ('includes/rodape.php');
