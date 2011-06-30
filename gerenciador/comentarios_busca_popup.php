<form action="comentarios.php" method="get" id="lightbox" onsubmit="return false;">
  <fieldset>
  <label for="textfield">Palavras-chave</label>
  <br />
  <input name="text" type="text" class="txt" id="relacionar_palavrachave_popup"  />
  <br />
  <label for="type">Filtrar por</label>
  <br />
  <select id="relacionar_buscarpor_popup" name="buscarpor_popup">
          <option value="" selected="selected">Todos</option>
          <option value="comentario">Coment&aacute;rio</option>
          <option value="aprovado">Aprovado</option>
          <option value="rejeitado">Rejeitado</option>
          <option value="aguardando">Aguardando aprova&ccedil;&atilde;o</option>
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
  <input name="submit" type="submit" class="bt-buscar" value="Buscar" onclick="javascript:buscaComentariosNavegacaoPopUp(0, 1); tb_remove();" />
  </fieldset>
</form>