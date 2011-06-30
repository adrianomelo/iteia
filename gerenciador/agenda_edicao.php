<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codagenda = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codagenda)
	$edicaodados = 1;

include_once("classes/bo/AgendaEdicaoBO.php");
$agendabo = new AgendaEdicaoBO;
$exibir_form = true;

if ($editar) {
	try {
		$cod_conteudo = $agendabo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: agenda_publicado.php?cod=".$cod_conteudo);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$agendabo;

$item_menu = 'agenda';
$item_submenu = 'inserir';
$paginatitulo = 'Inserir Evento&nbsp;';
include('includes/topo.php');

if ($codagenda && !$editar) {
	$agendabo->setDadosCamposEdicao($codagenda);
}

$codagenda = (int)$agendabo->getValorCampo("codagenda");
?>
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<script type="text/javascript" src="jscripts/edicao.js"></script>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>
    
<h2>Eventos</h2>

<?php if ($erro_mensagens || $agendabo->verificaErroCampo("titulo") || $agendabo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="agenda_edicao.php" method="post" enctype="multipart/form-data" name="form-evento" id="form-evento">
      <h3 class="titulo">Cadastro de eventos</h3>
      <div class="box">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codagenda" value="<?=$codagenda?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$agendabo->getValorCampo('imgtemp')?>" />

        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />
		<input type="text" class="txt" id="textfield" size="78" maxlength="100" name="titulo"  <?=$agendabo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 100)" />
        <input type="text" class="txt counter" value="100" size="4" disabled="disabled" id="cont_titulo" />
        <br />

        <label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label>
        <br />
        
		<textarea name="descricao" cols="60" style="height:150px;" rows="10" class="mceAdvanced" id="textarea" <?=$agendabo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 600);"><?=Util::clearText($agendabo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="600" size="4" disabled="disabled" id="cont_descricao" />
        <br />
        
        <label for="label">Tags (palavras-chave)</label>
        <br />
        <input type="text" name="tags" id="tags" value="<?=$agendabo->getValorCampo("tags")?>" class="txt" size="80" />
        <small>Separe por ponto-e-v&iacute;rgula &quot;;&quot;</small>
        
	      <br />
	      <label for="textfield4">Local<span>*</span></label>
	      <br />
	      <input type="text" class="txt" id="textfield4" size="80" name="local"  <?=$agendabo->verificaErroCampo("local")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("local")))?>" />
	      <br />
	      <label for="textfield5">Endere&ccedil;o<span>*</span></label>
	      <br />
	      <input type="text" class="txt" id="textfield5" size="80" name="endereco"  <?=$agendabo->verificaErroCampo("endereco")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("endereco")))?>" />
	      <br />
	      <label for="textfield6">Cidade<span>*</span></label>
	      <br />
	      <input type="text" class="txt" id="textfield6" size="50" name="cidade"  <?=$agendabo->verificaErroCampo("cidade")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("cidade")))?>" />
	      <br />
	      <label for="textfield2">Site/Hotsite</label><br />
	      <input type="text" class="txt" id="textfield2" name="site" value="http://<?=htmlentities(stripslashes($agendabo->getValorCampo("site")))?>" />
	      <br />
	      <label for="textfield9">Telefone</label>
	      <br />
	      <input type="text" class="txt phone" id="textfield9" name="telefone"  <?=$agendabo->verificaErroCampo("telefone")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("telefone")))?>" />
	      <br />
	      <label for="textfield10">Valor</label>
	      <br />
	      <input type="text" class="txt" id="textfield10" name="valor" <?=$agendabo->verificaErroCampo("valor")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("valor")))?>" />

        <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($agendabo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$agendabo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$agendabo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/colaborador.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$agendabo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$agendabo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codagenda;?>', '6');" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=conteudo&amp;cod=<?=$codagenda;?>&amp;height=180&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>

	<div class="box">
        <fieldset id="periodo">
      <legend>Per&iacute;odo</legend>
      <div>
        <label for="dFrom">De:<span>*</span></label>

        <input type="text" class="txt calendario date" id="dFrom" name="data_inicial" <?=$agendabo->verificaErroCampo("data_inicial")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("data_inicial")))?>" />
        <em><small>dd/mm/aaaa</small></em></div>
      <div>
        <label for="dTo">At&eacute;:</label>
        <input type="text" class="txt calendario date" id="dTo" name="data_final" <?=$agendabo->verificaErroCampo("data_final")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("data_final")))?>" />
        <em><small>dd/mm/aaaa</small></em></div>
      </fieldset>

        <fieldset id="horario">
        <legend>Hor&aacute;rio</legend>
        <div>
          <label for="textfield7">De:<span>*</span></label>
          <input type="text" class="txt hour" id="textfield7" name="hora_inicial" <?=$agendabo->verificaErroCampo("hora_inicial")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("hora_inicial")))?>" />
          <em><small>Ex. hh:mm</small></em></div>
        <div>
          <label for="textfield8">At&eacute;:</label>
          <input type="text" class="txt hour" id="textfield8" name="hora_final" <?=$agendabo->verificaErroCampo("hora_final")?> value="<?=htmlentities(stripslashes($agendabo->getValorCampo("hora_final")))?>" />
          <em><small>Ex. hh:mm</small></em></div>
        </fieldset>
      </div>

      <div id="botoes" class="box"> <a href="agenda.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<?php if ($exibir_form): ?>
<script language="javascript" type="text/javascript">
contarCaracteres(document.getElementById("textfield"), "cont_titulo", 100);
contarCaracteres(document.getElementById("textarea"), "cont_descricao", 600);
</script>
<?php
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
