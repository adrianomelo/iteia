<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codgrupo = (int)$_GET['cod'];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codgrupo) $edicaodados = 1;

include_once("classes/bo/GrupoAutoresBO.php");
$grupobo = new GrupoAutoresBO;

if (!$editar)
	unset($_SESSION['sessao_autores_relacionados']);
	
if (!count($_SESSION['sessao_autores_relacionados']))
	$_SESSION['sessao_autores_relacionados'] = array();
	
if ($editar) {
	try {
		$cod_usuario = $grupobo->editar($_POST, $_FILES);
		
		Header("Location: grupo_publicado.php?cod=".$cod_usuario);
		exit();
		
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($codgrupo && !$editar)
	$grupobo->carregarAutoresRelacionamento($codgrupo);

$paginatitulo = 'Selecionar autor&nbsp;';
$item_menu = $nao_carregar = "grupo";
include('includes/topo.php');
?>

	<script type="text/javascript" src="jscripts/autor.js"></script>

    <h2>Adicionar Autores</h2>

    <form action="grupo_adicionar_autores.php" method="post">
      <div class="box">
        <fieldset>
        
		<input type="hidden" value="<?=$codgrupo;?>" name="codgrupo" />
    	<input type="hidden" value="<?=$edicaodados;?>" name="edicaodados" />
		<input type="hidden" value="1" name="editar" />
        
		<legend>Buscar autor</legend>
        <label for="textfield">Nome do autor</label>
        <br />
        <input type="text" class="txt" id="relacionar_palavrachave" size="80" />
        <br />
        <input type="button" onclick="javascript:buscaAutoresRelacionamento();" class="bt-buscar" value="Buscar" />
        </fieldset>
      </div>

<div id="mostra_resultados_autores_relacionamento"></div>
<div id="mostra_lista_autores_relacionado"></div>
      
      <div id="botoes" class="box" style="display:none;">
        <a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
    
<script language="javascript" type="text/javascript">
carregarAutores();
</script>
    
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
