<?php
include('verificalogin.php');

include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];

$item_menu = "comentarios";
$item_submenu = "inicio";
$paginatitulo = "Coment&aacute;rios";
include('includes/topo.php');
?>

<script language="javascript" type="text/javascript">
var cod = <?=$codconteudo;?>;
</script>

    <h2>Coment&aacute;rios</h2>

	<script type="text/javascript" src="jscripts/comentarios.js"></script>
    
    <form action="comentarios.php" method="get" id="box-busca" onsubmit="return false;">
    <label for="textfield" class="display-none">Palavras-chave</label>
    <input type="hidden" name="buscar" value="1" />
    <input type="hidden" name="buscarpor" value="comentario" id="relacionar_buscarpor" />
      <input name="text" type="text" class="txt" id="relacionar_palavrachave"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" onclick="javascript:buscaComentariosNavegacao(0, 1);" />
      <a href="comentarios_busca_popup.php?height=280&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <div class="descricao">
      <?php if ($_SESSION['logado_dados']['nivel'] == 2): ?>
	  <p>Gerencie abaixo os coment&aacute;rios relacionados aos seus conte&uacute;dos</p>
      <?php else: ?>
      <p>Gerencie abaixo os coment&aacute;rios relacionados aos seus conte&uacute;dos ou vinculados ao(s) colaborador(es) que voc&ecirc; representa.</p>
      <?php endif; ?>
    </div>
    
    <!--
    <form action="comentarios.php" method="get" id="busca">
      <fieldset>
      <input type="hidden" name="buscar" value="1" />
      <legend>Buscar</legend>
        <div class="campos">
        <label for="textfield">Palavras-chave</label>
        <br />
        <input name="palavrachave" type="text" class="txt" id="relacionar_palavrachave"  />
      </div>
      <div class="campos">
        <label for="type">Filtrar por</label>
        <br />
        <select id="relacionar_buscarpor" name="buscarpor">
          <option value="" selected="selected">Todos</option>
          <option value="comentario">Coment&aacute;rio</option>
          <option value="aprovado">Aprovado</option>
          <option value="rejeitado">Rejeitado</option>
          <option value="aguardando">Aguardando aprova&ccedil;&atilde;o</option>
        </select>
      </div>
      <fieldset id="periodo">
      <legend class="seta">Buscar por per&iacute;odo</legend>
        <div class="fechada">
        <label for="dFrom">De:</label>
        <input name="de" type="text" class="txt calendario date" id="relacionar_de" />
        <em><small>dd/mm/aaaa</small></em>
        <label for="dTo">At&eacute;:</label>
        <input name="ate" type="text" class="txt calendario date" id="relacionar_ate" />
        <em><small>dd/mm/aaaa</small></em> </div>
      </fieldset>
        <input name="submit" type="button" class="bt-buscar" value="Buscar" id="bt_buscaconteudo" onclick="javascript:buscaComentariosNavegacao();" />
      </fieldset>
    </form>
    -->

	<form method="get" id="form-result" action="conteudo.php">
		<div id="mostra_resultados_comentarios"></div>
	</form>
	
  </div>

<script language="javascript" type="text/javascript">
buscaComentariosNavegacao(<?=$codconteudo;?>, 0);
</script>

<?php include('includes/rodape.php'); ?>