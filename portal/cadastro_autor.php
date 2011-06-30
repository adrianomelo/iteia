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

    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/autores.php">Autores</a> <span class="marcador">&raquo;</span> <span class="atual">Cadastro de autor</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Cadastro de autor</h2>
        <p class="caption">Preencha o formulário abaixo para enviar os seus dados ao InterCidadania. Seu cadastro será analisado e nossa equipe entrará em contato para efetivar o seu acesso.</p>
<p>Todos os campos são obrigatórios</p>
        <form action="/cadastro_autor" method="post" id="cadastro">
			<input type="hidden" name="enviar" value="1" />
<?php
if ($msgerro) {
?>
	<div class="aviso erro">Ocorreu um erro no envio dos seus dados. Por favor, verifique os campos marcados em vermelho.</div>
	<?php
	if ($cadbo->getErros()) {
	?>
		<div class="aviso alerta"> Por favor, preencha  os campos obrigatórios.
            <ul>
				<?=$cadbo->getErros();?>
			</ul>
		</div>
<?php
	}
}
?>
        <p class="caption no-padding-b"><strong>Dados do usuário</strong></p>

        <label for="name">Nome:</label>
		<em><small>(O mesmo do documento de identificação)</small></em><br />
        <input type="text" class="txt<?=$cadbo->verificaErroCampo('nome')?>" name="nome" value="<?=$cadbo->getValorCampo('nome')?>" id="seu-nome" /><br/>

		<label for="artistico">Nome artístico:</label>
		<em><small>(Opcional)</small></em><br />
		<input type="text" class="txt" id="artistico" name="nomeartistico" value="<?=$cadbo->getValorCampo('nomeartistico')?>" maxlength="100" onkeyup="contarCaracteres(this, 'cont_nome_artistico', 100);" />
		<input name="text3" type="text" id="cont_nome_artistico" disabled="disabled" class="txt contador" value="<?=100 - strlen($cadbo->getValorCampo('nomeartistico'))?>" size="6" /><br />

		<label for="historico">Biografia:</label>
		<em><small>(Fale um pouco sobre você)</small></em> <br />
		<textarea id="historico" cols="78" rows="7" class="<?=$cadbo->verificaErroCampo('historico')?>" name="historico" onkeyup="contarCaracteres(this, 'cont_historico', 1400);"><?=$cadbo->getValorCampo('historico')?></textarea>
		<input name="text32" type="text" id="cont_historico" disabled="disabled" class="txt contador" value="<?=1400 - strlen($cadbo->getValorCampo('historico'))?>" size="6" /><br />

        <label for="nascimento">Data de nascimento:</label>
        <em><small>(Opcional)</small></em><br />
        <input type="text" class="txt small date" name="datanascimento" value="<?=$cadbo->getValorCampo('datanascimento')?>" id="birthdate"/><br/>

		<fieldset id="doc">
			<legend>Documento de identificação: <em><small>(Opcional)</small></em></legend>
			<div id="d_tipo">
            <label for="tipo_doc">Tipo:</label>
            <br />
            <select id="tipo_doc" name="codtipo" class="slc mini">
				<option></option>
				<option value="1" <?=($cadbo->getValorCampo('codtipo') == 1 ? 'selected="selected"' : '');?>>Certidão de nascimento</option>
				<option value="2" <?=($cadbo->getValorCampo('codtipo') == 2 ? 'selected="selected"' : '');?>>CNH</option>
				<option value="3" <?=($cadbo->getValorCampo('codtipo') == 3 ? 'selected="selected"' : '');?>>CPF</option>
				<option value="4" <?=($cadbo->getValorCampo('codtipo') == 4 ? 'selected="selected"' : '');?>>Passaporte</option>
				<option value="5" <?=($cadbo->getValorCampo('codtipo') == 5 ? 'selected="selected"' : '');?>>RG</option>
            </select>
          </div>
          <div id="d_numero">
            <label for="numero_doc">Número:</label>
            <br />
            <input type="text" class="txt small" name="numero" value="<?=$cadbo->getValorCampo('numero')?>" id="numero_doc"/>
          </div>
          <div id="d_orgao">
            <label for="orgao_doc">Orgão expedidor:</label><br />
            <input type="text" class="txt small" name="orgao" value="<?=$cadbo->getValorCampo('orgao')?>" id="orgao_doc"/>
        </div>
        </fieldset>

		<label for="email">E-mail:</label><br />
		<em><small>(Não será publicado)</small></em><br />
		<input type="text" class="txt<?=$cadbo->verificaErroCampo('email')?>" id="email" name="email" value="<?=$cadbo->getValorCampo('email')?>" /><br/>

		<label for="endereco">Endereço:</label>
		<em><small>(opcional)</small></em> <br />
		<input type="text" class="txt<?=$cadbo->verificaErroCampo('endereco')?>" id="endereco" name="endereco" value="<?=$cadbo->getValorCampo('endereco')?>" /><br />

		<label for="complemento">Complemento: </label>
		<em><small>(opcional)</small></em> <br />
		<input name="complemento" type="text" class="txt" id="complemento" value="<?=$cadbo->getValorCampo('complemento')?>" /><br />

		<label for="bairro">Bairro:</label>
		<em><small>(opcional)</small></em> <br />
		<input type="text" name="bairro" class="txt" id="bairro" value="<?=$cadbo->getValorCampo('bairro')?>" /><br />

        <label for="pais">País:</label><br />
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
        <em><small>(Nome de acesso ao sistema. São permitidos apenas letras (a-z), números (0-9) e o sinal (-). Ex. jose-luiz)</small></em><br />
        <input type="text" class="txt medium<?=$cadbo->verificaErroCampo('finalendereco')?>" maxlength="30" name="finalendereco" id="final_endereco" value="<?=$cadbo->getValorCampo('finalendereco')?>" onkeyup="contarCaracteres(this, 'cont_final_endereco', 30); exibeTexto(this, 'texto_final_endereco'); lowercase('final_endereco');" /><input type="text" class="txt contador" disabled="disabled" id="cont_final_endereco" value="<?=30 - strlen($cadbo->getValorCampo('finalendereco'))?>" size="4" />
        <p>O endereço de sua página será: http://www.iteia.org.br/<strong id="texto_final_endereco"></strong></p>

        <label for="pass">Senha: </label>
        <em><small>(Mínimo 6 caracteres)</small></em><br />
        <input type="password" name="senha" class="txt small<?=$cadbo->verificaErroCampo('senha')?>" id="pass"/><br/>
        <label for="rePass">Repetir senha:</label><br />
        <input type="password" name="senha2" class="txt small<?=$cadbo->verificaErroCampo('senha')?>" id="rePass"/><br/>
        <p><strong>Termos de uso</strong></p>
		<p class="caption">Leia os termos e indique que você os aceitou para continuar o processo de cadastro.</p>

          <div id="termos">
            <p> Bem vindo ao iTEIA, rede colaborativa de cultura, arte e informação, idealizado pelo Instituto InterCidadania OSCIP, inscrita no CNPJ/MF sob o número 07.553.412/0001-06, com sede e foro jurídico na Av. Agamenon Magalhães, 2656, sala 1006, Espinheiro, Recife/PE, CEP 52020-000. </p>
            <p> O iTEIA é um projeto sem fins lucrativos, gerenciado de forma colaborativa, que promove o software livre, a diversidade cultural e visa desenvolver formas democráticas de expressão e acesso livre a conteúdos artísticos, respeitando os direitos do autor. O projeto envolve, de forma colaborativa, vídeos, músicas, textos, fotos, noticias, dados de produtores e autores, além de informações e indicadores culturais.</p>
            <p>Por acessar ou participar do portal iTEIA, disponível nos endereços eletrônicos www.iteia.org.br e www.iteia.com.br, seja apenas como visitante ou como usuário (postando novos conteúdos), você afirma que leu, entendeu e aceitou estar ligado a este Termo de Uso (ou Acordo).</p>
            <p>Informamos também que durante a realização do cadastro o usuário pode optar em preencher ou não alguns campos opcionais (ex: ID, CPF, RG...), porém estes são importantes, pois garantem a veracidade das informações postadas por você! Como sabemos, a internet é um ambiente livre muitos não sabem usá-la corretamente. Como o iTEIA trabalha com um enorme acervo cultural, e respeita a autoria artística, esses dados servirão como uma validação sua em nosso sistema alem de inibir a criação de perfis falsos. Mas, não se preocupe! Não divulgaremos seus dados para ninguém!</p>

<p>Isso posto, reservamos o direito de modificar, adicionar ou deletar porções deste Termo a qualquer momento. Seu uso contínuo do Serviço ou do Portal significa a aceitação da atualização dele. Caso você deixe de concordar ou não queira obedecer a essas informações aqui expostas, deve apenas deixar de usar e de acessar a página. O INTERCIDADANIA se compromete a notificar através de correio eletrônico os usuários cadastrados</p>
            <p> Sendo assim, as partes envolvidas resolvem firmar o presente Contrato de Hospedagem Eletrônica de Dados e Conteúdo, tudo de acordo com as cláusulas e estipulações contidas nos itens abaixo, as quais, mutuamente, outorgam, pactuam e aceitam, obrigando-se a cumpri-las por si e por seus herdeiros e sucessores a qualquer título:</p>
            <h3> DAS DECLARAÇÕES PRELIMINARES</h3>
            <p>A.1. Para melhor configuração do presente negócio jurídico, o INTERCIDADANIA declara que:</p>
            <p> (i) é uma instituição sem fins lucrativos, regularmente constituída sob as leis da República Federativa do Brasil; e</p>

            <p> (ii) por meio do portal iTEIA, têm como objetivo estimular a digitalização de conteúdos e a formação de acervos cultuais a serem disponibilizados pela rede mundial de computadores Internet, contribuindo para a disseminação da produção cultural estadual, regional e nacional, bem como promover a difusão e o intercâmbio desses conteúdos de cultura e a integração dos coletivos de cultura e de empreendimentos e produções culturais independentes do Brasil.</p>
            <p> A.2. Para melhor configuração do presente negócio jurídico, o USUÁRIO declara que:</p>
            <p> (i) tem consciência de que pode participar do portal iTEIA em quatro níveis: VISITANTE, AUTOR, GRUPO OU COLABORADOR. O primeiro deles (VISITANTE) apenas visualiza os conteúdos (texto, imagem, áudio, vídeo) postados por AUTORES, GRUPOS OU COLABORADORES e comenta sobre os arquivos, quando permitido. Os outros três níveis (AUTOR, GRUPO E COLABORADOR) recebem um nome de usuário (login) e senha exclusivos, que possibilita o acesso e a identificação do mesmo no sistema.</p>
            <p> (ii) um usuário do nível AUTOR pode criar ou fazer parte de um GRUPO quando quiser, tendo os mesmos direitos e deveres postos neste Termo de Uso - apenas mudando automaticamente para o nível GRUPO. Da mesma maneira, ele pode deixar de fazer parte de um GRUPO quando quiser, voltando ao nível AUTOR;</p>
            <p> (iii) um usuário do nível AUTOR é promovido ao nível de COLABORADOR quando convidado por uma entidade, instituição ou coletivo, para tornar-se representante do mesmo no sistema. Por sua vez, essa entidade, instituição ou coletivo precisa ter sido convidado pelo INTERCIDADANIA para ajudar a administrar o portal iTEIA;</p>

            <p> (iv) os usuários com nível COLABORADOR ajudam ao INTERCIDADANIA a controlar todos os conteúdos postados no portal iTEIA. Eles aprovam ou reprovam os conteúdos postados por usuários dos níveis AUTORES E GRUPOS, referendando a idoneidade e as informações, ou não, dos mesmos.</p>
            <p> (v) os usuários dos níveis AUTOR E GRUPO somente podem postar conteúdos próprios ou que tenham a participação deles; já os usuários do nível COLABORADOR podem postar conteúdos próprios ou de terceiros, beneficiando também pessoas que não tenham acesso a computadores e à Internet;</p>
            <p> (vi) o conteúdo disponibilizado pelos usuários no iTEIA não sofre qualquer edição pelo INTERCIDADANIA, não infringe qualquer lei brasileira ou extrangeira e não ofende direitos de quaisquer terceiros, inclusive, mas não se limitado a, direitos de propriedade intelectual;</p>
            <p> (vii) o INTERCIDADANIA reserva o direito de retirar do ar ou suspender, a qualquer momento, conteúdos que tenham informações ou imagens impróprias ou que, de alguma maneira, ofenda os direitos, a honra e a integridade de outros usuários, visitantes, empresas ou estados. Além disso, é proibida a publicação de conteúdos impróprios para menores de 18 anos;</p>
            <h3> CLÁUSULA PRIMEIRA - DO OBJETO</h3>

            <p> 1.1. O presente Termo de Uso ou Acordo tem por objeto estabelecer as cláusulas e condições a regerem a hospedagem eletrônica de dados e conteúdo a ser provida gratuitamente pelo INTERCIDADANIA e que será usufruída pelos AUTORES, GRUPOS E COLABORADORES do sistema, bem como a disponibilização gratuita, no portal iTEIA, do conteúdo eletronicamente enviado pelos AUTORES E COLABORADORES para hospedagem.</p>
            <h3> CLÁUSULA SEGUNDA - DO LICENCIAMENTO DO CONTEÚDO</h3>
            <p> 2.1. O portal iTEIA apresenta diversas formas de licenciamento de obras autorais para o conteúdo a ser disponibilizado, que deverão ser livremente escolhidas pelos AUTORES, GRUPOS E COLABORADORES no momento de disponibilização de cada conteúdo. Podendo, contudo, voltar atrás e rever a decisão de disponibilizar uma obra para download (copyleft/domínio público) ou apenas para consulta (copyright).</p>
            <p> 2.2. Para qualquer conteúdo disponibilizado no portal iTEIA, os AUTORES, GRUPOS E COLABORADORES se obrigam a fazer as devidas citações aos respectivos autores (pessoas físicas) das obras autorais (textos, imagens, áudios, vídeos, etc) disponibilizadas, em reconhecimento e respeito aos seus direitos morais, nos termos da legislação de direitos autorais aplicável. Vale salientar que os autores do sistema somente devem publicar conteúdos feitos/produzidos/criados por eles ou por terceiros que autorizem essa publicação na internet, sendo de responsabilidade dos mesmos possíveis problemas de direitos autorais a serem reclamados no futuro.</p>
            <p> 2.3. Os AUTORES E COLABORADORES declaram que todo e qualquer conteúdo enviado para hospedagem e disponibilização no portal iTEIA, notadamente obras protegidas por direitos autorais, se encontra devidamente autorizadas por seu(s) legítimo(s) titulare(s) para a forma de licenciamento optada por eles no portal iTEIA, devendo, em caso de demanda judicial ou extrajudicial apresentada por terceiros contra o INTERCIDADANIA, reponsabilizar-se e responder diretamente, excluindo qualquer responsabilidade ao INTERCIDADANIA, nos termos deste Acordo.</p>

            <h3> CLÁUSULA TERCEIRA - DA RESPONSABILIDADE PELO CONTEÚDO</h3>
            <p> 3.1. O COLABORADOR e o AUTOR são os únicos responsáveis pelo conteúdo, seus ou de terceiros, que disponibilizarem no portal iTEIA, obrigando-se a requererem substituição processual e a defenderem os interesses do INTERCIDADANIA caso isso venha a ser demandado judicial ou extrajudicialmente.</p>
            <p> 3.2. O COLABORADOR e o AUTOR se obrigam a indenizar o INTERCIDADANIA por quaisquer ônus que incorrerem em face de demanda judicial ou extrajudicial por conta do conteúdo disponibilizado por eles, incluindo, mas não se limitando a, indenização por decisão judicial, despesas administrativas, custas processuais e honorários advocatícios.</p>
            <h3> CLÁUSULA QUARTA - DA INEXISTÊNCIA DE NOVAÇÃO OU RENÚNCIA</h3>
            <p> 4.1. O eventual não exercício de direito ou tolerância por qualquer das partes não implicará em renúncia ou novação de direito, podendo ser exercido a qualquer tempo.</p>

            <h3> CLÁUSULA QUINTA - DAS DISPOSIÇÕES GERAIS</h3>
            <p> 5.1. O COLABORADOR e o AUTOR se obrigam a manter seus dados cadastrais devidamente atualizados perante o INTERCIDADANIA, entendendo que seu correio eletrônico será o principal meio de contato dos administradores do portal iTEIA, inclusive para fins de notificação previstos neste contrato.</p>
            <p> 5.2. Em caso de suspeita de conteúdo indevido, seja por suspeita de infração a direitos de terceiros, seja por qualquer outro motivo, a exclusivo critério do INTERCIDADANIA, esses poderão indisponibilizar o conteúdo do AUTOR ou do COLABORADOR temporariamente do portal iTEIA.</p>
            <p>5.3. Durante a visita do portal iTEIA, os internautas poderão usar um serviço, fazer download ou comprar bens fornecidos por outras pessoas ou empresas. A utilização desses serviços, conteúdos ou bens por parte dos visitantes ou usuários do sistema estarão sujeitos aos termos entre os internautas e as empresa ou as pessoas em questão, excluindo o INTERCIDADANIA de qualquer vínculo com possíveis transações financeiras, ou de outro tipo, iniciadas com ajuda do portal.</p>
            <h3> CLÁUSULA SEXTA - DO FORO PARA RESOLUÇÃO DE CONFLITOS</h3>

            <p> 6.1. Todos os itens deste Termo de Uso estão regidos pelas leis vigentes na República Federativa do Brasil. Para todos os assuntos referentes à interpretação e ao cumprimento deste Acordo, as partes elegem o foro da comarca do Recife, estado de Pernambuco, para dirimir quaisquer conflitos resultantes direta ou indiretamente, excluindo-se qualquer outro, por mais privilegiado que seja. </p>
            <p>Recife (PE), 15 de novembro de 2007. </p>
          </div>

<br />
<div class="caption">
  <input type="checkbox" value="1" id="acordo" name="deacordo"<?=($cadbo->getValorCampo('deacordo'))?' checked="checked"':''?> />
  <label for="checkbox">li e aceito as condições do Termo de uso do portal iTEIA</label>
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
