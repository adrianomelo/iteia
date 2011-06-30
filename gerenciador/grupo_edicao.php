<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codgrupo = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codgrupo)
	$edicaodados = 1;

include_once("classes/bo/GrupoEdicaoBO.php");
$grupobo = new GrupoEdicaoBO;
$exibir_form = true;

if (!$editar)
	unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados'], $_SESSION['sessao_autores_integrantes']);

if ($editar) {
	try {
		$cod_usuario = $grupobo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: grupo_publicado.php?cod=".$cod_usuario);
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$item_menu = "grupo";
$item_submenu = 'inserir';
include('includes/topo.php');

if ($codgrupo && !$editar) {
	$grupobo->setDadosCamposEdicao($codgrupo);
	$codestado = $grupobo->getValorCampo("codestado");
	$codcidade = $grupobo->getValorCampo("codcidade");
} else {
	$codestado = 17;
	$codcidade = 6330;
}

$codgrupo = (int)$grupobo->getValorCampo("codgrupo");
$tipo = (array)$grupobo->getValorCampo("tipo");
?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<h2>Grupos</h2>

<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>
<script type="text/javascript" src="jscripts/grupos.js"></script>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->
	
<script type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jscripts/autocompletar.js"></script>

<?php if ($erro_mensagens): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="grupo_edicao.php" method="post" enctype="multipart/form-data" name="form-grupo" id="form-grupo">
      <h3 class="titulo">Cadastro de Grupos</h3>
      <div class="box">
        <fieldset>

        <input type="hidden" name="editar" value="1" />
	  	<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
	  	<input type="hidden" name="codgrupo" id="codgrupo" value="<?=$codgrupo?>" />
	  	<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$grupobo->getValorCampo('imgtemp')?>" />

        <label for="textfield">Nome<span>*</span></label>
        <br />

        <input type="text" class="txt" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('nome')))?>" name="nome" id="nome" onkeyup="contarCaracteres(this, 'cont_nome', 100);" maxlength="100" size="80" <?=$grupobo->verificaErroCampo("nome")?> />
        <input type="text" disabled="disabled" id="cont_nome" class="txt counter" value="100" size="4" />

        <br />
        <label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label>
        <br />

		<textarea name="descricao" cols="60" rows="10" class="mceSimple" id="textarea" <?=$grupobo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 1400);"><?=Util::clearText($grupobo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="1400" size="4" disabled="disabled" id="cont_descricao" />

<div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($grupobo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$grupobo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$grupobo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/autor.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$grupobo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$grupobo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codgrupo;?>', 9);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=usuario&amp;cod=<?=$codgrupo;?>&amp;height=180&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>

      <div class="box">
        <fieldset>
        <legend>Endereço</legend>
        <label for="textfield5">Endere&ccedil;o</label>
        <br />
        <input type="text" class="txt" id="textfield5" size="80" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('endereco')))?>" name="endereco" />
        <br />
        <label for="textfield6">Complemento</label>
        <br />
        <input type="text" class="txt" id="textfield6" size="40" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('complemento')))?>" name="complemento" />
        <br />
        <label for="textfield7">Bairro</label>
        <br />
        <input type="text" class="txt" id="textfield7" size="40" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('bairro')))?>" name="bairro" />
        <br />
        <label for="textfield8">CEP</label>
        <br />
        <input type="text" class="txt cep" id="textfield8" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('cep')))?>" name="cep" />
        <em><small>Ex. 54000-000</small></em> <br />
        <label for="select">Pa&iacute;s<span>*</span></label>
        <br />
        <select name="codpais" id="pais" onchange="javascript:exibeEstadoCidade();" <?=$grupobo->verificaErroCampo("pais")?>>
		<?php
		$lista_paises = $grupobo->getListaPaises();
		$pais_selecionado = $grupobo->getValorCampo("codpais");
		if (!$pais_selecionado)
			$pais_selecionado = ConfigGerenciadorVO::getCodPaisBrasil();
		foreach ($lista_paises as $pais) {
			echo "<option value=\"".$pais["cod_pais"]."\"";
			if ($pais["cod_pais"] == $pais_selecionado)
				echo " selected=\"selected\"";
			echo ">".$pais["pais"]."</option>\n";
		}
		?>
      </select>
        <br />
      <div style="display: inline;" id="mostraestado">
      <label for="select2">Estado<span>*</span></label>
      <br />
      <select name="codestado" id="estado" onchange="obterCidades(this, <?=(int)$codcidade?>)" <?=$grupobo->verificaErroCampo("estado")?>>
		<?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $grupobo->getListaEstados();
		foreach ($lista_estados as $estado) {
			echo "<option value=\"".$estado["cod_estado"]."\"";
			if ($estado["cod_estado"] == $codestado)
				echo " selected=\"selected\"";
			echo ">".$estado["sigla"]."</option>\n";
		}
		?>
      </select>
      <br />
	  </div>

        <label for="select3">Cidade<span>*</span></label>
      <br />
      <select name="codcidade" id="selectcidade" <?=$grupobo->verificaErroCampo("codcidade")?>>
	  <option>.</option>
	  </select>

	  <input type="text" class="txt" <?=$grupobo->verificaErroCampo("cidade")?> style="display:none;" name="cidade" id="cidade" size="45" value="<?=htmlentities(stripslashes($grupobo->getValorCampo("cidade")))?>" maxlength="100" />

        </fieldset>
      </div>
      
<?php
include('includes/cadastro_colaborador_integrantes_busca.php');
if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8) || !$codgrupo):
?>
      <div class="box">
      <fieldset>
      <legend>P&aacute;gina do Grupo</legend>
      <label for="textfield12">Final do endere&ccedil;o<span>*</span></label>
      <br />
      <input type="text" class="txt" name="finalendereco" id="final_endereco" size="40" value="<?=htmlentities(stripslashes($grupobo->getValorCampo("finalendereco")))?>" maxlength="30" onkeyup="contarCaracteres(this, 'cont_final_endereco', 30); exibeTextoLogin(this, 'texto_final_endereco', 'textfield14'); lowercase('final_endereco');" <?=$grupobo->verificaErroCampo("finalendereco")?> />
      <input type="text" class="txt counter" size="4" disabled="disabled" value="30" id="cont_final_endereco" />
      <div>http://www.nacaoculturalpe.gov.br/<strong id="texto_final_endereco"></strong></div>
      </fieldset>
      </div>
<?php
endif;
?>

      <div class="box box-amarelo">
        <fieldset >
        <legend class="seta">Qual o tipo do seu Grupo?</legend>
        <div class="fechada" id="box-tipo">
			<select name="tipo[]">
			<option value="0">Escolha o tipo</option>
          	<?php
			foreach($grupobo->getListaTipos() as $key => $value):
          		if ((int)$value['cod']):
          	?>
          		<option value="<?=$value['cod'];?>" <?=Util::iif(in_array($value['cod'], $tipo), 'selected="selected"');?>><?=$value['tipo'];?></option>
          	<?php
          		endif;
          	endforeach;
            ?>
			</select>
        </div>
        </fieldset>
      </div>
      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Contato</legend>
        <div class="fechada" id="box-contato">
          <label for="textfield9">Telefone</label>
          <br />
          <input type="text" class="txt phone" id="textfield9" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('telefone')))?>" name="telefone" />
          <em><small>Ex. (81) 3222-3333</small></em> <br />
          <label for="textfield10">Celular</label>
          <br />
          <input type="text" class="txt phone" id="textfield10" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('celular')))?>" name="celular" />
          <em><small> Ex. (81) 3222-3333</small></em> <br />
          <label for="textfield11">E-mail</label>
          <br />
          <input type="text" class="txt" id="textfield11" size="80" value="<?=htmlentities(stripslashes($grupobo->getValorCampo('email')))?>" name="email" <?=$grupobo->verificaErroCampo("email")?> />
          <br />
          <label for="textfield13">Site</label>
          <br />
          <input type="text" class="txt" id="textfield13" value="http://<?=htmlentities(stripslashes($grupobo->getValorCampo('site')))?>" name="site" size="80" />
        </div>
        </fieldset>
      </div>

      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Contato Online</legend>
        <div class="fechada" id="box-online">
          <div class="campos">
            <label for="textfield18">Endere&ccedil;o</label>
            <em><small>(Ex. seu_endereço@hotmail.com)</small></em><br />
            <input type="text" class="txt" id="textfield18" size="60" />
  <br />

            <label for="select5">Comunicadores</label>
            <br />
            <select id="select5" name="comunicadores">
		        <option value="1">Gtalk</option>
		        <option value="2">MSN</option>
		        <option value="3">Skype</option>
		        <option value="4">Jabber</option>
		        <option value="5">ICQ</option>
		        <option value="6">Yahoo Messenger</option>
				<option value="7">AIM</option>
		        <option value="8">Outro</option>
      		</select>
<br />
          <input type="button" class="bt-adicionar" value="Adicionar" onclick="adicionarContato();" /></div>

          <div class="campos">
            <span>Comunicadores Adicionados</span>

            <ul class="lista-remover" id="lista_contatos">
            </ul>

          </div>
        </div>
        </fieldset>
      </div>

      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Links relacionados</legend>
        <div class="fechada" id="box-sitesrelacionados">

          <div class="campos">
            <label for="textfield19">Título</label>
            <br />
            <input name="textfield19" type="text" class="txt" id="textfield19" onkeyup="contarCaracteres(this, 'cont_titulo_site_relacionado', 25);" />
            <input type="text" disabled="disabled" class="txt counter" value="25" size="4" id="cont_titulo_site_relacionado" />

            <br />
            <label for="textfield17">Endere&ccedil;o</label>
            <br />

            <input type="text" class="txt" id="textfield17" size="80" value="http://" />
            <br />
            <input type="button" class="bt-adicionar" value="Adicionar" onclick="adicionarSiteRelacionado();" />
          </div>
          <div class="campos">
            <span>Links Adicionados</span>

            <ul class="lista-remover" id="lista_sitesrelacionados">
            </ul>

          </div>
        </div>
        </fieldset>
      </div>

      <div id="botoes" class="box">
        <a href="grupo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
  </div>
  <hr />
<?php if ($exibir_form): ?>
	<script language="javascript" type="text/javascript">
	carregaContatos();
	carregaSiteRelacionado();
	obterCidades(document.getElementById("estado"), <?=(int)$codcidade?>);
	contarCaracteres(document.getElementById('nome'), "cont_nome", 100);
	contarCaracteres(document.getElementById("final_endereco"), "cont_final_endereco", 30);
	exibeTexto(document.getElementById("final_endereco"), 'texto_final_endereco');
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 1400);
	</script>
<?php
	if (count($_SESSION['sessao_autores_integrantes'])):
		echo '<script language="javascript" type="text/javascript">carregarIntegrantes();</script>';
	endif;
	if (count($grupobo->getValorCampo("tipo"))):
		echo '<script language="javascript" type="text/javascript">$(\'#box-tipo\').show();</script>';
	endif;
	if ($grupobo->getValorCampo("contato")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-online\').show();</script>';
	endif;
	if ($grupobo->getValorCampo("sitesrelacionados")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-sitesrelacionados\').show();</script>';
	endif;
	if ($grupobo->getValorCampo("telefone") || $grupobo->getValorCampo("celular") || $grupobo->getValorCampo("email") || $grupobo->getValorCampo("site")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-contato\').show();</script>';
	endif;
endif;
?>

<?php include('includes/rodape.php'); ?>
