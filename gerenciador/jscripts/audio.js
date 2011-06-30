var total_faixas = 0;

function adicionarMaisCampos() {
	if (total_faixas < 50) {
		for (i = 0; i < 5; i++) {
			total_faixas++;
		
			var node_campoaudio = document.createElement("input");
			node_campoaudio.setAttribute("type", "file");
			node_campoaudio.setAttribute("name", "audio[]");
			node_campoaudio.setAttribute("size", "40");
			node_campoaudio.setAttribute("id", "audio_" + total_faixas);
		
			document.getElementById("div_adicionar_mais_faixas").appendChild(node_campoaudio);
			document.getElementById("div_adicionar_mais_faixas").appendChild(document.createElement("br"));
		}
	}
}

function enviaAudioAlbum() {
	options = {
		url: "ajax_conteudo.php?get=audio_upload&sessao_id="+sessao_id+"&titulofaixa="+$("#titulofaixa").val()+"&tempo="+$("#tempo").val()+getTitulos(),
		target: "#mostra_album_audios",
		beforeSubmit: exibeImgLoading(true),
		success: resetaCampoAudio
	};
	$('#form-audio').ajaxSubmit(options);
}

function submeteFormAudio() {
	if ($('#possui_audios').val() == 1)
		$.post("ajax_conteudo.php?get=salvar_audio_titulos&sessao_id="+sessao_id+getTitulos(), function html(html) { $('#form-audio').submit() });
	else
		alert('Envie ao menos um arquivo .MP3');
	//$('#form-audio').submit();
}

function salvaTitulos() {
	$.post("ajax_conteudo.php?get=salvar_audio_titulos&sessao_id="+sessao_id+getTitulos());
	//$.post("ajax_conteudo.php?get=salvar_audio_titulos"+getTitulos()+"&sessao_id="+sessao_id, function html(html) { alert(html) });
}

function getTitulos() {
	var lista_inputlegendas = $("input[type=text]");
	var comp_url = '';
	for (i = 0; i < lista_inputlegendas.length; i++) {
		var item = lista_inputlegendas[i];
		comp_url += '&'+item.name+'='+item.value;
	}
	return comp_url;
}

function resetaCampoAudio() {
	exibeImgLoading(false);
	$("#div_adicionar_mais_faixas").html("");
	adicionarMaisCampos();
	
	//$("#div_campoaudio").html("");
	//$("#div_campoaudio").html("<input type=\"file\" name=\"audio\" id=\"fileField1\" size=\"40\" />");
	//$("#titulofaixa").val("");
	//$("#tempo").val("");
}

/*
function exibeImgLoading(opc) {
	if (opc)
		$("#loading").show();
	else
		$("#loading").hide();
}
*/

function exibeImgLoading(opc) {
	if (opc)
		tb_show();
	else
		tb_remove();
}

function removerAudioAlbum() {
	var lista_checkboxes = $("input[name=codaudio]");
	var lista_marcados = new Array;
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$("#mostra_album_audios").load("ajax_conteudo.php?get=remover_audio_album&codaudio="+lista_marcados.join(",")+"&sessao_id="+sessao_id);
}

function irPaginaBuscaAudios(numpagina) {
	$("#mostra_album_audios").load("ajax_conteudo.php?get=listar_audios_album&sessao_id="+sessao_id);
}

function executaAcaoAudiosSelecionados(num) {
	var lista_checkboxes = $("input[name=codaudio]");
	var lista_marcados = new Array;
	var total = "";
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)) {
			lista_marcados.push(item.value);
			total++;
		}
	}
	
	if (total == 0)
		alert('Selecione ao menos um áudio!');
	else {
		salvaTitulos();
		$("#mostra_album_audios").load("ajax_conteudo.php?get=executa_acao_audio&numacao=" + num + "&itens=" + lista_marcados.join(",")+"&sessao_id="+sessao_id);
	}
}

function playTrack(url, song) {
	flashembed("player", { src: url+'FlowPlayerDark.swf', width: '320', height: '29' }, {config: { videoFile: song, initialScale: 'scale', autoPlay: true }});
}
