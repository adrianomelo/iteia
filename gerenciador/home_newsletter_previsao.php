<?php
include("verificalogin.php");

include_once("classes/bo/NewsletterBO.php");
$newsbo = new NewsletterBO;

echo $newsbo->getPrevisaoInterna($_GET['cod']);
