<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "conteudo";
$item_submenu = "inicio";
$paginatitulo = "Conte&uacute;do";
include('includes/topo.php');
?>
    <h2>Conte&uacute;do</h2>

	<script type="text/javascript" src="jscripts/conteudo.js"></script>

	<form action="" method="get" id="box-busca" onclick="return false;">
	  <input type="hidden" name="buscarpor" id="relacionar_buscarpor" value="titulo" />
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input name="text" type="text" class="txt" id="relacionar_palavrachave" onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" onclick="javascript:buscaConteudoNavegacao(1);" />
      <a href="conteudo_busca_pop.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>

    <?php if ($_SESSION['logado_dados']['nivel'] == 2): ?>

    <p class="descricao">Gerencie abaixo os conte&uacute;dos cadastrados por voc&ecirc;, editando ou apagando eles do   sistema. Al&eacute;m disso, confira se eles j&aacute; foram aprovados pelos colaboradores ou   se ainda est&atilde;o na Lista de autoriza&ccedil;&otilde;es. <a href="conteudo_tipo.php">Para incluir novos conte&uacute;dos, clique aqui</a> </p>

	<?php else: ?>

    <p class="descricao">Gerencie  os conte&uacute;dos cadastrados por voc&ecirc; ou pelos autores vinculados ao  colaborador que voc&ecirc; representa. Avalie antes de aprovar os poss&iacute;veis  arquivos pendentes ou <a href="conteudo_tipo.php">clique aqui para incluir um novo conte&uacute;do</a>, seja v&iacute;deo, &aacute;udio, imagem ou texto.</p>

    <?php endif; ?>

    <!--
    <form action="conteudo.php" method="get" id="busca">
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
        <label for="label">Tipo </label>
        <br />
        <select name="formato" id="relacionar_formato">
          <option value="0" selected="selected">Todos</option>
          <option value="1">Texto</option>
          <option value="3">&Aacute;udio</option>
          <option value="4">V&iacute;deo</option>
          <option value="2">Imagem</option>
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
        <input name="submit" type="button" class="bt-buscar" value="Buscar" id="bt_buscaconteudo" onclick="javascript:buscaConteudoNavegacao();" />
      </fieldset>
    </form>
    -->

	<form method="get" id="form-result" action="conteudo.php">
    	<h3 class="titulo">Mais recentes</h3>
		<div id="mostra_resultados_relacionamento" class="box"></div>
	</form>

  </div>
<script language="javascript" type="text/javascript">
buscaConteudoNavegacao();
</script>

<?php include('includes/rodape.php'); ?>