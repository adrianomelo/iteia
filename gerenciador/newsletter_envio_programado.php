<?php
include_once("classes/bo/NewsletterEdicaoBO.php");
$newsbo = new NewsletterEdicaoBO;

$newsbo->enviarNewsletterProgramadas();
