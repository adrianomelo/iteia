<?php
include('verificalogin.php');

include_once("classes/bo/PrincipalBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$indexbo = new PrincipalBO;

$usuariodados = $indexbo->getUsuarioDados();

include_once("classes/bo/ConteudoRecenteBO.php");
$recentebo = new ConteudoRecenteBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 6);
$inicial = ($pagina - 1) * $mostrar;

// quando voltar a pagina, senão tiver valor de mostrar e tiver setado o cookie, mostrar é igual valor do cookie
if (isset($_COOKIE['pag_recente']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_recente'];

$listarecente = $recentebo->getListaConteudoRecente($inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $listarecente['total'], $listarecente['link']);

$item_menu = 'index';
$item_submenu = 'conteudo_recente';
include('includes/topo.php');
?>

    <h2>Painel</h2>
    
    <?php include('includes/index_painel.php'); ?>
    
    <div id="painel">
      <h3 class="titulo">Conte&uacute;dos recentes </h3>
      <div id="lista-publica" class="box">
      <form method="get" id="form-result" action="index_lista_recentes.php">
       
    <input type="hidden" name="buscar" value="1" />
    
        <p>&Uacute;ltimos conte&uacute;dos publicados </p>
        
        <div class="view">Exibindo
        <select name="mostrar" onchange="submeteBuscaCadastro('pag_recente');" id="select3">
          <option value="10"<?=Util::iif($mostrar == 10, ' selected="selected"');?>>10</option>
          <option value="20"<?=Util::iif($mostrar == 20, ' selected="selected"');?>>20</option>
          <option value="30"<?=Util::iif($mostrar == 30, ' selected="selected"');?>>30</option>
        </select>
        por p&aacute;gina</div>
      
	  <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <!--
<thead>
            <tr>
              <th class="col-ico"  scope="col">Tipo</th>
              <th class="col-msg" scope="col">T&iacute;tulo</th>
              <th class="col-data"  scope="col">Data</th>
              <th class="col-ver"  scope="col">Ver</th>
            </tr>
          </thead>
-->
          <tbody>
            <?php
		  	foreach ($listarecente as $key => $value):
		  		if ((int)$value['cod_conteudo']):
		  	?>
            <tr>
              <td class="col-ico"><?=$value['formato'];?></td>
              <td class="col-msg"><strong><?=$value['titulo'];?></strong></td>
              <td class="col-data"><?=$value['data'];?></td>
              <td class="col-ver" ><a href="<?=$value['url_arquivo'];?>">Ver</a></td>
            </tr>
            <?php
        		endif;
        	endforeach;
			?>
          </tbody>
        </table>
        <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr />
        </form>
      </div>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
