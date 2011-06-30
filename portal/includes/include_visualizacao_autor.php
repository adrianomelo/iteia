<?php
$topo_class = 'cat-autores';
$titulopagina = htmlentities($autor['autor']['nome']);
$ativa = 8;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/autores">Autores</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$titulopagina;?></span></div>
    <div id="conteudo">
	  <h2 class="midia">Autores</h2>
	  <div id="rss"><a href="/feeds.php?usuario=<?=$autor['autor']['cod_usuario']?>" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
      <div class="principal">
        <div id="usuario-descricao">
			<h1 class="midia no-margin-b"><?=$titulopagina;?></h1>
			<p><a href="/busca_action.php?buscar=1&amp;formatos=9,10&amp;cidades=<?=$autor['autor']['cod_cidade']?>" title="Listar autores e colaboradores por cidade"><?=$autor['autor']['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=9,10&amp;estados=<?=$autor['autor']['cod_estado']?>" title="Listar autores e colaboradores por estado"><?=$autor['autor']['sigla']?></a><br/>
<?php
if (is_array($autor['autor']['colaboradores']) && count($autor['autor']['colaboradores'])) {
?>
			Vinculado ao colaborador:
			<?php foreach ($autor['autor']['colaboradores'] as $colaborador): ?>
			<a href="/<?=$colaborador['url'];?>" title="Ir para página do colaborador" id="vinculado"><?=$colaborador['nome'];?></a>
			<?php endforeach; ?>
<?php
}
?>
			</p>
			<p><?=nl2br(strip_tags($autor['autor']['descricao']));?></p>
        </div>
        <div id="usuario-ficha">
<?php if ($autor['autor']['imagem']): ?>
	<div class="foto"> <img src="/exibir_imagem.php?img=<?=$autor['autor']['imagem']?>&amp;tipo=c&amp;s=28" alt="Imagem do colaborador: <?=$titulopagina;?>" /></div>
<?php endif; ?>
          <p><strong><?=$autor['autor']['num_arquivos_total'];?> Arquivo(s) enviados</strong></p>
          <ul>
            <li><a href="/busca_action.php?buscar=1&amp;formatos=2&amp;autor=<?=$autor['autor']['cod_usuario'];?>" title="Listar os conteúdos deste autor" class="audio"><?=$autor['autor']['num_audios'];?> &Aacute;udios</a></li>
			<li><a href="/busca_action.php?buscar=1&amp;formatos=3&amp;autor=<?=$autor['autor']['cod_usuario'];?>" title="Listar os conteúdos deste autor" class="video"><?=$autor['autor']['num_videos'];?> V&iacute;deos</a></li>
			<li><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;autor=<?=$autor['autor']['cod_usuario'];?>" title="Listar os conteúdos deste autor" class="texto"><?=$autor['autor']['num_textos'];?> Textos</a></li>
			<li><a href="/busca_action.php?buscar=1&amp;formatos=5&amp;autor=<?=$autor['autor']['cod_usuario'];?>" title="Listar os conteúdos deste autor" class="imagem"><?=$autor['autor']['num_imagens'];?> Imagens</a></li>
          </ul>
        </div>
<?php if (count($autor['autor']['mais_acessados'])): ?>
        <div id="usuario-conteudo">
          <h3 class="mais"><span>Lista de </span> Conteúdos mais acessados</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($autor['autor']['mais_acessados'] as $key => $acessado):
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
		<li<?=((!isset($autor['autor']['mais_acessados'][$key + 1]) || $temcount == $colspan ) ? ' class="no-border"' : '')?>>
			<?php if ($acessado['cod_formato'] == 4):
			$imagemvideo = PlayerUtil::getImagemVideo($acessado['video']['arquivo'], $acessado['video']['link'], $acessado['imagem'], 27, 60, 45);
			?>
			<div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']).(!$imagemvideo ? ' no-image' : '');?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><?=$imagemvideo?></a></div>
			<?php else: ?>
			  <?php if ($acessado['imagem']): ?>
			  <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem']?>&amp;tipo=<?=$acessado['cod_formato']?>&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
			  <?php elseif ($acessado['imagem_album']): ?>
			  <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem_album']?>&amp;tipo=2&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
			  <?php else: ?>
			  <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?> no-image"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span></div>
			  <?php endif; ?>
			<?php endif; ?>
			<?=$acessado['canal']?>
            <strong><a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><?=Util::cortaTexto($acessado['titulo'], 60);?></a></strong><br />
			<div class="views">Visualizações: <?=$acessado['num_acessos'];?></div>
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
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor=<?=$autor['autor']['cod_usuario'];?>&amp;ordem=2" title="Listar conteúdos deste autor"><strong>Ver todos</strong></a></div>
        </div>
<?php endif; ?>
        <div id="usuario-contato">
         <h3 class="mais"><span>Lista de </span> Contatos</h3>
          <!--<ul>
<?php if ($autor['autor']['email'] && $autor['autor']['mostrar_email']): ?>
    <li><strong>E-mail:</strong> <a href="mailto:<?=$autor['autor']['email'];?>"><?=$autor['autor']['email'];?></a></li>
<?php endif; ?>
<?php if ($autor['autor']['telefone']): ?>
    <li><strong>Telefone:</strong> <?=$autor['autor']['telefone'];?></li>
<?php endif; ?>
<?php if ($autor['autor']['telefone']): ?>
    <li><strong>Telefone:</strong> <?=$autor['autor']['telefone'];?></li>
<?php endif; ?>
<?php if ($autor['autor']['endereco']): ?>
   <li> <strong>Endereço:</strong> <?=$autor['autor']['endereco'];?>, <?=$autor['autor']['complemento'];?>, <?=$autor['autor']['bairro'];?> - <?=$autor['autor']['cidade'];?>-<?=$autor['autor']['sigla'];?></li>
<?php endif; ?>
<?php if ($autor['autor']['site']): ?>
    <li><strong>Site:</strong> http://<?=$autor['autor']['site'];?></li>
<?php endif; ?>
<?php foreach ($autor['autor']['comunicadores'] as $key => $cmm): ?>
            <li><strong><?=$cmm['comunicador']?></strong> - <?=$cmm['nome_usuario']?></li>
<?php endforeach; ?>
          </ul>-->
          <strong><a href="/usuario_contato.php?cod=<?=$autor['autor']['cod_usuario'];?>" id="contactar">Entre em contato</a></strong>
        </div>
<?php if (count($autor['autor']['links'])): ?>
        <div id="usuario-site">
          <h3 class="mais"><span>Lista de </span> Sites relacionados</h3>
          <ul>
<?php foreach ($autor['autor']['links'] as $key => $links): ?>
            <li><strong><?=$links['site']?></strong> -  <a href="<?=$links['url']?>" target="_blank"><?=$links['url']?></a></li>
<?php endforeach; ?>
          </ul>
        </div>
<?php endif; ?>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
</div>
<?php
include ('includes/rodape.php');
