<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$coditem = (int)$_GET["cod"];

include_once("classes/bo/NewsletterItemEdicaoBO.php");
$itembo = new NewsletterItemEdicaoBO;
$exibir_form = true;

if ($coditem)
	$itembo->setDadosCamposEdicao($coditem);

$coditem = (int)$itembo->getValorCampo("coditem");
?>

<script language="javascript" type="text/javascript" src="jscripts/conteudo.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/newsletter_item.js"></script>

<form action="" method="get" id="lightbox" enctype="multipart/form-data">
  <fieldset>
  
  <input type="hidden" name="coditem" id="coditem" value="<?=$coditem?>" />
  <input type="hidden" name="imgtemp" id="imgtemp" value="<?=$itembo->getValorCampo('imgtemp')?>" />
  
  <label for="textfield6">T&iacute;tulo da chamada<span>*</span></label>
  <span><a href="javascript:restaurarTitulo(<?=$coditem?>);" title="Restaurar" class="restaurar">Restaurar</a></span> <br />
  
  	<input type="text" class="txt" id="textfield6" size="80" name="titulo" maxlength="100" value="<?=htmlentities(stripslashes($itembo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_home_titulo', 100)" />
	<input type="text" class="txt counter" size="4" disabled="disabled" id="cont_home_titulo" />
  
  <br />
  <label for="textarea2">Chamada<span>*</span></label>
  <span><a href="javascript:restaurarChamada(<?=$coditem?>);" title="Restaurar" class="restaurar">Restaurar</a></span> <br />
  
  	<textarea id="textarea" cols="60" rows="6" name="resumo" onkeyup="contarCaracteres(this, 'cont_home_resumo', 200)"><?=strip_tags(stripslashes(utf8_encode($itembo->getValorCampo("resumo"))))?></textarea>
    <input name="text" type="text" disabled="disabled" class="txt counter" size="4" id="cont_home_resumo" />

  <br />
  <label for="fileField1">Procurar</label>
imagem  <br />
  <input type="file" name="novaimagem" class="txt" id="fileField" size="40" />
  <br />
  <label for="cred">Cr&eacute;dito</label>
        <br />
        
		<input type="text" class="txt" id="cred" size="50" name="credito" maxlength="100" value="<?=htmlentities(stripslashes($itembo->getValorCampo("credito")))?>" onkeyup="contarCaracteres(this, 'cont_home_credito', 40)" />
		<input type="text" class="txt counter" size="4" value="40" disabled="disabled" id="cont_home_credito" />
      
        <br />
        <div>
		
<div id="div_imagem_exibicao">
<?php
if ($itembo->getValorCampo('imagem')) {
?>
		<a href="javascript:restaurarImagem(<?=$coditem?>);" title="Remover" class="remover">Restaurar imagem</a><img src="exibir_imagem.php?img=<?=$itembo->getValorCampo('imagem')?>&amp;tipo=<?=Util::iif(substr($itembo->getValorCampo('imagem'), 0, 10) == 'imggaleria', '2', 'a');?>&amp;s=21&amp;rand=<?=md5(microtime())?>" alt="" id="imagem_exibicao" />
<?php
} else {
?>
	<img src="img/imagens-padrao/texto.jpg" width="265" height="170" alt=""  />
<?php
}
?>
			
		</div>
		
		</div><br />

  </fieldset>
  </div>
  </fieldset>
  <input type="submit" class="bt-gravar" onclick="javascript:validaFormularioDestaque(); tb_remove(); return false;" value="Gravar" />
</form>

<script language="javascript" type="text/javascript">
contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
contarCaracteres(document.getElementById("cred"), "cont_home_credito", 40);
contarCaracteres(document.getElementById("textarea"), "cont_home_resumo", 200);
</script>