<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codplaylist = (int)$_GET["cod"];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codplaylist)
	$edicaodados = 1;

include_once("classes/bo/PlayListEdicaoBO.php");
$playbo = new PlayListEdicaoBO;
$exibir_form = true;

if (!$codplaylist && !$editar) {
	unset($_SESSION["sessao_playlist_itens"]);
	$playbo->limpaItens();
}

if ($editar) {
	try {
		$codplaylist = $playbo->editar($_POST);
		$exibir_form = false;

		Header("Location: home.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$paginatitulo = 'Destaque';
$item_menu = "home";
$item_submenu = "destaque_inserir";
include('includes/topo.php');

if ($codplaylist && !$editar)
	$playbo->setDadosCamposEdicao($codplaylist);

$codplaylist = (int)$playbo->getValorCampo("codplay");
?>
<script type="text/javascript" src="jscripts/home_playlist.js"></script>

<script type="text/javascript">
var cod_playlist = <?=$codplaylist;?>;
</script>
<script type="text/javascript">
function ActionGravar(){
document.formhome.submit();
}
</script>
    <h2>Destaques</h2>
    <form  method="post" action="home_playlist.php" onsubmit="return false" onkeydown="if(event.keyCode == 13) javascript:buscaConteudoHome();" name="formhome">
    
    <fieldset>
		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codplay" value="<?=$codplaylist?>" />
	</fieldset>
	
<?php if ($_SESSION['logado_dados']['nivel'] > 5): ?>
	<div class="box">
        <fieldset>
        <legend>Qual conta voc&ecirc; quer vincular a esta lista?<span>*</span></legend>
        <select name="cod_usuario" id="contas" <?=$playbo->verificaErroCampo("cod_usuario")?>>
            <option value="0">Escolha entre os colaboradores que voc&ecirc; representa</option>
            <?php if ($_SESSION['logado_dados']['nivel'] >= 7): ?>
            <option value="-2100" <?=Util::iif($playbo->getValorCampo("cod_usuario") == '-2100', 'selected="selected"');?>>ADM - home</option>
            <?php endif; ?>
            <?php
			if ($_SESSION['logado_dados']['cod_colaborador']):
			$dadoscolab = $playbo->getDadosUsuario($_SESSION['logado_dados']['cod_colaborador']);
			?>
            <option value="<?=$_SESSION['logado_dados']['cod_colaborador'];?>" <?=Util::iif($playbo->getValorCampo("cod_usuario") == $_SESSION['logado_dados']['cod_colaborador'], 'selected="selected"');?>>Colaborador - <?=$dadoscolab['nome'];?></option>
            <?php endif; ?>
			<?php
			if (count($_SESSION['logado_dados']['grupo_responsavel'])):
				foreach ($_SESSION['logado_dados']['grupo_responsavel'] as $cod => $value):
					$dadosgrupo = $playbo->getDadosUsuario($cod);
			?>
            <option value="<?=$cod;?>" <?=Util::iif($playbo->getValorCampo("cod_usuario") == $cod, 'selected="selected"');?>>Grupo - <?=$dadosgrupo['nome'];?></option>
			<?php
				endforeach;
			endif;
			?>
            <option value="<?=$_SESSION['logado_dados']['cod']; ?>" <?=Util::iif($playbo->getValorCampo("cod_usuario") == $_SESSION['logado_dados']['cod'], 'selected="selected"');?>>Autor - <?=$_SESSION['logado_dados']['nome']; ?></option>
        </select>
        </fieldset>
      </div>
<?php else: ?>
	<input type="hidden" name="cod_usuario" value="<?=$_SESSION['logado_dados']['cod']?>" />
<?php endif; ?>

      <h3 class="titulo">Cadastro de nova lista</h3>
      <div class="box" id="destaques">
      <p>Escolha a data e hora na qual a nova lista deve come&ccedil;ar a ser exibida na home do portal iTEIA</p>
        
        <fieldset class="campos">
        <div>
          <label for="dFrom">Data:<span>*</span></label>
          <br />
          <input type="text" <?=$playbo->verificaErroCampo("data_inicio")?> value="<?=htmlentities(stripslashes($playbo->getValorCampo("data_inicio")))?>" name="data_inicio" class="txt calendario date" id="dFrom" />
          <em><small><br />
          dd/mm/aaaa</small></em></div>
        </fieldset>
        
        <fieldset class="campos">
        <div>
          <label for="textfield7">Hora:<span>*</span></label>
          <br />
          <input type="text" <?=$playbo->verificaErroCampo("hora_inicio")?> value="<?=htmlentities(stripslashes($playbo->getValorCampo("hora_inicio")))?>" name="hora_inicio" class="txt hour" id="textfield7" />
          <br />
          <em><small>hh:mm</small></em></div>
        </fieldset>
        
		<div class="separador-hr"></div>
      </div>
      <h3 class="titulo">Escolha os itens que deseja incluir</h3>
        <div id="adicionar" class="box">
          <fieldset>
          <p>Procure abaixo por conteúdos que estejam cadastrados no sistema</p>
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
            <label for="select3">Tipo </label>
            <br />
            <select name="formato" id="relacionar_formato">
	          <option value="0" selected="selected">Todos</option>
	          <option value="1">Texto</option>
	          <option value="3">&Aacute;udio</option>
	          <option value="4">V&iacute;deo</option>
	          <option value="2">Imagem</option>
	          <option value="5">Not&iacute;cia</option>
        	</select>
            <input type="button" class="bt-buscar" value="Buscar" onclick="javascript:buscaConteudoHome();" />
          </div>
          </fieldset>
          
          <div id="mostra_resultados_relacionamento"></div>
          
        </div>
        
        <div id="mostra_selecionadas_homeconteudo"></div>
        <div id="up_edicao"></div>
      
      <div id="botoes" class="box"><a href="home.php" class="bt bt-cancelar">Cancelar</a>
        <input type="button" class="bt-gravar" value="Gravar" onclick="ActionGravar();" />
      </div>
    </form>
    
    </div>
<?php if (count($_SESSION["sessao_playlist_itens"])): ?>
<script language="javascript" type="text/javascript">
listarConteudoHome();
</script>
<?php endif; ?>
<?php include('includes/rodape.php'); ?>
