<?php
include("verificalogin.php");

include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/ImagemUtil.php");
ImagemUtil::exibirImagem($_GET["img"], $_GET["tipo"], $_GET["s"]);
