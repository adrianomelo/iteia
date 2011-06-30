<?php
include_once('classes/bo/HomeExibicaoBO.php');
$homebo = new HomeExibicaoBO;

$topo_class = 'index';
$js_jquery_ui = 1;
$js_index = true;
$titulopagina_index = 'iTEIA - Rede Colaborativa de Cultura, Arte e Informação';
include ('includes/topo.php');
?>
<div id="conteudo">
      <div id="destaques" class="principal">
        <!--<h2 class="midia">Destaques</h2>-->
        <div id="featured" >
          <?=$homebo->getHtmlConteudoHome()?>
        </div>
      </div>
      <div class="lateral">
        <div id="sobre">
          <h3 class="relacionado">Sobre o iTEIA</h3>
          <p>O Portal iTEIA tem como missão ser o acervo da produção multimidia de centros culturais nacionais e internacionais, integrando e relacionando conteúdos das redes atuais, como por exemplo, o projeto Pontos de Cultura e o programa Casa Brasil.</p>
          <div class="todos"><a href="/projeto.php" title="Conheça o projeto iTEIA"><strong>Saiba mais</strong></a></div>
        </div>
		<?php include('includes/banners_lateral.php');?>
      </div>
		<div id="jornal" class="principal">
			<h2 class="midia">Jornal <span class="azul">i</span>T<span class="verde">E</span><span class="amarelo">I</span><span class="preto">A</span></h2>
			<?=$homebo->getHtmlUltimasNoticias()?>
			<div class="todos"><a href="/noticias.php" title="Ir para página de notícias"><strong>Mais notícias</strong></a></div>
		</div>
      <div id="inferior" class="principal">
        <?=$homebo->getHtmlConteudoCanais()?>
      </div>
      <div class="lateral">
        <div class="eventos-canal">
          <h3 class="relacionado">Eventos da semana</h3>
		  <?=$homebo->getHtmlAgenda()?>
          <div class="todos"><a href="/eventos" title="Ir para página de eventos"><strong>Mais eventos</strong></a></div>
        </div>
        <div class="participantes-canal">
          <h3 class="relacionado">Quem faz</h3>
          <?=$homebo->getUsuarios()?>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=9,10" title="Listar autores e colaboradores"><strong>Mais</strong></a></div>
        </div>
      </div>
    </div>
<?php
include ('includes/rodape.php');
