<?php
require_once("classes/vo/ConfigPortalVO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."util/ImagemUtil.php");
ImagemUtil::exibirImagem($_GET["img"], $_GET["tipo"], $_GET["s"]);
