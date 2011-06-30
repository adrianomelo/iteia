<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codtag = $_GET["cod"];

include_once("classes/bo/TagBO.php");
$tagbo = new TagsBO;

if ($codtag)
	$tagbo->setDadosCamposEdicao($codtag);

$codtag = (int)$tagbo->getValorCampo("codtag");
?>
<script type="text/javascript" src="jscripts/jquery-1.2.1.pack.js"></script>

<form action="conteudo_tags.php" method="post" class="vaiagora" id="lightbox">
<fieldset>
	
<input type="hidden" name="editar" value="1" />
<input type="hidden" name="codtag" value="<?=$codtag?>" />
	
  <label for="textfield">Nome<span> da tag</span></label>
  <br />
  <input type="text" name="tag" value="<?=htmlentities(stripslashes($tagbo->getValorCampo("tag")))?>" <?=$tagbo->verificaErroCampo("tag")?> class="txt" id="agaga" />
  <br>
  <input type="button" value="Editar" class="bt-adicionar" onclick="javascript:checaTag();" />
  </fieldset>
</form>
</div>

<script language="javascript" type="text/javascript">
function checaTag() {
	if ($('#agaga').val() == '') {
		return false;
	} else {
		$('.vaiagora').submit();
		return true;
	}
}
</script>
