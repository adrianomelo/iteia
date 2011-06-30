<?php
include_once("classes/bo/LoginBO.php");
$loginbo = new LoginBO;

$loginbo->verificaLogado();

if ($_POST['enviar'])
	$valida = $loginbo->logar($_POST);
	
if ($_GET['redir'])
	$loginbo->dados['redir'] = $_GET['redir'];

include('includes/topo_limpo.php');
?>
  <div id="content">
    <div id="div-login" class="box">
      <h2>Acesso</h2>
      <form action="login.php" method="post">
		<input type="hidden" name="enviar" value="1" />
		<input type="hidden" name="redir" value="<?=$loginbo->getRedir();?>" />
        <fieldset>
        <label for="login">Login</label>
        <br />
        <input name="login" value="<?=$loginbo->getLogin();?>" type="text" maxlength="30" class="txt" id="login" />
        <?=($valida['errologin'])?"<span class=\"erro\">Login inv&aacute;lido</span>":""?>
		<?=($valida['emptylogin'])?"<span class=\"erro\">Preencha o Login</span>":""?>
		<br />
        <label for="senha">Senha</label>
        <br />
        <input name="senha" type="password" maxlength="30" class="txt" id="senha" />
        <?=($valida['errosenha'])?"<span class=\"erro\">Senha inv&aacute;lida</span>":""?>
		<?=($valida['emptysenha'])?"<span class=\"erro\">Preencha a Senha</span>":""?>
		<br />
        <input type="submit" value="Entrar" class="bt-adicionar" />
        </fieldset>
      </form>
      <ul>
        <li>&rsaquo; <a href="lembrar_senha.php" title="Esqueceu sua senha? Saiba com obter uma nova.">Esqueci minha senha</a></li>
        <li>&rsaquo; <a href="lembrar_login.php" title="Esqueceu seu login? Saiba com obter um novo.">Esqueci meu login</a></li>
      </ul>
    </div>
  </div>
<?php include('includes/rodape.php'); ?>