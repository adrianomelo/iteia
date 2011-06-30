<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/dao/ConteudoExibicaoDAO.php');
include_once('classes/bo/DenunciaBO.php');
$denunbo = new DenunciaBO;
$conteudo = new ConteudoExibicaoDAO;

$codconteudo = (int)$_GET['conteudo'];
$dadosconteudo = $conteudo->getDadosConteudo($codconteudo);

$topo_class = 'denuncie';
$js_denuncie = true;
$titulopagina = 'Denunciar conte�do impr�prio';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <a href="<?=$dadosconteudo['url']?>" title="Ir para p�gina deste conte�do"><?=$dadosconteudo['titulo']?></a> <span class="marcador">&raquo;</span> Denunciar conte�do impr�prio</div>
    <div id="conteudo">

      <div class="principal">
            <h2 class="midia">Denunciar conte�do impr�prio</h2>
        <p class="caption">Esse formul�rio deve ser usado se o conte�do que voc� estava visualizando � impr�prio para o portal iTEIA. Sua den�ncia ser� avaliada pelos moderados e pode ser acatada ou n�o.</p>
        <p><strong>T�tulo do conte�do:</strong><br />
        <a href="<?=$dadosconteudo['url']?>" title="Ir para p�gina deste conte�do"><?=$dadosconteudo['titulo']?></a></p>
        <p>
        <strong>Autor:</strong><br />
		<?=Util::getHtmlListaAutores($dadosconteudo['cod_conteudo'], '')?>
       </p>

      <div id="comentar">
          <form action="javascript:;" id="form-contato" name="formdenuncie">
            <div id="resposta_contato"></div>
			<input type="hidden" name="codconteudo" value="<?=$codconteudo?>" />
            <fieldset>
                <label for="denuncia">Descreva sua den�ncia:</label>
                <br />
                <textarea id="denuncia" cols="30" rows="5" name="mensagem"></textarea>
                <br />
                <label for="tipo">Tipo de den�ncia:</label>
                <br />
                <select name="tipo" id="tipo" class="slc">
                  <option value="1">Conte�do Impr�prio</option>
                  <option value="2">Conte�do de Outro Autor</option>
                  <option value="3">Conte�do Derivado de Obra Autoral</option>
                  <option value="4">Cadastro de Autor indevido</option>         
                  <option value="5">Outros</option>
                </select>
                <br />
                <label for="seu-nome">Seu nome:</label>
                <br />
                <input type="text" id="seu-nome" name="nome" class="txt" />
                <br />
                <label for="seu-email">Seu e-mail (n�o ser� publicado):</label>
                <br />
                <input type="text" id="seu-email" name="email" class="txt" />
                <br />
                <input class="btn" type="image" onclick="javascript:enviarDenuncia();" src="/img/botoes/bt_enviar.gif" />
            </fieldset>
          </form>
      </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div></div>
<?php
include ('includes/rodape.php');
