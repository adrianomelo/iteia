<?php
include("verificalogin.php");
include_once("classes/bo/AjaxHomeBO.php");

$ajaxbo = new AjaxHomeBO($_GET);
$ajaxbo->executaAcao();
