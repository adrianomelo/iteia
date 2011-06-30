<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/CanalBO.php');
$canalbo = new CanalBO;

$codcanal = (int)$_GET['cod'];
$dadoscanal = $canalbo->getCanalDados($codcanal);

$topo_class = 'cat-canais';
$titulopagina = 'Canais';
$ativa = 1;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/canais">Canais</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$dadoscanal['nome'];?></span></div>
    <div id="conteudo">
      <h2 class="midia"><?=$dadoscanal['nome'];?></h2>
		<div id="rss"><a href="/feeds.php?formato=8&amp;canal=<?=$codcanal?>" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
        <?php if($dadoscanal['descricao']):?>
		<div class="caption principal">
          <p><?=$dadoscanal['descricao'];?></p>
        </div>
		<?php endif; ?>
		<?php if ($dadoscanal['imagem']): ?>
		<div class="lateral">
			<img src="/exibir_imagem.php?img=<?=$dadoscanal['imagem'];?>&amp;tipo=1&amp;s=36" alt="Imagem do canal" width="269" class="foto" />
		</div>
		<?php endif; ?>
		<div class="principal">
		  
		<div id="controles" class="caption">
      <h3>Compartilhe</h3>

		<div id="bookmarks"> <a href="/bookmarks" class="link-oq">O que é isso?</a>
            <ul>
             <li id="b-twitter"><a href="http://twitter.com/home/?status=<?=urlencode(Util::bitly(ConfigVO::URL_SITE.'canal.php?cod='.$codcanal).' '.$titulo.' #iteia')?>">twitter</a></li>
               <li id="b-delicious"><a href="http://del.icio.us/post?url=<?=urlencode(ConfigVO::URL_SITE.'canal.php?cod='.$codcanal.' '.$titulo.' #iteia');?>">delicious</a></li>
				  <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=ConfigVO::URL_SITE.'canal.php?cod='.$codcanal;?>">facebook</a></li>
              <li id="b-yahoo"><a href="http://buzz.yahoo.com/buzz?targetUrl=<?=Util::bitly(ConfigVO::URL_SITE.'canal.php?cod='.$codcanal);?>">Yahoo buzz</a></li>
              <li id="b-digg"><a href="http://digg.com/submit?phase=2&amp;url=<?=Util::bitly(ConfigVO::URL_SITE.'canal.php?cod='.$codcanal);?>">digg it </a></li>
            </ul>
          </div>
		  </div>
		  
		<?=$canalbo->getConteudosRelacionados($codcanal)?>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;canal=<?=$codcanal?>&amp;formatos=2,3,4,5" title="Listar conteúdos deste canal"><strong>Ver todos</strong></a></div>
      </div>
      <div class="lateral">
        <div class="participantes-canal">
          <h3 class="relacionado">Autores relacionados</h3>
          <ul><?=$canalbo->getAutores($codcanal);?></ul>
          <div class="todos"><a href="/autores" title="Listar autores relacionados"><strong>Mais autores</strong></a></div>
        </div>
        <div class="tags-canal">
          <h3 class="relacionado">Tags relacionadas</h3>
         <div id="nuvem">
		 <?=$canalbo->getTags($codcanal);?>
            </div>
          <div class="todos"><a href="/tags" title="Ir para página de tags"><strong>Mais tags</strong></a></div>
        </div>
      </div>
    </div>
<?php
include ('includes/rodape.php');
