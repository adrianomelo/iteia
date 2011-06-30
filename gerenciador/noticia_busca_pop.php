<script type="text/javascript" src="jscripts/datepicker/ui.datepicker.js"></script>

<form action="" method="get" id="lightbox" onclick="return false;">
  <fieldset>
  <input type="hidden" name="buscar" value="1" />
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="text" type="text" class="txt" id="relacionar_palavrachave_popup"  />
  <br />
  <label for="type">Filtrar por</label>
  <br />
  <select name="select" id="relacionar_buscarpor_popup">
          <!--<option value="todos" selected="selected">Todos</option>-->
          <option value="titulo">T&iacute;tulo</option>
          <option value="colaborador">Colaborador</option>
  </select><br />
<label for="select2">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="relacionar_situacao_popup">
          <option value="" selected="selected">Todos</option>
          <option value="ativo">Ativo</option>
          <option value="inativo">Inativo</option>
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
  <input name="submit" type="submit" class="bt-buscar" id="bt_buscaconteudo" onclick="javascript:buscaNoticiaNavegacaoPopUp(1); tb_remove();" value="Buscar" />
  </fieldset>
</form>