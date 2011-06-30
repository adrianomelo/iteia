<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");


$chapeu = 'Painel';

$aguardando_aprovacao = $nao_mostrar_dados_edicao = true;

$item_menu = 'index';
$item_submenu = 'lista_publica';

include('cadastro_autor_publicado.php');
?>