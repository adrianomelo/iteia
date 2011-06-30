<?php
include('verificalogin.php');

include_once("classes/bo/ClassificacaoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$classbo = new ClassificacaoEdicaoBO;

$codclassificacao = (int)$_GET["cod"];
$editar = (int)$_POST['editar'];

if ($editar) {
	try {
		$cod_classificacao = $classbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: conteudo_formatos.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$listaclassificacoes = $classbo->getListaClassificacao($_GET);

$paginatitulo = 'Formatos&nbsp;';
$item_menu = "conteudo";
$item_submenu = "formatos";
include('includes/topo.php');

if ($codclassificacao && !$editar) {
	$classbo->setDadosCamposEdicao($codclassificacao);
}

$codclassificacao = (int)$classbo->getValorCampo("codclassificacao");

if (!count($listaclassificacoes[1]))
	$listaclassificacoes[1] = array();
if (!count($listaclassificacoes[2]))
	$listaclassificacoes[2] = array();
if (!count($listaclassificacoes[3]))
	$listaclassificacoes[3] = array();
if (!count($listaclassificacoes[4]))
	$listaclassificacoes[4] = array();

?>

    <h2>Conte&uacute;do &gt; Categorias</h2>
    
<?php if ($erro_mensagens || $classbo->verificaErroCampo("titulo") || $classbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>
    
    <h3 class="titulo">Adicionar Categorias de Formatos</h3>
    <div class="box">
      <form action="conteudo_formatos.php" method="post" id="frm-add-categoria">
        <fieldset>
        
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="codclassificacao" value="<?=$codclassificacao?>" />
        
        <label for="textfield">Nome<span>*</span></label>
        <br />
        <input type="text" class="txt" id="textfield" maxlength="100" name="nome" <?=$classbo->verificaErroCampo("nome")?> value="<?=htmlentities(stripslashes($classbo->getValorCampo("nome")))?>" />
        <br />
        <label for="select">Formato<span>*</span></label>
        <br />
        <select id="select" name="codformato">
          <option value="1" <?=Util::iif($classbo->getValorCampo("codformato") == 1, 'selected="selected"');?>>Texto</option>
          <option value="2" <?=Util::iif($classbo->getValorCampo("codformato") == 2, 'selected="selected"');?>>Imagem</option>
          <option value="4" <?=Util::iif($classbo->getValorCampo("codformato") == 4, 'selected="selected"');?>>V&iacute;deo</option>
          <option value="3" <?=Util::iif($classbo->getValorCampo("codformato") == 3, 'selected="selected"');?>>&Aacute;udio</option>
        </select>
        <br />
        <label for="textarea">Descri&ccedil;&atilde;o</label>
        <br />
        <textarea id="textarea" cols="60" rows="10" name="descricao" <?=$classbo->verificaErroCampo("descricao")?>><?=htmlentities(stripslashes($classbo->getValorCampo("descricao")))?></textarea>
        <br />
        <input type="submit" value="Gravar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    <h3 class="titulo">Categorias</h3>
    <div id="resultado" class="box">
    <form method="get" id="form-result" action="conteudo_formatos.php">
    <input type="hidden" id="acao" name="acao" value="0" />
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-result">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input type="checkbox" id="check-all"  /></th>
            <th class="col-titulo"  scope="col"><span class="col-titulo">Nome</span></th>
            <th class="col-conteudo"  scope="col">Conte&uacute;dos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="col-1">&nbsp;</td>
            <td class="col-titulo"><strong>Texto</strong></td>
            <td class="col-conteudo">&nbsp;</td>
          </tr>
          <?php foreach ($listaclassificacoes[1] as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" class="check" value="<?=$value['cod'];?>" name="codclassificacao[]" /></td>
            <td class="col-titulo">&mdash; <a href="conteudo_formatos.php?cod=<?=$value['cod'];?>" title="Clique para editar"><?=$value['nome'];?></a></td>
            <td class="col-conteudo"><?=$value['quantidade'];?></td>

          </tr>
          <?php endforeach; ?>
          <tr>
            <td class="col-1">&nbsp;</td>
            <td class="col-titulo"><strong>Imagem</strong></td>
            <td class="col-conteudo">&nbsp;</td>
          </tr>
          <?php foreach ($listaclassificacoes[2] as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" class="check" value="<?=$value['cod'];?>" name="codclassificacao[]" /></td>
            <td class="col-titulo">&mdash; <a href="conteudo_formatos.php?cod=<?=$value['cod'];?>" title="Clique para editar"><?=$value['nome'];?></a></td>
            <td class="col-conteudo"><?=$value['quantidade'];?></td>

          </tr>
          <?php endforeach; ?>
          <tr>
            <td class="col-1">&nbsp;</td>
            <td class="col-titulo"><strong>V&iacute;deo</strong></td>
            <td class="col-conteudo">&nbsp;</td>
          </tr>
          <?php foreach ($listaclassificacoes[4] as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" class="check" value="<?=$value['cod'];?>" name="codclassificacao[]" /></td>
            <td class="col-titulo">&mdash; <a href="conteudo_formatos.php?cod=<?=$value['cod'];?>" title="Clique para editar"><?=$value['nome'];?></a></td>
            <td class="col-conteudo"><?=$value['quantidade'];?></td>

          </tr>
          <?php endforeach; ?>
          <tr>
            <td class="col-1">&nbsp;</td>
            <td class="col-titulo"><strong>&Aacute;udio</strong></td>
            <td class="col-conteudo">&nbsp;</td>
          </tr>
          <?php foreach ($listaclassificacoes[3] as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" class="check" value="<?=$value['cod'];?>" name="codclassificacao[]" /></td>
            <td class="col-titulo">&mdash; <a href="conteudo_formatos.php?cod=<?=$value['cod'];?>" title="Clique para editar"><?=$value['nome'];?></a></td>
            <td class="col-conteudo"><?=$value['quantidade'];?></td>

          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
      </form>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
