<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once('classes/bo/ConteudoExibicaoBO.php');

$cod_conteudo = (int)$_GET['c'];
$cod_formato  = (int)$_GET['f'];
$tipo  = (int)$_GET['t'];

$contbo = new ConteudoExibicaoBO($cod_conteudo, $cod_formato);
echo $contbo->RecomendarConteudo($tipo);
