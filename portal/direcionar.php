<?php
include_once('classes/bo/DirecionarBO.php');
$dirbo = new DirecionarBO;
$novaurl = (int)$_GET['novaurl'];

//print_r($GLOBALS); exit();
//print_r($_SERVER['SCRIPT_URL']);

$endereco_pasta = $_GET['endereco'];
if ($novaurl) {
	$url_partes = explode('/', $_SERVER['SCRIPT_URL']);
	if (($_GET['tipo'] == 4) && isset($url_partes[2])) {
		$pasta = '';
		switch ((int)$_GET['codformato']) {
			case 1: $pasta = 'textos'; break;
			case 2: $pasta = 'imagens'; break;
			case 3: $pasta = 'audios'; break;
			case 4: $pasta = 'videos'; break;
			case 5: $pasta = 'jornal'; break;
			case 6: $pasta = 'eventos'; break;
		}
		$endereco_pasta = $pasta.'/'.$url_partes[2];
	}
	if (($_GET['tipo'] == 1) && isset($url_partes[2])) {
		$pasta = 'colaboradores';
		$endereco_pasta = $pasta.'/'.$url_partes[2];
	}
	if (($_GET['tipo'] == 2) && isset($url_partes[2])) {
		$pasta = 'autores';
		$endereco_pasta = $pasta.'/'.$url_partes[2];
	}
	if (($_GET['tipo'] == 3) && isset($url_partes[2])) {
		$pasta = 'grupos';
		$endereco_pasta = $pasta.'/'.$url_partes[2];
	}
}

//print_r($endereco_pasta); exit();

$direcionar = $dirbo->getTipoConteudo(trim($endereco_pasta), $_GET['tipo']);
if (!$direcionar['cod_item'])
	$direcionar = $dirbo->getTipoConteudo(trim($_GET['endereco']), $_GET['tipo']);
if ($novaurl && ($_GET['tipo'] == 4)) {
	$direcionar['cod_formato'] = (int)$_GET['codformato'];
}

if ($direcionar['cod_item']) {
	switch ($direcionar['tipo']) {
        case 1:
			if (!$_GET["completo"]) {
				include_once('classes/bo/ColaboradorExibicaoBO.php');
				$colbo = new ColaboradorExibicaoBO($direcionar['cod_item']);
				return $colbo->exibirConteudo();
			}
			else {
				include_once("classes/bo/ColaboradorExibicaoCompletoBO.php");
				$colbo = new ColaboradorExibicaoCompletoBO($direcionar["cod_item"]);
				return $colbo->exibirConteudo();
			}
            die;
            break;
        case 2:
			if (!$_GET["completo"]) {
				include_once('classes/bo/AutorExibicaoBO.php');
				$autorbo = new AutorExibicaoBO($direcionar['cod_item']);
				return $autorbo->exibirConteudo();
			}
			else {
				include_once("classes/bo/AutorExibicaoCompletoBO.php");
				$autorbo = new AutorExibicaoCompletoBO($direcionar["cod_item"]);
				return $autorbo->exibirConteudo();
			}
            die;
            break;
        case 3:
			if (!$_GET["completo"]) {
				include_once('classes/bo/GrupoExibicaoBO.php');
				$grupobo = new GrupoExibicaoBO($direcionar['cod_item']);
				return $grupobo->exibirConteudo();
			}
			else {
				include_once("classes/bo/GrupoExibicaoCompletoBO.php");
				$grupobo = new GrupoExibicaoCompletoBO($direcionar["cod_item"]);
				return $grupobo->exibirConteudo();
			}
            die;
            break;
        case 4:
            include_once('classes/bo/ConteudoExibicaoBO.php');
            $contbo = new ConteudoExibicaoBO($direcionar['cod_item'], $direcionar['cod_formato']);
            return $contbo->exibirConteudo();
            die;
            break;
    }
}

Header('Location: /404.php');