var total_faixas = 0;

function adicionarMaisCampos() {
	if (total_faixas < 50) {
		for (i = 0; i < 5; i++) {
			total_faixas++;
		
			var node_campoaudio = document.createElement("input");
			node_campoaudio.setAttribute("type", "file");
			node_campoaudio.setAttribute("name", "imagem[]");
			node_campoaudio.setAttribute("size", "40");
			node_campoaudio.setAttribute("id", "imagem_" + total_faixas);
		
			document.getElementById("div_adicionar_mais_imagens").appendChild(node_campoaudio);
			document.getElementById("div_adicionar_mais_imagens").appendChild(document.createElement("br"));
		}
	}
}

function enviaImagemGaleria() {
	options = {
		url: "ajax_conteudo.php?get=imagem_upload&sessao_id="+sessao_id+"&legenda="+$("#textfield2").val()+"&credito="+$("#credito").val()+getLegendas(),
		target: "#mostra_galeria_imagens",
		beforeSubmit: exibeImgLoading(true),
		success: resetaCampoImagem
	};
	$('#form-imagem').ajaxSubmit(options);
}

function salvaLegendas() {
	$.post("ajax_conteudo.php?get=salvar_imagem_legendas"+getLegendas());
}

function getLegendas() {
	var lista_inputlegendas = $("input[@type=text]");
	var comp_url = '';
	for (i = 0; i < lista_inputlegendas.length; i++) {
		var item = lista_inputlegendas[i];
		comp_url += '&'+item.name+'='+item.value;
	}
	comp_url += '&capa='+$("#capa").val();
	return comp_url;
}

function resetaCampoImagem() {
	exibeImgLoading(false);
	$("#div_adicionar_mais_imagens").html("");
	adicionarMaisCampos();
	
	//$("#div_campoimg").html("");
	//$("#div_campoimg").html("<input type=\"file\" name=\"imagem\" id=\"fileField1\" class=\"multi-pt\" size=\"40\" />");
	//$("#textfield2").val("");
	//$("#credito").val("");
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

function removerImagemAlbum() {
	var lista_checkboxes = $("input[@name=codimagem]");
	var lista_marcados = new Array;
	var capa = $("#capa").val();
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value))
			lista_marcados.push(item.value);
	}
	$("#mostra_galeria_imagens").load("ajax_conteudo.php?get=remover_imagem_album&codimg="+lista_marcados.join(",")+"&capa="+capa+"&sessao_id="+sessao_id);
}


function buscaMudaIntervaloImagem(interv) {
	var valor_total = parseInt($("#total").val());
	var valor_pagina = parseInt($("#pagina").val());
	var capa = $("#capa").val();
	var pagina_max = Math.floor((valor_total - 1) / parseInt(interv)) + 1;
	if (valor_pagina > pagina_max)
		$("#pagina").val(pagina_max);
	$("#intervalo").val(interv);
	$("#mostra_galeria_imagens").load("ajax_conteudo.php?get=listar_imagens_album&intervalo="+interv+"&total="+valor_total+"&capa="+capa+"&sessao_id="+sessao_id);
}

function irPaginaBuscaImagens(numpagina) {
	$("#mostra_galeria_imagens").load("ajax_conteudo.php?get=listar_imagens_album&pagina="+numpagina+"&intervalo="+$("#intervalo").val()+"&total="+$("#total").val()+"&capa="+$("#capa").val()+"&sessao_id="+sessao_id);
}

function definirCapaAlbum() {
	var lista_checkboxes = $("input[@name=codimagem]");
	var lista_marcados = new Array;
	var total = "";
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)) {
			lista_marcados.push(item.value);
			total++;
		}
	}
	
	if (total > 1)
		alert('Apenas uma imagem pode ser definida como capa!');
	else if (total == 0)
		alert('Selecione uma imagem pode ser definida como capa!');
	else {
		$("#capa").val(lista_marcados);
		salvaLegendas();
		$("#mostra_galeria_imagens").load("ajax_conteudo.php?get=definir_capa&codimg="+lista_marcados+"&sessao_id="+sessao_id);
	}
}

function executaAcaoImagemSelecionadas(num) {
	var lista_checkboxes = $("input[@name=codimagem]");
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
		alert('Selecione ao menos uma imagem!');
	else {
		salvaLegendas();
		$("#mostra_galeria_imagens").load("ajax_conteudo.php?get=executa_acao_imagem&numacao=" + num + "&itens=" + lista_marcados.join(",")+'&capa='+$("#capa").val()+"&sessao_id="+sessao_id);
	}
}
