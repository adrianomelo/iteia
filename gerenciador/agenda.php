<?php
include('verificalogin.php');

include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "agenda";
$item_submenu = "inicio";
$paginatitulo = 'Eventos';
include('includes/topo.php');
?>
    <h2>Eventos</h2>

	<script type="text/javascript" src="jscripts/conteudo.js"></script>
	
	<form action="" method="get" id="box-busca" onsubmit="return false;">
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input type="hidden" name="buscarpor" id="relacionar_buscarpor" value="titulo" />
      <input name="text" type="text" class="txt" id="relacionar_palavrachave"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" onclick="javascript:buscaAgendaNavegacao(1);" class="bt-buscar-img" />
      <a href="agenda_busca_pop.php?height=280&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <p class="descricao"> <a href="agenda_edicao.php">Publique eventos</a> sobre os mais variados acontecimentos relacionados ao(s) colaborador(es) que voc&ecirc; representa ou aos autores e grupos vinculados a ele. Os eventos aparecer&atilde;o na home e na p&aacute;gina do(s) colaborador(es) no portal. <br />
      <br />
    <small>OBS: quanto mais informa&ccedil;&otilde;es e detalhes voc&ecirc; cadastrar, melhor ser&aacute; divulgado o evento. </small></p>

	<!--
    <form action="agenda.php" method="get" id="busca">
      <fieldset>
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
          <option value="titulo">T&iacute;tulo</option>
          <option value="local">Local</option>
          <option value="valor">Valor</option>
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
          <em><small>dd/mm/aaaa</small></em>      </div>
      </fieldset>
      <input type="button" class="bt-buscar" value="Buscar" onclick="javascript:buscaAgendaNavegacao();" />
      </fieldset>
    </form>
    -->

	<form method="get" id="form-result" action="conteudo.php">
    	<h3 class="titulo"><?=Util::iif($buscar, 'Resultado da busca', 'Mais recentes');?></h3>
		<div id="mostra_resultados_relacionamento" class="box"></div>
	</form>

</div>

<script language="javascript" type="text/javascript">
buscaAgendaNavegacao();
</script>

<?php include('includes/rodape.php'); ?>