function exibeEstadoCidade() {
	if (document.getElementById('pais').value == '2') {
		document.getElementById('mostraestado').style.display = 'inline';
		document.getElementById('selectcidade').style.display = 'inline';
		document.getElementById('cidade').style.display = 'none';
	} else {
		document.getElementById('mostraestado').style.display = 'none';
		document.getElementById('selectcidade').style.display = 'none';
		document.getElementById('cidade').style.display = 'inline';
	}
}

function obterCidades(obj, codcidade) {
	codestado = obj.options[obj.options.selectedIndex].value;
	if (codestado == "0") {
		removeAllChildren(document.getElementById("selectcidade"));
		node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Selecione a Cidade"));
		document.getElementById("selectcidade").appendChild(node_option);
	}
	else {
		AjaxRequest();
		var url = "edicao_ajax.php?acao=getcidades&codestado=" + codestado;
		Ajax.onreadystatechange = function () {
			if ((Ajax.readyState == 4) && (Ajax.status == 200))
				listaCidades(codcidade);
		}
		Ajax.open("GET", url, true);
		Ajax.send(null);
	}
}

function listaCidades(codcidade) {
	var select_cidades = document.getElementById("selectcidade");
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

function adicionarContato() {
	$('#lista_contatos').load('ajax_contatos.php?acao=adicionar&cod_tipo='+$('#select5').val()+'&nome_usuario='+$('#textfield18').val());
	$('#textfield18').val('')
}

function removerContato(cod) {
	$('#lista_contatos').load('ajax_contatos.php?acao=remover&cod='+cod);
}

function carregaContatos() {
	$('#lista_contatos').load('ajax_contatos.php?acao=listar');
}

function adicionarSiteRelacionado() {

	$.post("ajax_linksrelacionados.php?acao=adicionar", { titulo: $('#textfield19').val(), endereco: $('#textfield17').val() }, function(data) { $('#lista_sitesrelacionados').html(data) } );

	$('#textfield19').val('');
	$('#textfield17').val('http://')
	$('#cont_titulo_site_relacionado').val('25')
}

function removerSiteRelacionado(cod) {
	$('#lista_sitesrelacionados').load('ajax_linksrelacionados.php?acao=remover&cod='+cod);
}

function carregaSiteRelacionado() {
	$('#lista_sitesrelacionados').load('ajax_linksrelacionados.php?acao=listar');
}

function exibeTextoLogin(campo, idvisual, idlogin) {
	document.getElementById(idvisual).innerHTML = campo.value.toLowerCase();
	if (document.getElementById(idlogin))
		document.getElementById(idlogin).value = campo.value.toLowerCase();
}

function lowercase(campo) {
	var texto = $('#'+campo).val();
	texto = texto.replace('.', '');
	texto = texto.replace(',', '');
	$('#'+campo).val(texto.toLowerCase());
}

function exibeTexto(campo, idvisual) {
	document.getElementById(idvisual).innerHTML = campo.value;
}

function homeTituloRestaurar() {
	$("#textfield6").val($("#textfield").val());
	contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
}

function homeChamadaRestaurar() {
	$("#textarea2").val($("#textarea").val());
	contarCaracteres(document.getElementById("textarea2"), "cont_home_resumo", 200);
}

function exibirCamposHome() {
	if (parseInt($("#n-home").val())) {
		if (!$("#textfield6").val() && !$("#textarea2").val()) {
			homeTituloRestaurar();
			homeChamadaRestaurar();
		}
		$("#chamada-home").show();
	}
	else
		$("#chamada-home").hide();
}
