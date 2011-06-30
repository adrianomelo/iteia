function enviaFormularioDestaque() {
	options = {
		url: "home_ajax.php?get=home_item_edicao_editar&titulo="+$("#textfield6").val()+"&resumo="+$("#textarea").val()+"&coditem="+$("#coditem").val(),
		success: listarConteudoHome,
		target: "#mostra_selecionadas_homeconteudo",
		type: "get"
	};
	$('#lightbox').ajaxSubmit(options);
}

function restaurarTitulo(cod) {
	$.get("home_ajax.php?get=home_item_edicao_restaurar_titulo&coditem="+cod, function(html) { $("#textfield6").val(html); });
	contarCaracteres(document.getElementById("textfield6"), "cont_home_titulo", 100);
}

function restaurarChamada(cod) {
	$.get("home_ajax.php?get=home_item_edicao_restaurar_chamada&coditem="+cod, function(html) { $("#textarea").val(html); });
	contarCaracteres(document.getElementById("textarea"), "cont_home_resumo", 200);
}

function restaurarImagem(cod) {
	$('#div_imagem_exibicao').html();
	$('#div_imagem_exibicao').load("home_ajax.php?get=home_item_edicao_restaurar_imagem&coditem="+cod);
	$('#imgtemp').val('1');
}

function validaFormularioDestaque() {
	var titulo = $("#textfield6").val();
	var chamada = $("#textarea").val();
	var erro = '';
	if (titulo == '') {
		$("#textfield6").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#textfield6").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	if (chamada == '') {
		$("#textarea").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
		erro = 1;
	} else {
		$("#textarea").attr({ style: '1px solid #8E8E8E; background: #FFF'});
	}
	
	if (erro)
		return false;
	else {
		enviaFormularioDestaque();
	}
}
