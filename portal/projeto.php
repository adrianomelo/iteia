<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-projeto iteia';
$titulopagina = 'Conheça o projeto';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Conheça o projeto </span></div>
    <div id="conteudo">
<div class="principal">

        <h2 class="midia">Conheça o projeto </h2>
        
        <p>O <strong>iTEIA</strong> é uma <strong>Rede Independente de Cultura e  Cidadania</strong>, idealizada pelo <strong>Instituto InterCidadania</strong> (Organização da Sociedade Civil de Interesse Público) e desenvolvida em  parceria com patrocinadores e organizações governamentais e não governamentais,  em sintonia com o Programa Cultura Viva do Ministério da Cultura. É um arrojado  sistema on-line de gerenciamento, difusão e intercâmbio de conteúdos culturais  digitalizados, com amplo suporte tecnológico e integrado com outras redes  digitais e não digitais. A idéia é servir de base de interligação com outros  projetos com objetivos similares, formando uma <strong>Teia de interação cultural na internet</strong>.</p>

      <p>O <strong>iTEIA</strong> é um projeto sem fins lucrativos, gerenciado de  forma colaborativa, que promove o software livre, a diversidade cultural e visa  desenvolver formas democráticas de expressão e acesso livre a conteúdos  artísticos, respeitando os direitos do autor. O <strong>iTEIA</strong> adotou o <a href="http://creativecommons.org/international/br/">Creative Commons</a> como referência de licenciamento de conteúdos e se propõe a pesquisar e debater  permanentemente formas alternativas de fortalecer a cultura livre e promover a  geração de modelos solidários de produção e comercialização de produtos  culturais.</p>
      <p>O projeto envolve, de forma colaborativa, vídeos, músicas, textos, fotos,  noticias, dados de produtores e autores, além de outras informações culturais.  Também prevê canais de orientação e capacitação; de divulgação de projetos  culturais e ambientes de aproximação entre artistas, produtores, patrocinadores  e público (que poderá promover rankings de melhores conteúdos, votando no seus  links preferidos).</p>
      <p><strong>Geração de Receitas para colaboradores e autores</strong></p>

      <p>Os colaboradores poderão gerenciar e explorar espaços publicitários  associados aos seus respectivos conteúdos. Ou seja, ao lado dos conteúdos e  resultados de buscas aparecerão banners que serão inseridos pelos próprios  colaboradores, possibilitando a captação de receitas.</p>
      <p>Os espaços não explorados serão ocupados por banners de mensagens de  cidadania e marcas dos patrocinadores do <strong>iTEIA</strong>.</p>
      <p>O <strong>iTEIA</strong> é um projeto não governamental, desenvolvido pelo  Instituto InterCidadania, com patrocínio da <a href="http://www.petrobras.com.br/pt/">Petrobras</a> e <a href="http://www.sxbrasil.com.br">SX Brasil Comunicação Digital</a>;  integrado ao sistema de busca <a href="http://www.achanoticias.com.br">AchaNoticias.com.br</a>;  com apoio institucional do <a href="http://www.cultura.gov.br/">MinC</a> e <a href="http://www.cultura.pe.gov.br/">Fundação de Cultura de Pernambuco -  Fundarpe</a>; e participação do <a href="http://www.estudiolivre.org/">Estúdio  livre</a> - <a href="http://culturadigital.org.br/">Cultura Digital</a> -  Centro de Desenvolvimento de Tecnologias Livres - PE e <a href="http://www.kmf.com.br/">KMF</a>.</p>

      <strong>Saiba mais</strong>
      <ul>
        <li>&raquo; <a href="/arquivos/apresentacaoiteia/index.html">Apresentação Visual</a></li>
        <li>&raquo; <a href="/arquivos/apresentacaoiteia/visao-geral-do-projeto-iTEIA.pdf">Visão geral do projeto</a> (.PDF)</li>
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
