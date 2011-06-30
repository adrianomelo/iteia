<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-mapa iteia';
$titulopagina = 'Mapa do site';

$css_mapa = 1;

include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Mapa do site</span></div>
    <div id="conteudo">
      <h2 class="midia">Mapa do site</h2>
 <div id="pagina">

        <ul id="utilityNav">
          <li><a href="contato" title="Fale conosco">Contato</a></li>
          <li><a href="busca_avancada" title="Busca avan�ada">Busca</a></li>
          <li><a href="http://nacaocultural.pe.gov.br/gerenciador/login.php" title="Entrar no gerenciador">Entrar</a></li>
        </ul>
        <ul id="primaryNav" class="col4">
          <li id="home-map"> <a href="/" class="url" rel="me" title="Ir para p�gina inicial">P�gina inicial</a></li>

          <li><a href="projeto" title="Conhe�a o projeto iTEIA">Conhe�a o projeto</a>
            <ul>
              <li><a href="acessibilidade" title="Pol�tica de acessibilidade">Acessibilidade</a></li>
              <li><a href="equipe" title="Conhe�a a nossa equipe">Equipe</a></li>
              <li><a href="publicidade" title="Saiba mais sobre a publicidade no iTEIA">Publicidade Colaborativa</a></li>
              <li><a href="faq" title="Tire suas d�vidas">Perguntas frequentes</a></li>

               <li><a href="suporte" title="Suporte">Suporte ao site</a></li>
            </ul>
          </li>
          <li> <a href="#" title="Conte�dos">Conte�dos</a>
            <ul>
              <li><a href="audios" title="�udios">�udios</a></li>
              <li><a href="eventos" title="Eventos">Eventos</a></li>

              <li><a href="imagens" title="Imagens">Imagens</a></li>
              <li><a href="noticias" title="Not�cias">Not�cias</a></li>
              <li><a href="textos" title="Textos">Textos</a></li>
              <li><a href="videos" title="V�deos">V�deos</a></li>
              <li><a href="rss" title="Saiba o que � RSS">Assine nosso RSS</a></li>
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
          <li><a href="#">Classifica��o</a>
            <ul>
              <li><a href="bookmarks" title="Ir para p�gina de bookmarks">Bookmarks</a></li>
              <li><a href="canais" title="Canais">Canais</a></li>
              <li><a href="tags" title="Ir para p�gina de tags">Tags</a></li>

            </ul>
          </li>
        </ul>
      </div>
    </div>




<?php
include ('includes/rodape.php');
