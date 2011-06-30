<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'O que � RSS?';
include ('includes/topo.php');
?>
  <div id="conteiner">
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">O que � RSS?</span></div>
    <div id="conteudo">
      <div class="principal texto-box">
        <h2 class="midia">O que � RSS?</h2>
        <p>Assinar o feed do iTEIA � como assinar uma  revista. Sempre que sai uma nova edi��o, voc� recebe a informa��o sem  precisar ir at� a banca. A diferen�a � que assinar o feed do nosso site  n�o custa nada, basta ter um programa leitor de feeds.</p>
        <p>Os  programas leitores de feeds avisam quando h� alguma novidade no site  cujo feed voc� assinou. No pr�prio programa voc� pode ler o que foi  atualizado ou ter o link para essa atualiza��o.</p>
        <p>Os  leitores de feeds podem ser programas instalados no seu computador ou  sites que oferecem o servi�o na internet. Os servi�os na internet s�o  mais recomendados, pois voc� pode ler seus feeds em qualquer computador  conectado � web.</p>
        <p>Alguns leitores de feeds dispon�veis na internet s�o o <a href="http://www.google.com.br/reader/">Google Reader</a> e <a href="http://www.bloglines.com/">Bloglines</a>. Basta cadastra-se inserir o endere�o do feed do iTEIA:</p>
        <ul>
            <li><strong>�udio</strong> - <a href="/feeds.php?formato=3"><?=ConfigVO::URL_SITE?>feeds.php?formato=3</a></li>
            <li><strong>V�deo</strong> - <a href="/feeds.php?formato=4"><?=ConfigVO::URL_SITE?>feeds.php?formato=4</a></li>
            <li><strong>Texto</strong> - <a href="/feeds.php?formato=1"><?=ConfigVO::URL_SITE?>feeds.php?formato=1</a></li>
            <li><strong>Imagem</strong> - <a href="/feeds.php?formato=2"><?=ConfigVO::URL_SITE?>feeds.php?formato=2</a></li>
            <li><strong>Eventos</strong> - <a href="/feeds.php?formato=6"><?=ConfigVO::URL_SITE?>feeds.php?formato=6</a></li>
            <li><strong>Not�cias</strong> - <a href="/feeds.php?formato=5"><?=ConfigVO::URL_SITE?>feeds.php?formato=5</a></li>
            <li><strong>Todos os conte�dos</strong> - <a href="/feeds.php?formato=10"><?=ConfigVO::URL_SITE?>feeds.php</a></li>
        </ul>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
