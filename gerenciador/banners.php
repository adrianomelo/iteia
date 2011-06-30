<?php
include('verificalogin.php');

include_once("classes/bo/BannersBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$bannerbo = new BannersBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;
$buscar = (int)$_GET['buscar'];

// quando voltar a pagina, senão tiver valor de mostrar e tiver setado o cookie, mostrar é igual valor do cookie
if (isset($_COOKIE['pag_banner']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_banner'];

$banners = $bannerbo->getListaBanners($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $banners['total'], $banners['link']);

$item_menu = "banners";
$item_submenu = "inicio";
$paginatitulo = "An&uacute;ncios";
include('includes/topo.php');
?>

    <h2>An&uacute;ncios</h2>
    
    <form action="banners.php" method="get" id="box-busca">
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input type="hidden" name="buscar" value="1" />
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="banners_busca_pop.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <p class="descricao"><a href="banners_edicao.php">Publique banners publicit&aacute;rios</a> e chame aten&ccedil;&atilde;o para a&ccedil;&otilde;es ou eventos relacionados &agrave;(s) entidade(s) que voc&ecirc; representa. Os banners aparecer&atilde;o em   diferentes locais do portal, com ordem aleat&oacute;ria. Eles devem ter o tamanho exato de 180 pixels x 150 pixels e podem ser animados (em GIF) ou est&aacute;ticos (em formato de imagem).</p>
    
    <!--
    <form id="busca" method="get" action="banners.php">
      <fieldset>
      <input type="hidden" name="buscar" value="1" />
      <legend>Buscar</legend>
      <div class="campos">
        <label for="textfield">Palavras-chave</label>
        <br />
        <input type="text" name="palavrachave" class="txt" id="textfield"  />
      </div>
      <div class="campos">
        <label for="type">Filtrar por</label>
        <br />
        <select id="type" name="buscarpor">
          <option value="" selected="selected">Todos</option>
          <option value="titulo">T&iacute;tulo</option>
          <option value="colaborador">Colaborador</option>
          <option value="prioridade">Prioridade</option>
        </select>
      </div>
      <div class="campos">
        <label for="select2">Situa&ccedil;&atilde;o</label>
        <br />
        <select name="situacao">
          <option value="" selected="selected">Todas</option>
          <option value="1">Ativo</option>
          <option value="2">Inativo</option>
        </select>
      </div>
      <fieldset id="periodo">
      <legend class="seta">Buscar por per&iacute;odo</legend>
      <div class="fechada">
        <label for="dFrom">De:</label>
        <input type="text" name="de" class="txt calendario date" id="dFrom" />
        <em><small>dd/mm/aaaa</small></em>
        <label for="dTo">At&eacute;:</label>
        <input type="text" name="ate" class="txt calendario date" id="dTo" />
        <em><small>dd/mm/aaaa</small></em> </div>
      </fieldset>
      <input type="submit" class="bt-buscar" value="Buscar" />
      </fieldset>
    </form>
    -->
    
    <h3 class="titulo"><?=($buscar ? $paginacao['num_total'].' resultados encontrados' : 'Mais Recentes');?></h3>
    <form method="get" id="form-result" action="banners.php">
    
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$bannerbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$bannerbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="situacao" value="<?=$bannerbo->getValorCampo('situacao')?>" />
	<input type="hidden" name="de" value="<?=$bannerbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$bannerbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
	
    <div id="resultado" class="box">
      <div class="view">Exibindo
         <select name="mostrar" onchange="submeteBuscaCadastro('pag_banner');" id="select3">
          <option value="10" <?=($mostrar == 10) ? 'selected="selected"' : '';?>>10</option>
          <option value="20" <?=($mostrar == 20) ? 'selected="selected"' : '';?>>20</option>
          <option value="30" <?=($mostrar == 30) ? 'selected="selected"' : '';?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-banner">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />
            </th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-colaborador" scope="col">Colaborador</th>
            <th class="col-prioridade" scope="col">Prioridade</th>
            <th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
            <th class="col-editar" scope="col">Editar</th>
          </tr>
        </thead>
        <tbody>
        	<?php
				foreach ($banners as $key => $value):
					if (intval($value['cod'])):
			?>
	          <tr>
	            <td class="col-1"><input type="checkbox" name="codbanner[]" class="check" value="<?=$value['cod'];?>"  /></td>
	            <td class="col-titulo"><a href="banners_publicado.php?cod=<?=$value['cod'];?>"><?=$value['titulo'];?></a></td>
	            <td class="col-colaborador"><?=$value['nome'];?></td>
	            <td class="col-prioridade"><?=$value['prioridade'];?></td>
				<td class="col-situacao"><?=$value['situacao'];?></td>
				<td class="col-editar"><a href="banners_edicao.php?cod=<?=$value['cod'];?>" title="Editar">Editar</a></td>
	          </tr>
          	<?php
					endif;
				endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a> | <a href="javascript:submeteAcoesCadastro(2);">Ativar</a> | <a href="javascript:submeteAcoesCadastro(3);">Desativar</a> |
              <label for="select">Mudar Prioridade</label>
              <select name="mudar_prioridade" onchange="javascript:submeteAcoesCadastro(4);" id="select">
		  <option value="0">Prioridade</option>
	      <option value="1">Alta</option>
	      <option value="2">M&eacute;dia</option>
	      <option value="3">Baixa</option>
	    </select></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <hr class="both" />
    </div>
    </form>
  </div>
<?php if (count($banners) == 2 && $buscar): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>
<?php include('includes/rodape.php'); ?>
