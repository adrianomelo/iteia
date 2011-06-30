<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once('classes/bo/ComentarioBO.php');
$comentbo = new ComentarioBO;

$atualizar = (int)$_POST['atualizar'];
if ($atualizar) {
	$comentbo->atualizarOpcoes($_POST);
	Header('Location: comentarios_opcoes.php');
}

$comentbo->setDadosCamposEdicao();

$item_menu = "comentarios";
$item_submenu = "opcoes";
include('includes/topo.php');
?>
    <h2>Coment&aacute;rios &gt; Op&ccedil;&otilde;es</h2>
    <form action="comentarios_opcoes.php" method="post" class="box" >
	<input type="hidden" name="atualizar" value="1" />
      <p>
        <input type="checkbox" name="permitirpublicacao" id="checkbox" value="1"<?=($comentbo->getValorCampo('permitirpublicacao'))?' checked="checked"':''?> />
        <label for="checkbox">Permitir que sejam publicados comentários.</label>
        <br />
        <small>(Estas configura&ccedil;&otilde;es poder&atilde;o ser substitu&iacute;das para cada post, individualmente.)</small></p>
      <p>
        <input type="checkbox" name="precisaaprovacao" id="checkbox2" value="1"<?=($comentbo->getValorCampo('precisaaprovacao'))?' checked="checked"':''?> />
        <label for="checkbox2">Os coment&aacute;rios precisam ser aprovados.</label>
      </p>
      <fieldset>
      <legend>Moderação de Comentários</legend>
      <p>Quando um coment&aacute;rio contiver qualquer uma destas palavras no respectivo conte&uacute;do, nome, URL, endere&ccedil;o de email ou IP, o mesmo ser&aacute; retido na lista de <a href="comentarios.php">coment&aacute;rios aguardando aprova&ccedil;&atilde;o</a>. Separe os termos por v&iacute;rgula.</p>
      <textarea id="textarea" name="moderacao" cols="65" rows="5"><?=$comentbo->getValorCampo('moderacao')?></textarea>
      </fieldset>
      <fieldset>
      <legend>Lista Negra de Coment&aacute;rios</legend>
      <p>Quando um coment&aacute;rio contiver qualquer uma destas palavras no respectivo conte&uacute;do, nome, URL, endere&ccedil;o de email ou IP, o mesmo ser&aacute; retido na lista de <a href="comentarios.php">coment&aacute;rios rejeitados</a>. Separe os termos por v&iacute;rgula.</p>
      <textarea id="textarea2" name="listanegra" cols="65" rows="5"><?=$comentbo->getValorCampo('listanegra')?></textarea>
      </fieldset>
      <input type="submit" class="bt-buscar" value="Salvar" />
    </form>
</div>
<?php include('includes/rodape.php'); ?>
