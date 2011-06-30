<?php
include_once('classes/bo/UsuarioBO.php');
$usrbo = new UsuarioBO;

$usuariosrecentes = $usrbo->getUsuarioMaisRecentes(1);
$usuariosativos = $usrbo->getUsuarioMaisAtivos(1);

$topo_class = 'cat-colaboradores';
$titulopagina = 'Colaboradores';
$ativa = 9;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Colaboradores</span></div>
    <div id="conteudo">
    <?php include('includes/canais.php');?>
      <h2 class="midia">Colaboradores</h2>
      <div id="ativos" class="principal">
        <p>Colaboradores são  entidades ou coletivos culturais com acesso direto para enviar conteúdo para o iTEIA. Entre eles estão os Pontos de Cultura do Programa Cultura Viva do Ministério da Cultura, espalhados por todo o Brasil, empresas e colecionadores dedicados à cultura popular.</p>
        <h3 class="mais"><span>Colaboradores</span> Mais Ativos</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
$i = 0;
foreach ($usuariosativos as $key => $value):

	if ((int)$value['cod']):
		$i++;
		$temul = false;
		if ($temcount == 1)
	
			echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
			<li<? if(($i==3)or($i==6)) { echo ' class="no-border"'; }?>>
				<?php if ($value['imagem']): ?>
				<div class="foto"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><img src="exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=a&amp;s=4" alt="Imagem do autor: <?=$value['nome'];?>" width="60" height="60" /></a></div>
				<?php endif; ?>
				<strong><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><?=$value['nome'];?></a></strong><br />
				<?php if ($value['cod_estado']): ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=10&amp;cidades=<?=$value['cod_cidade']?>" title="Listar colaboradores por cidade"><?=$value['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=10&amp;estados=<?=$value['cod_estado']?>" title="Listar colaboradores por estado"><?=$value['estado']?></a><br />
				<?php endif; ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;colaborador=<?=$value['cod'];?>" title="Listar os conteúdos deste colaborador" class="info"><?=$value['num_conteudo'];?> conteúdos</a>
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
	endif;
endforeach;
if (!$temul)
	echo '</ul>';
?>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=10&amp;ordem=3" title="Listar colaboradores"><strong>Ver todos</strong></a></div>
      </div>
      <div class="lateral">
        <div class="colaborador" id="cadastre"><strong>Ainda não é cadastrado?<br />
          <a href="/cadastro_colaborador" title="Cadastre-se como colaborador">Inscreva-se GRATUITAMENTE.</a></strong></div>
        <div id="recentes">
          <h3 class="mais"><span>Colaboradores</span> Mais Recentes</h3>
          <ul>
<?php
foreach ($usuariosrecentes as $key => $value):
	if ((int)$value['cod']):
?>
			<li<?=(!isset($usuariosrecentes[$key + 1]) ? ' class="no-border no-margin-b"' : '')?>>
				<?php if ($value['imagem']): ?>
				<div class="foto"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><img src="exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=a&amp;s=4" alt="Imagem do autor: <?=$value['nome'];?>" width="60" height="60" /></a></div>
				<?php endif; ?>
				<strong><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para página deste autor"><?=$value['nome'];?></a></strong><br />
				<?php if ($value['cod_estado']): ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=10&amp;cidades=<?=$value['cod_cidade']?>" title="Listar colaboradores por cidade"><?=$value['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=10&amp;estados=<?=$value['cod_estado']?>" title="Listar colaboradores por estado"><?=$value['estado']?></a><br />
				<?php endif; ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;colaborador=<?=$value['cod'];?>" title="Listar os conteúdos deste colaborador" class="info"><?=$value['num_conteudo'];?> conteúdos</a>
				<div class="hr"><hr /></div>
			</li>
<?php
	endif;
endforeach;
?>
          </ul>
          <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=10" title="Listar colaboradores"><strong>Ver todos</strong></a></div>
        </div>
      </div>
</div>
<?php
include ('includes/rodape.php');
