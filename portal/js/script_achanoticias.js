var destino_busca = 0;

function setBuscaOpc() {
	setDestinoBusca();
}

function setDestinoBusca() {
	form_an = document.getElementById('searchform');
	if (form_an.destbusca.checked)
		destino_busca = 2;
	else
		destino_busca = 1;
}

function EfetuarBusca(rodarsubmit) {
	setDestinoBusca();
	form_an = document.getElementById('searchform');
	if (destino_busca == 1) {
		var com = '';
		if ($('#myselectbox').val() != '0') {
			//$('#buscarpor').attr({name : $('#myselectbox').val()});
			$('#buscarpor').attr({value : $('#myselectbox').val()});
		}
		form_an.action = "/busca_action.php";
		form_an.target = "";
	}
	
	else if (destino_busca == 2) {
		form_an.action = "http://www.achanoticias.com.br/busca.kmf";
		form_an.target = "_blank";
	}
	campobusca = document.getElementById("termo");
	if ((campobusca.value == "Ache tudo aqui") || (campobusca.value == "") && destino_busca == 2) {
		campobusca.value = "";
		alert("Digite pelo menos uma palavra para fazer a busca.");
		campobusca.focus();
		return false;
	}
	else {
		form_an.submit();
	}
	return true;
}

function BuscaAvanc() {
	setDestinoBusca();
	if (destino_busca == 1) {
		document.location = "/busca_avancada.php";
	}
	else if (destino_busca == 2) {
		window.open("http://www.achanoticias.com.br/busca.kmf");
	}
}
