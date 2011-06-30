<div class="box">
<fieldset>
	<legend>Resultado da busca</legend>
      <small>Para selecionar mais de uma op&ccedil;&atilde;o, pressione a tecla Control (Ctrl) e clique sobre os itens escolhidos.</small><br />
      <select name="select2" class="select-list" size="6" multiple="multiple" id="lista_colaboradores">
<?php
foreach ($lista_colaboradores as $colaborador):
	if ((int)$colaborador["cod"]):
		echo "<option value=\"".$colaborador["cod"]."\">".htmlentities($colaborador["nome"])."</option>\n";
	endif;
endforeach;
?>
      </select>
      <br />
      <input type="button" value="Adicionar" onclick="javascript:adicionarColaboradorRevisao();" class="bt-adicionar" id="bt_adicionarautores" />
</fieldset>
</div>