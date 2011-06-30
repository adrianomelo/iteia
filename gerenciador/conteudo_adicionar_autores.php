<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codconteudo) $edicaodados = 1;

include_once("classes/bo/ConteudoAutoresRelacionamentoBO.php");
$autcontrelbo = new ConteudoAutoresRelacionamentoBO;

if (!$editar)
	unset($_SESSION['sessao_autores_relacionados']);

if (!count($_SESSION['sessao_autores_relacionados']))
	$_SESSION['sessao_autores_relacionados'] = array();

if ($editar) {
	try {
		$cod_conteudo = $autcontrelbo->editar($_POST, $_FILES);

		Header("Location: ".$cod_conteudo);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($codconteudo && !$editar)
	$autcontrelbo->carregarAutoresRelacionamento($codconteudo);

$paginatitulo = 'Selecionar autor&nbsp;';
$item_menu = $nao_carregar = "conteudo";
include('includes/topo.php');
?>

	<script type="text/javascript" src="jscripts/autor.js"></script>

    <h2>Adicionar Autores</h2>

<?php if ($_SESSION['logado_dados']['nivel'] >= 5): ?>
    <strong>Dica:</strong>
	<div class="box box-dica">
      Caso o autor desejado n&atilde;o seja encontrado na busca, <a href="cadastro_autor.php" class="add"><strong>cadastre-o agora</strong></a>.</div>
<?php endif; ?>

    <form action="conteudo_adicionar_autores.php" method="post">
      <div class="box">
        <fieldset>

		<input type="hidden" value="<?=$codconteudo;?>" name="codconteudo" />
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
