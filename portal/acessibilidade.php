<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'Acessibilidade';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Acessibilidade</span></div>
    <div id="conteudo">
      <h2 class="midia">Acessibilidade</h2>
      <div id="pagina">
        <p>O Portal iTEIA foi concebido de forma a oferecer o   m�ximo de acessibilidade aos visitantes, seguindo os guias de   acessibilidade do World Wide Web Consortium (W3C), cons�rcio   internacional de empresas que regulamenta os padr�es de constru��o de   p�ginas para internet.</p>
        <p>O site � 100% acess�vel em rela��o ao n�vel um de prioridade   do W3C Web Content Accessibility Guidelines. Nossa pol�tica de   acessibilidade permite que mesmo usu�rios portadores de defici�ncia   visual consigam navegar no site.</p>
        <p>Tal navega��o deve ser feita utilizando-se um navegador de   texto ou leitor de tela. Recomendamos o uso do navegador WEBVOX, do   sistema operacional <a href="http://intervox.nce.ufrj.br/dosvox/">DOSVOX</a>,   um projeto do N�cleo de Computa��o Eletr�nica da Universidade Federal   do Rio de Janeiro. <a href="http://intervox.nce.ufrj.br/dosvox/download.htm">Fa�a o download   do programa</a>.<br />
        </p>
        <p><strong>Acesso por dispositivos m�veis</strong></p>
        <p>O Portal de Busca Achix tamb�m � acess�vel por dispositivos   m�veis, como celulares e computadores de m�o. Para acessar, basta abrir o   navegador do seu celular ou computador de m�o e entrar com o endere�o <a href="http://www.iteia.org.br">www.iteia.org.br</a></p>
        <p>O acesso por celular e dispositivos m�veis geralmente � pago   por tr�fego de dados. Por isso o site foi constru�do de forma a   facilitar o acesso por esses dispositivos, ocultando imagens e outras   informa��es para deixar o site mais leve e r�pido.</p>
      </div>
    </div>
<?php
include ('includes/rodape.php');
