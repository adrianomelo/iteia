<script type="text/javascript" src="jscripts/datepicker/ui.datepicker.js"></script>

<form action="" method="get" id="lightbox" onsubmit="return false;">
  <fieldset>
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="text" type="text" class="txt" id="relacionar_palavrachave_popup"  />
  <br />
  <label for="type">Filtrar por</label>
  <br />
  <select name="formato" id="relacionar_formato_popup">
          <option value="0" selected="selected">Todos</option>
          <option value="1">Texto</option>
          <option value="3">&Aacute;udio</option>
          <option value="4">V&iacute;deo</option>
          <option value="2">Imagem</option>
        </select><br />
<label for="select2">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="relacionar_situacao_popup">
          <option value="ativo" selected="selected">Ativo</option>
          <option value="inativo">Inativo</option>
          <option value="pendente">Pendente</option>
        </select>
  <fieldset id="periodo">
  <legend>Buscar por per&iacute;odo</legend>
  <label for="dFrom">De:</label>
  <input name="text" type="text" class="txt calendario date" id="relacionar_de_popup" />
  <em><small>dd/mm/aaaa</small></em>
  <label for="dTo">At&eacute;:</label>
  <input name="text" type="text" class="txt calendario date" id="relacionar_ate_popup" />
  <em><small>dd/mm/aaaa</small></em>
  </fieldset>
  <br />
  <input name="submit" type="submit" class="bt-buscar" onclick="javascript:buscaConteudoParaRelacionamentoPopUp(); tb_remove();" value="Buscar" />
  </fieldset>
</form>