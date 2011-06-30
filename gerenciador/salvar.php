<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once('classes/bo/TextoEdicaoBO.php');

$cod_conteudo = (int)$_GET['c'];

$textobo = new TextoEdicaoBO();
return $textobo->DownloadArquivo($cod_conteudo);
