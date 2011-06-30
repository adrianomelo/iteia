<?php
include_once('classes/bo/NoticiaExibicaoBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
$notdao = new NoticiaDAO;
$ultimafoto = $notdao->getUltimaNoticiaComImagem();
$notbo = new NoticiaExibicaoBO;

$pagina = ($_GET['pagina'] ? (int)$_GET['pagina'] : 1);
//$pagina = (int)$_GET['pagina']; //lista invertida
$mostrar = ($_GET['mostrar'] ? (int)$_GET['mostrar'] : 50);
$inicial = ($pagina - 1) * $mostrar;

$classepagatual = ' class="local"';
$paginas = $notbo->getPaginasNoticias($pagina);
$noticias = $notbo->getGroupNoticiasDatas($pagina);
//echo $noticias;

//$paginacao = Util::paginacao($pagina, $mostrar, $noticias['total'], 'noticias.php?a=1');

$topo_class = 'cat-noticias';
$titulopagina = 'Jornal iTEIA';
$ativa = 6;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Jornal iTEIA</span></div>
	<h2 class="midia">Jornal <span class="azul">i</span>T<span class="verde">E</span><span class="amarelo">I</span><span class="preto">A</span></h2>
	<div id="rss"><a href="/feeds.php?formato=5" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
    <div id="conteudo" class="principal">
      <!--<h2 class="midia">Notícias</h2>-->
	  <div class="destaque">
      <small><?=date('d.m.Y - H\\hi', strtotime($ultimafoto["datahora"]))?></small><br />
          <div class="capa"><a href="<?=$ultimafoto['url']?>" title="Leia a matéria completa"><img src="exibir_imagem.php?img=<?=$ultimafoto['imagem']?>&tipo=1&s=33"  /></a></div>
          <h1><a href="<?=$ultimafoto['url']?>" title="Leia a matéria completa"><?=htmlentities($ultimafoto['titulo'])?></a></h1>
          <p><?=$ultimafoto['subtitulo']?></p>
          <hr class="separador3px" />
          </div>
<?php
$meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
foreach ($noticias as $key => $value):
    if (is_array($value)):
?>
	<h3 class="data"><span class="dia"><?=date('d', strtotime($key))?></span> de <span class="mes"><?=$meses[(int)date('m', strtotime($key))]?></span> de <span class="ano"><?=date('Y', strtotime($key))?></span></h3>
      <ul class="noticias-lista">
    <?php foreach ($value as $ind => $valueb): ?>
        <li<?=(!isset($value[$ind + 1]))?' class="no-border no-margin-b"':''?>><?=date('H\hi', strtotime($valueb['datahora']))?><!-- | <a href="canal.html" title="Listar conteúdos por canal">Mundo</a>-->
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
<?php include('includes/banners_lateral.php');?>
    </div>
<?php
include ('includes/rodape.php');
