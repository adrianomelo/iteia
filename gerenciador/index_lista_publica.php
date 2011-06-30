<?php
include('verificalogin.php');

include_once("classes/bo/PrincipalBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$indexbo = new PrincipalBO;

$usuariodados = $indexbo->getUsuarioDados();

include_once("classes/bo/ListaPublicaBO.php");
$listabo = new ListaPublicaBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 6);
$inicial = ($pagina - 1) * $mostrar;

$listapublica = $listabo->getListaPublica($inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $listapublica['total'], $listapublica['link']);

$item_menu = 'index';
$item_submenu = 'lista_publica';
include('includes/topo.php');
?>

    <h2>Painel</h2>
	
	<?php include('includes/index_painel.php'); ?>
	
    <div id="painel" >
      <h3 class="titulo">Lista de autoriza&ccedil;&atilde;o</h3>
    <div id="lista-publica" class="box">
      <p>Conte&uacute;dos que est&atilde;o aguardando aprova&ccedil;&atilde;o</p>
      <form method="get" id="form-result" action="index_lista_notificacao.php">
       
    <input type="hidden" name="buscar" value="1" />
        
        
        <div class="view">Exibindo
        <select name="mostrar" onchange="submeteBuscaCadastro();" id="select3">
          <option value="10"<?=Util::iif($mostrar == 10, ' selected="selected"');?>>10</option>
          <option value="20"<?=Util::iif($mostrar == 20, ' selected="selected"');?>>20</option>
          <option value="30"<?=Util::iif($mostrar == 30, ' selected="selected"');?>>30</option>
        </select>
        por p&aacute;gina</div>
      
	  <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
	  
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="col-ico"  scope="col">Tipo</th>
              <th class="col-msg" scope="col">Notifica&ccedil;&atilde;o</th>
              <th class="col-ver"  scope="col">Ver</th>
            </tr>
          </thead>
          <tbody>
          	<?php
		  	foreach ($listapublica as $key => $value):
		  		if ((int)$value['cod_conteudo']):
			?>
            <tr>
              <td class="col-ico"><?=$value['imagem'];?></td>
              
              <td class="col-msg">Novo(a) <?=Util::getFormatoConteudo($value['cod_formato']);?> de <a href="<?=ConfigVO::URL_SITE.'autor/'.$value['url'];?>"><?=htmlentities($value['nome']);?></a><br />
			  	<strong><?=htmlentities($value['titulo']);?></strong></td>
              <td class="col-ver"><a href="index_exibir_aprovacao_lista_publica.php?cod=<?=$value['cod_conteudo'];?>"title="Clique para visualizar">Ver</a></td>
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