<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$coditem = (int)$_GET["cod"];

include_once("classes/bo/HomeItemEdicaoBO.php");
$homebo = new HomeItemEdicaoBO;
$exibir_form = true;

if ($coditem)
	$homebo->setDadosCamposEdicao($coditem);

$coditem = (int)$homebo->getValorCampo("coditem");
?>

<script language="javascript" type="text/javascript" src="jscripts/conteudo.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/home_destaque.js"></script>

<form action="" method="post" id="lightbox" class="edthome" enctype="multipart/form-data">

  <div class="labels">
  <label for="textfield6">T&iacute;tulo da chamada<span>*</span></label>
  
  <input type="hidden" name="coditem" id="coditem" value="<?=$coditem?>" />
  <input type="hidden" name="imgtemp" id="imgtemp" value="<?=$homebo->getValorCampo('imgtemp')?>" />
  
  <span><a href="javascript:restaurarTitulo(<?=$coditem?>);" title="Restaurar" class="restaurar">Restaurar t&iacute;tulo original</a></span></div>
  
  	<input type="text" class="txt" id="textfield6" size="80" name="titulo" maxlength="100" value="<?=htmlentities(stripslashes($homebo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_home_titulo', 100)" />
	<input type="text" class="txt counter" size="4" disabled="disabled" id="cont_home_titulo" />
  
  <br />
  
  <label for="textarea2">Descri&ccedil;&atilde;o cadastrada pelo autor</label>
  <br />
  <textarea id="textarea2" cols="60" rows="10"><?=strip_tags(stripslashes(utf8_encode($homebo->getValorCampo("original"))))?></textarea>
<br />
  
  <div class="labels"><label for="textarea2">Chamada para home<span>*</span></label>
  <span><a href="javascript:restaurarChamada(<?=$coditem?>);" title="Restaurar" class="restaurar">Restaurar</a></span> </div>
  
	<textarea id="textarea" cols="60" rows="6" name="resumo" onkeyup="contarCaracteres(this, 'cont_home_resumo', 200)"><?=strip_tags(stripslashes(utf8_encode($homebo->getValorCampo("resumo"))))?></textarea>
    <input name="text" type="text" disabled="disabled" class="txt counter" size="4" id="cont_home_resumo" />
  
  	<div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
if ($homebo->getValorCampo('imagem')) {
?>
		<img src="exibir_imagem.php?img=<?=$homebo->getValorCampo('imagem')?>&amp;tipo=<?=Util::iif(substr($homebo->getValorCampo('imagem'), 0, 10) == 'imggaleria', '2', 'a');?>&amp;s=6&amp;rand=<?=md5(microtime())?>" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:removerImagem(<?=$coditem?>);" title="Remover" class="remover">Remover imagem</a>
<?php
} else {
?>
	<img src="img/imagens-padrao/texto.jpg" width="124" height="124" alt=""  />
<?php
}
?>			
			
		</div>

<a href="javascript:restaurarImagem(<?=$coditem?>);" title="Imagem ilustrativa">Restaurar foto original</a>
	</div>
  
  <br />
  <label for="fileField1">Nova imagem para home</label>
imagem  <br />
  <input type="file" class="txt" name="novaimagem" id="novaimagem" size="40" />


  <input type="button" onclick="javascript:validaFormularioDestaque(); tb_remove(); return false;" class="bt-gravar" value="Gravar" />
</form>

<script language="javascript" type="text/javascript">
contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
contarCaracteres(document.getElementById("textarea"), "cont_home_resumo", 200);
</script>