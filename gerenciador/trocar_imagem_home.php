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
		url: "conteudo_ajax.php?get=imagem_noticia_upload",
		target: "#imagem_html2",
		type: "post",
		success: reexibeImagem
	};
	$('#lightbox').ajaxSubmit(options);
	}
}

function reexibeImagem() {
	var imagemtemp = $('#imagem_html2').html();
	if (imagemtemp.length) {
		var dados_img = imagemtemp.split('[!]');
		$('#home_foto_credito').val(dados_img[1]);
		$('#home_foto_legenda').val(dados_img[2]);
		if (dados_img[0].length) {
			var html_img = '<img src="exibir_imagem_temp.php?img=' + dados_img[0] + '" width="124" height="124" alt="" />';
			$('#div_imagem_exibicao2').html(html_img);
			$('#imgtemp2').val(dados_img[0]);
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
<div id="imagem_html2" style="display: none;"></div>
</body>
<script type="text/javascript">
$('#textfield4').val($('#home_foto_credito').val());
$('#textfield5').val($('#home_foto_legenda').val());
</script>
