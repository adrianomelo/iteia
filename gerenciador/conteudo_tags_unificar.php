<script language="javascript" type="text/javascript">
function unificarTags() {
	if ($('#nova_tag').val() == '') {
		$("#nova_tag").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
	} else {
		var lista_checkboxes = $("input[@id=cod_tags]");
		var lista_marcados = new Array;

		for (i = 0; i < lista_checkboxes.length; i++) {
			var item = lista_checkboxes[i];
			if ((item.type == "checkbox") && item.checked && parseInt(item.value))
				lista_marcados.push(item.value);
		}
		$.get("ajax_conteudo.php?get=unificar_tags&codtag="+lista_marcados.join(",")+"&tag="+$('#nova_tag').val());
		window.location = 'conteudo_tags.php';
	}
}
</script>
<form action="" onsubmit="return false;" method="get" id="lightbox">
<fieldset>
<p>As tags selecionadas ser&atilde;o substituidas pela nova tag</p>
  <label for="textfield">Nome<span> da nova tag</span></label>
  <br />
  <input type="text" class="txt" id="nova_tag" />
  <br>
  <input type="button" onclick="javascript:unificarTags();" value="Salvar" class="bt-adicionar" />
  </fieldset>
</form>
</div>
