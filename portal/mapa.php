<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-mapa iteia';
$titulopagina = 'Mapa do site';

$css_mapa = 1;

include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Mapa do site</span></div>
    <div id="conteudo">
      <h2 class="midia">Mapa do site</h2>
 <div id="pagina">

        <ul id="utilityNav">
          <li><a href="contato" title="Fale conosco">Contato</a></li>
          <li><a href="busca_avancada" title="Busca avançada">Busca</a></li>
          <li><a href="http://nacaocultural.pe.gov.br/gerenciador/login.php" title="Entrar no gerenciador">Entrar</a></li>
        </ul>
        <ul id="primaryNav" class="col4">
          <li id="home-map"> <a href="/" class="url" rel="me" title="Ir para página inicial">Página inicial</a></li>

          <li><a href="projeto" title="Conheça o projeto iTEIA">Conheça o projeto</a>
            <ul>
              <li><a href="acessibilidade" title="Política de acessibilidade">Acessibilidade</a></li>
              <li><a href="equipe" title="Conheça a nossa equipe">Equipe</a></li>
              <li><a href="publicidade" title="Saiba mais sobre a publicidade no iTEIA">Publicidade Colaborativa</a></li>
              <li><a href="faq" title="Tire suas dúvidas">Perguntas frequentes</a></li>

               <li><a href="suporte" title="Suporte">Suporte ao site</a></li>
            </ul>
          </li>
          <li> <a href="#" title="Conteúdos">Conteúdos</a>
            <ul>
              <li><a href="audios" title="Áudios">Áudios</a></li>
              <li><a href="eventos" title="Eventos">Eventos</a></li>

              <li><a href="imagens" title="Imagens">Imagens</a></li>
              <li><a href="noticias" title="Notícias">Notícias</a></li>
              <li><a href="textos" title="Textos">Textos</a></li>
              <li><a href="videos" title="Vídeos">Vídeos</a></li>
              <li><a href="rss" title="Saiba o que é RSS">Assine nosso RSS</a></li>
            </ul>

          </li>
          <li><a href="participar">Como participar</a>
            <ul>
              <li><a href="autores" title="Autores">Autores</a>
                <ul>
                  <li><a href="cadastro_autor" title="Cadastre-se como autor">Cadastro de autor</a></li>
                </ul>

              </li>
              <li><a href="colaboradores" title="Colaboradores">Colaboradores</a>
                <ul>
                  <li><a href="cadastro_colaborador" title="Cadastre-se como colaborador">Cadastro de colaborador</a></li>
                </ul>
              </li>
              <li><a href="termos" title="Leia o regulamento">Termos de uso</a></li>

            </ul>
          </li>
          <li><a href="#">Classificação</a>
            <ul>
              <li><a href="bookmarks" title="Ir para página de bookmarks">Bookmarks</a></li>
              <li><a href="canais" title="Canais">Canais</a></li>
              <li><a href="tags" title="Ir para página de tags">Tags</a></li>

            </ul>
          </li>
        </ul>
      </div>
    </div>




<?php
include ('includes/rodape.php');
