<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codcolaborador = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];
$proprio = (int)Util::iif($_GET["proprio"], $_GET["proprio"], $_POST["proprio"]);

if ($proprio) {
	$codcolaborador = $_SESSION['logado_cod'];
	$edicaodados = 1;
}

include_once("classes/bo/ColaboradorEdicaoBO.php");
$colaboradorbo = new ColaboradorEdicaoBO;
$exibir_form = true;

if (!$editar)
	unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados'], $_SESSION['sessao_autores_integrantes']);

if ($editar) {
	try {
		$cod_usuario = $colaboradorbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: cadastro_colaborador_publicado.php?cod=".$cod_usuario.Util::iif($proprio, '&proprio=1'));
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$item_menu = "cadastro";
$item_submenu = Util::iif($proprio, 'meu_cadastro', 'colaborador');
include('includes/topo.php');

if ($codcolaborador && !$editar) {
	$colaboradorbo->setDadosCamposEdicao($codcolaborador);
	$codestado = $colaboradorbo->getValorCampo("codestado");
	$codcidade = $colaboradorbo->getValorCampo("codcidade");
} else {
	$codestado = 17;
	$codcidade = 6330;
}

$codcolaborador = (int)$colaboradorbo->getValorCampo("codcolaborador");
$rede = (array)$colaboradorbo->getValorCampo("rede");
?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<h2>Usu&aacute;rios</h2>

<script language="javascript" type="text/javascript" src="jscripts/ajax.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/edicao.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/colaborador.js"></script>

<!--
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<script language="javascript" type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/autocompletar.js"></script>

<?php if ($erro_mensagens || $colaboradorbo->verificaErroCampo("nomeinstituicao") || $colaboradorbo->verificaErroCampo("entidade") || $colaboradorbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="cadastro_colaborador.php" method="post" enctype="multipart/form-data" id="add-evento">
      <h3 class="titulo">Cadastro de colaboradores</h3>
      <div class="box">
		<p>Os campos marcados o asterísco (*) são obrigatórios.</p>
        <fieldset>

        <input type="hidden" name="editar" value="1" />
	  	<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
	  	<input type="hidden" name="codcolaborador" id="codcolaborador" value="<?=$codcolaborador?>" />
	  	<input type="hidden" name="proprio" value="<?=$proprio?>" />
	  	<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$colaboradorbo->getValorCampo('imgtemp')?>" />

        <label for="textfield">Nome da institui&ccedil;&atilde;o / Ponto de Cultura<span>*</span></label>
        <br />

        <input type="text" class="txt" name="nomeinstituicao" id="nomeinstituicao" size="80" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo("nomeinstituicao")))?>" <?=$colaboradorbo->verificaErroCampo("nomeinstituicao")?> maxlength="60" onkeyup="contarCaracteres(this, 'cont_nome_instituicao', 60)" />
        <input type="text" disabled="disabled" class="txt counter" value="60" size="4" id="cont_nome_instituicao" />

        <br />
        <!--<label for="textfield2">Vinculado a alguma entidade? Qual?</label>
        <br />

        <input type="text" class="txt" name="entidade" <?=$colaboradorbo->verificaErroCampo("entidade")?> id="entidade" size="80" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo("entidade")))?>" maxlength="100" onkeyup="contarCaracteres(this, 'cont_entidade', 100)" />
      <input type="text" class="txt counter" size="4" disabled="disabled" value="100" id="cont_entidade" />

        <br />-->
        <label for="descricao">Descri&ccedil;&atilde;o / Release sobre a institui&ccedil;&atilde;o*</label>
        <br />
        <textarea name="descricao" cols="60" style="height:150px;" rows="10" class="mceSimple<?=$colaboradorbo->verificaErroCampoNovo("descricao")?>" id="textarea" <?=$colaboradorbo->verificaErroCampoNovo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 1400);"><?=Util::clearText($colaboradorbo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="1400" size="4" disabled="disabled" id="cont_descricao" />

    <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($colaboradorbo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$colaboradorbo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$colaboradorbo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/colaborador.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$colaboradorbo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$colaboradorbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codcolaborador;?>', 6);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=usuario&amp;cod=<?=$codcolaborador;?>&amp;height=210&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>

      <div class="box">
        <fieldset>
        <legend>Endereço</legend>
        <label for="textfield5">Endere&ccedil;o</label>
        <br />
        <input type="text" class="txt" id="textfield5" size="80" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('endereco')))?>" name="endereco" />
        <br />
        <label for="textfield6">Complemento</label>
        <br />
        <input type="text" class="txt" id="textfield6" size="40" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('complemento')))?>" name="complemento" />
        <br />
        <label for="textfield7">Bairro</label>
        <br />
        <input type="text" class="txt" id="textfield7" size="40" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('bairro')))?>" name="bairro" />
        <br />
        <label for="textfield8">CEP</label>
        <br />
        <input type="text" class="txt cep" id="textfield8" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('cep')))?>" name="cep" />
        <em><small>Ex. 54000-000</small></em> <br />
        <label for="select">Pa&iacute;s<span>*</span></label>
        <br />
        <select name="codpais" id="pais" onchange="javascript:exibeEstadoCidade();" <?=$colaboradorbo->verificaErroCampo("pais")?>>
		<?php
		$lista_paises = $colaboradorbo->getListaPaises();
		$pais_selecionado = $colaboradorbo->getValorCampo("codpais");
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
      <select name="codestado" id="estado" onchange="obterCidades(this, <?=(int)$codcidade?>)" <?=$colaboradorbo->verificaErroCampo("estado")?>>
		<?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $colaboradorbo->getListaEstados();
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
      <select name="codcidade" id="selectcidade" <?=$colaboradorbo->verificaErroCampo("codcidade")?>>
	  <option>.</option>
	  </select>

	  <input type="text" class="txt" <?=$colaboradorbo->verificaErroCampo("cidade")?> style="display:none;" name="cidade" id="cidade" size="45" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo("cidade")))?>" maxlength="100" />

        </fieldset>
      </div>

      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Faz parte de alguma rede?</legend>
        <div class="fechada" id="box-rede">
          <ul>
            <li>
              <input type="checkbox" name="rede[]" <?=Util::iif(in_array(1, $rede), 'checked="checked"');?> value="1" id="checkbox" />
              <label for="checkbox">Pontos de Cultura</label>
            </li>
            <li>
              <input type="checkbox" name="rede[]" <?=Util::iif(in_array(2, $rede), 'checked="checked"');?> value="2" id="checkbox2" />
              <label for="checkbox2">Casas Brasil</label>
            </li>
            <li>
              <input type="checkbox" name="rede[]" <?=Util::iif(in_array(3, $rede), 'checked="checked"');?> value="3" id="checkbox3" />
              <label for="checkbox3">Cultura Digital</label>
            </li>
            <li>
              <input type="checkbox" name="rede[]" <?=Util::iif(in_array(4, $rede), 'checked="checked"');?> value="4" id="checkbox4" />
              <label for="checkbox4">Rede Ecofuturo</label>
            </li>
          </ul>
        </div>
        </fieldset>
      </div>

      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Contato</legend>
        <div class="fechada" id="box-contato">
          <label for="textfield9">Telefone</label> <em><small>Ex. (81) 3222-3333</small></em> 
          <br />
          <input type="text" class="txt phone" id="textfield9" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('telefone')))?>" name="telefone" /><br />
		  
          <label for="textfield10">Celular</label> <em><small> Ex. (81) 3222-3333</small></em> 

          <br />
          <input type="text" class="txt phone" id="textfield10" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('celular')))?>" name="celular" />
          <!--<em><small> Ex. (81) 3222-3333</small></em> <br />
          <label for="textfield11">E-mail</label>
          <br />
          <input type="text" class="txt" id="textfield11" size="80" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('email')))?>" name="email" <?=$colaboradorbo->verificaErroCampo("email")?> /> <input id="publicarmail" type="checkbox" name="mostrar_email" value="1" <?=Util::iif($colaboradorbo->getValorCampo('mostrar_email'), 'checked="chedked"');?> /> <label for="publicarmail">Mostrar na sua p&aacute;gina pessoal?</label>
          <br />
          <label for="textfield13">Site</label>
          <br />
          <input type="text" class="txt" id="textfield13" value="http://<?=htmlentities(stripslashes($colaboradorbo->getValorCampo('site')))?>" name="site" size="80" />-->
        </div>
        </fieldset>
      </div>

      <div class="box box-amarelo">
		<fieldset>
        <legend class="seta">Outros contatos online</legend>
        <div class="fechada" id="box-online">
			<div class="campos">
				<label for="textfield18">Endere&ccedil;o</label>
				<em><small>(Ex. seu_endereço@hotmail.com)</small></em><br/>
				<input type="text" class="txt" id="textfield18" size="60" /><br />
			</div>
			<div class="campos">
				<label for="select5">Comunicadores</label><br />
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
				<!--<input type="button" class="bt-adicionar" value="Adicionar" onclick="adicionarContato();" />-->
				<strong><a href="javascript:;" onclick="javascript:adicionarContato();">[+] Adicionar</a></strong>
			</div>
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
            <label for="textfield19">Título</label> <em><small>(Ex. Twitter)</small></em><br />
            <input name="textfield19" type="text" class="txt" id="textfield19" onkeyup="contarCaracteres(this, 'cont_titulo_site_relacionado', 25);" />
            <input type="text" disabled="disabled" class="txt counter" value="25" size="4" id="cont_titulo_site_relacionado" />

            <br />
            <label for="textfield17">Endereço</label> <em><small>(Ex. http://www.twitter.com/iteia)</small></em>
            <br />

            <input type="text" class="txt" id="textfield17" size="80" value="http://" />
            <!--<br />
            <input type="button" class="bt-adicionar" value="Adicionar" onclick="adicionarSiteRelacionado();" />-->
			<strong><a href="javascript:;" onclick="javascript:adicionarSiteRelacionado();">[+] Adicionar</a></strong>
          </div>
          <div class="campos">
            <span>Links Adicionados</span>

            <ul class="lista-remover" id="lista_sitesrelacionados">
            </ul>


          </div>
        </div>
        </fieldset>
      </div>

<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8) || !$codcolaborador): ?>
      <div class="box">
      <fieldset>
      <legend> Página colaborador no iTEIA</legend>
        <label for="textfield12">Final do endereço<span>*</span></label> 
    <em><small>(São permitidos apenas letras (a-z), numeros (0-9) e o sinal (-). Ex. jose-luiz)</small></em>
      <br />
      <input type="text" class="txt" name="finalendereco" id="final_endereco" size="40" value="<?=htmlentities(stripslashes($colaboradorbo->getValorCampo("finalendereco")))?>" maxlength="30" onkeyup="contarCaracteres(this, 'cont_final_endereco', 30); exibeTextoLogin(this, 'texto_final_endereco', 'textfield14'); lowercase('final_endereco');" <?=$colaboradorbo->verificaErroCampo("finalendereco")?> />
      <input type="text" class="txt counter" size="4" disabled="disabled" value="30" id="cont_final_endereco" />
      <div>www.iteia.org.br/<strong id="texto_final_endereco"><?=($colaboradorbo->getValorCampo("finalendereco") ? $colaboradorbo->getValorCampo("finalendereco") : 'nome-do-colaborador');?></strong></div>
      </fieldset>
      </div>
<?php
endif;

$mostrar_colaborador = true;
include('includes/cadastro_colaborador_integrantes_busca.php');

?>

      <div id="botoes" class="box">
        <a href="cadastro.php" class="bt bt-cancelar">Cancelar</a>
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
	contarCaracteres(document.getElementById('nomeinstituicao'), "cont_nome_instituicao", 100);
	contarCaracteres(document.getElementById('entidade'), "cont_entidade", 100);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 1400);
<?php if (!$edicaodados): ?>
	contarCaracteres(document.getElementById("final_endereco"), "cont_final_endereco", 30);
<?php
	endif;
?>
	</script>
<?php
	if (count($_SESSION['sessao_autores_integrantes'])):
		echo '<script language="javascript" type="text/javascript">carregarIntegrantes();</script>';
	endif;
	if (count($colaboradorbo->getValorCampo("rede"))):
		echo '<script language="javascript" type="text/javascript">$(\'#box-rede\').show();</script>';
	endif;
	if ($colaboradorbo->getValorCampo("contato")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-online\').show();</script>';
	endif;
	if ($colaboradorbo->getValorCampo("sitesrelacionados")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-sitesrelacionados\').show();</script>';
	endif;
	if ($colaboradorbo->getValorCampo("telefone") || $colaboradorbo->getValorCampo("celular") || $colaboradorbo->getValorCampo("email") || $colaboradorbo->getValorCampo("site")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-contato\').show();</script>';
	endif;
endif;
?>

<?php include('includes/rodape.php'); ?>