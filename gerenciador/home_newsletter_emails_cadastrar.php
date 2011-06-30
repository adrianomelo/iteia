<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/Newsletter_UsuariosEdicaoBO.php");
$newsbo = new Newsletter_UsuariosEdicaoBO;

$editar	= (int)$_POST['editar'];
$codusuario = (int)$_GET['cod'];

if ($codusuario)
	$edicaodados = 1;

if (!isset($_SESSION['sessao_newsletter_usuarios_lista']) || !$editar)
	$_SESSION['sessao_newsletter_usuarios_lista'] = array();

$erro = false;
if ($editar) {
	try {
		$cod_usuario_ok = $newsbo->editar($_POST, $_FILES);
	} catch (Exception $e) {
		$erro = true;
		$erro_mensagens = $e->getMessage();
	}
}

if ($codusuario && !$editar)
	$newsbo->setDadosCamposEdicao($codusuario);

$paginatitulo = 'Destaque';
$item_menu = "home";
$item_submenu = "newsletter_inserir";
include('includes/topo.php');
?>

<script type="text/javascript" src="jscripts/funcoes.js"></script>
<script type="text/javascript" src="jscripts/home_newsletter.js"></script>

    <h2>Destaques</h2>
    <div id="op-comentario">
      <a href="home_newsletter.php">In&iacute;cio</a> | <a href="home_newsletter_inserir.php">Criar newsletter</a> | <a href="home_newsletter_listas.php">Listas de emails</a> | <strong>Cadastrar emails</strong></div>

<?php if ($erro_mensagens): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($cod_usuario_ok): ?>
<div class="box box-dica">
<h3>Email cadastrado com sucesso!</h3>
</div>
<?php endif; ?>
    
	<form  method="post" action="home_newsletter_emails_cadastrar.php">
      <h3 class="titulo">Cadastro de emails </h3>
     <div class="box">
		
		<input type="hidden" name="editar" value="1" />
		  <input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codusuario" value="<?=$newsbo->getValorCampo('codusuario')?>" />
		
            <label for="label3">Nome do destinat&aacute;rio:</label>
            
            <br />
        <input type="text" class="txt" id="label3" name="nome"  <?=$newsbo->verificaErroCampo("nome")?> value="<?=stripslashes($newsbo->getValorCampo('nome'))?>"  />
        <br />
        <label for="label4">Email:*</label>
            <br />
        <input type="text" class="txt" id="label4"  <?=$newsbo->verificaErroCampo("email")?> name="email" value="<?=stripslashes($newsbo->getValorCampo('email'))?>" />
<br />
<label for="select5">Listas:</label>
            <br />
            <select name="select2" id="select5">
              <?php foreach($newsbo->getListasEnvio() as $value): ?>
				<option value="<?=$value['cod_lista'];?>|<?=$value['titulo'];?>"><?=$value['titulo'];?></option>
			<?php endforeach; ?>
              </select>
           
            <input type="button" class="bt" onclick="javascript:addListaUsuario(); return false;" value="Adicionar" />
      <br />

              <div id="mostra_lista_usuario"></div>
      </div>
      <div id="botoes" class="box">
        <input name="submit" type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
    
  </div>
<?php
if (count($_SESSION['sessao_newsletter_usuarios_lista'])):
?>
<script type="text/javascript">
carregaListaUsuario();
</script>
<?php
endif;
include('includes/rodape.php'); ?>
