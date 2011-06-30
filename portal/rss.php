<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'O que é RSS?';
include ('includes/topo.php');
?>
  <div id="conteiner">
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">O que é RSS?</span></div>
    <div id="conteudo">
      <div class="principal texto-box">
        <h2 class="midia">O que é RSS?</h2>
        <p>Assinar o feed do iTEIA é como assinar uma  revista. Sempre que sai uma nova edição, você recebe a informação sem  precisar ir até a banca. A diferença é que assinar o feed do nosso site  não custa nada, basta ter um programa leitor de feeds.</p>
        <p>Os  programas leitores de feeds avisam quando há alguma novidade no site  cujo feed você assinou. No próprio programa você pode ler o que foi  atualizado ou ter o link para essa atualização.</p>
        <p>Os  leitores de feeds podem ser programas instalados no seu computador ou  sites que oferecem o serviço na internet. Os serviços na internet são  mais recomendados, pois você pode ler seus feeds em qualquer computador  conectado à web.</p>
        <p>Alguns leitores de feeds disponíveis na internet são o <a href="http://www.google.com.br/reader/">Google Reader</a> e <a href="http://www.bloglines.com/">Bloglines</a>. Basta cadastra-se inserir o endereço do feed do iTEIA:</p>
        <ul>
            <li><strong>Áudio</strong> - <a href="/feeds.php?formato=3"><?=ConfigVO::URL_SITE?>feeds.php?formato=3</a></li>
            <li><strong>Vídeo</strong> - <a href="/feeds.php?formato=4"><?=ConfigVO::URL_SITE?>feeds.php?formato=4</a></li>
            <li><strong>Texto</strong> - <a href="/feeds.php?formato=1"><?=ConfigVO::URL_SITE?>feeds.php?formato=1</a></li>
            <li><strong>Imagem</strong> - <a href="/feeds.php?formato=2"><?=ConfigVO::URL_SITE?>feeds.php?formato=2</a></li>
            <li><strong>Eventos</strong> - <a href="/feeds.php?formato=6"><?=ConfigVO::URL_SITE?>feeds.php?formato=6</a></li>
            <li><strong>Notícias</strong> - <a href="/feeds.php?formato=5"><?=ConfigVO::URL_SITE?>feeds.php?formato=5</a></li>
            <li><strong>Todos os conteúdos</strong> - <a href="/feeds.php?formato=10"><?=ConfigVO::URL_SITE?>feeds.php</a></li>
        </ul>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
