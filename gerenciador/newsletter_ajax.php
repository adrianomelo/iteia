<?php
include("verificalogin.php");
include_once("classes/bo/AjaxNewsletterBO.php");

$ajaxbo = new AjaxNewsletterBO($_GET);
$ajaxbo->executaAcao();
