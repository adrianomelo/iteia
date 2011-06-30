<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codbanner = (int)$_GET['cod'];

include_once("classes/bo/BannerEdicaoBO.php");
$bannerbo = new BannerEdicaoBO;

if ($codbanner) {
	$bannerbo->setDadosCamposEdicao($codbanner);
}

$item_menu = "banners";
	
include('includes/topo.php');
?>
    <h2>Anúncios</h2>

    <h3 class="titulo">Anúncio cadastrado </h3>
    <div class="box">
      <div id="exibe_conteudo" class="separador" >
        <ul>
          <li><strong>T&iacute;tulo: </strong><?=$bannerbo->getValorCampo('titulo');?></li>
          <li><strong>URL:</strong> <?=Util::iif($bannerbo->getValorCampo('url'), "<a href=\"http://".$bannerbo->getValorCampo('url')."\">http://".$bannerbo->getValorCampo('url')."</a>", '&nbsp;');?></li>
          <li><strong>Prioridade:</strong>
		  <?php
		  	switch ($bannerbo->getValorCampo('prioridade')) {
				case 1: echo 'Alta'; break;
				case 2: echo 'M&eacute;dia'; break;
				case 3: echo 'Baixa'; break;
			}
			?>
		  </li>
          <li><strong>Per&iacute;odo:</strong> <?=Util::iif($bannerbo->getValorCampo('data_inicial'), $bannerbo->getValorCampo('data_inicial'));?><?=Util::iif($bannerbo->getValorCampo('data_final'), ' at&eacute; '.$bannerbo->getValorCampo('data_final'));?></li>
        </ul>
      </div>
      
      <div class="separador">
      <strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
          <img src="exibir_imagem.php?img=<?=$bannerbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=39" width="180" height="150" />
      </div>
      <div id="autores" class="separador"><strong>Publicado por:</strong> <?=$bannerbo->getValorCampo('colaborador');?></div>
      <a href="banners_edicao.php?cod=<?=$codbanner;?>" title="Editar" class="bt">Editar</a> </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
