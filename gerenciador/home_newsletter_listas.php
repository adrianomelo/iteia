<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once('classes/bo/Newsletter_ListaEdicaoBO.php');
$newsbo = new Newsletter_ListaEdicaoBO;

include_once('classes/bo/BuscaNewsletterListaBO.php');

$buscabo = new BuscaNewsletterListaBO();
$pagina  = (int)GlobalSiteUtil::iif($_GET['pagina'], $_GET['pagina'], 1);
$inicial = ($pagina - 1) * 10;

$editar	= (int)$_POST['editar'];

$erro = false;
if ($editar) {
	try {
		$cod_lista_ok = $newsbo->editar($_POST, $_FILES);
	} catch (Exception $e) {
		$erro = true;
		$erro_mensagem = $e->getMessage();
	}
}

$resultado = $buscabo->getNewsletterUsuariosListasBusca($_GET, $inicial, 10);
$paginacao = GlobalSiteUtil::paginacao($pagina, 10, $resultado['total'], 'newsletter_listar.kmf?buscar=1', ' ');

$paginatitulo = 'Destaque';
$item_menu = "home";
$item_submenu = "newsletter_inserir";
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/funcoes.js"></script>
<script type="text/javascript" src="jscripts/home_newsletter.js"></script>

    <h2>Newsletter</h2>
    <div id="op-comentario">
      <a href="home_newsletter.php">In&iacute;cio</a> | <a href="home_newsletter_inserir.php">Criar newsletter</a> | <strong>Listas de emails</strong> | <a href="home_newsletter_emails_cadastrar.php">Cadastrar emails</a></div>
    
<?php if ($erro_mensagem): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagem?>
</div>
<?php endif; ?>

<?php if ($cod_lista_ok): ?>
<div class="box box-dica">
<h3>Lista cadastrada com sucesso!</h3>
</div>
<?php endif; ?>
	
	<form  method="post" action="home_newsletter_listas.php" id="aa">
      <h3 class="titulo">Cadastro de lista de emails </h3>
      <div class="box">
		
		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="codlista" value="<?=$newsbo->getValorCampo('codlista')?>" />
		
        <label for="label3">Nome da lista</label>:
            <br />
            <input type="text" class="txt" <?=$newsbo->verificaErroCampo("titulo")?> name="titulo" value="<?=$newsbo->getValorCampo('titulo')?>" id="label3"  />
            <input name="submit2" type="submit" class="bt-gravar" value="inserir" />

     </div>
    </form>
    
    </div>
  <hr />

<form action="home_newsletter_listas.php" method="get" enctype="multipart/form-data" id="form-result" class="form-tooltip">
<input type="hidden" name="buscar" id="buscar" value="1" />
<input type="hidden" name="acao" id="acao" value="0" />
  
  <h3 class="titulo">Listas de email</h3>
  <div id="resultado" class="box">
   <div>
     <label for="label">Filtrar:</label>
     <br />
     <input type="text" name="titulo" class="txt" id="label"  />
     <input name="button" type="submit" onclick="this.form.submit();" class="bt-gravar" value="Filtrar" />
   </div>

   <div class="view">Exibindo
         <select name="mostrar" onchange="submeteBuscaCadastro();" id="select3">
          <option value="10" <?=($mostrar == 10) ? 'selected="selected"' : '';?>>10</option>
          <option value="20" <?=($mostrar == 20) ? 'selected="selected"' : '';?>>20</option>
          <option value="30" <?=($mostrar == 30) ? 'selected="selected"' : '';?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-result">
      <thead>
        <tr>
          <th class="col-1"  scope="col"><input type="checkbox" id="check-all"  /></th>
          <th class="col-titulo"  scope="col">Nome</th>
          <th class="col-titulo"  scope="col">Emails</th>
          <th class="col-editar" scope="col">Editar</th>
        </tr>
      </thead>
      <tbody>
<?php
foreach($newsbo->getListas() as $key => $value):
	//if ($value['lista'] != $lista):
?>
        <tr>
          <td class="col-1"><input type="checkbox" class="check" name="lista[]" value="<?=$value['cod_lista'];?>" /></td>
          <td class="col-titulo"><strong><?=htmlentities($value['titulo']);?></strong></td>
          <td class="col-titulo">&nbsp;</td>
          <td class="col-editar">&nbsp;</td>
        </tr>
<?php
//endif;
	if (count($resultado['resultado'][$value['cod_lista']])):
		foreach($resultado['resultado'][$value['cod_lista']] as $keya => $valuea):
?>
        <tr>
          <td class="col-1"><input type="checkbox" name="cod[]" value="<?=$valuea['cod_lista'];?>_<?=$valuea['cod_usuario'];?>" class="check" /></td>
          <td class="col-titulo">&mdash; <span class="col-conteudo"><?=htmlentities($valuea['nome']);?></span></td>
          <td class="col-titulo"><?=htmlentities($valuea['email']);?></td>
        <td class="col-editar"><a href="home_newsletter_emails_cadastrar.php?cod=<?=$valuea['cod_usuario'];?>">Editar</a></td>
        </tr>
<?php
		endforeach;
	endif;
	//$lista = $value['lista'];
endforeach;
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:;" onclick="javascript:submeteAcoesGerenciar('form-result', 1, 0, 0);"><span class="col-remover">Remover</span></a></td>
        </tr>
      </tfoot>
    </table>
    <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <hr class="both" />
  </div>
</form>

<?php include('includes/rodape.php'); ?>
