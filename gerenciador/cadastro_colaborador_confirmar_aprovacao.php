<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codautor = (int)$_GET['cod'];
$redir = (int)$_GET['redir'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enviar para lista p&uacute;blica</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body { background:none;}
-->
</style>
<script type="text/javascript" src="jscripts/colaborador.js"></script>
</head>
<body>
<form action="" method="get" id="lightbox" >
<input type="hidden" value="<?=$codautor;?>" name="codautor" id="codautor" />
<p>Deseja realmente aprovar este cadastro? </p>
<input type="button" value="Sim" class="bt-sim" onclick="aprovarColaborador(<?=$redir;?>,<?=$codautor;?>);" />
<input type="button" value="N&atilde;o" class="bt-nao" onclick="tb_remove()" />
</form>
</body>
</html>