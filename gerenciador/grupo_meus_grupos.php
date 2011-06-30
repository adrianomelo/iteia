<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/CadastroBO.php");
$cadbo = new CadastroBO;

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;
$buscar = (int)$_GET['buscar'];
$sucesso = (int)$_GET['sucesso'];

$_GET['tipogrupo'] = 2;
$_GET['tipo'] = 3;

$cadastros = $cadbo->getListaCadastros($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $cadastros['total'], $cadastros['link']);

$item_menu = "grupo";
$item_submenu = "meus_grupos";
include('includes/topo.php');
?>
    <h2>Meus grupos</h2>


<form action="grupo_meus_grupos.php" method="get" id="box-busca">
    <label for="textfield" class="display-none">Palavras-chave</label>
    <input type="hidden" name="buscar" value="1" />
    <input type="hidden" name="buscarpor" value="nome" />
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="grupo_meus_busca_popup.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>
    
    <p class="descricao"> Voc&ecirc; pode cadastrar grupos do qual faz parte, sejam eles c&iacute;rculos de leitura, bandas, n&uacute;cleos acad&ecirc;micos, companhias c&ecirc;nicas, coletivos culturais, entre outros exemplos, divulgando seus trabalhos. <a href="grupo_edicao.php">Crie novos grupos</a> ou <a href="grupo_meus_grupos.php">gerencie</a> aqueles que voc&ecirc; participa.</p>

    <!--
    <form id="busca" method="get" action="grupo_meus_grupos.php">
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
          <option value="autor">Autor</option>
        </select>
      </div>

      <div class="campos">
        <label for="select2">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="select2" name="situacao">
          <option value="0" selected="selected">Todos</option>
          <option value="3">Ativo</option>
          <option value="2">Inativo</option>
          <!-/-<option value="1">Pendente</option>-/->
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

    <form method="get" id="form-result" action="grupo_meus_grupos.php">
          <h3 class="titulo"><?=Util::iif($buscar, 'Resultado da busca', 'Meus grupos');?></h3>

	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$cadbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$cadbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="situacao" value="<?=$cadbo->getValorCampo('situacao')?>" />
	<input type="hidden" name="de" value="<?=$cadbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$cadbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
	<input type="hidden" name="tipo" value="3" />

<div id="resultado" class="box">

      <div class="view">Exibindo
        <select name="mostrar" onchange="submeteBuscaCadastro();" id="select3">
          <option value="10"<?=Util::iif($mostrar == 10, ' selected="selected"');?>>10</option>
          <option value="20"<?=Util::iif($mostrar == 20, ' selected="selected"');?>>20</option>
          <option value="30"<?=Util::iif($mostrar == 30, ' selected="selected"');?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>

	  <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>

      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-result">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />
            </th>
            <th class="col-img" scope="col">Imagem</th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
            <th class="col-participantes" scope="col">Autores</th>
          </tr>
        </thead>
        <tbody>
        <?php
		foreach ($cadastros as $key => $value):
			if (intval($value['cod'])):
		?>
          <tr>
            <td class="col-1">
			<?php if ($_SESSION['logado_dados']['nivel'] >= 5):?>
				<input name="codusuario[]" type="checkbox" class="check" value="<?=$value['cod'];?>" />
			<?php else: ?>
			&nbsp;
			<?php endif; ?>
			</td>
            <td class="col-img"><?=Util::iif($value["imagem"], "<img src=\"exibir_imagem.php?img=".$value["imagem"]."&amp;tipo=a&amp;s=1\" width=\"50\" height=\"50\" />", "<img src=\"img/imagens-padrao/mini-autor.jpg\" width=\"50\" height=\"50\" />");?></td>
            <td class="col-titulo"><?=Util::iif(isset($_SESSION['logado_dados']['grupo_responsavel'][$value['cod']]), "<a href=\"".$value['url']."\" title=\"Clique para visualizar\">".$value['nome']."</a>", $value['nome']);?><br />
              <?=$value['descricao'];?></td>
            <td class="col-situacao"><?=$value['situacao'];?></td>
            <td class="col-participantes">
			<?php if (isset($_SESSION['logado_dados']['grupo_responsavel'][$value['cod']])):?>
				<a href="grupo_autores.php?cod=<?=$value['cod'];?>"><?=$value['num_autores'];?></a>
			<?php else: ?>
				<?=$value['num_autores'];?>
			<?php endif; ?>
			</td>
          </tr>
        <?php
			endif;
		endforeach;
		?>
        </tbody>

		<?php if ($_SESSION['logado_dados']['nivel'] >= 5):?>
        <tfoot>
          <tr>
            <td colspan="5" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a> | <a href="javascript:submeteAcoesCadastro(2);">Ativar</a> | <a href="javascript:submeteAcoesCadastro(3);">Desativar</a></td>
          </tr>
        </tfoot>
        <?php endif; ?>

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
