<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codconteudo) $edicaodados = 1;

include_once("classes/bo/ConteudoColaboradoresRevisaoBO.php");
$colabrevbo = new ConteudoColaboradoresRevisaoBO;

if (!$editar)
	unset($_SESSION['sessao_colaboradores_revisao']);
	
if (!count($_SESSION['sessao_colaboradores_revisao']))
	$_SESSION['sessao_colaboradores_revisao'] = array();
	
if ($editar) {
	try {
		$cod_conteudo = $colabrevbo->editar($_POST, $_FILES);
		
		Header("Location: ".$cod_conteudo);
		exit();
		
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($codconteudo && !$editar)
	$colabrevbo->carregarColaboradoresRevisao($codconteudo);

$paginatitulo = 'Selecionar colaborador&nbsp;';
$nao_carregar = $item_menu = 'conteudo';
include('includes/topo.php');
?>

	<script type="text/javascript" src="jscripts/colaborador.js"></script>

    <h2>Adicionar Colaboradores</h2>
    <form action="conteudo_enviar_colaboradores.php" method="post">
      <div class="box">
        <fieldset>
        
		<input type="hidden" value="<?=$codconteudo;?>" name="codconteudo" />
    	<input type="hidden" value="<?=$edicaodados;?>" name="edicaodados" />
		<input type="hidden" value="1" name="editar" />
        
		<legend>Buscar colaborador</legend>
        <label for="textfield">Nome do colaborador</label>
        <br />
        <input type="text" class="txt" id="relacionar_palavrachave" size="80" />
        <br />
        <input type="button" onclick="javascript:buscaColaboradoresRevisao();" class="bt-buscar" value="Buscar" />
        </fieldset>
      </div>

<div id="mostra_resultados_colaboradores_revisao"></div>
<div id="mostra_lista_colaboradores_revisao"></div>
      
      <div id="botoes" class="box" style="display:none;">
        <a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
    
<script language="javascript" type="text/javascript">
carregarColaboradores();
</script>
    
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
