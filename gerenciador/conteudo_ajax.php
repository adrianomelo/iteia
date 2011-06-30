<?php
include("verificalogin.php");
include_once("classes/bo/AjaxConteudoBO.php");

$ajaxbo = new AjaxConteudoBO($_GET);
$ajaxbo->executaAcao();
