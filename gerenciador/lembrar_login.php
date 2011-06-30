<?php
// ===================================================================
include_once("classes/bo/LoginBO.php");
$loginbo = new loginBO;
$loginbo->verificaLogado();
// ===================================================================
if ($_POST['lembrar']) {
	$lembrar = $loginbo->lembrarAcesso($_POST);
}
// ===================================================================
include('includes/topo_limpo.php');
// ===================================================================
?>  
<div id="content">
    <div id="div-login" class="box">
      <h2>Acesso</h2>
      <h3>Lembrar login </h3>
<?php
if ($_POST['lembrar'] && !$lembrar) {
?>
      <p>Seu login foi enviado para o seu e-mail registrado no sistema.</p>
	  <ul>
		<li>› <a href="login.php" title="Voltar para acesso ao sistema">Voltar para acesso ao sistema</a></li>
	  </ul>
<?php
} else {
?>
      <p>Digite o endere&ccedil;o de e-mail registrado no sistema para lembrar o seu login.</p>
      <form action="lembrar_login.php" method="post">
        <fieldset>
		
        <label for="email">E-mail</label>
        <br />
        <input type="hidden" name="lembrar" value="login" />
        <input type="text" class="txt" id="email" name="buscarpor" size="55" />
		<?=($lembrar)?"<span class=\"erro\">O e-mail informado n&atilde;o est&aacute; registrado no sistema</span>":""?>
        <br />
       
        <input type="submit" value="Enviar" class="bt-adicionar" />
		
        </fieldset>
      </form>
	  
	 	<ul>
		<li>&rsaquo; <a href="lembrar_email.php" title="Esqueceu seu e-mail?">Esqueci meu e-mail</a></li>
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