<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/HomeBuscaBO.php");
$buscabo = new HomeBuscaBO;

$pre = (int)$_GET["pre"];
$codu = (int)$_GET["codu"];

// carrega pagina do usuario colab/autor
if ($_SESSION['logado_dados']['nivel'] <= 6) {
	$codusuario = $_SESSION['logado_cod'];

	switch ($_SESSION['logado_dados']['nivel']) {
		case 2:
			$titulo = '<p>Este conte&uacute;do ser&aacute; publicado pelo Autor: <strong>'.$_SESSION['logado_dados']['nome'].'</strong></p>';
			$codtipo = 2;
		break;
		case 5:
		case 6:
			$titulo = '<p>Este conte&uacute;do ser&aacute; publicado pelo Colaborador: <strong>'.$_SESSION['logado_dados']['nome'].'</strong></p>';
			$codtipo = 1;
		break;
	}
}

if ($codu) {
	$dadosusuario = $buscabo->getDadosUsuario($codu);
	$codusuario = $dadosusuario['cod_usuario'];

	switch ($dadosusuario['cod_tipo']) {
		case 1:
			$titulo = '<p>Este conte&uacute;do ser&aacute; publicado pelo Autor: <strong>'.$dadosusuario['nome'].'</strong></p>';
			$codtipo = 1;
		break;
		case 2:
			$titulo = '<p>Este conte&uacute;do ser&aacute; publicado pelo Colaborador: <strong>'.$dadosusuario['nome'].'</strong></p>';
			$codtipo = 2;
		break;
		case 3:
			$titulo = '<p>Este conte&uacute;do ser&aacute; publicado pelo Grupo: <strong>'.$dadosusuario['nome'].'</strong></p>';
			$codtipo = 3;
		break;
	}
}

$paginatitulo = 'Gerenciar Destaques';
//$item_menu = "home";
//$item_submenu = "destaque_usuario";
include('includes/topo.php');
?>

<script type="text/javascript" src="jscripts/home.js"></script>

<?php if (!$pre): ?>
<script language="javascript" type="text/javascript">
apagaItensIniciais();
</script>
<?php endif; ?>

    <h2>Destaques do usu&aacute;rio</h2>
<?php if ($_SESSION['logado_dados']['nivel'] >= 7): ?>
    <div class="box box-dica"><span id="add" style="display:none;">Voc&ecirc; pode <a href="#adicionar"><strong>adicionar mais conte&uacute;dos a esta playlist</strong></a><br /></span>
    Nesta tela voc&ecirc; pode gerenciar a home de <a href="home_vincular.php?height=370&amp;width=305&amp;tipo=2" title="Vincular a um autor" class="thickbox"><strong>Autor</strong></a>, <a href="home_vincular.php?height=370&amp;width=305&amp;tipo=1" title="Vincular a um colaborador" class="thickbox"><strong>Colaborador</strong></a> e <a href="home_vincular.php?height=370&amp;width=305&amp;tipo=3" title="Vincular a um grupo"  class="thickbox"><strong>Grupo</strong></a></div>
<?php endif; ?>

    <div>

    <div id="publicado_por"><?=$titulo;?></div>

	<div id="mostra_selecionadas_homeconteudo"></div>

	<input type="hidden" value="<?=$codusuario;?>" id="codusuario" />
    <input type="hidden" value="<?=$codtipo;?>" id="tipousuario" />

    <div id="adicionar" style="display:none;">
	<br />
 <h3 class="titulo">Adicionar  Conte&uacute;do &agrave; playlist</h3>
    <form action="conteudo.php" method="get" id="busca">
      <fieldset>
      <input type="hidden" name="buscar" value="1" />
      <legend>Buscar</legend>
        <div class="campos">
        <label for="textfield">Palavras-chave</label>
        <br />
        <input name="palavrachave" type="text" class="txt" id="relacionar_palavrachave"  />
      </div>
      <div class="campos">
        <label for="type">Filtrar por</label>
        <br />
        <select id="relacionar_buscarpor" name="buscarpor">
          <option value="titulo" selected="selected">T&iacute;tulo</option>
          <option value="autor">Autor</option>
          <option value="ativo">Ativo</option>
          <option value="inativo">Inativo</option>
        </select>
      </div>
      <div class="campos">
        <label for="label">Tipo </label>
        <br />
        <select name="formato" id="relacionar_formato">
          <option value="0" selected="selected">Todos</option>
          <option value="1">Texto</option>
          <option value="3">&Aacute;udio</option>
          <option value="4">V&iacute;deo</option>
          <option value="2">Imagem</option>
        </select>
      </div>
      <fieldset id="periodo">
      <legend class="seta">Buscar por per&iacute;odo</legend>
        <div class="fechada">
        <label for="dFrom">De:</label>
        <input name="de" type="text" class="txt calendario date" id="relacionar_de" />
        <em><small>dd/mm/aaaa</small></em>
        <label for="dTo">At&eacute;:</label>
        <input name="ate" type="text" class="txt calendario date" id="relacionar_ate" />
        <em><small>dd/mm/aaaa</small></em> </div>
      </fieldset>
        <input name="submit" type="button" class="bt-buscar" value="Buscar" id="bt_buscaconteudo" onclick="javascript:buscaConteudoHomeUsuario();" />
      </fieldset>
    </form>

    </div>

	<div id="mostra_resultados_relacionamento"></div>

	</div>
  </div>
  <hr />

<?php
if (($_SESSION['logado_dados']['nivel'] <= 6) || $codu):
echo '<script language="javascript" type="text/javascript">

$("#add").show();
$("#mostra_resultados_relacionamento").hide();
$("#mostra_selecionadas_homeconteudo").load("home_ajax.php?get=exibir_dadoshome_usuario&tipousuario='.$codtipo.'&codusuario='.$codusuario.'");
$("#adicionar").show();

</script>';
endif;
?>

<?php include('includes/rodape.php'); ?>
