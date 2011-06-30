<?php
include_once('classes/bo/ConteudoHomeBO.php');
$chomebo = new ConteudoHomeBO(1, 4);
$topo_class = 'cat-textos';
$titulopagina = 'Textos';
$ativa = 4;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Textos</span></div>
    <div id="conteudo">
      <h2 class="midia">Textos</h2>
	  <div id="rss"><a href="/feeds.php?formato=1" title="Assine e receba atualiza��es">Assine</a><br /> <a href="/rss.php" title="Saiba o que � RSS e como utilizar">O que � isso?</a></div>
      <div id="recentes">
        <h3 class="mais"><span>Conte�dos</span> Mais Recentes</h3>
        <div id="bloco1" class="principal">
		<?=$chomebo->getHtmlUltima()?>
           </div>
        <div id="bloco2">
          <ul>
			<?=$chomebo->getHtmlVejaMais()?>
          </ul>
        </div>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4" title="Listar conte�dos por ordem cronol�gica"><strong>Ver todos por ordem cronol�gica</strong></a></div>
      </div>
      <div class="hr">
        <hr />
      </div>
      <div id="inferior" class="conteudo-lista">
        <div id="recomendados" class="coluna">
          <h3 class="mais"><span>Conte�dos</span> Mais Recomendados</h3>
          <ul>
            <?=$chomebo->getHtmlMaisRecomendados()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;ordem=1" title="Listar conte�dos por ordem de recomenda��o"><strong>Ver todos por ordem de recomenda��o</strong></a></div>
        </div>
        <div id="acessados" class="coluna">
          <h3 class="mais"><span>Conte�dos</span> Mais Acessados</h3>
          <ul>
            <?=$chomebo->getHtmlMaisAcessadas()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;ordem=2" title="Listar conte�dos por ordem de acesso"><strong>Ver todos por ordem de acesso</strong></a></div>
        </div>
      </div>
      <div class="lateral">
      <?php include('includes/banners_lateral.php');?>
    </div>
  </div>
<?php
include ('includes/rodape.php');
