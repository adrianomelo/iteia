<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'denuncie';
$titulopagina = 'Erro 404 - N�o encontrado';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Erro 404 - N�o encontrado</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Erro 404 - N�o encontrado</h2>

        <div class="texto-box">
        <h1 class="midia">Buscando algo no iTEIA?</h1>
        <p>O servidor n�o p�de localizar o que voc� est� procurando.</p>

        	<strong>Motivos:</strong>
            <ul>
<li>O link que voc� seguiu pode estar desatualizado ou voc� digitou um endere�o com algum erro.</li>
<li>Voc� pode tentar fazer uma pesquisa ou navegar atrav�s dos arquivos.
</li></ul>        </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
