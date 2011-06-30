<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once('classes/bo/ConteudoExibicaoBO.php');

$cod_conteudo = (int)$_GET['c'];
$cod_formato  = (int)$_GET['f'];
$opcional  = (int)$_GET['i'];

$contbo = new ConteudoExibicaoBO($cod_conteudo, $cod_formato);
return $contbo->DownloadArquivo($opcional);
