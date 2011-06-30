<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'denuncie';
$titulopagina = 'Erro 404 - Não encontrado';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Erro 404 - Não encontrado</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Erro 404 - Não encontrado</h2>

        <div class="texto-box">
        <h1 class="midia">Buscando algo no iTEIA?</h1>
        <p>O servidor não pôde localizar o que você está procurando.</p>

        	<strong>Motivos:</strong>
            <ul>
<li>O link que você seguiu pode estar desatualizado ou você digitou um endereço com algum erro.</li>
<li>Você pode tentar fazer uma pesquisa ou navegar através dos arquivos.
</li></ul>        </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
