<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/BuscaiTeiaBO.php');

$buscar = (int)$_GET['buscar'];
$buscar = true;

if ($buscar) {
	$buscabo = new BuscaiTeiaBO;
	$dados = $_GET;
	
	if ($dados['data2']) {
		$data_itens = explode('/', $dados['data2']);
		$dados['dia2'] = (int)$data_itens[0];
		$dados['mes2'] = (int)$data_itens[1];
		$dados['ano2'] = (int)$data_itens[2];
	}/* else {
		$dados['dia2'] = date('d');
		$dados['mes2'] = date('m');
		$dados['ano2'] = date('Y');
	}*/
	if ($dados['data1']) {
		$data_itens = explode('/', $dados['data1']);
		$dados['dia1'] = (int)$data_itens[0];
		$dados['mes1'] = (int)$data_itens[1];
		$dados['ano1'] = (int)$data_itens[2];
	}/* else {
		$datainicial = date('Ymd', mktime(0, 0, 0, ($dados['mes2'] - 6), $dados['dia2'], $dados['ano2']));
		$dados['dia1'] = substr($datainicial, 6, 2);
		$dados['mes1'] = substr($datainicial, 4, 2);
		$dados['ano1'] = substr($datainicial, 0, 4);
	}*/

	if ($dados['formatos']) $dados['formatos'] = explode(',', $dados['formatos']);
	if (!$dados['formatos'] || in_array(0, $dados['formatos'])) $dados['formatos'] = array();
		
	if ($dados['cidades']) $dados['cidades'] = explode(',', $dados['cidades']);
	if (!$dados['cidades']) $dados['cidades'] = array();
	if ($dados['estados']) $dados['estados'] = explode(',', $dados['estados']);
	if (!$dados['estados']) $dados['estados'] = array();

	$dados['extras'] = array(
		'conteudo' => (int)$dados['conteudo'], // conteudos relacionados / autores
		'codcanal' => (int)$dados['canal'], // conteudos
		'direito' => (int)$dados['direito'], // conteudos
		'tag' => trim(strip_tags($dados['tag'])), // conteudos
		'ordenacao' => (int)$dados['ordem'], // conteudos
		'relacionado' => (int)$dados['relacionado'], // conteudos
		'colaborador' => (int)$dados['colaborador'], // conteudos -> colaborador
		'autor' => (int)$dados['autor'], // conteudos -> autor
		'cidades' => $dados['cidades'], // cidades -> colaboradores/autores
		'estados' => $dados['estados'], // estados -> colaboradores/autores
	);

	$dados_todos = $dados;
	$memid1 = $buscabo->efetuaBusca($dados_todos);
	$link_resultado = 'busca_resultado.php?id1='.$memid1;
	
	if (in_array(2, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_audios = $dados;
		$dados_audios['formatos'] = array(2);
		$memid2 = $buscabo->efetuaBusca($dados_audios);
		$link_resultado .= '&id2='.$memid2;
	}
	
	if (in_array(3, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_videos = $dados;
		$dados_videos['formatos'] = array(3);
		$memid3 = $buscabo->efetuaBusca($dados_videos);
		$link_resultado .= '&id3='.$memid3;
	}
	
	if (in_array(4, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_textos = $dados;
		$dados_textos['formatos'] = array(4);
		$memid4 = $buscabo->efetuaBusca($dados_textos);
		$link_resultado .= '&id4='.$memid4;
	}
	
	if (in_array(5, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_imagens = $dados;
		$dados_imagens['formatos'] = array(5);
		$memid5 = $buscabo->efetuaBusca($dados_imagens);
		$link_resultado .= '&id5='.$memid5;
	}
	
	if (in_array(6, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_noticias = $dados;
		$dados_noticias['formatos'] = array(6);
		$memid6 = $buscabo->efetuaBusca($dados_noticias);
		$link_resultado .= '&id6='.$memid6;
	}
	
	if (in_array(7, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_eventos = $dados;
		$dados_eventos['formatos'] = array(7);
		$memid7 = $buscabo->efetuaBusca($dados_eventos);
		$link_resultado .= '&id7='.$memid7;
	}
	
	if (in_array(8, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_canais = $dados;
		$dados_canais['formatos'] = array(8);
		$memid8 = $buscabo->efetuaBusca($dados_canais);
		$link_resultado .= '&id8='.$memid8;
	}
	
	if (in_array(9, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_autores = $dados;
		$dados_autores['formatos'] = array(9);
		$memid9 = $buscabo->efetuaBusca($dados_autores);
		$link_resultado .= '&id9='.$memid9;
	}
	
	if (in_array(10, $dados['formatos']) || !count($dados['formatos'])) {
		$dados_colaboradores = $dados;
		$dados_colaboradores['formatos'] = array(10);
		$memid10 = $buscabo->efetuaBusca($dados_colaboradores);
		$link_resultado .= '&id10='.$memid10;
	}
	//print_r($dados);
	header('location: '.$link_resultado);
}
else
	header('location: busca_resultado_erro.php');
