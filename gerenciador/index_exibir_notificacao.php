<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/NotificacaoBO.php");
$notifbo = new NotificacaoBO;

$formato = $notifbo->getFormatoConteudo((int)$_GET['cod']);
$formato = Util::getIconeConteudo($formato);

$publicado = $notifbo->getStatusConteudo((int)$_GET['cod']);

$chapeu = 'Painel';
$item_menu = 'index';
$nao_carregar = '';
$item_submenu = 'lista_publica';

$nao_exibir_adicionais = true;
$nao_mostrar_situacao_acao = true;

$mostrar_notificacao = true;

$nao_exibir_link_add_grupos = $nao_exibir_link_add_relacionados = true;

$pagina = 'conteudo_publicado_'.$formato.'.php';

if ((int)$_GET['aprovar']) {
	// aprovacao de conteudo
	$dados_get = array();
	$dados_get['get'] = 'aprovar_conteudo';
	$dados_get['cod_conteudo'] = $_GET['cod'];
	
	include_once("classes/bo/AjaxConteudoBO.php");
	$ajaxbo = new AjaxConteudoBO($dados_get);
	$ajaxbo->executaAcao();
	
	header("Location: index_lista_notificacao.php");
	die;
}

if ((int)$_POST['reprovar']) {
	// rejeiчуo de conteudo
	$dados_get = array();
	$dados_get['get'] = 'reprovar_conteudo';
	$dados_get['cod_conteudo'] = $_POST['codconteudo'];
	$dados_get['comentario'] = $_POST['comentario'];
	
	include_once("classes/bo/AjaxConteudoBO.php");
	$ajaxbo = new AjaxConteudoBO($dados_get);
	$ajaxbo->executaAcao();
	
	header("Location: index_lista_notificacao.php");
	die;
}

if ($publicado) {
	header("Location: " . $pagina . "?cod=" . (int)$_GET['cod']);
	die;
}

include('conteudo_publicado_'.$formato.'.php');
?>