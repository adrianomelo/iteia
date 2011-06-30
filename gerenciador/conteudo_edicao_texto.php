<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codtexto = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codtexto)
	$edicaodados = 1;
	
if (!$editar)
	$sessao_id = Util::geraRandomico('num'); // gera uma nova id pra sessão	

include_once("classes/bo/TextoEdicaoBO.php");
$textobo = new TextoEdicaoBO;
$exibir_form = true;

if (!isset($_SESSION["sess_conteudo_autores_ficha"]))
	$_SESSION["sess_conteudo_autores_ficha"] = array();

//if (!$editar)
//	unset($_SESSION["sess_conteudo_autores_ficha"]);

if ($editar) {
	try {
		$cod_conteudo = $textobo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: conteudo_publicado_texto.php?cod=".$cod_conteudo);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$textobo;

$item_menu = 'conteudo';
$item_submenu = 'inserir';
$nao_carregar_thickbox = true;
include('includes/topo.php');

$codformato_class = 1;

if (!$editar)
	$textobo->setValorCampo('sessao_id', $sessao_id);

if ($codtexto && !$editar)
	$textobo->setDadosCamposEdicao($codtexto);

$permitir_comentarios = $textobo->getValorCampo("permitir_comentarios");
if (!$codtexto)
	$permitir_comentarios = true;

$codtexto = (int)$textobo->getValorCampo("codtexto");
?>
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<script language="javascript" type="text/javascript">
var cod_autor_pessoal = '<?=$_SESSION['logado_dados']['cod'];?>';
var sessao_id = '<?=$sessao_id;?>';
</script>

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>

<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>

<h2>Conte&uacute;do</h2>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<?php if ($erro_mensagens || $textobo->verificaErroCampo("titulo") || $textobo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="conteudo_edicao_texto.php" method="post" enctype="multipart/form-data" name="form-texto" id="form-texto">
      <h3 class="titulo">Cadastro de texto</h3>
      <div class="box">
        <fieldset>
        
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codtexto" value="<?=$codtexto?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$textobo->getValorCampo('imgtemp')?>" />
		
		<input type="hidden" name="foto_credito" id="foto_credito" value="<?=$textobo->getValorCampo('foto_credito')?>" />
		<input type="hidden" name="foto_legenda" id="foto_legenda" value="<?=$textobo->getValorCampo('foto_legenda')?>" />
		<input type="hidden" name="sessao_id" id="sessao_id" value="<?=$textobo->getValorCampo("sessao_id")?>" />
		
        <legend>Conte&uacute;do</legend>
        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />

		<input type="text" class="txt" id="textfield" size="78" maxlength="60" name="titulo"  <?=$textobo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($textobo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 60)" />
        <input type="text" class="txt counter" value="60" size="4" disabled="disabled" id="cont_titulo" />

        <br />
        <label for="textarea">Texto<span>*</span></label>
        <br />
        <textarea name="descricao" cols="60" rows="10" class="mceAdvanced" id="textarea" <?=$textobo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 20000);"><?=Util::clearText($textobo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="20000" size="4" disabled="disabled" id="cont_descricao" />
        <br />
        <label for="fileField">Anexar arquivo</label><br />
        <small>
        Você pode fazer upload de arquivo .odt, .ogg, .ods, .doc, .pdf, .ppt, .pps, .rtf, .txt, .xls e .zip (Tamanho máximo de 100MB)</small>
        <br />
        <input type="file" id="fileField" name="arquivo" size="45" />
        <br />

		<?php if ($textobo->getValorCampo("arquivo")):?>
		<div class="arquivo"> <span><?=$textobo->getValorCampo("nome_arquivo_original")?></span> <a href="conteudo_remover_arquivo.php?height=100&amp;width=305&amp;modal=true&amp;cod=<?=$codtexto;?>&tipo=1" class="thickbox" title="Remover">Remover</a> </div>
		<?php endif; ?>

        <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($textobo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$textobo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$textobo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/texto.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$textobo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$textobo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codtexto;?>', 1);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem_texto.php?tipo=conteudo&amp;cod=<?=$codtexto;?>&amp;height=280&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>

<?php

	include("includes/conteudo_box_categorias.php");

	include("includes/conteudo_direitos_autorais.php");
	
	//if ($_SESSION['logado_dados']['cod_colaborador'] && !$codtexto && $_SESSION['logado_dados']['nivel'] >= 5)
	//	include("includes/conteudo_interno_colaborador_vincular.php");
	
	if ($_SESSION['logado_dados']['nivel'] == 2)
		include("includes/conteudo_interno_autorizacao.php");
	
	if (($_SESSION['logado_dados']['nivel'] >= 5) || count($_SESSION['logado_dados']['cod_grupo']))
		include("includes/conteudo_interno_pertence_voce.php");
	
	include("includes/conteudo_autores_wiki.php");
	//}
?>		

	<div class="box box-amarelo" id="classificar2">
        <fieldset>
        <legend class="seta">Coment&aacute;rios</legend>
          <div class="fechada" id="box-comentarios">
          <p>Voc&ecirc; pode permitir que os visitantes do portal iTEIA deixem ou n&atilde;o coment&aacute;rios neste conte&uacute;do. Caso voc&ecirc; autorize,  vale ressaltar que eles ser&atilde;o publicados automaticamente na p&aacute;gina. No  entanto, voc&ecirc; e os colaboradores do sistema poder&atilde;o gerenciar todos os  coment&aacute;rios (apagando ou suspendendo os mesmos) atrav&eacute;s do <a href="comentarios.php" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">menu  principal</a>.</p>
          <p>
            <input type="checkbox" id="checkbox" name="permitir_comentarios" value="1" <?=Util::iif($permitir_comentarios, 'checked="checked"');?> />
            <label for="checkbox">Permitir que sejam publicados coment&aacute;rios.</label>
          </p>
          </div>
        </fieldset>
      </div>

      <div id="botoes" class="box"> <a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<?php if ($exibir_form): ?>
	<script language="javascript" type="text/javascript">
	contarCaracteres(document.getElementById("textfield"), "cont_titulo", 60);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 20000);
	</script>
<?php
	if ($contbo->getValorCampo('pertence_voce') == 1)
		echo '<script language="javascript" type="text/javascript">$(\'#ficha-tecnica\').show(); $(\'#sou_autor_conteudo\').show();</script>';
	if ($_SESSION['logado_dados']['nivel'] == 2)
		echo '<script language="javascript" type="text/javascript">$(\'#sou_autor_conteudo\').show();</script>';
	if ($textobo->getValorCampo("codcanal") || $textobo->getValorCampo("codsegmento")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-classificar\').show();</script>';
	endif;
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
