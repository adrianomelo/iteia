<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$coditem = (int)$_GET["cod"];
$pre = (int)$_GET["pre"];
$codusuario = (int)$_REQUEST["codu"];
$edicaodados = (int)$_POST["edicaodados"];

if ($coditem)
	$edicaodados = 1;

include_once("classes/bo/HomeItemEdicaoBO.php");
$homebo = new HomeItemEdicaoBO;
$exibir_form = true;

if ($editar) {
	try {
		$cod_item = $homebo->editar($_POST, $_FILES);
		$exibir_form = false;

		if (!$codusuario)
			Header("Location: home_playlist.php?pre=1");
		else
			Header("Location: home_destaque.php?pre=1&codu=".$codusuario);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$item_menu = 'home';
$item_submenu = 'destaque_home';
if ($codusuario) $item_submenu = 'destaque_usuario';
include('includes/topo.php');

if ($coditem && !$editar) {
	$homebo->setDadosCamposEdicao($coditem);
}

$coditem = (int)$homebo->getValorCampo("coditem");
?>
<script type="text/javascript" src="jscripts/edicao.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>

    <h2>Destaques da home</h2>

<?php if ($erro_mensagens || $homebo->verificaErroCampo("titulo") || $homebo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="home_item_edicao.php" method="post" enctype="multipart/form-data" name="form-noticias" id="form-noticias">
      <h3 class="titulo">Formul&aacute;rio de edi&ccedil;&atilde;o de chamada</h3>
      <div class="box">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="coditem" value="<?=$coditem?>" />
		<input type="hidden" name="codu" value="<?=$codusuario?>" />
		<input type="hidden" name="individual" value="1" />
	
          <label for="textfield6">T&iacute;tulo da chamada<span>*</span></label>
          <br />
          <input type="text" class="txt" id="textfield6" size="80" name="titulo" <?=$homebo->verificaErroCampo("titulo")?> maxlength="100" value="<?=htmlentities(stripslashes($homebo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_home_titulo', 100)" />
          <input type="text" class="txt counter" size="4" disabled="disabled" id="cont_home_titulo" />
          <br />
          <label for="textarea2">Chamada<span>*</span></label>
          <br />
          <textarea id="textarea2" cols="60" rows="10" name="resumo" <?=$homebo->verificaErroCampo("resumo")?> onkeyup="contarCaracteres(this, 'cont_home_resumo', 200)"><?=strip_tags(stripslashes($homebo->getValorCampo("resumo")))?></textarea>
          <input name="text" type="text" disabled="disabled" class="txt counter" size="4" id="cont_home_resumo" />
      </fieldset>
	 </div>

      <div id="botoes" class="box"> <a href="<?=Util::iif($codusuario, 'home_destaque.php?pre=1&codu='.$codusuario, 'home_playlist.php?pre=1');?>" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<?php if ($exibir_form): ?>
<script language="javascript" type="text/javascript">
contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
contarCaracteres(document.getElementById("textarea2"), "cont_home_resumo", 200);
</script>
<?php
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
