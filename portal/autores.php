<?php
include_once('classes/bo/UsuarioBO.php');
$usrbo = new UsuarioBO;

$usuariosrecentes = $usrbo->getUsuarioMaisRecentes(2);
$usuariosativos = $usrbo->getUsuarioMaisAtivos(2);

$topo_class = 'cat-autores';
$titulopagina = 'Autores';
$ativa = 8;
$js_sem_jquery = true;
include ('includes/topo.php');
?>
<div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Autores</span></div>
    <div id="conteudo">
		<?php include('includes/canais.php');?>
		<h2 class="midia">Autores</h2>
		<div id="ativos" class="principal">
			<p>� quem produz os conte�dos que ir�o ao ar, a figura mais importante da rede. Cada indiv�duo ter� direito a uma p�gina com seu nome, que ser� administrada por ele. Exemplos: Ariano Suassuna (escritor), J. Borges (xilogravurista), Fred 04 (m�sico), Mestre Meia-noite (dan�arino), Hermila Guedes (atriz), sem esquecer de voc�, do seu vizinho baterista, do seu amigo que faz quadrinhos, etc</p>
			<h3 class="mais"><span>Autores</span> Mais Ativos</h3>
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
				<div class="foto"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para p�gina deste autor"><img src="exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=a&amp;s=4" alt="Imagem do autor: <?=$value['nome'];?>" width="60" height="60" /></a></div>
				<?php endif; ?>
				<strong><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para p�gina deste autor"><?=$value['nome'];?></a></strong><br />
				<?php if ($value['cod_estado']): ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades=<?=$value['cod_cidade']?>" title="Listar autores por cidade"><?=$value['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados=<?=$value['cod_estado']?>" title="Listar autores por estado"><?=$value['estado']?></a><br />
				<?php endif; ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor=<?=$value['cod'];?>" title="Listar os conte�dos deste autor" class="info"><?=$value['num_conteudo'];?> conte�dos</a>
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
			<div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=9&amp;ordem=3" title="Listar autores"><strong>Ver todos</strong></a></div>
		</div>
		<div class="lateral">
			<div class="autor" id="cadastre"><strong>Ainda n�o � cadastrado?<br />
			<a href="/cadastro_autor" title="Cadastre-se como autor">Inscreva-se GRATUITAMENTE.</a></strong></div>
			<div id="recentes">
				<h3 class="mais"><span>Autores</span> Mais Recentes</h3>
				<ul>
<?php
foreach ($usuariosrecentes as $key => $value):
	if ((int)$value['cod']):
?>
			<li<?=(!isset($usuariosrecentes[$key + 1]) ? ' class="no-border no-margin-b"' : '')?>>
				<?php if ($value['imagem']): ?>
				<div class="foto"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para p�gina deste autor"><img src="exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=a&amp;s=29" alt="Imagem do autor: <?=$value['nome'];?>" width="40" height="40" /></a></div>
				<?php endif; ?>
				<strong><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Ir para p�gina deste autor"><?=$value['nome'];?></a></strong><br />
				<?php if ($value['cod_estado']): ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades=<?=$value['cod_cidade']?>" title="Listar autores por cidade"><?=$value['cidade']?></a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados=<?=$value['cod_estado']?>" title="Listar autores por estado"><?=$value['estado']?></a><br />
				<?php endif; ?>
				<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor=<?=$value['cod'];?>" title="Listar os conte�dos deste autor" class="info"><?=$value['num_conteudo'];?> conte�dos</a>
				<div class="hr"><hr /></div>
			</li>
<?php
	endif;
endforeach;
?>
        </ul>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=9" title="Listar autores"><strong>Ver todos</strong></a></div>
    </div>
</div>
</div>
<?php
include ('includes/rodape.php');
