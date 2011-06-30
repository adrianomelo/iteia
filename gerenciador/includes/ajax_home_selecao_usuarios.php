<fieldset>
	<legend>Resultado da busca</legend>
      <small>Selecione uma das op&ccedil;&otilde;es abaixo.</small><br />
      <select name="select2" class="select-list" size="6" id="codusuario_sec">
<?php
foreach ($lista_usuarios as $usuario):
	if ((int)$usuario["cod"]):
		echo "<option value=\"".$usuario["cod"]."\">".htmlentities($usuario["nome"])."</option>\n";
	endif;
endforeach;
?>
      </select>
      <br />
      <input type="button" value="Vincular" onclick="javascript:selecionarUsuario(<?=$this->dados_get['tipo'];?>);" class="bt-adicionar" />
</fieldset>