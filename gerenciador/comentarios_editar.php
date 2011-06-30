<?php
include('verificalogin.php');

include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codcomentario = (int)$_GET["cod"];

include_once("classes/bo/ComentarioEdicaoBO.php");
$comentbo = new ComentarioEdicaoBO;
$exibir_form = true;

if ($editar) {
	try {
		$comentbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: comentarios.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$item_menu = "comentarios";
$item_submenu = "inicio";
include('includes/topo.php');

if ($codcomentario && !$editar) {
	$comentbo->setDadosCamposEdicao($codcomentario);
}

$url = $comentbo->getUrlConteudo($codcomentario);

$codcomentario = (int)$comentbo->getValorCampo("codcomentario");
?>
    <h2>Coment&aacute;rios</h2>
    
<?php if ($erro_mensagens || $comentbo->verificaErroCampo("nome") || $comentbo->verificaErroCampo("email") || $comentbo->verificaErroCampo("comentario")) : ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>
    
    <h3 class="titulo">Formul&aacute;rio de edi&ccedil;&atilde;o de coment&aacute;rios</h3>
    <div class="box">
    <p class="dash"><strong>P&aacute;gina:</strong> <a href="<?=ConfigVO::URL_SITE.$url;?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$url;?></a></p>
      <form action="comentarios_editar.php" method="post" id="frm-add-categoria">
        <fieldset>
        
		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="codcomentario" value="<?=$codcomentario?>" />
		
        <label for="textfield">Nome</label>
        <br />
        <input type="text" class="txt" id="textfield" maxlength="60" name="nome" <?=$comentbo->verificaErroCampo("nome")?> value="<?=htmlentities(stripslashes($comentbo->getValorCampo("nome")))?>" />
        <br />
        <label for="textfield2">Email</label><br />
        <input type="text" class="txt" id="textfield2" maxlength="60" name="email" <?=$comentbo->verificaErroCampo("email")?> value="<?=htmlentities(stripslashes($comentbo->getValorCampo("email")))?>" />
        <br />
        <label for="textfield3">Site</label><br />
        <input type="text" class="txt" id="textfield3" maxlength="60" name="site" value="<?=htmlentities(stripslashes($comentbo->getValorCampo("site")))?>" />
        <br />
        <label for="textarea">Coment&aacute;rio</label>
        <br />
        <textarea id="textarea" cols="60" rows="10" name="comentario" <?=$comentbo->verificaErroCampo("comentario")?>><?=htmlentities(stripslashes($comentbo->getValorCampo("comentario")))?></textarea>
        <br />
        <input type="submit" value="Gravar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    
    </div>

<?php include('includes/rodape.php'); ?>
