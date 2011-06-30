<?php
include('verificalogin.php');

$tipo = $_GET['tipo'];
$cod = (int)$_GET["cod"];
?>
<script type="text/javascript" src="jscripts/jquery.form.js"></script>
<script language="javascript" type="text/javascript">

function vai() {

	if ($('#textfield4').val() == '') {
		$("#textfield4").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
	} else {

		options = {
			url: "conteudo_ajax.php?get=imagem_texto_upload",
			target: "#imagem_html",
			type: "post",
			success: reexibeImagem
		};
		
		$('#lightbox').ajaxSubmit(options);
	
	}
}

function reexibeImagem() {
	var imagemtemp = $('#imagem_html').html();
	if (imagemtemp.length) {
		var dados_img = imagemtemp.split('[!]');
		$('#foto_credito').val(dados_img[1]);
		$('#foto_legenda').val(dados_img[2]);
		if (dados_img[0].length) {
			var html_img = '<img src="exibir_imagem_temp.php?img=' + dados_img[0] + '" width="124" height="124" alt="" />';
			$('#div_imagem_exibicao').html(html_img);
			$('#imgtemp').val(dados_img[0]);
		}
	}
	tb_remove();
}

</script>

<form action="" method="post" id="lightbox" enctype="multipart/form-data">
<input type="hidden" name="cod" value="<?=$cod?>" />
<input type="hidden" name="tipo" value="<?=$tipo?>" />
<p>&Eacute; poss&iacute;vel associar a este conte&uacute;do uma imagem ilustrativa, ela aparecer&aacute; nos resultados de buscas e destaques do site</p>
  <label for="fileField1">Procurar</label>
  <br />
  <input type="file" name="imagem" class="txt" id="fileField2" size="40" />
  <br />
  <label for="textfield4">Cr&eacute;dito<span>*</span></label>
  <br />
  <input type="text" name="foto_credito" class="txt" id="textfield4" size="80" maxlength="100" />
  <br />
  <label for="textfield5">Legenda<span></span></label>
  <br />
  <input type="text" name="foto_legenda" class="txt" id="textfield5" size="80" maxlength="200" />
  <br />
  <input type="button" id="button_submit_imagem" onclick="vai();" value="Enviar" class="bt"  />
</form>
<div id="imagem_html" style="display: none;"></div>
</body>
<script type="text/javascript">
$('#textfield4').val($('#foto_credito').val());
$('#textfield5').val($('#foto_legenda').val());
</script>
