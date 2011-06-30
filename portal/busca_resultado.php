<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');

$id1 = trim($_GET['id1']);
$id2 = trim($_GET['id2']);
$id3 = trim($_GET['id3']);
$id4 = trim($_GET['id4']);
$id5 = trim($_GET['id5']);
$id6 = trim($_GET['id6']);
$id7 = trim($_GET['id7']);
$id8 = trim($_GET['id8']);
$id9 = trim($_GET['id9']);
$id10 = trim($_GET['id10']);

$pagina = (int)$_GET['pagina'];
if ($pagina <= 0) $pagina = 1;
$tipores = (int)$_GET['tipores'];
if (!$tipores) $tipores = 1;

$link_resultado = 'busca_resultado.php?id1='.$id1;

if ($id1) {
	include_once('classes/bo/BuscaiTeiaBO.php');
	if ($id1)
		$buscabo1 = new BuscaiTeiaBO($id1, Util::iif($tipores == 1, $pagina, 1));
	if ($id2) {
		$buscabo2 = new BuscaiTeiaBO($id2, Util::iif($tipores == 2, $pagina, 1));
		$link_resultado .= '&amp;id2='.$id2;
	}
	if ($id3) {
		$buscabo3 = new BuscaiTeiaBO($id3, Util::iif($tipores == 3, $pagina, 1));
		$link_resultado .= '&amp;id3='.$id3;
	}
	if ($id4) {
		$buscabo4 = new BuscaiTeiaBO($id4, Util::iif($tipores == 4, $pagina, 1));
		$link_resultado .= '&amp;id4='.$id4;
	}
	if ($id5) {
		$buscabo5 = new BuscaiTeiaBO($id5, Util::iif($tipores == 5, $pagina, 1));
		$link_resultado .= '&amp;id5='.$id5;
	}
	if ($id6) {
		$buscabo6 = new BuscaiTeiaBO($id6, Util::iif($tipores == 6, $pagina, 1));
		$link_resultado .= '&amp;id6='.$id6;
	}
	if ($id7) {
		$buscabo7 = new BuscaiTeiaBO($id7, Util::iif($tipores == 7, $pagina, 1));
		$link_resultado .= '&amp;id7='.$id7;
	}
	if ($id8) {
		$buscabo8 = new BuscaiTeiaBO($id8, Util::iif($tipores == 8, $pagina, 1));
		$link_resultado .= '&amp;id8='.$id8;
	}
	if ($id9) {
		$buscabo9 = new BuscaiTeiaBO($id9, Util::iif($tipores == 9, $pagina, 1));
		$link_resultado .= '&amp;id9='.$id9;
	}
	if ($id10) {
		$buscabo10 = new BuscaiTeiaBO($id10, Util::iif($tipores == 10, $pagina, 1));
		$link_resultado .= '&amp;id10='.$id10;
	}
}

switch ($tipores) {
	case 1: $buscabo = &$buscabo1; break;
	case 2: $buscabo = &$buscabo2; break;
	case 3: $buscabo = &$buscabo3; break;
	case 4: $buscabo = &$buscabo4; break;
	case 5: $buscabo = &$buscabo5; break;
	case 6: $buscabo = &$buscabo6; break;
	case 7: $buscabo = &$buscabo7; break;
	case 8: $buscabo = &$buscabo8; break;
	case 9: $buscabo = &$buscabo9; break;
	case 10: $buscabo = &$buscabo10; break;
}
		
if (!$buscabo->getTotal()) {
	header('location: busca_resultado_erro.php');
	exit();
}

$paginacao = Util::paginacao($pagina, $buscabo->getMostrar(), $buscabo->getTotal(), $link_resultado.'&amp;tipores='.$tipores);

$js_busca = true;
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'Resultado da busca';
include ('includes/topo.php');
?>
<div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Resultado de busca</span></div>
<div id="conteudo">
    <div class="principal">
		<h2 class="midia">Resultado de busca</h2>
        <div class="msg">Mostrando de <strong><?=$paginacao['inicio'];?></strong> a <strong><?=$paginacao['fim'];?></strong> de <strong><?=$buscabo->getTotal();?></strong> resultados encontrados<?php if ($buscabo->getPalavraChave()): ?> para a busca por <strong class="palavra">"<?=$buscabo->getPalavraChave()?>"</strong><?php endif; ?><?php if ($tag): ?> com a tag <strong class="palavra">"<?=$tag?>"</strong><?php endif; ?>.</div>
		<table cellspacing="0" cellpadding="0" border="0" id="resultado-busca">
			<tbody>
<?php
if ($buscabo->getTotal())
	echo $buscabo->exibeResultadoHtml();
?>

			</tbody>
        </table>
        <ul id="paginacao">
			<li id="anterior"><?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\" title=\"Anterior\">&laquo; Anterior</a>" : "<span>&laquo; Anterior</span>");?></li>
			<li id="item"><?=$paginacao['page_string'];?></li>
			<li id="proximo"><?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\" title=\"Próxima\">Próxima &raquo;</a> " : "<span>Próxima &raquo;</span>");?></li>
      </ul>
    </div>
    <div class="lateral">
        <div id="busca-filtro">
			<h3>Refine sua busca</h3>  
			<ul>
<?php
	if ($id1) {
		$titulo_item = 'Todos ('.$buscabo1->getTotal().')';
		if ($tipores == 1)
			echo "<li class=\"filtro\">".$titulo_item."</li>\n";
		else
			echo "<li><a href=\"".$link_resultado."&amp;tipores=1\">".$titulo_item."</a></li>\n";
	}
	if ($id2) {
		if ($buscabo2->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=2">Áudios ('.$buscabo2->getTotal().')</a>';
			if ($tipores == 2)
				echo "<li class=\"filtro\">Áudios (".$buscabo2->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id3) {
		if ($buscabo3->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=3">Vídeos ('.$buscabo3->getTotal().')</a>';
			if ($tipores == 3)
				echo "<li class=\"filtro\">Vídeos (".$buscabo3->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id4) {
		if ($buscabo4->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=4">Textos ('.$buscabo4->getTotal().')</a>';
			if ($tipores == 4)
				echo "<li class=\"filtro\">Textos (".$buscabo4->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id5) {
		if ($buscabo5->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=5">Imagens ('.$buscabo5->getTotal().')</a>';
			if ($tipores == 5)
				echo "<li class=\"filtro\">Imagens (".$buscabo5->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id6) {
		if ($buscabo6->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=6">Jornal ('.$buscabo6->getTotal().')</a>';
			if ($tipores == 6)
				echo "<li class=\"filtro\">Jornal (".$buscabo6->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id7) {
		if ($buscabo7->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=7">Eventos ('.$buscabo7->getTotal().')</a>';
			if ($tipores == 7)
				echo "<li class=\"filtro\">Eventos (".$buscabo7->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id8) {
		if ($buscabo8->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=8">Canais ('.$buscabo8->getTotal().')</a>';
			if ($tipores == 8)
				echo "<li class=\"filtro\">Canais (".$buscabo8->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id9) {
		if ($buscabo9->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=9">Autores ('.$buscabo9->getTotal().')</a>';
			if ($tipores == 9)
				echo "<li class=\"filtro\">Autores (".$buscabo9->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
	if ($id10) {
		if ($buscabo10->getTotal()) {
			$titulo_item = '<a href="'.$link_resultado.'&amp;tipores=10">Colaboradores ('.$buscabo10->getTotal().')</a>';
			if ($tipores == 10)
				echo "<li class=\"filtro\">Colaboradores (".$buscabo10->getTotal().")</li>\n";
			else
				echo "<li>".$titulo_item."</li>\n";
		}
	}
?>
			</ul>
        </div>
        <?php include('includes/banners_lateral.php');?>
    </div>
<?php
include ('includes/rodape.php');
