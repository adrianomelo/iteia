<script type="text/javascript" src="jscripts/datepicker/ui.datepicker.js"></script>

<form id="lightbox" method="get" action="home.php">
  <fieldset>
  <input type="hidden" name="buscar" value="1" />
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="palavrachave" type="text" class="txt" id="textfield"  />
  <br />
  <!--<label for="type">Filtrar por</label>
  <br />
  <select name="select" id="type">
    <option value="4" selected="selected">Todas</option>
    <option value="0">Minhas playlists</option>
    <option value="1">Autor</option>
    <option value="2">Colaborador</option>
    <option value="3">Grupo</option>
  </select>-->
  <fieldset id="periodo">
  <legend>Buscar por per&iacute;odo</legend>
  <label for="dFrom">De:</label>
  <input name="de" type="text" class="txt calendario date" id="dFrom" />
  <em><small>dd/mm/aaaa</small></em>
  <label for="dTo">At&eacute;:</label>
  <input name="ate" type="text" class="txt calendario date" id="dTo" />
  <em><small>dd/mm/aaaa</small></em>
  </fieldset>
  <br />
  <input name="submit" type="submit" class="bt-buscar" value="Buscar" />
  </fieldset>
</form>