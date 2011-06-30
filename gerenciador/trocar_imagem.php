<?php
include('verificalogin.php');

$tipo = $_GET['tipo'];
$cod = (int)$_GET["cod"];
?>
<script type="text/javascript" src="jscripts/jquery.form.js"></script>

<script language="javascript" type="text/javascript">

function vai() {

options = {
		url: "conteudo_ajax.php?get=imagem_conteudo_upload",
		target: "#imagem_html",
		type: "post",
		success: reexibeImagem
	};
	$('#lightbox').ajaxSubmit(options);

}

function reexibeImagem() {
	var imagemtemp = $('#imagem_html').html();
	if (imagemtemp.length) {
		var html_img = '<img src="exibir_imagem_temp.php?img=' + imagemtemp + '" id="imagem_exibicao" width="124" height="124" alt="" />';
		$('#div_imagem_exibicao').html(html_img);
		$('#imgtemp').val(imagemtemp);
	}
	tb_remove();
}

</script>

<form action="" method="post" id="lightbox" enctype="multipart/form-data">
<input type="hidden" name="cod" value="<?=$cod?>" />
<input type="hidden" name="tipo" value="<?=$tipo?>" />

<?php if ($tipo == 'usuario'): ?>
<p>Insira abaixo uma imagem ou foto sua, que servir&aacute; para identificar voc&ecirc; no portal iTEIA<br />
<small>Tamanho ideal = 124 pixels x 124 pixels</small></p>
<?php else: ?>
<p>&Eacute; poss&iacute;vel associar a este conte&uacute;do uma imagem ilustrativa, ela aparecer&aacute; nos resultados de buscas e destaques do site</p>
<?php endif; ?>

  <label for="fileField1">Procurar</label>
  <br />
  <input type="file" name="imagem" class="txt" id="fileField" size="40" />
  <br />
  <input type="button" id="button_submit_imagem" onclick="vai();" value="Enviar" class="bt"  />
</form>
<div id="imagem_html" style="display: none;"></div>
