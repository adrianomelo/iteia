<div class="box">
<fieldset>
	<legend>Resultado da busca</legend>
      <small>Para selecionar mais de uma op&ccedil;&atilde;o, pressione a tecla Control (Ctrl) e clique sobre os itens escolhidos.</small><br />
      <select name="select2" class="select-list" size="6" multiple="multiple" id="lista_autores">
<?php
foreach ($lista_autores as $autor):
	if ((int)$autor["cod"]):
		echo "<option value=\"".$autor["cod"]."\">".htmlentities($autor["nome"])."</option>\n";
	endif;
endforeach;
?>
      </select>
      <br />
      <input type="button" value="Adicionar" onclick="javascript:adicionarAutor();" class="bt-adicionar" id="bt_adicionarautores" />
</fieldset>
</div>
<?php if (count($lista_autores) == 2): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>