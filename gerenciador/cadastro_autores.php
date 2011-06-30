<?php
include('verificalogin.php');

include_once("classes/bo/CadastroBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$cadbo = new CadastroBO;

if ($_SESSION['logado_dados']['nivel'] == 2) {
	Header('Location: cadastro_meu.php');
	die;
}

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;
$buscar = (int)$_GET['buscar'];
$sucesso = (int)$_GET['sucesso'];

//$_GET['usuario'] = 1;
$_GET['tipo'] = 2;

$cadastros = $cadbo->getListaCadastros($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $cadastros['total'], $cadastros['link']);

$item_menu = 'cadastro';
$item_submenu = 'autor';
$paginatitulo = 'Usu&aacute;rios&nbsp;';
include('includes/topo.php');
?>
    <h2>Usu&aacute;rios</h2>

	<form action="cadastro_autores.php" method="get" id="box-busca">
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="tipo" value="2" />
	<input type="hidden" name="buscarpor" value="nome" />
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="cadastro_autores_busca_popup.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>

    <p class="descricao"> Publique novos autores, gerencie as informa&ccedil;&otilde;es sobre o colaborador que voc&ecirc; representa ou sobre os autores vinculados a ela. <br />
      <a href="cadastro_autor.php">Cadastre novos autores</a> ou gerencie os autores vinculados ao colaborador que voc&ecirc; representa.<br />
    </p>

	<!--
    <form id="busca" method="get" action="cadastro.php">
      <fieldset>
      <legend>Buscar</legend>
      <div class="campos">
        <label for="textfield">Palavras-chave</label>
        <br />
        <input type="hidden" name="buscar" value="1" />
        <input type="text" name="palavrachave" class="txt" id="textfield"  />
      </div>

      <div class="campos">
        <label for="type">Filtrar por</label>
        <br />
        <select id="type" name="buscarpor">
          <option value="nome">Nome</option>
          <option value="estado">Estado</option>
        </select>
      </div>

      <div class="campos">
        <label for="select">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="select" name="situacao">
          <option value="0" selected="selected">Todos</option>
          <option value="3">Ativo</option>
          <option value="2">Inativo</option>
          <option value="1">Pendente</option>
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
          <em><small>dd/mm/aaaa</small></em>
      </div>
      </fieldset>
      <input type="submit" class="bt-buscar" value="Buscar" />
      </fieldset>
    </form>

    -->

    <form method="get" id="form-result" action="cadastro_autores.php">
          <h3 class="titulo"><?=Util::iif($buscar, 'Resultado da busca', 'Mais recentes');?></h3>

	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$cadbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$cadbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="situacao" value="<?=$cadbo->getValorCampo('situacao')?>" />
	<input type="hidden" name="de" value="<?=$cadbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$cadbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
<div id="resultado" class="box">

      <div class="view">Exibindo
        <select name="mostrar" onchange="submeteBuscaCadastro();" id="select3">
          <option value="10"<?=Util::iif($mostrar == 10, ' selected="selected"');?>>10</option>
          <option value="20"<?=Util::iif($mostrar == 20, ' selected="selected"');?>>20</option>
          <option value="30"<?=Util::iif($mostrar == 30, ' selected="selected"');?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>

	  <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>

	  <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-cadastro">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-titulo" scope="col">Nome</th>
            <th class="col-tipo" scope="col">Tipo</th>
            <th class="col-uf" scope="col">Estado</th>
            <th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
			<th class="col-editar" scope="col">Editar</th>
          </tr>
        </thead>
        <tbody>
        <?php
		foreach ($cadastros as $key => $value):
			if (intval($value['cod'])):
		?>
          <tr>
            <td class="col-1"><input name="codusuario[]" type="checkbox" class="check" value="<?=$value['cod'];?>" /></td>
            <td class="col-titulo"><a href="<?=$value['url'];?>" title="Clique para visualizar"><?=$value['nome'];?></a></td>
            <td class="col-tipo"><?=$value['tipo'];?></td>
            <td class="col-uf"><?=$value['estado'];?></td>
            <td class="col-situacao"><?=$value['situacao'];?></td>
            <td class="col-editar"><a href="<?=$value['url_editar'];?>" title="Editar">Editar</a></td>
          </tr>
        <?php
			endif;
		endforeach;
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro('1');">Apagar</a> | <a href="javascript:submeteAcoesCadastro('2');">Ativar</a> | <a href="javascript:submeteAcoesCadastro('3');">Desativar</a></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
            <hr class="both" />
    </div>
</form>
  </div>
<?php if (count($cadastros) == 2 && $buscar): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>
<?php include('includes/rodape.php'); ?>