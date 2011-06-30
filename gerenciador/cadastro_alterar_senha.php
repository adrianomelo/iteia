<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codusuario = (int)$_GET['cod'];
$editar = (int)$_POST['editar'];

include_once("classes/bo/SenhaBO.php");
$senhabo = new SenhaBO;
$exibir_form = true;
$sucesso = false;

if ($editar) {
	try {
		$senhabo->editar($_POST, $_FILES);
		$exibir_form = false;
		$sucesso = true;
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($editar) $codusuario = (int)$_POST['codusuario'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Trocar Senha</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body { background:none;}
-->
</style>
</head>
<body>
<?php if ($sucesso): ?>
<div class="box box-alerta">
      Senha alterada com sucesso.</div>
<?php 
endif;
if ($exibir_form):
?>
<form action="cadastro_alterar_senha.php" method="post" id="lightbox">
<div class="box box-alerta">
      Todos os campos s&atilde;o obrigat&oacute;rios</div>
      <input type="hidden" name="editar" value="1" />
      <input type="hidden" name="codusuario" value="<?=$codusuario;?>" />
  <label for="senha">Senha atual</label>
  <br />
  <input name="senha" type="password" class="txt" id="nsenha" <?=$senhabo->verificaErroCampo("senha")?> /><br />
<label for="senha">Nova senha</label>
  <br />
  <input name="novasenha" type="password" class="txt" id="senha" <?=$senhabo->verificaErroCampo("novasenha")?> />
  <br />
  <label for="senha-2">Repetir a nova senha</label>
  <br />
  <input name="novasenha2" type="password" class="txt" id="senha-2" <?=$senhabo->verificaErroCampo("novasenha2")?> />
  <br />
  <input type="submit" value="Enviar" class="bt" />
</form>
<?php endif; ?>
</body>
</html>