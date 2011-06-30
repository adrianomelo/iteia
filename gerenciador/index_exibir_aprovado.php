<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/NotificacaoBO.php");
$notifbo = new NotificacaoBO;

$formato = $notifbo->getFormatoConteudo((int)$_GET['cod']);
$formato = Util::getIconeConteudo($formato);

$chapeu = 'Painel';
$item_menu = 'index';
$nao_carregar = '';
$item_submenu = 'index';

$nao_exibir_adicionais = true;
$nao_mostrar_situacao_acao = true;

$mostrar_aprovado = true;

$nao_exibir_link_add_grupos = $nao_exibir_link_add_relacionados = true;

include('conteudo_publicado_'.$formato.'.php');
?>