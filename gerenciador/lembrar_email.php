<?php
// ===================================================================
include_once("classes/bo/LoginBO.php");
$loginbo = new loginBO;
$loginbo->verificaLogado();
// ===================================================================
if ($_POST['lembrar']) {
	$lembrar = $loginbo->lembrarEmail($_POST);
}
// ===================================================================
include('includes/topo_limpo.php');
// ===================================================================
?>
<script src="scripts/lembraremail.js" type="text/javascript"></script>
<div id="content">
    <div id="div-login" class="box">
      <h2>Acesso</h2>
      <h3>Lembrar e-mail</h3>
<?php
if ($_POST['lembrar'] && !$lembrar) {
?>
      <p>Dados enviados com sucesso!<br />
	  A equipe do InterCidadania entrará em contato o mais breve possível para regularizar o seu acesso ao sistema.
	  </p>
	  <ul>
		<li>› <a href="login.php" title="Voltar para acesso ao sistema">Voltar para acesso ao sistema</a></li>

	  </ul>
<?php
} else {
?>
      <p>Entre em contato com o Instituto InterCidadania para recuperar o seu e-mail registrado no sistema.<br />
	  Todos os campos s&atilde;o de preenchimento obrigatório.
	  </p>
      <form method="post" name="lembrarsenha" action="lembrar_email.php">
        <fieldset>
        <label for="nome">Nome</label>
        <br />
        <input type="hidden" name="lembrar" value="email" />
        <input type="text" class="txt" id="nome" name="nome" size="55" />
        <br />
        <label for="e-mail">E-mail</label>
        <br />
        <input type="text" class="txt" id="email" name="email" size="55" />
        <br />
        <input type="submit" value="Enviar" class="bt-adicionar" />
        </fieldset>
      </form>
      <ul>
		<li>&rsaquo; <a href="login.php" title="Voltar para acesso ao sistema">Voltar para acesso ao sistema</a></li>
	  </ul>
<?php
}
?>
    </div>
  </div>
<?php
// ===================================================================
include('includes/rodape.php');
// ===================================================================
?>