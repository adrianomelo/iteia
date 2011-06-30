<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-contato iteia';
$titulopagina = 'Contato';
$js_contato = 1 ;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Contato</span></div>
    <div id="conteudo">

      <div class="principal">
      <h2 class="midia">Contato</h2>
        <p class="caption">Para entrar em contato com a equipe do iTEIA, utilize o fomulário abaixo, telefone para (81) 3242-7373 ou envie um e-mail para contato@iteia.org.br.</p>

      <div id="comentar">
        <form action="javascript:;" id="formcontato" name="formcontato">
            <div id="resposta_contato"></div>
				<fieldset>
                <label for="denuncia">Mensagem:</label><br />
                <textarea id="sua-mensagem" name="mensagem" cols="30" rows="5"></textarea><br />
                <label for="seu-nome">Seu nome:</label><br />
                <input type="text" id="seu-nome" name="nome" class="txt" /><br />
                <label for="seu-email">Seu e-mail:</label><br />
                <input type="text" id="seu-email" name="email" class="txt" /><br />
                <input class="btn" type="image" onclick="javascript:enviarContato();" src="/img/botoes/bt_enviar.gif" />
            </fieldset>
          </form>
      </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
