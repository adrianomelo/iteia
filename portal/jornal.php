<?php
include_once('classes/bo/NoticiaExibicaoBO.php');
$notbo = new NoticiaExibicaoBO;

include_once('classes/bo/HomeExibicaoBO.php');
$homebo = new HomeExibicaoBO;

$pagina = ($_GET['pagina'] ? (int)$_GET['pagina'] : 1);
$mostrar = ($_GET['mostrar'] ? (int)$_GET['mostrar'] : 50);
$inicial = ($pagina - 1) * $mostrar;

$classepagatual = ' class="local"';
$paginas = $notbo->getPaginasNoticias($pagina);
$noticias = $notbo->getGroupNoticiasDatas($pagina);
$noticiadestaque = $homebo->getNoticiaDestaque();

$topo_class = 'cat-noticias';
$titulopagina = 'Jornal iTEIA';
$ativa = 6;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Jornal iTEIA</span></div>
	<h2 class="midia">Jornal <span class="azul">i</span>T<span class="verde">E</span><span class="amarelo">I</span><span class="preto">A</span></h2>
	<div id="rss"><a href="/feeds.php?formato=5" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
    <div id="conteudo" class="principal">
<?php
echo $noticiadestaque;
$meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
foreach ($noticias as $key => $value):
    if (is_array($value)):
?>
	<h3 class="data"><span class="dia"><?=date('d', strtotime($key))?></span> de <span class="mes"><?=$meses[(int)date('m', strtotime($key))]?></span> de <span class="ano"><?=date('Y', strtotime($key))?></span></h3>
      <ul class="noticias-lista">
    <?php foreach ($value as $ind => $valueb): ?>
        <li<?=(!isset($value[$ind + 1]))?' class="no-border no-margin-b"':''?>><?=substr($valueb['periodo'], 12, 3)?>h<?=substr($valueb['periodo'], 16, 6)?>
          <h1><a href="/<?=$valueb['url'];?>" title="Ir para página deste conteúdo"><?=$valueb['titulo']?></a></h1>
        </li>
    <?php endforeach; ?>
      </ul>
<?php
    endif;
endforeach;
echo $paginas;
?>
    </div>
    <div class="lateral">
<?php
include('includes/banners_lateral.php');
?>
</div>
<?php
include ('includes/rodape.php');
