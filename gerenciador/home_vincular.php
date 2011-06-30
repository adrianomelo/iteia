<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$tipo = (int)$_GET['tipo'];

switch($tipo) {
	case 1: $titulo = 'Nome do colaborador'; $msg = 'Escolha o colaborador respons&aacute;vel pelo novo conte&uacute;do. Digite abaixo o nome do colaborador e selecione-o no resultado da busca.'; break;
	case 2: $titulo = 'Nome do Autor  '; $msg = 'Escolha o autor respons&aacute;vel pelo novo conte&uacute;do. Digite abaixo o nome do autor e selecione-o no resultado da busca.'; break;
	case 3: $titulo = 'Nome do Grupo  '; $msg = 'Escolha o grupo respons&aacute;vel pelo novo conte&uacute;do. Digite abaixo o nome do grupo e selecione-o no resultado da busca.'; break;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Vincular a colaborador</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body {
	background:none;
}
-->
</style>

</head>
<body>

<form id="lightbox" action="" name="lightbox">
    <fieldset>
    
    <input type="hidden" value="<?=$tipo;?>" id="vincular_tipo" />
    
    <p class="dica"><?=$msg;?></p>
    <label for="textfield10"><?=$titulo;?></label>
    <br />
    <input type="text" class="txt" id="vincular_palavrachave" size="80"  />
    <br />
    <input type="button" class="bt-buscar" onclick="javascript:buscaUsuariosVincular();" value="Buscar" />
    </fieldset><br />

	<div id="mostra_resultados_usuarios"></div>

</form>

</body>
</html>