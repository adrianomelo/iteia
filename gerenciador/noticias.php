<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "noticias";
$item_submenu = "inicio";
$paginatitulo = "Not&iacute;cias";
include('includes/topo.php');
?>
    <h2>Not&iacute;cias</h2>

	<script type="text/javascript" src="jscripts/conteudo.js"></script>
    
    <form action="noticias.php" method="get" id="box-busca" onclick="return false;">
    	<input type="hidden" name="buscar" value="1" />
    	<input type="hidden" name="buscarpor" value="titulo" id="relacionar_buscarpor" />
      	<label for="textfield" class="display-none">Palavras-chave</label>
      	<input name="text" type="text" class="txt" id="relacionar_palavrachave" onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      	<input type="image" onclick="javascript:buscaNoticiaNavegacao(1);" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      	<a href="noticia_busca_pop.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <p class="descricao"><a href="noticia_edicao.php">Publique not&iacute;cias</a> sobre os mais variados acontecimentos relacionados ao(s) colaborador(es) que voc&ecirc; representa ou aos autores vinculados a ele. As not&iacute;cias aparecer&atilde;o na home e na p&aacute;gina do(s) colaborador(es) no portal. <br />
      <br />
    <small>OBS: not&iacute;cias s&atilde;o informa&ccedil;&otilde;es a respeito de novos acontecimentos, de mudan&ccedil;as recentes em alguma situa&ccedil;&atilde;o ou do estado em que algo se encontra.</small></p>
    
    <!--
    <form action="noticias.php" method="get" id="busca">
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
          <option value="titulo" selected="selected">T&iacute;tulo</option>
          <option value="autor">Autor</option>
          <option value="ativo">Ativo</option>
          <option value="inativo">Inativo</option>
        </select>
      </div>
      <div class="campos">
        <label for="label">Situação </label>
        <br />
        <select name="situacao" id="relacionar_situacao">
          <option value="0" selected="selected">Todos</option>
          <option value="1">Ativo</option>
          <option value="2">Inativo</option>
          <option value="3">Pendente</option>
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
        <input name="submit" type="button" class="bt-buscar" value="Buscar" id="bt_buscaconteudo" onclick="javascript:buscaNoticiaNavegacao();" />
      </fieldset>
    </form>
    -->

	<form method="get" id="form-result" action="conteudo.php">
		<h3 class="titulo">Mais Recentes</h3>
		<div id="mostra_resultados_relacionamento" class="box"></div>
	</form>
	
</div>
  
<script language="javascript" type="text/javascript">
buscaNoticiaNavegacao();
</script>

<?php include('includes/rodape.php'); ?>