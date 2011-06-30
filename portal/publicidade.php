<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'iteia';
$titulopagina = 'Publicidade colaborativa';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Publicidade colaborativa</span></div>
    <div id="conteudo">
      <div class="principal">
       <h2 class="midia">Publicidade colaborativa</h2>
        <h3>Gera��o de Receitas para colaboradores e autores</h3>
      <p>Os colaboradores poder�o gerenciar e explorar espa�os publicit�rios  associados aos seus respectivos conte�dos. Ou seja, ao lado dos conte�dos e  resultados de buscas aparecer�o banners que ser�o inseridos pelos pr�prios  colaboradores, possibilitando a capta��o de receitas.</p>
      <p>Os espa�os n�o explorados ser�o ocupados por banners de mensagens de  cidadania e marcas dos patrocinadores do <strong>iTEIA</strong>.</p>
      <p>O <strong>iTEIA</strong> � um projeto n�o governamental, desenvolvido pelo  Instituto InterCidadania, com patroc�nio da <a href="http://www.petrobras.com.br/pt/">Petrobras</a> e <a href="http://www.sxbrasil.com.br">SX Brasil Comunica��o Digital</a>;  integrado ao sistema de busca <a href="http://www.achanoticias.com.br">AchaNoticias.com.br</a>;  com apoio institucional do <a href="http://www.cultura.gov.br/">MinC</a> e <a href="http://www.cultura.pe.gov.br/">Funda��o de Cultura de Pernambuco -  Fundarpe</a>; e participa��o do <a href="http://www.estudiolivre.org/">Est�dio  livre</a> - <a href="http://culturadigital.org.br/">Cultura Digital</a> -  Centro de Desenvolvimento de Tecnologias Livres - PE e <a href="http://www.kmf.com.br/">KMF</a>.</p>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
