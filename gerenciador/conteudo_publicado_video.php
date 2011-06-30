<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/PlayerUtil.php");

$codvideo = (int)$_GET['cod'];

include_once("classes/bo/VideoEdicaoBO.php");
$videobo = new VideoEdicaoBO;

if ($codvideo) {
	$videobo->setDadosCamposEdicao($codvideo);
	$postador = $videobo->getPostadorConteudo($codvideo);
	$autores_ficha = $videobo->getAutoresFichaConteudo($codvideo);
	$colaborador = $videobo->getColaboradorConteudo($codvideo);
	$categoria = $videobo->getCategoriaConteudo($codvideo);
	$segmento = $videobo->getSegmentoConteudo($codvideo);
	$subarea = $videobo->getSubAreaConteudo($codvideo);
	$conteudo_relacionado = $videobo->getConteudoRelacionado($codvideo);
	$grupo_relacionado = $videobo->getGrupoRelacionado($codvideo);
	$licenca = $videobo->getLicenca($videobo->getValorCampo('codlicenca'));
	$nome_colaborador_aprovacao = $videobo->getColaboradorConteudoAprovado($videobo->getValorCampo('codcolaborador'));
	$lista_colaborador_aprovacao = $videobo->getListaColaboradoresAprovacao($codvideo);
}

$contbo = &$videobo;

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
	if ($videobo->getValorCampo('codautor') != $_SESSION['logado_dados']['cod']) $nao_exibir_adicionais = true;
	
?>
<script type="text/javascript" src="jscripts/conteudo.js"></script>

    <h3 class="titulo">V&iacute;deo cadastrado </h3>
    <div class="box">
      <div id="exibe_conteudo" class="separador" >
        <h3><?=$videobo->getValorCampo('titulo');?></h3>
        <p><?=nl2br(Util::autoLink($videobo->getValorCampo('descricao'), 'both', true));?></p>

<?php
if ($videobo->getValorCampo('tipo') == 1) {
	if (file_exists(ConfigVO::getDirVideo().'convertidos/'.$videobo->getValorCampo('arquivo_video'))) {
?>
        <object type="application/x-shockwave-flash" data="<?=ConfigVO::URL_SITE;?>FlowPlayerDark.swf" id="FlowPlayer" width="352" height="288">
          <param name="allowScriptAccess" value="sameDomain">
          <param name="movie" value="<?=ConfigVO::URL_SITE;?>FlowPlayerDark.swf">
          <param name="quality" value="high">
          <param name="scale" value="noScale">
          <param name="wmode" value="transparent">
          <param name="flashvars" value="config={videoFile: '<?=ConfigVO::getUrlVideo().$videobo->getValorCampo('arquivo_video');?>', autoBuffering: false, streamingServer: 'lighttpd',initialScale: 'orig' }">
        </object>
<?php
	}
	else {
?>
        <p><em>Este vídeo ainda não foi convertido.</em></p>
<?php
	}
} elseif ($videobo->getValorCampo('tipo') == 2) {
	echo PlayerUtil::playerVideo($videobo->getValorCampo('link_video'), 2);
}
?>

      </div>
      <div class="separador"><strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
    	<img src="<?=Util::iif($videobo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$videobo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/video.jpg");?>" width="124" height="124" />
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
        
		<strong>Data de publica&ccedil;&atilde;o: </strong><?=date('d/m/Y - H:i', strtotime($videobo->getValorCampo('datahora')));?>
		
	</div>

      <div class="separador"><strong>Tipo da obra</strong>: <?=$categoria['nome'];?><br />
        <strong>Área de atuação: </strong> <?=$segmento['nome'];?><br />
        <strong>Sub-área: </strong> <?=$subarea['nome'];?><br />
        <strong>Tags: </strong> <?=str_replace(';', ',', $videobo->getValorCampo('tags'));?></div>

      <div id="licensas" class="separador"><strong>Licen&ccedil;a:</strong><br />
        <?php
        echo Util::getTipoLicenca($licenca);
        ?>
		</div>

      <div class="separador"><strong>Grupos em que este conte&uacute;do est&aacute; associado: </strong>
	  <?php if (!$nao_exibir_link_add_grupos): ?>
	  	<a href="grupo_associar.php?height=345&amp;width=305&amp;cod=<?=$codvideo;?>" title="Associar a um grupo" class="thickbox">clique aqui para associar a grupo(s)</a>
	  <?php endif; ?>
        <ul>
          <?php foreach ($grupo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['nome'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>
      <div class="separador"><strong>Conte&uacute;dos relacionados: </strong>
      <?php if (!$nao_exibir_link_add_relacionados): ?>
      	<a href="conteudo_relacionar.php?cod=<?=$codvideo;?>">clique aqui para vincular outros conte&uacute;dos</a>
      <?php endif; ?>
        <ul>
        <?php foreach ($conteudo_relacionado as $value): ?>
          <li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['titulo'];?></a></li>
        <?php endforeach; ?>
        </ul>
      </div>
<?php if (!$nao_exibir_adicionais): ?>
      <a href="conteudo_edicao_video.php?cod=<?=$codvideo;?>" title="Editar" class="bt">Editar</a>
<?php endif; ?>
</div>
<?php if (!$nao_exibir_adicionais): ?>
    <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <li><a href="conteudo_relacionar.php?cod=<?=$codvideo;?>">Relacionar a outros conte&uacute;dos</a></li>
        <li><a href="conteudo_adicionar_autores.php?cod=<?=$codvideo;?>">Adicionar  autores</a></li>
        <li><a href="grupo_associar.php?height=345&amp;width=305&amp;cod=<?=$codvideo;?>" title="Associar a um grupo" class="thickbox">Associar a um grupo</a></li>
        <li><a href="comentarios.php?cod=<?=$codvideo;?>">Gerenciar coment&aacute;rios</a></li>
        <?php if ($videobo->getValorCampo('publicado')): ?>
        	<li><a href="<?=ConfigVO::URL_SITE.$videobo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
        <?php endif; ?>
      </ul>
    </div>
<?php endif; ?>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
