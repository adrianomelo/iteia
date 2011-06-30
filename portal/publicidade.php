<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'iteia';
$titulopagina = 'Publicidade colaborativa';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Publicidade colaborativa</span></div>
    <div id="conteudo">
      <div class="principal">
       <h2 class="midia">Publicidade colaborativa</h2>
        <h3>Geração de Receitas para colaboradores e autores</h3>
      <p>Os colaboradores poderão gerenciar e explorar espaços publicitários  associados aos seus respectivos conteúdos. Ou seja, ao lado dos conteúdos e  resultados de buscas aparecerão banners que serão inseridos pelos próprios  colaboradores, possibilitando a captação de receitas.</p>
      <p>Os espaços não explorados serão ocupados por banners de mensagens de  cidadania e marcas dos patrocinadores do <strong>iTEIA</strong>.</p>
      <p>O <strong>iTEIA</strong> é um projeto não governamental, desenvolvido pelo  Instituto InterCidadania, com patrocínio da <a href="http://www.petrobras.com.br/pt/">Petrobras</a> e <a href="http://www.sxbrasil.com.br">SX Brasil Comunicação Digital</a>;  integrado ao sistema de busca <a href="http://www.achanoticias.com.br">AchaNoticias.com.br</a>;  com apoio institucional do <a href="http://www.cultura.gov.br/">MinC</a> e <a href="http://www.cultura.pe.gov.br/">Fundação de Cultura de Pernambuco -  Fundarpe</a>; e participação do <a href="http://www.estudiolivre.org/">Estúdio  livre</a> - <a href="http://culturadigital.org.br/">Cultura Digital</a> -  Centro de Desenvolvimento de Tecnologias Livres - PE e <a href="http://www.kmf.com.br/">KMF</a>.</p>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
