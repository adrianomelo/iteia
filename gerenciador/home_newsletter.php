<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once('classes/bo/NewsletterBO.php');

$newsbo = new NewsletterBO();

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;

if ((int)$_GET['acao'])
	$newsbo->removeProgramacao($_GET['cod']);

$agendadas = $newsbo->getAgendadas();

$enviadas = $newsbo->getEnviadas($inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $enviadas['total'], 'home_newsletter.php?buscar=1');

$paginatitulo = 'Destaque';
$item_menu = "home";
$item_submenu = "newsletter_inserir";
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/funcoes.js"></script>
<script type="text/javascript" src="jscripts/home_newsletter.js"></script>

    <h2>Newsletter</h2>
    <div id="op-comentario">
      <strong>In&iacute;cio</strong> | <a href="home_newsletter_inserir.php">Criar newsletter</a> | <a href="home_newsletter_listas.php">Listas de emails</a> | <a href="home_newsletter_emails_cadastrar.php">Cadastrar emails</a></div>
    <h3 class="titulo">Newsletter agendadas</h3>

<form action="home_newsletter.php" method="get" enctype="multipart/form-data" id="form-result">
<input type="hidden" name="buscar" id="buscar" value="1" />
<input type="hidden" name="acao" id="acao" value="0" />

    <div id="resultado" class="box">

      <table width="100%" border="1" cellpadding="0" cellspacing="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-data" scope="col">Data</th>
            <th class="col-titulo" scope="col">Nome da lista</th>
            <th class="col-conteudo" scope="col">Conte&uacute;dos</th>
            
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Remover</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($agendadas as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" name="cod[]" value="<?=$value['cod_newsletter'];?>" class="check" /></td>
           
            <td class="col-data"><?=date('d/m/Y, H:i', strtotime($value['data_envio']));?></td>
            <td class="col-titulo"><?=$value['titulo'];?></td>
            <td class="col-conteudo"><?=$value['total'];?></td>
            
            <td class="col-editar"><a href="home_newsletter_inserir.php?cod=<?=$value['cod_newsletter'];?>" title="Editar: <?=$value['titulo'];?>">Editar</a></td>
            <td class="col-remover"><a href="home_newsletter.php?acao=1&amp;cod[]=<?=$value['cod_newsletter'];?>" title="Remover">Remover</a></td>
          </tr>
<?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="selecionados"><strong>Selecionados:</strong>  <a href="javascript:;" onclick="javascript:submeteAcoesGerenciar('form-result', 1, 0, 0);"><span class="col-remover">Remover</span></a></td>
          </tr>
        </tfoot>
      </table>

      <hr class="both" />
    </div>
	
    <h3 class="titulo">Newsletter enviadas</h3>
    
	<div class="box">
   <div class="view">Exibindo
         <select name="mostrar" onchange="submeteBuscaCadastro('form-result');" id="select3">
          <option value="10" <?=($mostrar == 10) ? 'selected="selected"' : '';?>>10</option>
          <option value="20" <?=($mostrar == 20) ? 'selected="selected"' : '';?>>20</option>
          <option value="30" <?=($mostrar == 30) ? 'selected="selected"' : '';?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <table width="100%" border="1" cellpadding="0" cellspacing="0" id="table-conteudo2">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="check-all" type="checkbox" id="check-all2" />            </th>
            <th class="col-data" scope="col">Data</th>
            <th class="col-titulo" scope="col">Nome da lista</th>
            <th class="col-conteudo" scope="col">Conte&uacute;dos</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Remover</th>
          </tr>
        </thead>
        <tbody>
<?php foreach($enviadas['resultado'] as $key => $value): ?>
          <tr>
            <td class="col-1"><input type="checkbox" name="cod[]" value="<?=$value['cod_newsletter'];?>" class="check" /></td>
           
            <td class="col-data"><?=date('d/m/Y, H:i', strtotime($value['data_envio']));?></td>
            <td class="col-titulo"><?=$value['titulo'];?></td>
            <td class="col-conteudo"><?=$value['total'];?></td>
            
            <td class="col-editar"><a href="home_newsletter_inserir.php?cod=<?=$value['cod_newsletter'];?>" title="Editar: <?=$value['titulo'];?>">Editar</a></td>
            <td class="col-remover"><a href="home_newsletter.php?acao=1&amp;cod[]=<?=$value['cod_newsletter'];?>" title="Remover">Remover</a></td>
          </tr>
<?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="selecionados"><strong>Selecionados:</strong>  <a href="javascript:;" onclick="javascript:submeteAcoesGerenciar('form-result', 1, 0, 0);"><span class="col-remover">Remover</span></a></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
	  <hr class="both" />
    </div>
	</form>
  </div>
<?php include('includes/rodape.php'); ?>
