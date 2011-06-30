<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codatividade = $_GET["cod"];

include_once("classes/bo/AtividadeBO.php");
$ativbo = new AtividadeBO;

if ($codatividade)
	$ativbo->setDadosCamposEdicao($codatividade);

$codatividade = (int)$ativbo->getValorCampo("codatividade");
?>
<script type="text/javascript" src="jscripts/jquery-1.2.1.pack.js"></script>

<form action="conteudo_atividades.php" method="post" class="vaiagora" id="lightbox">
<fieldset>
	
<input type="hidden" name="editar" value="1" />
<input type="hidden" name="codatividade" value="<?=$codatividade?>" />
	
  <label for="textfield">Nome<span> da atividade</span></label>
  <br />
  <input type="text" name="atividade" value="<?=htmlentities(stripslashes($ativbo->getValorCampo("atividade")))?>" <?=$ativbo->verificaErroCampo("atividade")?> class="txt" id="agaga" />
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
