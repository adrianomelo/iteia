<?php
include_once('classes/bo/ConteudoHomeBO.php');
$chomebo = new ConteudoHomeBO(1, 4);
$topo_class = 'cat-textos';
$titulopagina = 'Textos';
$ativa = 4;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Textos</span></div>
    <div id="conteudo">
      <h2 class="midia">Textos</h2>
	  <div id="rss"><a href="/feeds.php?formato=1" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
      <div id="recentes">
        <h3 class="mais"><span>Conteúdos</span> Mais Recentes</h3>
        <div id="bloco1" class="principal">
		<?=$chomebo->getHtmlUltima()?>
           </div>
        <div id="bloco2">
          <ul>
			<?=$chomebo->getHtmlVejaMais()?>
          </ul>
        </div>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4" title="Listar conteúdos por ordem cronológica"><strong>Ver todos por ordem cronológica</strong></a></div>
      </div>
      <div class="hr">
        <hr />
      </div>
      <div id="inferior" class="conteudo-lista">
        <div id="recomendados" class="coluna">
          <h3 class="mais"><span>Conteúdos</span> Mais Recomendados</h3>
          <ul>
            <?=$chomebo->getHtmlMaisRecomendados()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;ordem=1" title="Listar conteúdos por ordem de recomendação"><strong>Ver todos por ordem de recomendação</strong></a></div>
        </div>
        <div id="acessados" class="coluna">
          <h3 class="mais"><span>Conteúdos</span> Mais Acessados</h3>
          <ul>
            <?=$chomebo->getHtmlMaisAcessadas()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;ordem=2" title="Listar conteúdos por ordem de acesso"><strong>Ver todos por ordem de acesso</strong></a></div>
        </div>
      </div>
      <div class="lateral">
      <?php include('includes/banners_lateral.php');?>
    </div>
  </div>
<?php
include ('includes/rodape.php');
