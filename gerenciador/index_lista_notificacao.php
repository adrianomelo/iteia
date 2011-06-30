<?php
include('verificalogin.php');

include_once("classes/bo/PrincipalBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$indexbo = new PrincipalBO;

$usuariodados = $indexbo->getUsuarioDados();

include_once("classes/bo/NotificacaoBO.php");
$notifbo = new NotificacaoBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 6);
$inicial = ($pagina - 1) * $mostrar;

// quando voltar a pagina, senão tiver valor de mostrar e tiver setado o cookie, mostrar é igual valor do cookie
if (isset($_COOKIE['pag_notificacao']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_notificacao'];

$notificacoes = $notifbo->getListaNotificacao($inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $notificacoes['total'], $notificacoes['link']);

$item_menu = 'index';
$item_submenu = 'lista_publica';
include('includes/topo.php');
?>

    <h2>Painel</h2>

<?php include('includes/index_painel.php'); ?>

    <div id="painel">
	 <h3 class="titulo">Lista de autoriza&ccedil;&otilde;es</h3>
       <div id="lista-publica" class="box">
       <form method="get" id="form-result" action="index_lista_notificacao.php">

    <input type="hidden" name="buscar" value="1" />

           <p><?=Util::iif(($_SESSION['logado_dados']['nivel'] >= 5), 'Conte&uacute;dos que est&atilde;o aguardando aprova&ccedil;&atilde;o');?></p>

         <div class="view">Exibindo
        <select name="mostrar" onchange="submeteBuscaCadastro('pag_notificacao');" id="select3">
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
              <th class="col-msg" scope="col">Notifica&ccedil;&atilde;o</th>
              <th class="col-ver" scope="col">Ver</th>
            </tr>
          </thead>
-->
          <tbody>
          	<?php
		  	foreach ($notificacoes as $key => $value):
		  		if ((int)$value['cod_notificacao']):
			?>
            <tr>
              <td class="col-ico"><?=$value['img_formato'];?></td>
              
              <td class="col-msg">
			  <?php if (($value['cod_tipo'] == (150))or($value['cod_tipo'] == (250))): ?>
			  <? if($value['cod_tipo'] == (250)){ echo "Novo colaborador"; } else { echo "Novo Autor"; }?> <strong><?=htmlentities($value['nome']);?></strong> (<?=$value['sigla'];?>)<br />
                  <strong>Solicita aprovação de cadastro</strong><br />
			  <?php else: ?>
			  	Autor: <a href="<?=ConfigVO::URL_SITE.$value['url'];?>"><?=htmlentities($value['nome']);?></a>
<!--
Novo(a) <?=Util::getFormatoConteudo($value['cod_formato']);?> de <a href="<?=ConfigVO::URL_SITE.$value['url'];?>"><?=htmlentities($value['nome']);?></a> (<?=$value['sigla'];?>)
-->
<br />
			  	<?php if ($value['cod_tipo'] == 5): ?>
				  Modificou este conteúdo e pediu nova aprovação:
				<?php else: ?>
				  Novo(a) <?=Util::getFormatoConteudo($value['cod_formato']);?>:
				<?php endif; ?>
			  	<strong><?=htmlentities($value['titulo']);?></strong>
			  <?php endif; ?>
			  </td>
              <td class="col-ver"><a href="<? if($value['cod_tipo']==150){ echo $value['url_arquivo']; } else { echo "index_exibir_colaborador_pendente.php?cod=$value[cod_colaborador]"; }?>"title="Clique para visualizar">Ver</a></td>
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