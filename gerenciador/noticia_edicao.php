<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codnoticia = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codnoticia)
	$edicaodados = 1;

include_once("classes/bo/NoticiaEdicaoBO.php");
$notbo = new NoticiaEdicaoBO;
$exibir_form = true;

if ($editar) {
	try {
		$cod_conteudo = $notbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: noticia_publicado.php?cod=".$cod_conteudo);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$notbo;

$paginatitulo = 'Inserir notícia';
$item_menu = 'noticias';
$item_submenu = 'inserir';
include('includes/topo.php');

if ($codnoticia && !$editar) {
	$notbo->setDadosCamposEdicao($codnoticia);
} else {
	$notbo->setValorCampo('data', date('d/m/Y'));
	$notbo->setValorCampo('hora', date('H:i'));
}

$codnoticia = (int)$notbo->getValorCampo("codnoticia");
?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<script language="javascript" type="text/javascript" src="jscripts/edicao.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<h2>Not&iacute;cias</h2>

<?php if ($erro_mensagens || $notbo->verificaErroCampo("titulo") || $notbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="noticia_edicao.php" method="post" enctype="multipart/form-data" name="form-noticias" id="form-noticias">
      <h3 class="titulo">Cadastro de not&iacute;cias</h3>
      <div class="box">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codnoticia" value="<?=$codnoticia?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$notbo->getValorCampo('imgtemp')?>" />
		<input type="hidden" name="imgtemp2" id="imgtemp2" value="<?=$notbo->getValorCampo('imgtemp2')?>" />
		<input type="hidden" name="foto_credito" id="foto_credito" value="<?=$notbo->getValorCampo('foto_credito')?>" />
		<input type="hidden" name="foto_legenda" id="foto_legenda" value="<?=$notbo->getValorCampo('foto_legenda')?>" />
		<input type="hidden" name="home_foto_credito" id="home_foto_credito" value="<?=$notbo->getValorCampo('home_foto_credito')?>" />
		<input type="hidden" name="home_foto_legenda" id="home_foto_legenda" value="<?=$notbo->getValorCampo('home_foto_legenda')?>" />

        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />
		<input type="text" class="txt" id="textfield" size="78" maxlength="100" name="titulo"  <?=$notbo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($notbo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 100)" />
        <input type="text" class="txt counter" value="100" size="4" disabled="disabled" id="cont_titulo" />
        <br />
		
        <label for="textfield2">Subt&iacute;tulo</label>
        <br />
        <input type="text" class="txt" id="textfield2" size="80" maxlength="200" name="subtitulo" <?=$notbo->verificaErroCampo("subtitulo")?> value="<?=htmlentities(stripslashes($notbo->getValorCampo("subtitulo")))?>" onkeyup="contarCaracteres(this, 'cont_subtitulo', 200)" />
        <input type="text" class="txt counter" value="200" size="4" disabled="disabled" id="cont_subtitulo" />
        <br />
		
		<label for="textfield3">Assinatura<span>*</span></label>
        <br />
        <input type="text" class="txt" id="textfield3" size="60" maxlength="100" name="assinatura" <?=$notbo->verificaErroCampo("assinatura")?> value="<?=htmlentities(stripslashes($notbo->getValorCampo("assinatura")))?>" />
        <br />
		
        <label for="textarea">Not&iacute;cia<span>*</span></label>
        <br />
        <textarea name="texto" cols="60" style="height:350px;" rows="10" class="mceAdvanced" id="textarea" <?=$notbo->verificaErroCampo("texto")?> onkeyup="contarCaracteres(this, 'cont_descricao', 20000);"><?=Util::clearText($notbo->getValorCampo("texto"));?></textarea>
        <input type="text" class="txt counter" value="20000" size="4" disabled="disabled" id="cont_descricao" />
        <br />
        
        <label for="label">Tags (palavras-chave)</label>
        <br />
        <input type="text" name="tags" id="tags" value="<?=$notbo->getValorCampo("tags")?>" class="txt" size="80" />
        <small>Separe por ponto-e-v&iacute;rgula &quot;;&quot;</small>

        <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($notbo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$notbo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$notbo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/texto.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$notbo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$notbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codnoticia;?>', 5);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem_noticia.php?tipo=conteudo&amp;cod=<?=$codnoticia;?>&amp;height=280&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>
	  
      <div class="box">
        <fieldset id="periodo">
        <legend>Data de publica&ccedil;&atilde;o</legend>
        <div>
          <label for="dFrom">De:</label>
          <input type="text" class="txt calendario date" id="dFrom" name="data" <?=$notbo->verificaErroCampo("data")?> value="<?=htmlentities(stripslashes($notbo->getValorCampo("data")))?>" />
          <em><small>dd/mm/aaaa</small></em></div>
        <div>
          <label for="dTo">Hora:</label>
          <input type="text" class="txt hour" id="dTo" name="hora" <?=$notbo->verificaErroCampo("hora")?> value="<?=htmlentities(stripslashes($notbo->getValorCampo("hora")))?>" />
          <em><small>Ex. hh:mm</small></em></div>
        </fieldset>
      </div>
      
<?php if ($_SESSION['logado_dados']['nivel'] == 1000000): ?>
      
      <label for="n-home">Not&iacute;cia da home?</label>
      <select id="n-home" name="home" onchange="exibirCamposHome()">
        <option value="1"<? if ($notbo->getValorCampo("home")) echo " selected=\"selected\""; ?>>Sim</option>
        <option value="0"<? if (!$notbo->getValorCampo("home")) echo " selected=\"selected\""; ?>>N&atilde;o</option>
      </select>
      <br />
      <div id="chamada-home">
        <div class="box">
          <fieldset>
          <legend>Editar Chamada </legend>
          <label for="textfield6">T&iacute;tulo da chamada<span>*</span></label>
          <span><a href="javascript:homeTituloRestaurar()" title="Restaurar" class="restaurar">Restaurar</a></span> <br />
          <input type="text" class="txt" id="textfield6" size="80" name="home_titulo" <?=$notbo->verificaErroCampo("home_titulo")?> maxlength="100" value="<?=htmlentities(stripslashes($notbo->getValorCampo("home_titulo")))?>" onkeyup="contarCaracteres(this, 'cont_home_titulo', 100)" />
          <input type="text" class="txt counter" size="4" disabled="disabled" id="cont_home_titulo" />
          <br />
          <label for="textarea2">Chamada<span>*</span></label>
          <span><a href="javascript:homeChamadaRestaurar()" title="Restaurar" class="restaurar">Restaurar</a></span> <br />
          <textarea id="textarea2" cols="60" rows="10" name="home_resumo" <?=$notbo->verificaErroCampo("home_resumo")?> onkeyup="contarCaracteres(this, 'cont_home_resumo', 200)"><?=htmlentities(stripslashes($notbo->getValorCampo("home_resumo")))?></textarea>
          <input name="text" type="text" disabled="disabled" class="txt counter" size="4" id="cont_home_resumo" />
		  
	   <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao2">
<?php
	if ($notbo->getValorCampo('imgtemp2')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$notbo->getValorCampo('imgtemp2')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$notbo->getValorCampo('imagem_home')) {
?>
        	<img src="img/imagens-padrao/texto.jpg" id="imagem_exibicao2" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$notbo->getValorCampo('imagem_home')?>" name="imagem_home" />
	  		<img src="exibir_imagem.php?img=<?=$notbo->getValorCampo('imagem_home')?>&amp;tipo=5&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codnoticia;?>', '7');" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem_home.php?tipo=conteudo&amp;cod=<?=$codnoticia;?>&amp;height=280&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>
          </fieldset>
      </div>
	 </div>
	 
<?php endif; ?>	 

      <div id="botoes" class="box"> <a href="noticias.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<?php if ($exibir_form): ?>
<script language="javascript" type="text/javascript">
contarCaracteres(document.getElementById("textfield"), "cont_titulo", 100);
contarCaracteres(document.getElementById("textfield2"), "cont_subtitulo", 200);
contarCaracteres(document.getElementById("textarea"), "cont_descricao", 2000);
//contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
//contarCaracteres(document.getElementById("textarea2"), "cont_home_resumo", 200);
//exibirCamposHome();
</script>
<?php
endif;
?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
