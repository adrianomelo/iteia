function conteudoPertenceVoceMostra() {
	$('#sou_autor_conteudo').slideDown("slow");
}

function conteudoPertenceVoceOculta() {
	$('#sou_autor_conteudo').slideUp("slow");
}

function exibeEstadoCidade2() {
	if (document.getElementById('pais').value == '2') {
		document.getElementById('mostraestado').style.display = 'inline';
		document.getElementById('selectcidade').style.display = 'inline';
		document.getElementById('selectcidade2').style.display = 'inline';
		document.getElementById('cidade').style.display = 'none';
		$("#campocidade").val('');
	} else {
		document.getElementById('mostraestado').style.display = 'none';
		document.getElementById('selectcidade').style.display = 'none';
		document.getElementById('selectcidade2').style.display = 'none';
		document.getElementById('cidade').style.display = 'inline';
	}
}

function exibeEstadoCidade3() {
	if (document.getElementById('pais_edicao').value == '2') {
		document.getElementById('mostraestado_edicao').style.display = 'inline';
		document.getElementById('selectcidade_edicao').style.display = 'inline';
		document.getElementById('selectcidade3_edicao').style.display = 'inline';
		document.getElementById('cidade_edicao').style.display = 'none';
		$("#campocidade_edicao").val('');
	} else {
		document.getElementById('mostraestado_edicao').style.display = 'none';
		document.getElementById('selectcidade_edicao').style.display = 'none';
		document.getElementById('selectcidade3_edicao').style.display = 'none';
		document.getElementById('cidade_edicao').style.display = 'inline';
	}
}

function obterCidades3(obj, codcidade, nomeid) {
	codestado = obj.options[obj.options.selectedIndex].value;
	if (codestado == "0") {
		removeAllChildren(document.getElementById(nomeid));
		node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Selecione a Cidade"));
		document.getElementById(nomeid).appendChild(node_option);
	}
	else {
		AjaxRequest();
		var url = "edicao_ajax.php?acao=getcidades&codestado=" + codestado;
		Ajax.onreadystatechange = function () {
			if ((Ajax.readyState == 4) && (Ajax.status == 200))
				listaCidades3(codcidade, nomeid);
		}
		Ajax.open("GET", url, true);
		Ajax.send(null);
	}
}

function listaCidades3(codcidade, nomeid) {
	var select_cidades = document.getElementById(nomeid);
	removeAllChildren(select_cidades);

	node_option = document.createElement("option");
	node_option.setAttribute("value", "0");
	node_option.appendChild(document.createTextNode("Selecione a Cidade"));
	select_cidades.appendChild(node_option);

	var cidades = Ajax.responseXML.getElementsByTagName("cidades")[0];
	for (i = 0; i < cidades.childNodes.length; i++) {
		codcid = cidades.childNodes[i].getAttribute("cod");
		node_option = document.createElement("option");
		node_option.setAttribute("value", codcid);
		if (codcid == codcidade)
			node_option.setAttribute("selected", "selected");
		node_option.appendChild(document.createTextNode(cidades.childNodes[i].firstChild.nodeValue));
		select_cidades.appendChild(node_option);
	}
}

function adicionarAutorFichaPessoal() {
	if ($('#ficha_atividade_pessoal').val() == 0) {
		$("#ficha_atividade_pessoal").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
	} else {
		$("#ficha_atividade_pessoal").attr({ style: '1px solid #8E8E8E; background: #FFF'});
		if (cod_autor_pessoal) {
			$.get("ajax_conteudo.php?get=adicionar_autorficha_nalista&cod=" + cod_autor_pessoal + "&atividade=" + $('#ficha_atividade_pessoal').val()+"&sessao_id="+sessao_id, function (html) { $("#mostra_autores_wiki_selecionados").html(html); });
			//exibeListaAutoresFicha();
			limparFormularioFicha();
		}
		else {
			if (!nao_adiciona_wiki)
				adicionarAutorWiki();
		}
	}
}

function adicionarAutorFicha() {
	if ($('#autor_selecionado').val()) {
		if ($('#ficha_atividade').val() == 0) {
			$("#ficha_atividade").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		} else {
			$("#ficha_atividade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
			$.get("ajax_conteudo.php?get=adicionar_autorficha_nalista&cod=" + $('#autor_selecionado').val() + "&atividade=" + $('#ficha_atividade').val()+"&sessao_id="+sessao_id, function(html) { $("#mostra_autores_wiki_selecionados").html(html); });
			//exibeListaAutoresFicha();
			limparFormularioFicha();
		}
	}
	else {
		if (!nao_adiciona_wiki)
			adicionarAutorWiki();
	}
}

function aleatorio(inferior, superior) {
    numPossibilidades = superior - inferior;
    aleat = Math.random() * numPossibilidades;
    aleat = Math.floor(aleat);
    return parseInt(inferior) + aleat;
} 

function removerAutorFicha(cod) {
	$.get("ajax_conteudo.php?get=remover_autor_listaficha&cod=" + cod + "&sessao_id="+sessao_id);
	cancelarAutorFicha();
	exibeListaAutoresFicha();
}

function exibeListaAutoresFicha() {
	$("#mostra_autores_wiki_selecionados").load("ajax_conteudo.php?get=listar_autores_ficha&timestamp=" + aleatorio(1, 10000000) + "&sessao_id="+sessao_id);
}

function abreEdicaoAutorFicha(codautor) {
	$.getJSON("ajax_conteudo.php?get=autor_dados_ficha&cod="+codautor+"&sessao_id="+sessao_id, function(dados) {		
		$("#nome_autor_wiki_edicao").val(dados['nome_autor']);
		$("#ficha_nome_completo_edicao").val(dados['nome']);
		$("#ficha_atividade_edicao").val(dados['atividade']);
		$("#pais_edicao").val(dados['pais']);
		$("#estado_edicao").val(dados['estado']);
		if (dados['estado'])
			obterCidades3(document.getElementById("estado_edicao"), dados['codcidade'], 'selectcidade_edicao');
			
		exibeEstadoCidade3();
			
		//$("#selectcidade").val(dados['codcidade']);
		$("#campocidade_edicao").val(dados['cidade']);
		$("#ficha_email_edicao").val(dados['email']);
		$("#ficha_telefone_edicao").val(dados['telefone']);
		$("#ficha_falecido_edicao").attr('checked', dados['falecido']);
		$("#ficha_descricao_edicao").val(dados['descricao']);
	});
}

function editarAutorFicha() {

	var erro = 0;

	if ($('#nome_autor_wiki_edicao').val() == '') {
		$("#nome_autor_wiki_edicao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#nome_autor_wiki_edicao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	
	if ($('#ficha_atividade_edicao').val() == 0) {
		$("#ficha_atividade_edicao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#ficha_atividade_edicao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	
	if ($('#ficha_descricao_edicao').val() == '') {
		$("#ficha_descricao_edicao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#ficha_descricao_edicao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}

	if ($('#pais_edicao').val() == 2) {
		if ($('#selectcidade_edicao').val() == null || $('#selectcidade_edicao').val() == 0) {
			$("#selectcidade_edicao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
			erro = 1;
		} else {
			$("#selectcidade_edicao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
		}
	} else if ($('#pais_edicao').val() != 2) {
		if ($('#campocidade_edicao').val() == '') {
			$("#campocidade_edicao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF; width: 300px;'});
			erro = 1;
		} else {
			$("#campocidade_edicao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
		}
	}

	if (erro)
		return false;
	else {
		var marcado = 0;
		if(document.getElementById('ficha_falecido_edicao').checked == true) marcado = 1;
		
		$.get("ajax_conteudo.php?get=editar_autor_wiki&nome=" + $('#nome_autor_wiki_edicao').val() + "&nome_completo=" + $('#ficha_nome_completo_edicao').val() + "&atividade=" + $('#ficha_atividade_edicao').val() + "&codpais=" + $('#pais_edicao').val() + "&codestado=" + $('#estado_edicao').val() + "&codcidade=" + $('#selectcidade_edicao').val() + "&cidade=" + $('#campocidade_edicao').val() + "&email=" + $('#ficha_email_edicao').val() + "&telefone=" + $('#ficha_telefone_edicao').val() + "&falecido=" + marcado + "&descricao=" + encodeURI($('#ficha_descricao_edicao').val()) + "&codautor=" + $('#cod_autor_edicao').val() + "&sessao_id="+sessao_id, function(html) { $("#mostra_autores_wiki_selecionados").html(html); });
		//exibeListaAutoresFicha();
		tb_remove();
		//cancelarAutorFicha();
	}
}

function cancelarAutorFicha() {
	$("#btn_adicionar").show();
	$("#btn_editar").hide();
	$("#btn_cancelar").hide();
	
	$("#nome_autor_wiki").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#ficha_atividade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#selectcidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#campocidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#ficha_descricao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	
	limparFormularioFicha();
	exibeEstadoCidade2();
}

function limparFormularioFicha() {
	$("#autor_selecionado").val('');
	$("#autor_selecionado_atualizar").val('');
	$("#nome_autor_wiki").val('');
	$("#ficha_atividade").val(0);
	$("#ficha_nome_completo").val('');
	$("#pais").val(2);
	$("#estado").val(17);
	obterCidades(document.getElementById("estado"), 6330);
	//$("#cidade").val(6330);
	$("#campocidade").val('');
	$("#ficha_email").val('');
	$("#ficha_telefone").val('');
	
	$("#ficha_descricao").val('');
	$("#ficha_falecido").attr('checked', false);
	
	$("#nome_autor_wiki").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#ficha_atividade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#selectcidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#campocidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	$("#ficha_descricao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
}

function adicionarAutorWiki() {

	var erro = 0;

	if ($('#nome_autor_wiki').val() == '') {
		$("#nome_autor_wiki").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#nome_autor_wiki").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	
	if ($('#ficha_atividade').val() == 0) {
		$("#ficha_atividade").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#ficha_atividade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	
	if ($('#ficha_descricao').val() == '') {
		$("#ficha_descricao").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#ficha_descricao").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}

	if ($('#pais').val() == 2) {
		if ($('#selectcidade').val() == null) {
			$("#selectcidade").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
			erro = 1;
		} else {
			$("#selectcidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
		}
	} else if ($('#pais').val() != 2) {
		if ($('#campocidade').val() == '') {
			$("#campocidade").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
			erro = 1;
		} else {
			$("#campocidade").attr({ style: '1px solid #8E8E8E; background: #FFF'});
		}
	}

	if (erro)
		return false;
	else {
		var marcado = 0;
		if(document.getElementById('ficha_falecido').checked == true) marcado = 1;
		
		$.get("ajax_conteudo.php?get=adicionar_autor_wiki&nome=" + $('#nome_autor_wiki').val() + "&nome_completo=" + $('#ficha_nome_completo').val() + "&atividade=" + $('#ficha_atividade').val() + "&codpais=" + $('#pais').val() + "&codestado=" + $('#estado').val() + "&codcidade=" + $('#selectcidade').val() + "&cidade=" + $('#campocidade').val() + "&email=" + $('#ficha_email').val() + "&telefone=" + $('#ficha_telefone').val() + "&falecido=" + marcado + "&descricao=" + encodeURI($('#ficha_descricao').val() + "&sessao_id="+sessao_id), function(html) { $("#mostra_autores_wiki_selecionados").html(html); });
		//exibeListaAutoresFicha();
		limparFormularioFicha();
	}
}
