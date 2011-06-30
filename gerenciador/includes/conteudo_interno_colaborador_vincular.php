<?php
include_once("classes/bo/PrincipalBO.php");
$indexbo = new PrincipalBO;
?>
<div class="box">
        <fieldset>
        <legend>Qual colaborador voc&ecirc; quer vincular esse conte&uacute;do?<span>*</span></legend>
        <select <?=$contbo->verificaErroCampo("colaborador")?> name="codcolaborador" id="contas">
          <option value="0">Escolha entre os colaboradores que voc&ecirc; representa</option>
          <?php foreach($indexbo->getListaColaboradoresEdicao() as $value): ?>
          		<option value="<?=$value['cod_usuario'];?>" <?=Util::iif($contbo->getValorCampo('codcolaborador') == $value['cod_usuario'], 'selected="selected"');?>><?=$value['nome'];?></option>
          <?php endforeach; ?>
        </select>
        </fieldset>
      </div>