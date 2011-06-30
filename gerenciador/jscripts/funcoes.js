function submeteAcoesGerenciar(id, valor, comalert, mensagem) {
	if (comalert == 1) {
		if (confirm(mensagem)) {
			document.getElementById("acao").value = valor;
			document.getElementById(id).submit();
		}
	} else {
		document.getElementById("acao").value = valor;
		document.getElementById(id).submit();
	}
}

function submeteFormularioId(id) {
	document.getElementById(id).submit();
}

function submeteBuscaCadastro(nomecookie) {
	mostrar = document.getElementById("select3").options[document.getElementById("select3").selectedIndex].value;
	
	// salva cookie com valor da paginação
	if (nomecookie)
    	setcookie(nomecookie, mostrar);
	
	document.getElementById("form-result").submit();
}

function submeteAcoesCadastro(valor) {
	document.getElementById("acao").value = valor;
	document.getElementById("form-result").submit();
}

function popup (url, nome, w, h, scrollbars, center) {
    var top = (center == 1) ? Math.ceil((screen.height-h) / 2) : 0;
    var left = (center == 1) ? Math.ceil((screen.width-w) / 2) : 0;
    janela = window.open(url, nome, "scrollbars=" +scrollbars+ ",width=" +w+ ",height=" +h+ ",top=" +top+ ",left=" +left);
    janela.focus();
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

function contarCaracteres2(campo, idvisual, limite) {
	document.getElementById(idvisual).value = (limite - campo.text.length) + "";
}

function navegaConteudo(url, campo, pagina) {
    $(campo).load(url+"&pagina="+pagina);
}

function mostrarTotal(campo, url, nomecookie) {
    var mostrar = $("#exibir").val();
    
    // salva cookie com valor da paginação
    setcookie(nomecookie, mostrar);
	
	// exibe conteudo
	$(campo).load(url+"&mostrar="+mostrar);
}

function setcookie(name, value) {
	var hoje = new Date();
    var expira = new Date(hoje.getTime()+10*24*60*60*1000);
    var expira = expira.toGMTString();
	document.cookie = name + "=" + escape(value) + ";expires=" + expira;
}

function getcookie(name) {
	var cookieValue = "";
	var search = name + "=";
	if(document.cookie.length > 0) {
		offset = document.cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = document.cookie.indexOf(";", offset);
			if (end == -1) end = document.cookie.length;
			cookieValue = unescape(document.cookie.substring(offset, end))
		}
	}
	return cookieValue;
}

function apagarImagem(cod, tipo) {
	$('#div_imagem_exibicao').load('ajax_conteudo.php?get=apagar_imagem_visualizacao&tipo='+tipo+'&cod='+cod);
	$('.remover').hide();
}

function apagarArquivo(cod, tipo) {
	$('.arquivo').load("excluir_arquivo.php?cod="+cod+"&tipo="+tipo);
	$('.arquivo').hide();
	tb_remove();
}
