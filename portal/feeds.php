<?php
include_once('classes/bo/FeedsBO.php');
$feedsbo = new FeedsBO();

$formato = (int)$_GET['formato'];
$usuario = (int)$_GET['usuario'];
$codcanal = (int)$_GET['canal'];

if ($usuario) {
	echo $feedsbo->getFeedsUsuario($usuario);
} else {
	if ($formato == 5)
		echo $feedsbo->getFeedsNoticias();
	elseif ($formato == 6)
		echo $feedsbo->getFeedsAgenda();
	elseif ($formato == 8)
		echo $feedsbo->getFeedsCanal($codcanal);
	elseif ($formato < 5)
		echo $feedsbo->getFeedsConteudo($formato);
	else
		echo $feedsbo->getFeedsGeral();
}