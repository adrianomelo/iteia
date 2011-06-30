<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codtipo = $_GET["cod"];

include_once("classes/bo/GrupoTipoBO.php");
$tipobo = new GrupoTipoBO;

if ($codtipo)
	$tipobo->setDadosCamposEdicao($codtipo);

$codtipo = (int)$tipobo->getValorCampo("codtipo");
?>
<script type="text/javascript" src="jscripts/jquery-1.2.1.pack.js"></script>

<form action="grupo_tipos.php" method="post" class="vaiagora" id="lightbox">
<fieldset>
	
<input type="hidden" name="editar" value="1" />
<input type="hidden" name="codtipo" value="<?=$codtipo?>" />
	
  <label for="textfield">Tipo<span> do grupo</span></label>
  <br />
  <input type="text" name="tipo" value="<?=htmlentities(stripslashes($tipobo->getValorCampo("tipo")))?>" <?=$tipobo->verificaErroCampo("tipo")?> class="txt" id="agaga" />
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
