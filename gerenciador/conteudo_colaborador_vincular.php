<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];
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

<script type="text/javascript" src="jscripts/colaborador.js"></script>

</head>
<body>

<form id="lightbox" action="" name="lightbox">
    <fieldset>
    
	<input type="hidden" value="<?=$codconteudo;?>" name="codconteudo" id="codconteudo" />
    
    <p class="dica">Escolha o colaborador respons&aacute;vel pelo novo conte&uacute;do. Digite abaixo o nome do colaborador e selecione-o no resultado da busca.</p>
    <label for="textfield10">Nome do colaborador</label>
    <br />
    <input type="text" class="txt" id="relacionar_palavrachave" size="80"  />
    <br />
    <input type="button" class="bt-buscar" onclick="javascript:buscaColaboradoresRelacionamento();" value="Buscar" />
    </fieldset><br />

	<div id="mostra_resultados_colaboradores_relacionamento"></div>

</form>

</body>
</html>