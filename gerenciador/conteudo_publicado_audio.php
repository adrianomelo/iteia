<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codaudio = (int)$_GET['cod'];

include_once("classes/bo/AlbumAudioEdicaoBO.php");
$audiobo = new AlbumAudioEdicaoBO;

if ($codaudio) {
	$audiobo->setDadosCamposEdicao($codaudio);
	$postador = $audiobo->getPostadorConteudo($codaudio);
	$autores_ficha = $audiobo->getAutoresFichaConteudo($codaudio);
	$colaborador = $audiobo->getColaboradorConteudo($codaudio);
	$categoria = $audiobo->getCategoriaConteudo($codaudio);
	$segmento = $audiobo->getSegmentoConteudo($codaudio);
	$subarea = $audiobo->getSubAreaConteudo($codaudio);
	$conteudo_relacionado = $audiobo->getConteudoRelacionado($codaudio);
	$grupo_relacionado = $audiobo->getGrupoRelacionado($codaudio);
	$licenca = $audiobo->getLicenca($audiobo->getValorCampo('codlicenca'));
	$lista_audios = $audiobo->getAudiosAlbum($codaudio);
	$nome_colaborador_aprovacao = $audiobo->getColaboradorConteudoAprovado($audiobo->getValorCampo('codcolaborador'));
	$lista_colaborador_aprovacao = $audiobo->getListaColaboradoresAprovacao($codaudio);
}

$contbo = &$audiobo;

if (!$item_menu)
	$item_menu = "conteudo";
	
if (!$nao_carregar)
	$nao_carregar = "conteudo";
	
if (!$chapeu)
	$chapeu = 'Conte&uacute;do';
	
include('includes/topo.php');
?>
    <h2><?=$chapeu;?></h2>
<?php
if (!$nao_mostrar_situacao_acao):
	include('includes/conteudo_situacao_acao.php');
elseif ($mostrar_aguardando_aprovacao):
	include('includes/conteudo_situacao_aguardando_aprovacao.php');
elseif ($mostrar_lista_publica):
	include('includes/conteudo_situacao_lista_publica.php');
elseif ($mostrar_aprovado):
	include('includes/conteudo_situacao_aprovado.php');
elseif ($mostrar_reprovado):
	include('includes/conteudo_situacao_reprovado.php');
elseif ($mostrar_notificacao):
	include('includes/conteudo_situacao_notificacao.php');
elseif ($mostrar_recente):
	include('includes/conteudo_situacao_recente.php');
endif;

if ($_SESSION['logado_dados']['nivel'] == 2 || $_SESSION['logado_dados']['nivel'] == 5 || $_SESSION['logado_dados']['nivel'] == 6)
	if ($audiobo->getValorCampo('codautor') != $_SESSION['logado_dados']['cod']) $nao_exibir_adicionais = true;

?>
<script type="text/javascript" src="jscripts/conteudo.js"></script>
<!--<script language="javascript" type="text/javascript" src="jscripts/flashembed.min.js"></script>-->

<script type="text/javascript" src="/js/flowplayer-3.1.5/flowplayer-3.1.4.min.js"></script>

<script language="javascript" type="text/javascript" src="jscripts/audio.js"></script>

    <h3 class="titulo">&Aacute;udio cadastrado </h3>
    <div class="box">
      <div id="exibe_conteudo" class="separador" >
        <h3><?=$audiobo->getValorCampo('titulo');?></h3>
        <p><?=nl2br(Util::autoLink($audiobo->getValorCampo('descricao'), 'both', true));?></p>
        
        <ul id="playlist">
			<?php
			$i = 1;
			foreach ($lista_audios as $value):
			?>
          		<li><a href="javascript:playPause(<?=$i;?>);"><?=$value['titulo'];?></a> - <?=$value['tempo'];?></li>
        	<?php
			$i++;
			endforeach;
			?>
        </ul>
<script type="text/javascript">
var player = flowplayer("example", "/js/flowplayer-3.1.5/flowplayer-3.1.5.swf", {
		clip: {
			onStart: function(clip) {
			},
			onPause: function(clip) {
			},
			onPause: function(clip) {
			},
			onResume : function(clip) {
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
next: 'Próximo',
play: 'Tocar',
pause: 'Parar',
mute: 'Mudo',
unmute: 'Ligar o som',
}
}
},
		playlist:[
		<?php foreach ($lista_audios as $cod_audio => $audio): ?>
			{
				url: '<?=ConfigVO::URL_SITE.'conteudo/audios/'.$audio['audio'];?>'
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
var total_faixas = <?=count($lista_audios);?>;
</script>
		<div id="example" style="display:block;width:485px;height:24px;padding-left:135px;"></div>
		
      </div>
      <div class="separador"><strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
    	<img src="<?=Util::iif($audiobo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$audiobo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/audio.jpg");?>" width="124" height="124" />
	</div>
	
	<?php if (count($autores_ficha)): ?>
		<div id="autores2" class="separador"><strong>Autores deste conte&uacute;do:</strong>
	    	<ul>
	    	<?php foreach ($autores_ficha as $value): ?>
				<li><a href="<?=Util::iif($value['titulo'], ConfigVO::URL_SITE.$value['titulo'], 'javascript:void(0);');?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=$value['nome'];?></a> <?=$value['atividade'];?></li>
			<?php endforeach; ?>
			</ul>
	    </div>
    <?php endif;?>
	
    <div id="autores" class="separador">
      
		<strong>Postado por:</strong> <a href="<?=ConfigVO::URL_SITE.$postador['titulo'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=$postador['nome'];?></a><br />
        
		<strong>Autorizado por:</strong> <a href="<?=ConfigVO::URL_SITE.$colaborador['titulo'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste colaborador"><?=$colaborador['nome'];?></a><br />
        
		<strong>Data de publica&ccedil;&atilde;o: </strong><?=date('d/m/Y - H:i', strtotime($audiobo->getValorCampo('datahora')));?>
		
	</div>
        
      <div class="separador"><strong>Tipo da obra</strong>: <?=$categoria['nome'];?><br />
        <strong>Área de atuação: </strong> <?=$segmento['nome'];?><br />
        <strong>Sub-área: </strong> <?=$subarea['nome'];?><br />
        <strong>Tags: </strong> <?=str_replace(';', ',', $audiobo->getValorCampo('tags'));?></div>
        
      <div id="licensas" class="separador"><strong>A licen&ccedil;a desta obra ser&aacute;:</strong><br />
        <?php
        echo Util::getTipoLicenca($licenca);
        ?>
		</div>
        
      <div class="separador"><strong>Grupos em que este conte&uacute;do est&aacute; associado: </strong>
	  <?php if (!$nao_exibir_link_add_grupos): ?>
	  	<a href="grupo_associar.php?height=345&amp;width=305&amp;cod=<?=$codaudio;?>" title="Associar a um grupo" class="thickbox">clique aqui para associar a grupo(s)</a>
	  <?php endif; ?>
        <ul>
          <?php foreach ($grupo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['nome'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>
      <div class="separador"><strong>Conte&uacute;dos relacionados: </strong>
      <?php if (!$nao_exibir_link_add_relacionados): ?>
      	<a href="conteudo_relacionar.php?cod=<?=$codaudio;?>">clique aqui para vincular outros conte&uacute;dos</a>
      <?php endif; ?>
        <ul>
        <?php foreach ($conteudo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['titulo'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>
<?php if (!$nao_exibir_adicionais): ?>
      <a href="conteudo_edicao_audio.php?cod=<?=$codaudio;?>" title="Editar" class="bt">Editar</a>
<?php endif; ?>
	  </div>
<?php if (!$nao_exibir_adicionais): ?>
    <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <li><a href="conteudo_relacionar.php?cod=<?=$codaudio;?>">Relacionar a outros conte&uacute;dos</a></li>
        <li><a href="conteudo_adicionar_autores.php?cod=<?=$codaudio;?>">Adicionar  autores</a></li>
        <li><a href="grupo_associar.php?height=345&amp;width=305&amp;cod=<?=$codaudio;?>" title="Associar a um grupo" class="thickbox">Associar a um grupo</a></li>
        <li><a href="comentarios.php?cod=<?=$codaudio;?>">Gerenciar coment&aacute;rios</a></li>
        <?php if ($audiobo->getValorCampo('publicado')): ?>
        	<li><a href="<?=ConfigVO::URL_SITE.$audiobo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
        <?php endif; ?>
      </ul>
    </div>
<?php endif; ?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
