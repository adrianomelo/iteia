<h3>Selecionar grupo</h3>
<p>Este conte&uacute;do tamb&eacute;m pode ser associado aos grupos em que os autores selecionados fazem parte. Selecione-os grupos na lista abaixo.</p>
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
      <input type="button" value="Associar" onclick="javascript:adicionarGruposConteudo();" class="bt-adicionar" id="bt_adicionarautores" />

