<script type="text/javascript" src="jscripts/datepicker/ui.datepicker.js"></script>

<form action="" method="get" id="lightbox" onsubmit="return false;">
  <fieldset>
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="text" type="text" class="txt" id="relacionar_palavrachave_popup"  />
  <br />
  <label for="type">Filtrar por</label>
  <br />
  <select id="relacionar_buscarpor_popup" name="buscarpor">
          <option value="titulo">T&iacute;tulo</option>
          <option value="local">Local</option>
          <option value="valor">Valor</option>
        </select><br />

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
  <input name="submit" type="submit" class="bt-buscar" value="Buscar" onclick="javascript:buscaAgendaNavegacaoPopUp(1); tb_remove();" />
  </fieldset>
</form>