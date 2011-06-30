<?php
// ===================================================================
include_once("classes/bo/LoginBO.php");
$loginbo = new LoginBO;
$loginbo->verificaLogado();
// ===================================================================
if ($_POST['lembrar'])
	$lembrar = $loginbo->lembrarAcesso($_POST);
// ===================================================================
include('includes/topo_limpo.php');
// ===================================================================
?>  
  <div id="content">
    <div id="div-login" class="box">
      <h2>Acesso</h2>
      <h3>Lembrar senha</h3>
<?php
if ($_POST['lembrar'] && !$lembrar) {
?>
      <p>Sua senha foi enviada para o seu e-mail registrado no sistema.</p>
	  <ul>
		<li>&rsaquo; <a href="login.php" title="Voltar para acesso ao sistema">Voltar para acesso ao sistema</a></li>
	  </ul>
<?php
} else {
?>
      <p>Informe o seu login registrado no sistema para lembrar a sua senha.</p>
      <form action="lembrar_senha.php" method="post">
        <fieldset>
        <label for="email">Login</label>
        <br />
        <input type="hidden" name="lembrar" value="senha" />
		<input type="text" class="txt" id="login" name="buscarpor" size="55" />
		<?=($lembrar)?"<span class=\"erro\">O login informado n&atilde;o est&aacute; registrado no sistema</span>":""?>
        <br />
        <input type="submit" value="Enviar" class="bt-adicionar" />
        </fieldset>
      </form>
	 	<ul>
		<li>&rsaquo; <a href="lembrar_login.php" title="Esqueceu seu login? Saiba com obter um novo.">Esqueci meu login</a></li>
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