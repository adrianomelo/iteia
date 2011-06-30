<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codautor = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];
$proprio = (int)Util::iif($_GET["proprio"], $_GET["proprio"], $_POST["proprio"]);

if ($proprio) {
	$codautor = $_SESSION['logado_cod'];
	$edicaodados = 1;
}

include_once("classes/bo/AutorEdicaoBO.php");
$autorbo = new AutorEdicaoBO;
$exibir_form = true;

if (!$editar)
	unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados']);

if ($editar) {
	try {
		$cod_usuario = $autorbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: cadastro_autor_publicado.php?cod=".$cod_usuario.Util::iif($proprio, '&proprio=1&visual=1'));
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$item_menu = "cadastro";
$item_submenu = Util::iif($proprio, 'meu_cadastro', 'autor');
include('includes/topo.php');

if ($codautor && !$editar) {
	$autorbo->setDadosCamposEdicao($codautor);
	$codestado = $autorbo->getValorCampo("codestado");
	$codcidade = $autorbo->getValorCampo("codcidade");
} else {
	$codestado = 17;
	$codcidade = 6330;
}

$codautor = (int)$autorbo->getValorCampo("codautor");
?>
    <h2>Usu&aacute;rios</h2>
    
<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/autor.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>

<!--
<script type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="jscripts/tiny_mce/editor-settings.js"></script>
-->

<?php if ($erro_mensagens || $autorbo->verificaErroCampo("nomeartistico") || $autorbo->verificaErroCampo("nomecompleto") || $autorbo->verificaErroCampo("biografia")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
    <form action="cadastro_autor.php" method="post" enctype="multipart/form-data" name="add-evento" id="add-evento">
      <h3 class="titulo"><?=Util::iif($proprio, 'Meu cadastro', 'Cadastro de autores');?></h3>
      <div class="box">
        <fieldset>

        <input type="hidden" name="editar" value="1" />
	  	<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
	  	<input type="hidden" name="codautor" value="<?=$codautor?>" />
	  	<input type="hidden" name="proprio" value="<?=$proprio?>" />
	  	<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$autorbo->getValorCampo('imgtemp')?>" />

		<p>Os campos marcados o asterísco (*) são obrigatórios.</p>
        <fieldset>
		<legend>Dados pessoais</legend>
		
		<label for="label2">Nome completo<span>*</span></label> <em><small>(mesmo do CPF)</small></em>
        <br />
        <input type="text" class="txt" onkeyup="contarCaracteres(this, 'cont_nome_completo', 100);" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('nomecompleto')))?>" <?=$autorbo->verificaErroCampo("nomecompleto")?> id="nome_completo" name="nomecompleto" maxlength="100" size="80" />
		<input type="text" class="txt counter" size="4" id="cont_nome_completo" disabled="disabled" value="100" />
        <br />
		
		<label for="textfield">Nome art&iacute;stico</label>
        <br />
		<input type="text" class="txt" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('nomeartistico')))?>" name="nomeartistico" id="nome" onkeyup="contarCaracteres(this, 'cont_nome', 100);" maxlength="100" size="80" <?=$autorbo->verificaErroCampo("nomeartistico")?> />
        <input type="text" disabled="disabled" id="cont_nome" class="txt counter" value="100" size="4" /><br />

		<label for="textfield11">E-mail<span>*</span></label> <em><small>(Não será exibido)</small></em>
<br />
        <input type="text" class="txt" id="textfield11" size="80" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('email')))?>" name="email" <?=$autorbo->verificaErroCampo("email")?> /><!--<input id="publicarmail" type="checkbox" name="mostrar_email" value="1" <?=Util::iif($autorbo->getValorCampo('mostrar_email'), 'checked="chedked"');?> /> <label for="publicarmail">Mostrar na sua p&aacute;gina pessoal?</label>--><br />
		
		<label for="textfield20">CPF</label>  <em><small>(Não será exibido)</small></em><br />
        <input type="text" name="cpf" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('cpf')))?>" class="txt cpf" id="textfield20" <?=$autorbo->verificaErroCampo("cpf")?> />
        <br />

        <label for="textfield3">Data de nascimento</label> <em><small>(Ex. 02/10/2005)</small></em>
        <br />
        <input type="text" class="txt date" id="textfield3" value="<?=$autorbo->getValorCampo('datanascimento')?>" name="datanascimento" <?=$autorbo->verificaErroCampo("datanascimento")?> /><br />
<br />
</fieldset>
<?php if (!$proprio): ?>

		<input type="checkbox" id="falecido" name="falecido" value="1" <?=Util::iif($autorbo->getValorCampo("falecido"), "checked=\"checked\"")?> />
        
		<label for="falecido">J&aacute; falecido?</label>
        <br />
        <div class="display-none" id="box-falecido">
        <br />
        <label for="textfield4">Data de falecimento</label>
        <br />

        <input type="text" class="txt date" value="<?=$autorbo->getValorCampo('datafalecimento')?>" name="datafalecimento" id="textfield4 <?=$autorbo->verificaErroCampo("datafalecimento")?>" />
        <em><small>Ex. dd/mm/aaaa</small></em><br />
        </div>

  <br />

<?php endif; ?>
        
        <label for="textarea">Biografia:</label> <em><small>(Fale um pouco sobre você)</small></em>
        <br />
        <textarea name="biografia" cols="60" rows="10" class="mceSimple" id="textarea" <?=$autorbo->verificaErroCampo("biografia")?> onkeyup="contarCaracteres(this, 'cont_descricao', 1400);"><?=Util::clearText($autorbo->getValorCampo("biografia"));?></textarea>
        <input type="text" class="txt counter" value="1400" size="4" disabled="disabled" id="cont_descricao" />

    <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($autorbo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$autorbo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$autorbo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/autor.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$autorbo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$autorbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codautor;?>', 7);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=usuario&amp;cod=<?=$codautor;?>&amp;height=210&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

        </fieldset>
      </div>
      <div class="box">
        <fieldset>
        <legend>Endereço</legend>
        <label for="textfield5">Endere&ccedil;o</label>
        <br />
        <input type="text" class="txt" id="textfield5" size="80" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('endereco')))?>" name="endereco" />
        <br />
        <label for="textfield6">Complemento</label>
        <br />
        <input type="text" class="txt" id="textfield6" size="40" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('complemento')))?>" name="complemento" />
        <br />
        <label for="textfield7">Bairro</label>
        <br />
        <input type="text" class="txt" id="textfield7" size="40" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('bairro')))?>" name="bairro" />
        <br />
        <label for="textfield8">CEP</label> <em><small>(Ex. 50000-000)</small></em>
        <br />
        <input type="text" class="txt cep" id="textfield8" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('cep')))?>" name="cep" />
        <!--<em><small>Ex. 54000-000</small></em>--> <br />
        <label for="select">Pa&iacute;s<span>*</span></label>
        <br />
        <select name="codpais" id="pais" onchange="javascript:exibeEstadoCidade();" <?=$autorbo->verificaErroCampo("pais")?>>
		<?php
		$lista_paises = $autorbo->getListaPaises();
		$pais_selecionado = $autorbo->getValorCampo("codpais");
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
      <select name="codestado" id="estado" onchange="obterCidades(this, <?=(int)$codcidade?>)" <?=$autorbo->verificaErroCampo("estado")?>>
		<?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $autorbo->getListaEstados();
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
      <select name="codcidade" id="selectcidade" <?=$autorbo->verificaErroCampo("codcidade")?>>
	  <option>.</option>
	  </select>

	  <input type="text" class="txt" <?=$autorbo->verificaErroCampo("cidade")?> style="display:none;" name="cidade" id="cidade" size="45" value="<?=htmlentities(stripslashes($autorbo->getValorCampo("cidade")))?>" maxlength="100" />

        </fieldset>
      </div>

      <div class="box box-amarelo">
        <fieldset>
        <legend class="seta">Contato</legend>
        <div class="fechada" id="box-contato">
          <label for="textfield9">Telefone</label> <em><small>Ex. (81) 3222-3333</small></em><br />
          <input type="text" class="txt phone" id="textfield9" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('telefone')))?>" name="telefone" /> <br />
          <label for="textfield10">Celular</label> <em><small> Ex. (81) 3222-3333</small></em>
          <br />
          <input type="text" class="txt phone" id="textfield10" value="<?=htmlentities(stripslashes($autorbo->getValorCampo('celular')))?>" name="celular" /><!--<br />
          <label for="textfield13">Site</label>
          <br />
          <input type="text" class="txt" id="textfield13" value="http://<?=htmlentities(stripslashes($autorbo->getValorCampo('site')))?>" name="site" size="80" />-->
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

<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8) || !$codautor): ?>
      <div class="box">
      <fieldset>
      <legend>Sua P&aacute;gina no iTEIA</legend>
        <label for="textfield12">Final do endereço<span>*</span> </label>
		<em><small>(S&atilde;o permitidos apenas letras (a-z), numeros (0-9) e o sinal (-). Ex. jose-luiz)</small></em><br />
      <input type="text" class="txt" name="finalendereco" id="final_endereco" size="40" value="<?=htmlentities(stripslashes($autorbo->getValorCampo("finalendereco")))?>" maxlength="30" onkeyup="contarCaracteres(this, 'cont_final_endereco', 30); exibeTextoLogin(this, 'texto_final_endereco', 'textfield14'); lowercase('final_endereco');" <?=$autorbo->verificaErroCampo("finalendereco")?> />
      <input type="text" class="txt counter" size="4" disabled="disabled" value="30" id="cont_final_endereco" />
      <div>www.iteia.org.br/<strong id="texto_final_endereco">nome-do-autor</strong></div>
      </fieldset>
      </div>
<?php endif; ?>
<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
<div class="box">
	<fieldset>
		<legend>N&iacute;vel de usu&aacute;rio</legend>
		<?php /*if (!$codautor):*/ ?>
		<select name="tipo_autor" id="tipo_autor" <?=$autorbo->verificaErroCampo("tipo_autor")?> onchange="javascript:mudaCampoLogin();">
			<option value="0">Selecione</option>
			<option value="1" <?=Util::iif($autorbo->getValorCampo("tipo_autor") == 1, 'selected="selected"');?>>Wiki</option>
			<option value="2" <?=Util::iif($autorbo->getValorCampo("tipo_autor") > 1  && $autorbo->getValorCampo("tipo_autor") < 7, 'selected="selected"');?>>Autor</option>
			<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
				<option value="<?=$_SESSION['logado_dados']['nivel'];?>" <?=Util::iif($autorbo->getValorCampo("tipo_autor") > 6, 'selected="selected"');?>>Administrador</option>
			<?php endif; ?>
		</select>
		<?php /*else: ?>
		<?=Util::iif($autorbo->getValorCampo("tipo_autor") == 1, 'Wiki');?>
		<?=Util::iif($autorbo->getValorCampo("tipo_autor") == 2, 'Autor');?>
		<?php endif;*/?>
	</fieldset>
</div>
<?php else: ?>
<input name="tipo_autor" type="hidden" value="<?=$_SESSION['logado_dados']['nivel'];?>" />
<?php endif; ?>
<?php if (!$codautor): ?>
      <div class="box" id="box_login" <?=Util::iif($codautor, 'style="display: none;"');?>>
        <fieldset>
        <legend>Dados de acesso ao sistema</legend>
        <?php if (!$edicaodados): ?>
        <label for="textfield14">Login</label>  
		<em><small>(O nome de acesso ao sistema &eacute; o mesmo do final do endere&ccedil;o escolhido)</small></em>
        <br />
        <input type="text" disabled="disabled" class="txt login" id="textfield14" value="<?=htmlentities(stripslashes($autorbo->getValorCampo("finalendereco")))?>" <?=$autorbo->verificaErroCampo("login")?> />
        <br />
        <?php endif; ?>
        <label for="textfield15">Senha<span>*</span></label> <em><small>(No m&iacute;nimo 6 caracteres)</small></em>
        <br />
        <input type="password" class="txt senha" id="textfield15" value="" name="senha" maxlength="20" <?=$autorbo->verificaErroCampo("senha")?> />
        <br />
        <label for="textfield16">Repetir a senha<span>*</span></label>
        <br />
        <input type="password" class="txt senha" id="textfield16" name="senha2" maxlength="20" />
        </fieldset>
      </div>
<?php
else:
?>
<div class="box">
        <fieldset>
        <legend>Dados de acesso ao sistema</legend>
       <p> Login:
        <strong><br /><?=htmlentities(stripslashes($autorbo->getValorCampo("finalendereco")))?></strong></p>
       <p><a href="cadastro_alterar_senha.php?cod=<?=$autorbo->getValorCampo('codautor');?>&amp;height=250&amp;width=305" class="thickbox" title="Alterar senha">Alterar a senha do cadastro</a></p>

        </fieldset>
      </div>
<?php
endif;
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
	contarCaracteres(document.getElementById('nome'), "cont_nome", 100);
	contarCaracteres(document.getElementById('nome_completo'), "cont_nome_completo", 100);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 1400);
<?php if (!$edicaodados): ?>
	contarCaracteres(document.getElementById("final_endereco"), "cont_final_endereco", 30);
	exibeTexto(document.getElementById("final_endereco"), 'texto_final_endereco');
<?php
	endif;
?>
	</script>
<?php
	if ($autorbo->getValorCampo("codpais") != 2):
		echo '<script language="javascript" type="text/javascript">exibeEstadoCidade();</script>';
	endif;
	if ($autorbo->getValorCampo("tipo_autor") == 2):
		echo '<script language="javascript" type="text/javascript">$(\'#box_login\').show();</script>';
	endif;
	if ($autorbo->getValorCampo("falecido")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-falecido\').show(); $(\'#falecido\').attr(\'checked\', 1);</script>';
	endif;
	if ($autorbo->getValorCampo("contato")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-online\').show();</script>';
	endif;
	if ($autorbo->getValorCampo("sitesrelacionados")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-sitesrelacionados\').show();</script>';
	endif;
	if ($autorbo->getValorCampo("telefone") || $autorbo->getValorCampo("celular") || $autorbo->getValorCampo("email") || $autorbo->getValorCampo("site")):
		echo '<script language="javascript" type="text/javascript">$(\'#box-contato\').show();</script>';
	endif;
endif;
?>

<?php include('includes/rodape.php'); ?>
