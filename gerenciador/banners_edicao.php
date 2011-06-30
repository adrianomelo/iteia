<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codbanner = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codbanner)
	$edicaodados = 1;

include_once("classes/bo/BannerEdicaoBO.php");
$bannerbo = new BannerEdicaoBO;
$exibir_form = true;

if ($editar) {
	try {
		$cod_banner = $bannerbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: banners_publicado.php?cod=".$cod_banner);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$bannerbo;

$paginatitulo = 'Inserir banner';
$item_menu = 'banners';
$item_submenu = 'inserir';
include('includes/topo.php');

if ($codbanner && !$editar) {
	$bannerbo->setDadosCamposEdicao($codbanner);
}

$codbanner = (int)$bannerbo->getValorCampo("codbanner");
?>

    <h2>Anúncios</h2>
    
<?php if ($erro_mensagens || $bannerbo->verificaErroCampo("titulo")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>
    
<?php if ($exibir_form): ?>
    <form action="banners_edicao.php" method="post" enctype="multipart/form-data" id="form-banner">
      <h3 class="titulo">Cadastro de anúncios</h3>
      <div class="box">
        <fieldset>
        
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codbanner" value="<?=$codbanner?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$bannerbo->getValorCampo('imgtemp')?>" />
        
        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />
        <input type="text" class="txt" id="textfield" <?=$bannerbo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($bannerbo->getValorCampo("titulo")))?>" name="titulo" size="80" />
        <br />
        <label for="textfield2">URL</label>
        <br />
        <input type="text" class="txt" id="textfield2" name="url" value="http://<?=htmlentities(stripslashes($bannerbo->getValorCampo("url")))?>" size="80" />
        <br />
        
        <label for="select">Prioridade</label>
        <br />
        <select id="select" name="prioridade">
          <option value="1" <?=Util::iif($bannerbo->getValorCampo("prioridade") == 1,'selected="selected"')?>>1 - Alta</option>
          <option value="2" <?=Util::iif($bannerbo->getValorCampo("prioridade") == 2,'selected="selected"')?>>2 - M&eacute;dia</option>
          <option value="3" <?=Util::iif($bannerbo->getValorCampo("prioridade") == 3,'selected="selected"')?>>3 - Baixa</option>
        </select>
<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
<p><input type="checkbox" value="1" name="home" id="checkbox" class="checkbox" <?=Util::iif($bannerbo->getValorCampo("home"), 'checked="checked"')?> />
        <label for="checkbox">Banner da home (administrador)</label>
       </p>
<?php endif; ?>
        
<div class="box-imagem">
    	<div class="visualizar-banner" id="div_imagem_exibicao">
<?php
	if ($bannerbo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$bannerbo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="180" height="150" alt="" />
<?php
	}
	else {
		if (!$bannerbo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/texto.jpg" id="imagem_exibicao" width="180" height="150" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$bannerbo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$bannerbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=39"  width="180" height="150" id="imagem_exibicao" />
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem_banner.php?tipo=banner&amp;cod=<?=$codbanner;?>&amp;height=200&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>
          
        </fieldset>
        <fieldset id="periodo">
        <legend>Per&iacute;odo</legend>
        <div>
          <label for="dFrom">De:</label>
          <input type="text" name="data_inicial" value="<?=htmlentities(stripslashes($bannerbo->getValorCampo("data_inicial")))?>" class="txt calendario date" id="dFrom" />
          <em><small>dd/mm/aaaa</small></em></div>
        <div>
          <label for="dTo">At&eacute;:</label>
          <input type="text" name="data_final" value="<?=htmlentities(stripslashes($bannerbo->getValorCampo("data_final")))?>" class="txt calendario date" id="dTo" />
          <em><small>dd/mm/aaaa</small></em></div>
        </fieldset>
      </div>
      <div id="botoes" class="box"> <a href="banners.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
