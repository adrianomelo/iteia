<form action="cadastro.php" method="get" id="lightbox">
  <fieldset>
  <input type="hidden" name="buscar" value="1" />
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="palavrachave" type="text" class="txt" id="textfield"  />
  <br />
  <label for="type">Filtrar por</label>
  <br />
  <select id="type" name="buscarpor">
          <option value="nome">Nome</option>
          <option value="estado">Estado</option>
          <option value="wiki">Wiki</option>
          <option value="autor">Autor</option>
          <option value="colaborador">Colaborador</option>
        </select><br />

  <label for="select2">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="select" name="situacao">
          <option value="0" selected="selected">Todos</option>
          <option value="3">Ativo</option>
          <option value="2">Inativo</option>
          <option value="1">Pendente</option>
        </select>
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