<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/HomeBuscaBO.php");
$buscabo = new HomeBuscaBO;

$buscabo->atualiza();

echo 'ok';
