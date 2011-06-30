<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/BuscaBO.php');

if ($_GET['assunto'])
	$assunto = trim($_GET['assunto']);
	
if ($_GET['palavra'])
	$assunto = trim($_GET['palavra']);

$js_busca = true;
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'Resultado da busca';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Resultado de busca</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Resultado de busca</h2>
        <div class="texto-box">
        <h1 class="midia">Ops! Nenhum resultado encontrado <?php if ($assunto): ?> para: "<span class="palavra"><? echo $assunto;?></span>"<?php endif; ?></h1>
        
        	<strong>Sugestões:</strong>
            <ul>
              <li>Verifique se as palavras foram digitadas corretamente.</li>
              <li>Tente outras palavras.</li>
              <li>Tente palavras mais gerais.</li>
          </ul>
            <a href="busca_avancada.php" title="Refine sua busca">Ir para busca avançada</a>        </div>
<table cellspacing="0" cellpadding="0" border="0" id="resultado-busca">

        </table>
      </div>
      <div class="lateral">
     
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
