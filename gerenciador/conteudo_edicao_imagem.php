<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codalbum = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codalbum)
	$edicaodados = 1;

if (!$editar)
	$sessao_id = Util::geraRandomico('num'); // gera uma nova id pra sessão

include_once("classes/bo/AlbumImagemEdicaoBO.php");
$albumbo = new AlbumImagemEdicaoBO;
$exibir_form = true;

if (!isset($_SESSION["sess_conteudo_imagens_album"]))
	$_SESSION["sess_conteudo_imagens_album"] = array();
if (!isset($_SESSION["sess_conteudo_autores_ficha"]))
	$_SESSION["sess_conteudo_autores_ficha"] = array();

if (!$editar) {
	//unset($_SESSION["sess_conteudo_imagens_album"]);
	//unset($_SESSION["sess_conteudo_autores_ficha"]);
}

//echo $sessao_id;

$codformato_class = 2;

if ($editar) {
	try {
		$cod_conteudo = $albumbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: conteudo_publicado_imagem.php?cod=".$cod_conteudo);
		exit();
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$albumbo;

$item_menu = 'conteudo';
$item_submenu = 'inserir';
$nao_carregar_thickbox = true;
include('includes/topo.php');

if (!$editar)
	$albumbo->setValorCampo('sessao_id', $sessao_id);

if ($codalbum && !$editar)
	$albumbo->setDadosCamposEdicao($codalbum);

$permitir_comentarios = $albumbo->getValorCampo("permitir_comentarios");
if (!$codalbum)
	$permitir_comentarios = true;

$codalbum = (int)$albumbo->getValorCampo("codalbum");
$sessao_id = $albumbo->getValorCampo("sessao_id");
?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<script language="javascript" type="text/javascript">
var cod_autor_pessoal = '<?=$_SESSION['logado_dados']['cod'];?>';
var sessao_id = '<?=$sessao_id;?>';
</script>

<h2>Conte&uacute;do</h2>

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>

<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>

<?php if ($erro_mensagens || $albumbo->verificaErroCampo("titulo") || $albumbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>

<script language="javascript" type="text/javascript" src="jscripts/conteudo.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/imagem.js"></script>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

    <form action="conteudo_edicao_imagem.php" method="post" enctype="multipart/form-data" name="form-imagem" id="form-imagem">
      <h3 class="titulo">Cadastro de &aacute;lbum</h3>
      <div class="box">
        <fieldset>
        
		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codalbum" value="<?=$codalbum?>" />
		<input type="hidden" name="sessao_id" id="sessao_id" value="<?=$albumbo->getValorCampo("sessao_id")?>" />
		<input type="hidden" id="capa" name="capa" value="<?=$albumbo->getValorCampo("capa")?>" />
		
        <legend>Conte&uacute;do</legend>
        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />

        <input type="text" name="titulo" class="txt" <?=$albumbo->verificaErroCampo("titulo")?> id="textfield" size="78" maxlength="60" value="<?=htmlentities(stripslashes($albumbo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 60)" />
      	<input type="text" class="txt counter" size="4" disabled="disabled" value="60" id="cont_titulo" />

        <br />
        <label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label>
        <br />

        <textarea name="descricao" cols="60" rows="10" class="mceSimple" id="textarea" <?=$albumbo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 2000);"><?=Util::clearText($albumbo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="2000" size="4" disabled="disabled" id="cont_descricao" />

        <br />
        </fieldset>
      </div>
      
<?php include("includes/conteudo_box_categorias.php"); ?>

      <div class="box">
        <fieldset>
        <legend>Imagens</legend>
        <p>Voc&ecirc; pode fazer upload de um ou de mais arquivos JPG, GIF ou PNG (tamanho m&aacute;ximo   de 5MB cada)</p>
        <label for="fileField1">Procurar</label><br />
        
        <!--
        <div id="div_campoimg"><input type="file" name="imagem" id="fileField1" class="multi-pt" size="40" /></div>
        <br />
		<label for="cred">Cr&eacute;dito</label>
        <br />
        <input type="text" name="legenda" class="txt" onkeyup="contarCaracteres(this, 'cont_credito', 60);" id="credito" size="80" maxlength="200" />
        <input type="text" disabled="disabled" class="txt counter" id="cont_credito" value="60" size="4" />
        <br />
        <label for="leg">Legenda</label>
        <br />
        <input type="text" name="legenda" class="txt" onkeyup="contarCaracteres(this, 'cont_legenda', 60);" id="textfield2" size="80" maxlength="200" />
        <input type="text" disabled="disabled" id="cont_legenda" class="txt counter" value="60" size="4" />

		<br />
        <input type="button" id="bt_carregar_imggaleria" onclick="enviaImagemGaleria();" class="bt-adicionar" value="Adicionar" />
        <br />
        <br />
        -->
        
        <div id="div_adicionar_mais_imagens"></div>
        
		<p><a href="javascript:void(0);" onclick="adicionarMaisCampos();">Selecionar mais imagens</a></p>
		
		<input type="button" onclick="enviaImagemGaleria();" class="bt-adicionar" value="Enviar" /><br /><br />

        <div id="mostra_galeria_imagens"></div>

        </fieldset>
      </div>

<?php
	include("includes/conteudo_direitos_autorais.php");
	
	//if ($_SESSION['logado_dados']['cod_colaborador'] && !$codalbum && $_SESSION['logado_dados']['nivel'] >= 5)
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
        <input type="submit" class="bt-gravar" onclick="javascript:salvaLegendas();" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<?php if ($exibir_form): ?>
	<script language="javascript" type="text/javascript">
	adicionarMaisCampos();
	contarCaracteres(document.getElementById("textfield"), "cont_titulo", 60);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 2000);
	<?php if (count($_SESSION["sess_conteudo_imagens_album"][$sessao_id])): ?>
	irPaginaBuscaImagens('1');
<?php endif; ?>
	</script>
<?php
	if ($albumbo->getValorCampo('pertence_voce') == 1)
		echo '<script language="javascript" type="text/javascript">$(\'#ficha-tecnica\').show(); $(\'#sou_autor_conteudo\').show();</script>';
	if ($albumbo->getValorCampo("codcanal") || $albumbo->getValorCampo("codsegmento")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-classificar\').show();</script>';
	endif;
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>