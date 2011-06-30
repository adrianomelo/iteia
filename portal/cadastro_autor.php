<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/CadastroCulturalBO.php');

$cadbo = new CadastroCulturalBO;
$exibir_form = true;
$enviar =  (int)$_POST['enviar'];

if ($enviar) {
	try {
		$cadbo->cadastrar($_POST);
		$exibir_form = false;
		$msgtrue = true;
		Header('Location: /cadastrado?t=1');
		die;
	} catch (exception $e) {
		$msgerro = $e->getMessage();
	}
}

if (!$enviar) {
	//$cadbo->setValorCampo("codestado", 17);
	//$cadbo->setValorCampo("codcidade", 6330);
}

$topo_class = 'cat-suporte iteia';
$titulopagina = 'Cadastro de autor';
$js_busca = true;
include ('includes/topo.php');
?>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/edicao.js"></script>
<script type="text/javascript">
<!--
jQuery(document).ready(function() {
	$('.aviso a').click(function() {
		var target = $(this).attr("href");
		$(target).focus();
		return false;
	});
});
-->
</script>

    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <a href="/autores.php">Autores</a> <span class="marcador">&raquo;</span> <span class="atual">Cadastro de autor</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Cadastro de autor</h2>
        <p class="caption">Preencha o formul�rio abaixo para enviar os seus dados ao InterCidadania. Seu cadastro ser� analisado e nossa equipe entrar� em contato para efetivar o seu acesso.</p>
<p>Todos os campos s�o obrigat�rios</p>
        <form action="/cadastro_autor" method="post" id="cadastro">
			<input type="hidden" name="enviar" value="1" />
<?php
if ($msgerro) {
?>
	<div class="aviso erro">Ocorreu um erro no envio dos seus dados. Por favor, verifique os campos marcados em vermelho.</div>
	<?php
	if ($cadbo->getErros()) {
	?>
		<div class="aviso alerta"> Por favor, preencha  os campos obrigat�rios.
            <ul>
				<?=$cadbo->getErros();?>
			</ul>
		</div>
<?php
	}
}
?>
        <p class="caption no-padding-b"><strong>Dados do usu�rio</strong></p>

        <label for="name">Nome:</label>
		<em><small>(O mesmo do documento de identifica��o)</small></em><br />
        <input type="text" class="txt<?=$cadbo->verificaErroCampo('nome')?>" name="nome" value="<?=$cadbo->getValorCampo('nome')?>" id="seu-nome" /><br/>

		<label for="artistico">Nome art�stico:</label>
		<em><small>(Opcional)</small></em><br />
		<input type="text" class="txt" id="artistico" name="nomeartistico" value="<?=$cadbo->getValorCampo('nomeartistico')?>" maxlength="100" onkeyup="contarCaracteres(this, 'cont_nome_artistico', 100);" />
		<input name="text3" type="text" id="cont_nome_artistico" disabled="disabled" class="txt contador" value="<?=100 - strlen($cadbo->getValorCampo('nomeartistico'))?>" size="6" /><br />

		<label for="historico">Biografia:</label>
		<em><small>(Fale um pouco sobre voc�)</small></em> <br />
		<textarea id="historico" cols="78" rows="7" class="<?=$cadbo->verificaErroCampo('historico')?>" name="historico" onkeyup="contarCaracteres(this, 'cont_historico', 1400);"><?=$cadbo->getValorCampo('historico')?></textarea>
		<input name="text32" type="text" id="cont_historico" disabled="disabled" class="txt contador" value="<?=1400 - strlen($cadbo->getValorCampo('historico'))?>" size="6" /><br />

        <label for="nascimento">Data de nascimento:</label>
        <em><small>(Opcional)</small></em><br />
        <input type="text" class="txt small date" name="datanascimento" value="<?=$cadbo->getValorCampo('datanascimento')?>" id="birthdate"/><br/>

		<fieldset id="doc">
			<legend>Documento de identifica��o: <em><small>(Opcional)</small></em></legend>
			<div id="d_tipo">
            <label for="tipo_doc">Tipo:</label>
            <br />
            <select id="tipo_doc" name="codtipo" class="slc mini">
				<option></option>
				<option value="1" <?=($cadbo->getValorCampo('codtipo') == 1 ? 'selected="selected"' : '');?>>Certid�o de nascimento</option>
				<option value="2" <?=($cadbo->getValorCampo('codtipo') == 2 ? 'selected="selected"' : '');?>>CNH</option>
				<option value="3" <?=($cadbo->getValorCampo('codtipo') == 3 ? 'selected="selected"' : '');?>>CPF</option>
				<option value="4" <?=($cadbo->getValorCampo('codtipo') == 4 ? 'selected="selected"' : '');?>>Passaporte</option>
				<option value="5" <?=($cadbo->getValorCampo('codtipo') == 5 ? 'selected="selected"' : '');?>>RG</option>
            </select>
          </div>
          <div id="d_numero">
            <label for="numero_doc">N�mero:</label>
            <br />
            <input type="text" class="txt small" name="numero" value="<?=$cadbo->getValorCampo('numero')?>" id="numero_doc"/>
          </div>
          <div id="d_orgao">
            <label for="orgao_doc">Org�o expedidor:</label><br />
            <input type="text" class="txt small" name="orgao" value="<?=$cadbo->getValorCampo('orgao')?>" id="orgao_doc"/>
        </div>
        </fieldset>

		<label for="email">E-mail:</label><br />
		<em><small>(N�o ser� publicado)</small></em><br />
		<input type="text" class="txt<?=$cadbo->verificaErroCampo('email')?>" id="email" name="email" value="<?=$cadbo->getValorCampo('email')?>" /><br/>

		<label for="endereco">Endere�o:</label>
		<em><small>(opcional)</small></em> <br />
		<input type="text" class="txt<?=$cadbo->verificaErroCampo('endereco')?>" id="endereco" name="endereco" value="<?=$cadbo->getValorCampo('endereco')?>" /><br />

		<label for="complemento">Complemento: </label>
		<em><small>(opcional)</small></em> <br />
		<input name="complemento" type="text" class="txt" id="complemento" value="<?=$cadbo->getValorCampo('complemento')?>" /><br />

		<label for="bairro">Bairro:</label>
		<em><small>(opcional)</small></em> <br />
		<input type="text" name="bairro" class="txt" id="bairro" value="<?=$cadbo->getValorCampo('bairro')?>" /><br />

        <label for="pais">Pa�s:</label><br />
        <select name="codpais" id="pais" class="slc" onchange="javascript:exibeEstadoCidade();" <?=$cadbo->verificaErroCampo("pais")?>>
		<?php
		$lista_paises = $cadbo->getListaPaises();
		$pais_selecionado = $cadbo->getValorCampo("codpais");
		if (!$pais_selecionado)
			$pais_selecionado = 2;
		foreach ($lista_paises as $pais) {
			echo "<option value=\"".$pais["cod_pais"]."\"";
			if ($pais["cod_pais"] == $pais_selecionado)
				echo " selected=\"selected\"";
			echo ">".$pais["pais"]."</option>\n";
		}
		?>
		</select><br/>

		<div style="display: inline;" id="mostraestado">
			<label for="estado">Estado:</label><br />
			<select name="codestado" id="estado" onchange="obterCidades(this, <?=(int)$cadbo->getValorCampo("codcidade")?>)" class="slc<?=$cadbo->verificaErroCampo("estado")?>">
		<?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $cadbo->getListaEstados();
		foreach ($lista_estados as $estado) {
			echo "<option value=\"".$estado["cod_estado"]."\"";
			if ($estado["cod_estado"] == $cadbo->getValorCampo("codestado"))
				echo " selected=\"selected\"";
			echo ">".$estado["sigla"]."</option>\n";
		}
		?>
			</select><br/>
		</div>

        <label for="selectcidade">Cidade:</label><br />
        <select name="codcidade" id="selectcidade" class="slc<?=$cadbo->verificaErroCampo("cidade")?>">
			<option>.</option>
		</select>
		<input type="text" class="txt<?=$cadbo->verificaErroCampo("cidade")?>" style="display:none;" name="cidade" id="cidade" size="45" value="<?=htmlentities(stripslashes($cadbo->getValorCampo("cidade")))?>" maxlength="100" /><br />

		<label for="fone-autor">Telefone:</label>
        <em><small>(Opcional) (Ex. (81) 3222-3333)</small></em> <br />
		<input type="text" class="txt small fone" id="telefone" name="fone" value="<?=$cadbo->getValorCampo('fone')?>" />

		<p class="caption no-padding-b"><strong>Dados de acesso ao sistema</strong></p>

		<label for="final_endereco">Login:</label>
        <em><small>(Nome de acesso ao sistema. S�o permitidos apenas letras (a-z), n�meros (0-9) e o sinal (-). Ex. jose-luiz)</small></em><br />
        <input type="text" class="txt medium<?=$cadbo->verificaErroCampo('finalendereco')?>" maxlength="30" name="finalendereco" id="final_endereco" value="<?=$cadbo->getValorCampo('finalendereco')?>" onkeyup="contarCaracteres(this, 'cont_final_endereco', 30); exibeTexto(this, 'texto_final_endereco'); lowercase('final_endereco');" /><input type="text" class="txt contador" disabled="disabled" id="cont_final_endereco" value="<?=30 - strlen($cadbo->getValorCampo('finalendereco'))?>" size="4" />
        <p>O endere�o de sua p�gina ser�: http://www.iteia.org.br/<strong id="texto_final_endereco"></strong></p>

        <label for="pass">Senha: </label>
        <em><small>(M�nimo 6 caracteres)</small></em><br />
        <input type="password" name="senha" class="txt small<?=$cadbo->verificaErroCampo('senha')?>" id="pass"/><br/>
        <label for="rePass">Repetir senha:</label><br />
        <input type="password" name="senha2" class="txt small<?=$cadbo->verificaErroCampo('senha')?>" id="rePass"/><br/>
        <p><strong>Termos de uso</strong></p>
		<p class="caption">Leia os termos e indique que voc� os aceitou para continuar o processo de cadastro.</p>

          <div id="termos">
            <p> Bem vindo ao iTEIA, rede colaborativa de cultura, arte e informa��o, idealizado pelo Instituto InterCidadania OSCIP, inscrita no CNPJ/MF sob o n�mero 07.553.412/0001-06, com sede e foro jur�dico na Av. Agamenon Magalh�es, 2656, sala 1006, Espinheiro, Recife/PE, CEP 52020-000. </p>
            <p> O iTEIA � um projeto sem fins lucrativos, gerenciado de forma colaborativa, que promove o software livre, a diversidade cultural e visa desenvolver formas democr�ticas de express�o e acesso livre a conte�dos art�sticos, respeitando os direitos do autor. O projeto envolve, de forma colaborativa, v�deos, m�sicas, textos, fotos, noticias, dados de produtores e autores, al�m de informa��es e indicadores culturais.</p>
            <p>Por acessar ou participar do portal iTEIA, dispon�vel nos endere�os eletr�nicos www.iteia.org.br e www.iteia.com.br, seja apenas como visitante ou como usu�rio (postando novos conte�dos), voc� afirma que leu, entendeu e aceitou estar ligado a este Termo de Uso (ou Acordo).</p>
            <p>Informamos tamb�m que durante a realiza��o do cadastro o usu�rio pode optar em preencher ou n�o alguns campos opcionais (ex: ID, CPF, RG...), por�m estes s�o importantes, pois garantem a veracidade das informa��es postadas por voc�! Como sabemos, a internet � um ambiente livre muitos n�o sabem us�-la corretamente. Como o iTEIA trabalha com um enorme acervo cultural, e respeita a autoria art�stica, esses dados servir�o como uma valida��o sua em nosso sistema alem de inibir a cria��o de perfis falsos. Mas, n�o se preocupe! N�o divulgaremos seus dados para ningu�m!</p>

<p>Isso posto, reservamos o direito de modificar, adicionar ou deletar por��es deste Termo a qualquer momento. Seu uso cont�nuo do Servi�o ou do Portal significa a aceita��o da atualiza��o dele. Caso voc� deixe de concordar ou n�o queira obedecer a essas informa��es aqui expostas, deve apenas deixar de usar e de acessar a p�gina. O INTERCIDADANIA se compromete a notificar atrav�s de correio eletr�nico os usu�rios cadastrados</p>
            <p> Sendo assim, as partes envolvidas resolvem firmar o presente Contrato de Hospedagem Eletr�nica de Dados e Conte�do, tudo de acordo com as cl�usulas e estipula��es contidas nos itens abaixo, as quais, mutuamente, outorgam, pactuam e aceitam, obrigando-se a cumpri-las por si e por seus herdeiros e sucessores a qualquer t�tulo:</p>
            <h3> DAS DECLARA��ES PRELIMINARES</h3>
            <p>A.1. Para melhor configura��o do presente neg�cio jur�dico, o INTERCIDADANIA declara que:</p>
            <p> (i) � uma institui��o sem fins lucrativos, regularmente constitu�da sob as leis da Rep�blica Federativa do Brasil; e</p>

            <p> (ii) por meio do portal iTEIA, t�m como objetivo estimular a digitaliza��o de conte�dos e a forma��o de acervos cultuais a serem disponibilizados pela rede mundial de computadores Internet, contribuindo para a dissemina��o da produ��o cultural estadual, regional e nacional, bem como promover a difus�o e o interc�mbio desses conte�dos de cultura e a integra��o dos coletivos de cultura e de empreendimentos e produ��es culturais independentes do Brasil.</p>
            <p> A.2. Para melhor configura��o do presente neg�cio jur�dico, o USU�RIO declara que:</p>
            <p> (i) tem consci�ncia de que pode participar do portal iTEIA em quatro n�veis: VISITANTE, AUTOR, GRUPO OU COLABORADOR. O primeiro deles (VISITANTE) apenas visualiza os conte�dos (texto, imagem, �udio, v�deo) postados por AUTORES, GRUPOS OU COLABORADORES e comenta sobre os arquivos, quando permitido. Os outros tr�s n�veis (AUTOR, GRUPO E COLABORADOR) recebem um nome de usu�rio (login) e senha exclusivos, que possibilita o acesso e a identifica��o do mesmo no sistema.</p>
            <p> (ii) um usu�rio do n�vel AUTOR pode criar ou fazer parte de um GRUPO quando quiser, tendo os mesmos direitos e deveres postos neste Termo de Uso - apenas mudando automaticamente para o n�vel GRUPO. Da mesma maneira, ele pode deixar de fazer parte de um GRUPO quando quiser, voltando ao n�vel AUTOR;</p>
            <p> (iii) um usu�rio do n�vel AUTOR � promovido ao n�vel de COLABORADOR quando convidado por uma entidade, institui��o ou coletivo, para tornar-se representante do mesmo no sistema. Por sua vez, essa entidade, institui��o ou coletivo precisa ter sido convidado pelo INTERCIDADANIA para ajudar a administrar o portal iTEIA;</p>

            <p> (iv) os usu�rios com n�vel COLABORADOR ajudam ao INTERCIDADANIA a controlar todos os conte�dos postados no portal iTEIA. Eles aprovam ou reprovam os conte�dos postados por usu�rios dos n�veis AUTORES E GRUPOS, referendando a idoneidade e as informa��es, ou n�o, dos mesmos.</p>
            <p> (v) os usu�rios dos n�veis AUTOR E GRUPO somente podem postar conte�dos pr�prios ou que tenham a participa��o deles; j� os usu�rios do n�vel COLABORADOR podem postar conte�dos pr�prios ou de terceiros, beneficiando tamb�m pessoas que n�o tenham acesso a computadores e � Internet;</p>
            <p> (vi) o conte�do disponibilizado pelos usu�rios no iTEIA n�o sofre qualquer edi��o pelo INTERCIDADANIA, n�o infringe qualquer lei brasileira ou extrangeira e n�o ofende direitos de quaisquer terceiros, inclusive, mas n�o se limitado a, direitos de propriedade intelectual;</p>
            <p> (vii) o INTERCIDADANIA reserva o direito de retirar do ar ou suspender, a qualquer momento, conte�dos que tenham informa��es ou imagens impr�prias ou que, de alguma maneira, ofenda os direitos, a honra e a integridade de outros usu�rios, visitantes, empresas ou estados. Al�m disso, � proibida a publica��o de conte�dos impr�prios para menores de 18 anos;</p>
            <h3> CL�USULA PRIMEIRA - DO OBJETO</h3>

            <p> 1.1. O presente Termo de Uso ou Acordo tem por objeto estabelecer as cl�usulas e condi��es a regerem a hospedagem eletr�nica de dados e conte�do a ser provida gratuitamente pelo INTERCIDADANIA e que ser� usufru�da pelos AUTORES, GRUPOS E COLABORADORES do sistema, bem como a disponibiliza��o gratuita, no portal iTEIA, do conte�do eletronicamente enviado pelos AUTORES E COLABORADORES para hospedagem.</p>
            <h3> CL�USULA SEGUNDA - DO LICENCIAMENTO DO CONTE�DO</h3>
            <p> 2.1. O portal iTEIA apresenta diversas formas de licenciamento de obras autorais para o conte�do a ser disponibilizado, que dever�o ser livremente escolhidas pelos AUTORES, GRUPOS E COLABORADORES no momento de disponibiliza��o de cada conte�do. Podendo, contudo, voltar atr�s e rever a decis�o de disponibilizar uma obra para download (copyleft/dom�nio p�blico) ou apenas para consulta (copyright).</p>
            <p> 2.2. Para qualquer conte�do disponibilizado no portal iTEIA, os AUTORES, GRUPOS E COLABORADORES se obrigam a fazer as devidas cita��es aos respectivos autores (pessoas f�sicas) das obras autorais (textos, imagens, �udios, v�deos, etc) disponibilizadas, em reconhecimento e respeito aos seus direitos morais, nos termos da legisla��o de direitos autorais aplic�vel. Vale salientar que os autores do sistema somente devem publicar conte�dos feitos/produzidos/criados por eles ou por terceiros que autorizem essa publica��o na internet, sendo de responsabilidade dos mesmos poss�veis problemas de direitos autorais a serem reclamados no futuro.</p>
            <p> 2.3. Os AUTORES E COLABORADORES declaram que todo e qualquer conte�do enviado para hospedagem e disponibiliza��o no portal iTEIA, notadamente obras protegidas por direitos autorais, se encontra devidamente autorizadas por seu(s) leg�timo(s) titulare(s) para a forma de licenciamento optada por eles no portal iTEIA, devendo, em caso de demanda judicial ou extrajudicial apresentada por terceiros contra o INTERCIDADANIA, reponsabilizar-se e responder diretamente, excluindo qualquer responsabilidade ao INTERCIDADANIA, nos termos deste Acordo.</p>

            <h3> CL�USULA TERCEIRA - DA RESPONSABILIDADE PELO CONTE�DO</h3>
            <p> 3.1. O COLABORADOR e o AUTOR s�o os �nicos respons�veis pelo conte�do, seus ou de terceiros, que disponibilizarem no portal iTEIA, obrigando-se a requererem substitui��o processual e a defenderem os interesses do INTERCIDADANIA caso isso venha a ser demandado judicial ou extrajudicialmente.</p>
            <p> 3.2. O COLABORADOR e o AUTOR se obrigam a indenizar o INTERCIDADANIA por quaisquer �nus que incorrerem em face de demanda judicial ou extrajudicial por conta do conte�do disponibilizado por eles, incluindo, mas n�o se limitando a, indeniza��o por decis�o judicial, despesas administrativas, custas processuais e honor�rios advocat�cios.</p>
            <h3> CL�USULA QUARTA - DA INEXIST�NCIA DE NOVA��O OU REN�NCIA</h3>
            <p> 4.1. O eventual n�o exerc�cio de direito ou toler�ncia por qualquer das partes n�o implicar� em ren�ncia ou nova��o de direito, podendo ser exercido a qualquer tempo.</p>

            <h3> CL�USULA QUINTA - DAS DISPOSI��ES GERAIS</h3>
            <p> 5.1. O COLABORADOR e o AUTOR se obrigam a manter seus dados cadastrais devidamente atualizados perante o INTERCIDADANIA, entendendo que seu correio eletr�nico ser� o principal meio de contato dos administradores do portal iTEIA, inclusive para fins de notifica��o previstos neste contrato.</p>
            <p> 5.2. Em caso de suspeita de conte�do indevido, seja por suspeita de infra��o a direitos de terceiros, seja por qualquer outro motivo, a exclusivo crit�rio do INTERCIDADANIA, esses poder�o indisponibilizar o conte�do do AUTOR ou do COLABORADOR temporariamente do portal iTEIA.</p>
            <p>5.3. Durante a visita do portal iTEIA, os internautas poder�o usar um servi�o, fazer download ou comprar bens fornecidos por outras pessoas ou empresas. A utiliza��o desses servi�os, conte�dos ou bens por parte dos visitantes ou usu�rios do sistema estar�o sujeitos aos termos entre os internautas e as empresa ou as pessoas em quest�o, excluindo o INTERCIDADANIA de qualquer v�nculo com poss�veis transa��es financeiras, ou de outro tipo, iniciadas com ajuda do portal.</p>
            <h3> CL�USULA SEXTA - DO FORO PARA RESOLU��O DE CONFLITOS</h3>

            <p> 6.1. Todos os itens deste Termo de Uso est�o regidos pelas leis vigentes na Rep�blica Federativa do Brasil. Para todos os assuntos referentes � interpreta��o e ao cumprimento deste Acordo, as partes elegem o foro da comarca do Recife, estado de Pernambuco, para dirimir quaisquer conflitos resultantes direta ou indiretamente, excluindo-se qualquer outro, por mais privilegiado que seja. </p>
            <p>Recife (PE), 15 de novembro de 2007. </p>
          </div>

<br />
<div class="caption">
  <input type="checkbox" value="1" id="acordo" name="deacordo"<?=($cadbo->getValorCampo('deacordo'))?' checked="checked"':''?> />
  <label for="checkbox">li e aceito as condi��es do Termo de uso do portal iTEIA</label>
</div>
          <input class="btn" type="image" src="/img/botoes/bt_enviar.gif" />
        </form>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<script language="javascript" type="text/javascript">
obterCidades(document.getElementById("estado"), <?=(int)$cadbo->getValorCampo('codcidade')?>);
</script>
<?php
include ('includes/rodape.php');
