<?php
$faixas_audios = $conteudo['dados_faixas_audios'];
$topo_class = 'cat-audios';
$titulo = htmlentities($conteudo['conteudo']['titulo']);
$titulopagina = $titulo.' | �udios';
$ativa = 2;
$js_player = true;

$jsconteudo = 1 ;
$jsautores = 1;
$js_galeria = 1;
$js_texto = 1;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <a href="/audios">�udios</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$titulo?></span></div>
    <div id="conteudo">
      <h2 class="midia">�udios</h2>
      <div class="principal">
		<?=$conteudo['canal']?>
        <h1 class="midia"><?=$titulo?></h1>
		<div class="capa cd120">
		<?php if($conteudo['conteudo']['imagem']): ?>
        <img src="/exibir_imagem.php?img=<?=$conteudo['conteudo']['imagem']?>&amp;tipo=1&amp;s=23" width="120" height="120" />
		<?php endif; ?>
		</div>
        <div id="audio-descricao">
			<strong>Tocando:</strong> <tt id="tocando"></tt><br />
			<strong>Tempo:</strong> <tt id="tempo"></tt>
		</div>
<script type="text/javascript">
var player = flowplayer("example", "/js/flowplayer-3.1.5/flowplayer-3.1.5.swf", {
		clip: {
			onStart: function(clip) {
			   limparCss();
			   $('#tocando').html(clip.title);
			   $('#tempo').html(clip.time);
			   $('#faixa_'+ (parseInt(clip.index) + 1)).addClass('playing');
			},
			onPause: function(clip) {
				if (clip.isInStream) {
					limparCss();
				}
			},
			onPause: function(clip) {
			   limparCss();
			},
			onResume : function(clip) {
			   limparCss();
			   $('#faixa_'+ (parseInt(clip.index) + 1)).addClass('playing');
			},
		},
		plugins: {
			controls: {
				url: '/js/flowplayer-3.1.5/flowplayer.controls-3.1.5.swf',
				playlist: false,
				loop: false,
				fullscreen : false,
				scrubber: true,
				tooltips: {
				buttons: true,
				fullscreen: 'Tela cheia',
				fullscreenExit: 'Sair',
				previous: 'Anterior',
				next: 'Pr�ximo',
				play: 'Tocar',
				pause: 'Parar',
				mute: 'Mudo',
				unmute: 'Ligar o som',
			}
		}
	},
		playlist:[
		<?php foreach ($faixas_audios as $cod_audio => $audio): ?>
			{
				url: '<?=$audio['audio'];?>',
				title: '<?=($audio['titulo'] ? $audio['titulo'] : $audio['arquivo_original']);?>',
				time: '<?=$audio['tempo'];?>'
			},
		<?php endforeach; ?>

		]
});

function playPause(index) {
	var obj = $f("example");
	var ind = parseInt(index) - 1;
	if (obj.getClip().index == ind) {
		if (obj.isPaused())
			obj.resume();
		else
			obj.pause();
	} else {
		obj.play(ind);
	}
}
var total_faixas = <?=count($faixas_audios);?>;
</script>
		<div id="example" style="display:block;width:485px;height:24px;padding-left:135px;"></div>
        <div id="playlist">
          <ul class="items">
<?php
$i = 1;
foreach ($faixas_audios as $cod_audio => $audio):
?>
				<li>
					<a href="javascript:playPause(<?=$i;?>);" class="playing" id="faixa_<?=$i;?>"><span><?=htmlentities($audio['titulo']);?> - <?=$audio['tempo'];?></span></a>
					<?php if ($conteudo['conteudo']['cod_licenca'] < 8): ?>
					<a href="/salvar.php?c=<?=$conteudo['conteudo']['cod_conteudo'];?>&amp;f=<?=intval($conteudo['conteudo']['cod_formato']);?>&amp;i=<?=$audio['cod_audio'];?>" class="baixar">baixar</a>
					<?php endif; ?>
				</li>
<?php
	$i++;
endforeach;
?>
			</ul>
        </div>
        <div id="funcoes">
          <div id="views">Visualiza��es: <?=number_format(intval($conteudo['conteudo']['num_acessos']),'0','.','.');?></div>
          <div id="copie">
            <label for="embed">Copie para o seu site:</label>
<?php
$audio_embed = '<object id="flowplayer" width="450" height="350" data="'.ConfigVO::URL_SITE.'js/flowplayer-3.1.5/flowplayer-3.1.5.swf" type="application/x-shockwave-flash"><param name="movie" value="'.ConfigVO::URL_SITE.'js/flowplayer-3.1.5/flowplayer-3.1.5.swf" /><param name="allowfullscreen" value="true" /><param name="flashvars" value=\'config={"playList":[';
  foreach($faixas_audios as $cod_audio => $audio)
	$audio_embed .= '{ "url":"'.$audio['audio'].'" },';
  $audio_embed .= ']}\' /></object>';
?>
			<input type="text" class="txt" id="embed" value="<?=htmlentities($audio_embed)?>" />
          </div>
          <div id="vote" class="no-border">Gostou?! Ent�o vote!
            <ul>
              <li id="vote-sim"><tt id="voto1"><?=intval($conteudo['conteudo']['num_recomendacoes']);?></tt> <span>pessoas votaram</span> <a href="javascript:;" onclick="javascript:recomendar(<?=intval($conteudo['conteudo']['cod_conteudo']);?>, <?=intval($conteudo['conteudo']['cod_formato']);?>, 1);">Sim</a> </li>
              <li id="vote-nao" class="no-margin-r"><tt id="voto2"><?=intval($conteudo['conteudo']['num_negativos']);?></tt> <span>pessoas votaram</span> <a href="javascript:;" onclick="javascript:recomendar(<?=intval($conteudo['conteudo']['cod_conteudo']);?>, <?=intval($conteudo['conteudo']['cod_formato']);?>, 2);">N�o</a></li>
            </ul>
        </div>
        </div>
        <div id="controles">
			<ul id="opcoes">
				<?php if ($conteudo['conteudo']['cod_licenca'] < 8): ?>
				<li id="baixe"><a href="/salvar.php?c=<?=intval($conteudo['conteudo']['cod_conteudo']);?>&amp;f=<?=intval($conteudo['conteudo']['cod_formato']);?>">Baixe o �lbum completo</a></li>
				<?php endif; ?>
				<?php if ($conteudo['permitir_comentarios']): ?>
				<li id="comente"><a href="#comentar">Comente</a> (<?=$conteudo['comentarios'];?>)</li>
				<?php endif; ?>
				<li id="compartilhe"><a href="#bookmark">Compartilhe</a></li>
				<li id="denuncie" class="no-border"><a href="/denuncie.php?conteudo=<?=$conteudo['conteudo']['cod_conteudo'];?>">Denuncie</a></li>
			</ul>
			<div id="bookmarks"> <a href="/bookmarks" class="link-oq">O que � isso?</a>
            <ul>
             <li id="b-twitter"><a href="http://twitter.com/home/?status=<?=urlencode(Util::bitly($conteudo['compartilhar']).' '.$titulo.' #iteia')?>">twitter</a></li>
               <li id="b-delicious"><a href="http://del.icio.us/post?url=<?=urlencode($conteudo['compartilhar'].' '.$titulo.' #iteia');?>">delicious</a></li>
				  <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=$conteudo['compartilhar'];?>">facebook</a></li>
              <li id="b-yahoo"><a href="http://buzz.yahoo.com/buzz?targetUrl=<?=Util::bitly($conteudo['compartilhar']);?>">Yahoo buzz</a></li>
              <li id="b-digg"><a href="http://digg.com/submit?phase=2&amp;url=<?=Util::bitly($conteudo['compartilhar']);?>">digg it </a></li>
            </ul>
          </div>
        </div>
		</div>
		<div class="lateral">
        <div id="ficha">
          <div class="trecho"><strong class="trecho-titulo">Descri��o:</strong>
            <p><?=nl2br(Util::autoLink($conteudo['conteudo']['descricao']));?></p>
          </div>
<?php if ($conteudo['autores_ficha_tecnica'] && !$conteudo['exibir_unico']): ?>
<div id="criado" class="trecho">
	<strong class="trecho-titulo">Esse conte�do foi criado por:</strong>
	<? //=$conteudo['autores_ficha_tecnica'];?>
	<? $retorno=$conteudo['autores_ficha_tecnica']; print($retorno[1]);?>
	<div class="todos"></div>

</div>
<?php endif; ?>
<div class="trecho">
<?php if (!$conteudo['autores_ficha_tecnica'] || $conteudo['exibir_unico']): ?>
	<strong class="trecho-titulo">Esse conte�do foi criado e postado por:</strong>
<?php else: ?>
	<strong class="trecho-titulo">Postado por:</strong>
<?php endif; ?>
	<?=$conteudo['autores'];?>
</div>
		<div class="trecho"><strong class="trecho-titulo">Autorizado por:</strong>
			<p class="no-margin-b"><a href="/<?=$conteudo['conteudo']['url_colaborador'];?>" title="Ir para p�gina deste colaborador"><?=strip_tags($conteudo['conteudo']['colaborador']);?></a> em <?=date('d.m.Y', strtotime($conteudo['conteudo']['data_cadastro'])).' &agrave;s '.date('H\\hi', strtotime($conteudo['conteudo']['data_cadastro']))?></p>
        </div>
        <div class="trecho"><strong class="trecho-titulo">Direitos Autorais:</strong>
			<div class="selos-cc"><?=$conteudo['licenca'];?></div>
        </div>
        <div class="trecho"><strong class="trecho-titulo">Tags:</strong>
			<div id="nuvem"><?=$conteudo['tags'];?></div>
            <div class="todos"><a href="/tags" title="Ir para p�gina de tags"><strong>Outras tags</strong></a></div>
        </div>
		<div class="trecho"><strong class="trecho-titulo">Este Conte�do faz parte dos canais:</strong>
            <div><?=$conteudo['canal'];?></div>
            <div class="todos"><a href="/canais" title="Ir para p�gina de canais"><strong>Outros canais</strong></a></div>
        </div>
    </div>
<?php if (count($conteudo['maisconteudo_autores'])): ?>
    <div id="mais-conteudos">
        <h3>Mais conte�dos desse autor</h3>
        <ul>
		<?php foreach ($conteudo['maisconteudo_autores'] as $key => $relacionado_autores): ?>
				<li<?=((!isset($relacionado_autores[$key + 1])) ? ' class="no-border"' : '')?>>
				<?php if ($relacionado_autores['imagem']): ?>
				<div class="capa"><span class="<?=Util::getIconeConteudo($relacionado_autores['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($relacionado_autores['cod_formato']);?>" title="Ir para p�gina do conteudo">Textos</a></span> <a href="/<?=$relacionado_autores['url'];?>/" title="Ir para p�gina deste conte�do"><img src="/exibir_imagem.php?img=<?=$relacionado_autores['imagem']?>&amp;tipo=<?=$relacionado_autores['cod_formato']?>&amp;s=27" alt="Descri��o da imagem" width="60" /></a></div>
				<?php elseif ($relacionado_autores['imagem_album']): ?>
				<div class="capa"><span class="<?=Util::getIconeConteudo($relacionado_autores['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($relacionado_autores['cod_formato']);?>" title="Ir para p�gina do conteudo">Textos</a></span> <a href="/<?=$relacionado_autores['url'];?>/" title="Ir para p�gina deste conte�do"><img src="/exibir_imagem.php?img=<?=$relacionado_autores['imagem_album']?>&amp;tipo=2&amp;s=27" alt="Descri��o da imagem" width="60" /></a></div>
				<?php else: ?>
				<div class="capa"><span class="<?=Util::getIconeConteudo($relacionado_autores['cod_formato']);?> no-image"><a href="/<?=Util::getFormatoConteudoBusca($relacionado_autores['cod_formato']);?>" title="Ir para p�gina do conteudo">Textos</a></span></div>
				<?php endif; ?>
				<strong><a href="/<?=$relacionado_autores['url'];?>/" title="Ir para p�gina deste conte�do"><?=Util::cortaTexto($relacionado_autores['titulo'], 60);?></a></strong><br />
				<?=Util::getHtmlListaAutores($relacionado_autores['cod_conteudo']);?>
				<div class="hr"><hr /></div>
			</li>
		<?php endforeach; ?>
        </ul>
        <div class="todos no-padding-b"><a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;conteudo=<?=$conteudo['conteudo']['cod_conteudo']?>" title="Listar conte�dos deste autor"><strong>Ver todos</strong></a></div>
    </div>
<?php endif;?>
</div>
<?php if (count($conteudo['relacionado'])): ?>
	<div id="relacionados" class="principal">
	<h3 class="mais"><span>Lista dos</span> Conte�dos relacionados</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($conteudo['relacionado'] as $key => $acessado):
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
		<li<?=((!isset($autor['autor']['relacionado'][$key + 1]) || $temcount == $colspan ) ? ' class="no-border"' : '')?>>
			<?php if ($acessado['imagem']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para p�gina do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para p�gina deste conte�do"><img src="/exibir_imagem.php?img=<?=$acessado['imagem']?>&amp;tipo=<?=$acessado['cod_formato']?>&amp;s=27" alt="Descri��o da imagem" width="60" /></a></div>
            <?php elseif ($acessado['imagem_album']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para p�gina do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para p�gina deste conte�do"><img src="/exibir_imagem.php?img=<?=$acessado['imagem_album']?>&amp;tipo=2&amp;s=27" alt="Descri��o da imagem" width="60" /></a></div>
            <?php else: ?>
			<div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?> no-image"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>.php" title="Ir para p�gina do conteudo">Textos</a></span></div>
			<?php endif; ?>
            <strong><a href="/<?=$acessado['url'];?>" title="Ir para p�gina deste conte�do"><?=Util::cortaTexto($acessado['titulo'], 60);?></a></strong><br />
			<?=Util::getHtmlListaAutores($acessado['cod_conteudo']);?>
            <div class="hr"><hr /></div>
        </li>
<?php
	if ($temcount == $colspan):
		$temcount -= $colspan;
		echo '</ul>';
		$temul = true;
		$cont++;
	endif;
	$temcount++;
endforeach;
if (!$temul)
	echo '</ul>';
?>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;relacionado=<?=$conteudo['conteudo']['cod_conteudo']?>" title="Listar conte�dos relacionados"><strong>Ver todos</strong></a></div>
    </div>
<?php endif; ?>
<?php if ($conteudo['permitir_comentarios']): ?>
	<div id="comentarios" class="principal">
		<div id="carrega_comentarios"></div>
    </div>
    <div id="comentar" class="principal">
		<form action="javascript:;" id="formcomentario" name="formcomentario">
			<fieldset>
				<legend>Deixe um coment�rio</legend>
				<div id="resposta_comentario"></div>
				<input type="hidden" value="<?=$conteudo['conteudo']['cod_conteudo']?>" name="cod_conteudo" id="cod1" />
				<label for="comentario">Coment�rio:</label><br />
				<textarea id="comentario" name="comentario" cols="30" rows="5"></textarea><br />
				<label for="seu-nome">Seu nome:</label><br />
				<input type="text" id="seu-nome" name="nome" class="txt" /><br />
				<label for="seu-email">Seu e-mail (n�o ser� publicado):</label><br />
				<input type="text" id="seu-email" name="email" class="txt" /><br />
				<label for="seu-site">Site / Url (opcional):</label><br />
				<input type="text" id="seu-site" name="site" class="txt" /><br />
				<input class="btn" type="image" onclick="javascript:enviarComentario();" src="/img/botoes/bt_enviar.gif" />
			</fieldset>
        </form>
      </div>
	</div>
<script type="text/javascript">
loadComentarios(<?=$conteudo['conteudo']['cod_conteudo'];?>);
</script>
<?php endif; ?>
<?php
include ('includes/rodape.php');
