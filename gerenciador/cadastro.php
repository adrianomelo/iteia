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

$_GET['usuario'] = 1;

// quando voltar a pagina, senão tiver valor de mostrar e tiver setado o cookie, mostrar é igual valor do cookie
if (isset($_COOKIE['pag_cadastro']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_cadastro'];

$cadastros = $cadbo->getListaCadastros($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $cadastros['total'], $cadastros['link']);

$item_menu = 'cadastro';
$item_submenu = 'inicio';
$paginatitulo = 'Usu&aacute;rios';
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/autor.js"></script>

    <h2>Usu&aacute;rios</h2>

	<form action="cadastro.php" method="get" id="box-busca">
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="buscarpor" value="nome" />
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="cadastro_busca_popup.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>

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

    <p class="descricao">
		<?php if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6)): ?>

		Cadastre <a href="cadastro_autor.php">novos autores</a> ou gerencie os autores vinculados ao colaborador que voc&ecirc; representa.<br />

		<?php elseif (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): ?>

    	Clique aqui para cadastrar <a href="cadastro_colaborador.php">novos colaboradores</a> ou <a href="cadastro_autor.php">novos autores</a>.

    	<?php endif; ?>

	</p>

    <form method="get" id="form-result" action="cadastro.php">
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
        <select name="mostrar" onchange="submeteBuscaCadastro('pag_cadastro');" id="select3">
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
            <td class="col-1">
			<?php /*if ($_SESSION['logado_dados']['nivel'] > 6 || $value['autor_wiki']):*/ ?>
				<input name="codusuario[]" id="codusuario" type="checkbox" class="check" value="<?=$value['cod'];?>" />
				<input name="tipoautor_<?=$value['cod'];?>" id="tipoautor_<?=$value['cod'];?>" type="hidden" value="<?=Util::iif($value['autor_wiki'], '5', $value['tipo_autor']);?>" />
			<?php /*endif;*/ ?>
			</td>
            <td class="col-titulo"><a href="<?=$value['url'];?>" title="Clique para visualizar"><?=$value['nome'];?></a></td>
            <td class="col-tipo"><?=Util::iif($value['autor_wiki'], 'Wiki', $value['tipo']);?></td>
            <td class="col-uf"><?=$value['estado'];?></td>
            <td class="col-situacao"><?=$value['situacao'];?></td>
            <td class="col-editar">
			<?php
			if ($_SESSION['logado_dados']['nivel'] > 6 || $value['autor_wiki']): ?> 
				<a href="<?=$value['url_editar'];?>" title="Editar">Editar</a>
			<?php else: ?>
			&nbsp;
			<?php endif; ?>
			</td>
          </tr>
        <?php
			endif;
		endforeach;
		?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" class="selecionados"><strong>Selecionados:</strong>
			<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
				<a href="javascript:submeteAcoesCadastro('1');">Apagar</a> | 
			<?php endif; ?>
			<a href="javascript:submeteAcoesCadastro('2');">Ativar</a> | <a href="javascript:submeteAcoesCadastro('3');">Desativar</a>
			<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
				
| <a href="javascript:submeteUnificacao();">Unificar</a>

			<?php endif; ?>
			</td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
            <hr class="both" />
    </div>
</form>
  </div>

<?php include('includes/rodape.php'); ?>