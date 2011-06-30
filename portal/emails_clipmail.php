<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/UsuarioDAO.php');

$userdao = new UsuarioDAO;
$usuarios_emails = $userdao->getEmailsClipmail();
$listatxt = '';
foreach ($usuarios_emails as $usuario) {
	$listatxt .= $usuario['email'].'|[!]|'.$usuario['cod_usuario'].'|[!]|'.$usuario['nome']."\n";
}

echo $listatxt;
