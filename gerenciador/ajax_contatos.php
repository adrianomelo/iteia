<?php
include('verificalogin.php');
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

if (!$_SESSION['contato_comunicadores'])
	$_SESSION['contato_comunicadores'] = array();

$cod = (int)$_GET['cod'];	
$cod_tipo = (int)$_GET['cod_tipo'];
$nome_usuario = trim($_GET['nome_usuario']);
$acao = $_GET['acao'];

if ($acao == 'adicionar') {
	if ($cod_tipo && $nome_usuario)
		$_SESSION['contato_comunicadores'][] = array('tipo' => $cod_tipo, 'nome_usuario' => $nome_usuario);
	$acao = 'listar';
}

if ($acao == 'remover') {
	unset($_SESSION['contato_comunicadores'][$cod]);
	$acao = 'listar';
}

if ($acao == 'listar') {
	foreach ($_SESSION['contato_comunicadores'] as $key => $value) {
		echo "<li><span>".Util::getTipoContato($value['tipo'])." - ".$value['nome_usuario']."</span> <a href=\"javascript:removerContato('".$key."');\" title=\"Remover\" class=\"remover\">Remover</a></li>\n";
	}	
}