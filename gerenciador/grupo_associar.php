<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/CadastroBO.php");
$cadbo = new CadastroBO;

$_GET['tipogrupo'] = 2;
$_GET['tipo'] = 3;

$cadastros = $cadbo->getListaCadastros($_GET, 0, 100000);

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
<script type="text/javascript" src="jscripts/conteudo.js"></script>
</head>
<body>

<form id="lightbox" method="post" action="">
<div class="box box-alerta">
	<input type="hidden" value="<?=$codconteudo;?>" name="codconteudo" id="codconteudo" />
      Caso n&atilde;o existam grupos cadastrados, <a href="grupo_edicao.php" class="add"><strong>cadastre-os agora</strong></a>.</div>
  <h3>Selecionar grupo</h3>
  <p>Este conte&uacute;do tamb&eacute;m pode ser associado aos grupos em que os autores selecionados fazem parte. Selecione-os grupos na lista abaixo.</p>
  <small>Para selecionar mais de uma op&ccedil;&atilde;o, pressione a tecla Control (Ctrl) e clique sobre os itens escolhidos.</small><br />
      <select name="select2" class="select-list" size="6" multiple="multiple" id="lista_grupos">
<?php
foreach ($cadastros as $grupo):
	if (intval($grupo['cod'])):
		echo "<option value=\"".$grupo["cod"]."\">".htmlentities($grupo["nome"])."</option>\n";
	endif;
endforeach;
?>
      </select>
  <br />
    <input type="button" class="bt-adicionar" onclick="javascript:associarGruposConteudo();" value="Associar" />
</form>
</body>
</html>
