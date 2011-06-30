<?php
include_once('classes/bo/ConteudoHomeBO.php');
$chomebo = new ConteudoHomeBO(3, 10);
$topo_class = 'cat-audios';
$titulopagina = 'Áudios';
$ativa = 2;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Áudios</span></div>
    <div id="conteudo">
      <h2 class="midia">Áudios</h2>
	  <div id="rss"><a href="/feeds.php?formato=3" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
      <div id="recentes">
        <h3 class="mais"><span>Conteúdos</span> Mais Recentes</h3>
        <div class="conteudo-lista">
			<?=$chomebo->getHtmlUltima()?>
			<div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2" title="Listar conteúdos por ordem cronológica"><strong>Ver todos por ordem cronológica</strong></a></div>
        </div>
      </div>
      <div class="hr"><hr /></div>
      <div id="inferior" class="conteudo-lista">
        <div id="recomendados" class="coluna">
          <h3 class="mais"><span>Conteúdos</span> Mais Recomendados</h3>
		  <ul>
		  <?=$chomebo->getHtmlMaisRecomendados()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2&amp;ordem=1" title="Listar conteúdos por ordem de recomendação"><strong>Ver todos por ordem de recomendação</strong></a></div>
        </div>
        <div id="acessados" class="coluna">
          <h3 class="mais"><span>Conteúdos</span> Mais Acessados</h3>
          <ul>
		  <?=$chomebo->getHtmlMaisAcessadas()?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2&amp;ordem=2" title="Listar conteúdos por ordem de acesso"><strong>Ver todos por ordem de acesso</strong></a></div>
        </div>
	  </div>
      <div class="lateral">
      <?php include('includes/banners_lateral.php');?>
    </div>
  </div>
<?php
include ('includes/rodape.php');
