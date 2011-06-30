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
		var url = "/ajax.php?acao=getcidades&codestado=" + codestado;
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

function exibeTextoLogin(campo, idlogin) {
	document.getElementById(idlogin).value = campo.value.toLowerCase();
}

function contarCaracteres(campo, idvisual, limite) {
	total = limite;
	tam = campo.value.length;
	str = "";
	str = str+tam;
	document.getElementById(idvisual).value = total - str;
	if (tam > total) {
		campo.value = campo.value.substring(0, total);
		document.getElementById(idvisual).value = 0;
	}
}

function exibeTexto(campo, idvisual) {
	document.getElementById(idvisual).innerHTML = campo.value;
}

function lowercase(campo) {
	var texto = document.getElementById(campo).value;
	texto = texto.replace('.', '');
	texto = texto.replace(',', '');
	texto = texto.replace(' ', '');
	document.getElementById(campo).value = (texto.toLowerCase());
}

// cadastros
function exibeEstadoCidade2() {
	if (document.getElementById('pais2').value == '2') {
		document.getElementById('mostraestado2').style.display = 'inline';
		document.getElementById('selectcidade2').style.display = 'inline';
		document.getElementById('cidade2').style.display = 'none';
	} else {
		document.getElementById('mostraestado2').style.display = 'none';
		document.getElementById('selectcidade2').style.display = 'none';
		document.getElementById('cidade2').style.display = 'inline';
	}
}

function obterCidades2(obj, codcidade) {
	codestado = obj.options[obj.options.selectedIndex].value;
	if (codestado == "0") {
		removeAllChildren(document.getElementById("selectcidade2"));
		node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Selecione a Cidade"));
		document.getElementById("selectcidade2").appendChild(node_option);
	}
	else {
		AjaxRequest();
		var url = "/ajax.php?acao=getcidades&codestado=" + codestado;
		Ajax.onreadystatechange = function () {
			if ((Ajax.readyState == 4) && (Ajax.status == 200))
				listaCidades2(codcidade);
		}
		Ajax.open("GET", url, true);
		Ajax.send(null);
	}
}

function listaCidades2(codcidade) {
	var select_cidades = document.getElementById("selectcidade2");
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