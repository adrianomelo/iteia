<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'Acessibilidade';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Acessibilidade</span></div>
    <div id="conteudo">
      <h2 class="midia">Acessibilidade</h2>
      <div id="pagina">
        <p>O Portal iTEIA foi concebido de forma a oferecer o   máximo de acessibilidade aos visitantes, seguindo os guias de   acessibilidade do World Wide Web Consortium (W3C), consórcio   internacional de empresas que regulamenta os padrões de construção de   páginas para internet.</p>
        <p>O site é 100% acessível em relação ao nível um de prioridade   do W3C Web Content Accessibility Guidelines. Nossa política de   acessibilidade permite que mesmo usuários portadores de deficiência   visual consigam navegar no site.</p>
        <p>Tal navegação deve ser feita utilizando-se um navegador de   texto ou leitor de tela. Recomendamos o uso do navegador WEBVOX, do   sistema operacional <a href="http://intervox.nce.ufrj.br/dosvox/">DOSVOX</a>,   um projeto do Núcleo de Computação Eletrônica da Universidade Federal   do Rio de Janeiro. <a href="http://intervox.nce.ufrj.br/dosvox/download.htm">Faça o download   do programa</a>.<br />
        </p>
        <p><strong>Acesso por dispositivos móveis</strong></p>
        <p>O Portal de Busca Achix também é acessível por dispositivos   móveis, como celulares e computadores de mão. Para acessar, basta abrir o   navegador do seu celular ou computador de mão e entrar com o endereço <a href="http://www.iteia.org.br">www.iteia.org.br</a></p>
        <p>O acesso por celular e dispositivos móveis geralmente é pago   por tráfego de dados. Por isso o site foi construído de forma a   facilitar o acesso por esses dispositivos, ocultando imagens e outras   informações para deixar o site mais leve e rápido.</p>
      </div>
    </div>
<?php
include ('includes/rodape.php');
