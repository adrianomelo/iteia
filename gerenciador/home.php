<?php
include('verificalogin.php');

include_once("classes/bo/PlayListBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$playbo = new PlayListBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;
$buscar = (int)$_GET['buscar'];

// quando voltar a pagina, senão tiver valor de mostrar e tiver setado o cookie, mostrar é igual valor do cookie
if (isset($_COOKIE['pag_home']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_home'];

$playlists = $playbo->getListaPlayList($_GET, $inicial, $mostrar, 1);
$paginacao = Util::paginacao($pagina, $mostrar, $playlists['total'], $playlists['link']);

$playlists_passadas = $playbo->getListaPlayList(array(), 0, 0, 0);

unset($_SESSION['sessao_playlist_itens']);

$paginatitulo = 'Painel';
$item_menu = 'home';
$item_submenu = 'destaque_home';
include('includes/topo.php');
?>
    <h2>Destaques</h2>
    
    <form action="home.php" method="get" id="box-busca">
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input type="hidden" name="buscar" value="1" />
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="home_busca_pop.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <form method="get" id="form-result" action="home.php">
    
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$playbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$playbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="de" value="<?=$playbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$playbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
    
    <p class="descricao">Aqui voc&ecirc; pode escolher conte&uacute;dos para aparecerem na capa do portal iTEIA. Para isso, <a href="home_playlist.php">insira listas de destaque</a> e defina a data e a hora para que ela apare&ccedil;a para os visitantes.</p>
    <h3 class="titulo">Lista de destaques em exibi&ccedil;&atilde;o</h3>
    <div id="resultado" class="box">
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-data" scope="col">Data de in&iacute;cio</th>
            <th class="col-data" scope="col">Conta</th>
            <th class="col-conteudo" scope="col">Destaques</th>
            <th class="col-conteudo" scope="col">Dura&ccedil;&atilde;o</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Remover</th>
          </tr>
        </thead>
        <tbody>
        	<?php
				foreach ($playlists_passadas as $key => $value):
					if (intval($value['cod'])):
			?>
	          <tr>
	            <td class="col-1"><input type="checkbox" name="codplaylist[]" class="check" value="<?=$value['cod'];?>"  /></td>
	            <td class="col-data"><?=date('d/m/Y', strtotime($value['data']));?> - <?=date('H:i', strtotime($value['hora']));?></td>
	            <td class="col-conteudo"><?=$value['conta'];?></td>
	            <td class="col-conteudo"><?=$value['total'];?></td>
            	<td class="col-conteudo"><?=$value['duracao'];?></td>
				<td class="col-editar"><a href="home_playlist.php?cod=<?=$value['cod'];?>" title="Editar">Editar</a></td>
				<td class="col-remover"><a href="home.php?buscar=1&amp;acao=1&amp;codplaylist[]=<?=$value['cod'];?>" title="Remover">Remover</a></td>
	          </tr>
          	<?php
					endif;
				endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
      <hr class="both" />
    </div>
    
    
    <h3 class="titulo"><?=($buscar ? $paginacao['num_total'].' resultados encontrados' : 'Pr&oacute;ximas listas de destaques');?></h3>
    <div id="resultado" class="box">
      <div class="view">Exibindo
         <select name="mostrar" onchange="submeteBuscaCadastro('pag_home');" id="select3">
          <option value="10" <?=($mostrar == 10) ? 'selected="selected"' : '';?>>10</option>
          <option value="20" <?=($mostrar == 20) ? 'selected="selected"' : '';?>>20</option>
          <option value="30" <?=($mostrar == 30) ? 'selected="selected"' : '';?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-data" scope="col">Data de in&iacute;cio</th>
            <th class="col-data" scope="col">Conta</th>
            <th class="col-conteudo" scope="col">Destaques</th>
            <th class="col-conteudo" scope="col">Dura&ccedil;&atilde;o</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Remover</th>
          </tr>
        </thead>
        <tbody>
        	<?php
				foreach ($playlists as $key => $value):
					if (intval($value['cod'])):
			?>
	          <tr>
	            <td class="col-1"><input type="checkbox" name="codplaylist[]" class="check" value="<?=$value['cod'];?>"  /></td>
	            <td class="col-data"><?=date('d/m/Y', strtotime($value['data']));?> - <?=date('H:i', strtotime($value['hora']));?></td>
	            <td class="col-conteudo"><?=$value['conta'];?></td>
	            <td class="col-conteudo"><?=$value['total'];?></td>
            	<td class="col-conteudo"><?=$value['duracao'];?></td>
				<td class="col-editar"><a href="home_playlist.php?cod=<?=$value['cod'];?>" title="Editar">Editar</a></td>
				<td class="col-remover"><a href="home.php?buscar=1&amp;acao=1&amp;codplaylist[]=<?=$value['cod'];?>" title="Remover">Remover</a></td>
	          </tr>
          	<?php
					endif;
				endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <hr class="both" />
    </div>
    </form>
  </div>
<?php include('includes/rodape.php'); ?>
