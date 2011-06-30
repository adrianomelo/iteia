$().ready(function() {

	function formatResult(row) {
		return row[0].replace(/(<.+?>)/gi, '');
	}

	$("#tags").autocomplete("ajax_conteudo.php?get=buscar_tag", {
		multiple: true
	});

	/*	$("#nome_integrante").autocomplete("ajax_conteudo.php?get=buscar_integrantes&buscar=1&tipo=2&buscarpor=nome&integrar_colaborador="+$("#codcolaborador").val()+"&integrar_codgrupo="+$("#codgrupo").val()+"&integrantes=1", {
		multiple: false
	});
	*/

	$("#nome_integrante").autocomplete("ajax_conteudo.php?get=buscar_integrantes&buscar=1&tipo=2&buscarpor=nome&integrar_codgrupo="+$("#codgrupo").val()+"&integrantes=1&conteudo_simples=1", {
		multiple: false
	});	
	
	$("#nome_autor_wiki").autocomplete("ajax_conteudo.php?get=buscar_integrantes&buscar=1&tipo=2&buscarpor=nome&buscar_autor_ficha=1&buscar_tipo=3&integrantes=1&limiteresultado=30&conteudo_simples=1", {
		multiple: false,
		//cacheLength: 0,
		delay: 1,
		formatItem: function(row) {
			return row[0];
		},
		formatMatch: function(row, i, max) {
			return row[0];
		}
	});
	
	$("#nome_colaborador").autocomplete("ajax_conteudo.php?get=buscar_integrantes&buscar=1&tipo=1&buscarpor=nome", {
		multiple: true
	});

	$("#nome_autor_wiki").result(function(event, data, formatted) {
		if (data) {
			$("#autor_selecionado").val(data[1]);
			$.getJSON("ajax_conteudo.php?get=autor_dados_ficha&cod="+data[1], function(dados) {
				$("#autor_selecionado").val(data[1]);
				
				$("#nome_autor_wiki").val(dados['nome_autor']);
				
				$("#ficha_nome_completo").val(dados['nome']);
				$("#pais").val(dados['pais']);
				$("#estado").val(dados['estado']);
				if (dados['estado'])
					obterCidades(document.getElementById("estado"), dados['codcidade']);
				//$("#selectcidade").val(dados['codcidade']);
				$("#campocidade").val(dados['cidade']);
				$("#ficha_email").val(dados['email']);
				$("#ficha_telefone").val(dados['telefone']);
				$("#ficha_descricao").val(html_entity_decode(dados['descricao']));
				$("#ficha_falecido").attr('checked', dados['falecido']);
			});
		}
	});

});

function html_entity_decode(str) {
	try {
		var tarea=document.createElement('textarea');
   		tarea.innerHTML = str; return tarea.value;
		tarea.parentNode.removeChild(tarea);
	} catch(e) {
  		//for IE add <div id="htmlconverter" style="display:none;"></div> to the page
		document.getElementById("htmlconverter").innerHTML = '<textarea id="innerConverter">' + str + '</textarea>';
		var content = document.getElementById("innerConverter").value;
		document.getElementById("htmlconverter").innerHTML = "";
      	return content;
    }
}
