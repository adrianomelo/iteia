<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codconteudo) $edicaodados = 1;

include_once("classes/bo/ConteudoRelacionamentoBO.php");
$contrelbo = new ConteudoRelacionamentoBO;

if (!$editar)
	unset($_SESSION['sessao_conteudo_relacionamento']);
	
if (!count($_SESSION['sessao_conteudo_relacionamento']))
	$_SESSION['sessao_conteudo_relacionamento'] = array();
	
if ($editar) {
	try {
		$cod_conteudo = $contrelbo->editar($_POST, $_FILES);
		
		Header("Location: ".$cod_conteudo);
		exit();
		
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($codconteudo && !$editar)
	$contrelbo->carregarConteudoRelacionamento($codconteudo);

$item_menu = $nao_carregar = "conteudo";
include('includes/topo.php');
?>
	<script type="text/javascript" src="jscripts/conteudo.js"></script>

    <h2><span>Relacionar Conte&uacute;dos</span></h2>
    <!--
	<form id="busca" method="post" action="">
      <p class="dica">O sistema relaciona automaticamente conte&uacute;dos de acordo com as classifica&ccedil;&otilde;es selecionadas anteriormente. Caso existam outros conte&uacute;dos/noticias/eventos que voc&ecirc; deseje associar a este conte&uacute;do utilize a busca abaixo para pesquisar e adicione os resultados desejados na lista de conte&uacute;dos.</p>
      <fieldset>
      <div class="campos">
        <label for="textfield3">Buscar conte&uacute;dos</label>
        <br />
        <input name="relacionar_palavrachave" type="text" class="txt" id="relacionar_palavrachave"  />
      </div>
      <div class="campos">
        <label for="select8">Buscar por</label>
        <br />
        <select id="relacionar_buscarpor" name="relacionar_buscarpor">
          <option value="titulo" selected="selected">T&iacute;tulo</option>
          <option value="autor">Autor</option>
        </select>
      </div>
      <div class="campos">
        <label for="formato">Tipo</label>
        <br />
        <select name="relacionar_formato" id="relacionar_formato">
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
        <input name="relacionar_de" type="text" class="txt calendario date" id="relacionar_de" />
        <em><small>dd/mm/aaaa</small></em>
        <label for="dTo">At&eacute;:</label>
        <input name="relacionar_ate" type="text" class="txt calendario date" id="relacionar_ate" />
        <em><small>dd/mm/aaaa</small></em> </div>
      </fieldset>
      <input type="button" class="bt-buscar" onclick="javascript:buscaConteudoParaRelacionamento();" value="Buscar" />
      </fieldset>
    </form>
    -->
    
    <form action="" method="get" id="box-busca" onclick="return false;">
	  
	  <input type="hidden" name="buscarpor" id="relacionar_buscarpor" value="titulo" />
	  <input type="hidden" value="1" name="relacionar_evento" id="relacionar_evento" />
	  
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input name="text" type="text" class="txt" id="relacionar_palavrachave" onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" onclick="buscaConteudoParaRelacionamento();" />
      <a href="conteudo_relacionar_busca_pop.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <p class="descricao">O sistema relaciona automaticamente conte&uacute;dos de acordo com as classifica&ccedil;&otilde;es selecionadas anteriormente. Caso existam outros conte&uacute;dos/noticias/eventos que voc&ecirc; deseje associar a este conte&uacute;do utilize a busca abaixo para pesquisar e adicione os resultados desejados na lista de conte&uacute;dos.</p>
    
<form name="add-conteudo" id="add-conteudo" method="post" action="conteudo_relacionar.php">
    <fieldset>
    	<input type="hidden" value="<?=$codconteudo;?>" name="codconteudo" />
    	<input type="hidden" value="<?=$edicaodados;?>" name="edicaodados" />
		<input type="hidden" value="1" name="editar" />
    </fieldset>

    <div id="mostra_resultados_relacionamento"></div>
	<div id="mostra_lista_conteudo_relacionado"></div>

      <div id="botoes" class="box">
        <a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
      
</form>
    
<script language="javascript" type="text/javascript">
carregarConteudoRelacionamento(<?=$codconteudo;?>);
</script>

  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
