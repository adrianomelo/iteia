<?php
$topo_class = 'cat-colaboradores';
$titulopagina = htmlentities($colaborador['colaborador']['nome']);
$ativa = 9;
include ('includes/topo.php');
?>
<div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/colaboradores">Colaboradores</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$titulopagina;?></span></div>
    <div id="conteudo">
		<h2 class="midia">Colaboradores</h2>
		<div id="rss"><a href="/feeds.php?usuario=<?=$colaborador['colaborador']['cod_usuario']?>" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
		<div class="principal">
			<div id="usuario-descricao">
				<h1 class="midia no-margin-b"><?=$titulopagina;?></h1>
				<p><a href="/busca_action.php?buscar=1&amp;formatos=9,10&amp;cidades=<?=$colaborador['colaborador']['cod_cidade']?>" title="Listar autores e colaboradores por cidade"><?=$colaborador['colaborador']['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=9,10&amp;estados=<?=$colaborador['colaborador']['cod_estado']?>" title="Listar autores e colaboradores por estado"><?=$colaborador['colaborador']['sigla']?></a></p>
				<p><?=nl2br($colaborador['colaborador']['descricao']);?></p>
        </div>
        
		<div id="usuario-ficha">
			<?php if ($colaborador['colaborador']['imagem']): ?>
			<div class="foto"> <img src="/exibir_imagem.php?img=<?=$colaborador['colaborador']['imagem']?>&amp;tipo=c&amp;s=28" alt="Imagem do colaborador: <?=$titulopagina;?>" /></div>
			<?php endif; ?>
			<p><strong><?=$colaborador['colaborador']['num_arquivos_total'];?> Arquivo(s) aprovados</strong></p>
			<ul>
				<li><a href="/busca_action.php?buscar=1&amp;formatos=2&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>" title="Listar os conteúdos deste colaborador" class="audio"><?=$colaborador['colaborador']['num_audios'];?> &Aacute;udios</a></li>
				<li><a href="/busca_action.php?buscar=1&amp;formatos=3&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>" title="Listar os conteúdos deste colaborador" class="video"><?=$colaborador['colaborador']['num_videos'];?> V&iacute;deos</a></li>
				<li><a href="/busca_action.php?buscar=1&amp;formatos=4&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>" title="Listar os conteúdos deste colaborador" class="texto"><?=$colaborador['colaborador']['num_textos'];?> Textos</a></li>
				<li><a href="/busca_action.php?buscar=1&amp;formatos=5&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>" title="Listar os conteúdos deste colaborador" class="imagem"><?=$colaborador['colaborador']['num_imagens'];?> Imagens</a></li>
			</ul>
        </div>
		
		<?php if (count($colaborador['colaborador']['autores'])):?>
        <div id="autores-ativos">
			<h3 class="mais"><span>Lista de</span>Autores mais ativos vinculados a este colaborador</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($colaborador['colaborador']['autores'] as $key => $value):
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
			<li<?=(!isset($colaborador['colaborador']['autores'][$key + 1]) ? ' class="no-border"' : '')?>>
				<?php if ($value['imagem']): ?>
				<div class="foto"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><img src="/exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=a&amp;s=4" alt="Imagem do autor: <?=$value['nome'];?>" width="60" height="60" /></a></div>
				<?php endif; ?>
				<strong><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><?=$value['nome'];?></a></strong><br />
				<?php if ($value['cod_estado']): ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades=<?=$value['cod_cidade']?>" title="Listar autores por cidade"><?=$value['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados=<?=$value['cod_estado']?>" title="Listar autores por estado"><?=$value['sigla']?></a><br />
				<?php endif; ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor=<?=$value['cod_usuario'];?>" title="Listar os conteúdos deste autor" class="info"><?=$value['autor_num_conteudo'];?> conteúdos</a>
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
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=9&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>" title="Listar autores"><strong>Ver todos</strong></a></div>
    </div>
<?php endif; ?>
<?php if (count($colaborador['colaborador']['mais_acessados'])): ?>
        <div id="usuario-conteudo">
			<h3 class="mais"><span>Lista de </span> Conteúdos mais acessados</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($colaborador['colaborador']['mais_acessados'] as $key => $acessado):
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
			<li<?=((!isset($colaborador['colaborador']['mais_acessados'][$key + 1]) || $temcount == $colspan ) ? ' class="no-border"' : '')?>>
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
			<?=$acessado['autores'];?>
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
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;colaborador=<?=$colaborador['colaborador']['cod_usuario'];?>&amp;ordem=2" title="Listar conteúdos deste colaborador"><strong>Ver todos</strong></a></div>
        </div>
<?php endif; ?>
        <div id="usuario-contato">
          <h3 class="mais"><span>Lista de </span> Contatos</h3>
          <ul>
<?php if ($colaborador['colaborador']['email'] && $colaborador['colaborador']['mostrar_email']): ?>
    <li><strong>E-mail:</strong> <a href="mailto:<?=$colaborador['colaborador']['email'];?>"><?=$colaborador['colaborador']['email'];?></a></li>
<?php endif; ?>
<?php if ($colaborador['colaborador']['telefone']): ?>
    <li><strong>Telefone:</strong> <?=$colaborador['colaborador']['telefone'];?></li>
<?php endif; ?>
<?php if ($colaborador['colaborador']['telefone']): ?>
    <li><strong>Telefone:</strong> <?=$colaborador['colaborador']['telefone'];?></li>
<?php endif; ?>
<?php if ($colaborador['colaborador']['endereco']): ?>
   <li> <strong>Endereço:</strong> <?=$colaborador['colaborador']['endereco'];?>, <?=$colaborador['colaborador']['complemento'];?>, <?=$colaborador['colaborador']['bairro'];?> - <?=$colaborador['colaborador']['cidade'];?>-<?=$colaborador['colaborador']['sigla'];?></li>
<?php endif; ?>
<?php if ($colaborador['colaborador']['site']): ?>
    <li><strong>Site:</strong> http://<?=$colaborador['colaborador']['site'];?></li>
<?php endif; ?>
<?php foreach ($colaborador['colaborador']['comunicadores'] as $key => $cmm): ?>
            <li><strong><?=$cmm['comunicador']?></strong> - <?=$cmm['nome_usuario']?></li>
<?php endforeach; ?>            
          </ul>
          <strong><a href="/usuario_contato.php?cod=<?=$colaborador['colaborador']['cod_usuario'];?>" id="contactar">Entre em contato</a></strong>
        </div>
<?php if (count($colaborador['colaborador']['links'])): ?>
        <div id="usuario-site">
          <h3 class="mais"><span>Lista de </span> Sites relacionados</h3>
          <ul>
<?php foreach ($colaborador['colaborador']['links'] as $key => $links): ?>
            <li><strong><?=$links['site']?></strong> - Site: <a href="<?=$links['url']?>" target="_blank"><?=$links['url']?></a></li>
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
