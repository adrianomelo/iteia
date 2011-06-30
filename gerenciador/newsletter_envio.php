<?php
include_once('classes/bo/Newsletter_EnvioBO.php');

$newsbo = new Newsletter_EnvioBO();
$newsbo->envioNewsletter();
