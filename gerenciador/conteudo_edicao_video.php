<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codvideo = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codvideo)
	$edicaodados = 1;
	
if (!$editar)
	$sessao_id = Util::geraRandomico('num'); // gera uma nova id pra sessão	

include_once("classes/bo/VideoEdicaoBO.php");
$videobo = new VideoEdicaoBO;
$exibir_form = true;

if (!isset($_SESSION["sess_conteudo_autores_ficha"]))
	$_SESSION["sess_conteudo_autores_ficha"] = array();

//if (!$editar)
//	unset($_SESSION["sess_conteudo_autores_ficha"]);

$codformato_class = 4;

if ($editar) {
	try {
		$cod_conteudo = $videobo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: conteudo_publicado_video.php?cod=".$cod_conteudo);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$videobo;

$item_menu = 'conteudo';
$item_submenu = 'inserir';
$nao_carregar_thickbox = true;
include('includes/topo.php');

if (!$editar)
	$videobo->setValorCampo('sessao_id', $sessao_id);

if ($codvideo && !$editar) {
	$videobo->setDadosCamposEdicao($codvideo);
}

$permitir_comentarios = $videobo->getValorCampo("permitir_comentarios");
if (!$codvideo)
	$permitir_comentarios = true;

$codvideo = (int)$videobo->getValorCampo("codvideo");

$id_upload = mt_rand();
?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<script language="javascript" type="text/javascript">
var cod_autor_pessoal = '<?=$_SESSION['logado_dados']['cod'];?>';
var sessao_id = '<?=$sessao_id;?>';
</script>

    <h2>Conte&uacute;do</h2>

<script language="javascript" type="text/javascript" src="jscripts/conteudo.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/jquery.progressbar.min.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/progressbar.js"></script>
    
<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>

<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>
<script type="text/javascript">
var progress_key = '<?=$id_upload?>';
</script>

<?php if ($erro_mensagens || $videobo->verificaErroCampo("titulo") || $videobo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="conteudo_edicao_video.php" method="post" enctype="multipart/form-data" name="form-video" id="form-video" onsubmit="$('#uploadprogressbar').show(); setTimeout('showUpload()', 1500);">
      <h3 class="titulo">Cadastro de V&iacute;deo</h3>
      <div class="box">
        <fieldset>

		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="tipo" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codvideo" value="<?=$codvideo?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$videobo->getValorCampo('imgtemp')?>" />
		<input type="hidden" name="sessao_id" id="sessao_id" value="<?=$videobo->getValorCampo("sessao_id")?>" />
		
		<input type="hidden" name="UPLOAD_IDENTIFIER" id="progress_key" value="<?=$id_upload?>" />

        <legend>Conte&uacute;do</legend>
        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />

		<input type="text" class="txt" id="textfield" size="78" maxlength="60" name="titulo"  <?=$videobo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($videobo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 60)" />
        <input type="text" class="txt counter" value="60" size="4" disabled="disabled" id="cont_titulo" />

        <br />
        <label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label>
        <br />
        <textarea name="descricao" cols="60" rows="10" class="mceSimple" id="textarea" <?=$videobo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 2000);"><?=Util::clearText($videobo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="2000" size="4" disabled="disabled" id="cont_descricao" />
        <br />

<?php if (!$codvideo || $_SESSION['logado_dados']['nivel'] > 6): ?>

        <input type="checkbox" name="tipo" value="2" onclick="javascript:showBoxVideo();" id="youtube" />
        <label for="youtube">Cadastrar v&iacute;deo do youtube</label>
        <br />

        <div id="ytbox" class="display-none"> <br />
          <label for="textfield4">endere&ccedil;o do v&iacute;deo.</label><small>ex. http://www.youtube.com/watch?v=fqMF5TfdXf</small>
          <br />
          <input type="text" name="link_video" value="<?=$videobo->getValorCampo("link_video")?>" class="txt" id="textfield4" <?=$videobo->verificaErroCampo("link_video")?> />
        </div>

        <div id="anexo">
       <br />
       <label for="fileField">Anexar v&iacute;deo*</label>
       <br />
       <small>Voc&ecirc; pode fazer upload de arquivo flv, avi, wmv, asf e mpeg. (Tamanho m&aacute;ximo de 200MB)</small> <br />
        <br />
        <input type="file" name="arquivo_video" id="fileField" size="45" <?=$videobo->verificaErroCampo("arquivo_video")?> />
        <br /><span class="progressbar" id="uploadprogressbar" style="display: none;">0%</span><img src="img/progressbar.gif" width="1" style="border:0px; background:#f4f4f4;" height="1" alt="" /><img src="img/progressbg_yellow.gif" style="border:0px; background:#f4f4f4;" width="1" height="1" alt="" />
        <br />
        <?php if ($videobo->getValorCampo("arquivo_video")):?>
		<div class="arquivo"> <span><?=$videobo->getValorCampo("arquivo_original")?></span></div>
		<?php endif; ?>
       </div>
       
<?php else: ?>

	<?php if ($videobo->getValorCampo("link_video")):?>
		<div class="arquivo"> <span><?=$videobo->getValorCampo("link_video")?></span></div>
	<?php endif; ?>
       
    <?php if ($videobo->getValorCampo("arquivo_video")):?>
		<div class="arquivo"> <span><?=$videobo->getValorCampo("arquivo_original")?></span></div>
	<?php endif; ?>
       
<?php endif; ?>

        <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($videobo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$videobo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$videobo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/video.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$videobo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$videobo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codvideo;?>', 4);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=conteudo&amp;cod=<?=$codvideo;?>&amp;height=180&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>
      

<?php
	include("includes/conteudo_box_categorias.php");
	
	include("includes/conteudo_direitos_autorais.php");
	
	//if ($_SESSION['logado_dados']['cod_colaborador'] && !$codvideo && $_SESSION['logado_dados']['nivel'] >= 5)
	//	include("includes/conteudo_interno_colaborador_vincular.php");
	
	if ($_SESSION['logado_dados']['nivel'] == 2)
		include("includes/conteudo_interno_autorizacao.php");
	
	if (($_SESSION['logado_dados']['nivel'] >= 5) || count($_SESSION['logado_dados']['cod_grupo']))
		include("includes/conteudo_interno_pertence_voce.php");
	
	include("includes/conteudo_autores_wiki.php");
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
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 2000);
	</script>
<?php
	if ($videobo->getValorCampo('pertence_voce') == 1)
		echo '<script language="javascript" type="text/javascript">$(\'#ficha-tecnica\').show(); $(\'#sou_autor_conteudo\').show();</script>';
	if ($videobo->getValorCampo("codcanal") || $videobo->getValorCampo("codsegmento")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-classificar\').show();</script>';
	endif;
	if ($videobo->getValorCampo("tipo") == 2):
		echo '<script language="javascript" type="text/javascript">$(".display-none").show(); $("#anexo").hide(); $("#youtube").attr("checked","checked");</script>';
	endif;
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
