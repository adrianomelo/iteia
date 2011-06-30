<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-projeto iteia';
$titulopagina = 'Conhe�a o projeto';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Conhe�a o projeto </span></div>
    <div id="conteudo">
<div class="principal">

        <h2 class="midia">Conhe�a o projeto </h2>
        
        <p>O <strong>iTEIA</strong> � uma <strong>Rede Independente de Cultura e  Cidadania</strong>, idealizada pelo <strong>Instituto InterCidadania</strong> (Organiza��o da Sociedade Civil de Interesse P�blico) e desenvolvida em  parceria com patrocinadores e organiza��es governamentais e n�o governamentais,  em sintonia com o Programa Cultura Viva do Minist�rio da Cultura. � um arrojado  sistema on-line de gerenciamento, difus�o e interc�mbio de conte�dos culturais  digitalizados, com amplo suporte tecnol�gico e integrado com outras redes  digitais e n�o digitais. A id�ia � servir de base de interliga��o com outros  projetos com objetivos similares, formando uma <strong>Teia de intera��o cultural na internet</strong>.</p>

      <p>O <strong>iTEIA</strong> � um projeto sem fins lucrativos, gerenciado de  forma colaborativa, que promove o software livre, a diversidade cultural e visa  desenvolver formas democr�ticas de express�o e acesso livre a conte�dos  art�sticos, respeitando os direitos do autor. O <strong>iTEIA</strong> adotou o <a href="http://creativecommons.org/international/br/">Creative Commons</a> como refer�ncia de licenciamento de conte�dos e se prop�e a pesquisar e debater  permanentemente formas alternativas de fortalecer a cultura livre e promover a  gera��o de modelos solid�rios de produ��o e comercializa��o de produtos  culturais.</p>
      <p>O projeto envolve, de forma colaborativa, v�deos, m�sicas, textos, fotos,  noticias, dados de produtores e autores, al�m de outras informa��es culturais.  Tamb�m prev� canais de orienta��o e capacita��o; de divulga��o de projetos  culturais e ambientes de aproxima��o entre artistas, produtores, patrocinadores  e p�blico (que poder� promover rankings de melhores conte�dos, votando no seus  links preferidos).</p>
      <p><strong>Gera��o de Receitas para colaboradores e autores</strong></p>

      <p>Os colaboradores poder�o gerenciar e explorar espa�os publicit�rios  associados aos seus respectivos conte�dos. Ou seja, ao lado dos conte�dos e  resultados de buscas aparecer�o banners que ser�o inseridos pelos pr�prios  colaboradores, possibilitando a capta��o de receitas.</p>
      <p>Os espa�os n�o explorados ser�o ocupados por banners de mensagens de  cidadania e marcas dos patrocinadores do <strong>iTEIA</strong>.</p>
      <p>O <strong>iTEIA</strong> � um projeto n�o governamental, desenvolvido pelo  Instituto InterCidadania, com patroc�nio da <a href="http://www.petrobras.com.br/pt/">Petrobras</a> e <a href="http://www.sxbrasil.com.br">SX Brasil Comunica��o Digital</a>;  integrado ao sistema de busca <a href="http://www.achanoticias.com.br">AchaNoticias.com.br</a>;  com apoio institucional do <a href="http://www.cultura.gov.br/">MinC</a> e <a href="http://www.cultura.pe.gov.br/">Funda��o de Cultura de Pernambuco -  Fundarpe</a>; e participa��o do <a href="http://www.estudiolivre.org/">Est�dio  livre</a> - <a href="http://culturadigital.org.br/">Cultura Digital</a> -  Centro de Desenvolvimento de Tecnologias Livres - PE e <a href="http://www.kmf.com.br/">KMF</a>.</p>

      <strong>Saiba mais</strong>
      <ul>
        <li>&raquo; <a href="/arquivos/apresentacaoiteia/index.html">Apresenta��o Visual</a></li>
        <li>&raquo; <a href="/arquivos/apresentacaoiteia/visao-geral-do-projeto-iTEIA.pdf">Vis�o geral do projeto</a> (.PDF)</li>
        <li>&raquo; Parceiros e apoios</li>
        <li>&raquo; <a href="/equipe">Equipe</a></li>
        <li>&raquo; <a href="/contato">Contato</a></li>
        <li>&raquo; <a href="http://www.intercidadania.org.br/" target="_blank">InterCidadania</a></li>
      </ul>

      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
    </div>
<?php
include ('includes/rodape.php');
