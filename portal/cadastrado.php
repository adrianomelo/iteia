<?php
include_once('classes/bo/UsuarioBO.php');
$tipo = (int)$_GET['t'];
$topo_class = 'cat-faq iteia';
$titulopagina = 'Confirmação de cadastro';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/<?=($tipo == 1 ? 'autores' : 'colaboradores')?>.php"><?=($tipo == 1 ? 'Autores' : 'Colaboradores')?></a> <span class="marcador">&raquo;</span> <a href="/cadastro_autor.php">Cadastro de <?=($tipo == 1 ? 'autor' : 'colaborador')?></a> <span class="marcador">&raquo;</span> <span class="atual">Confirmação de cadastro</span></div>
    <div id="conteudo">

      <h2 class="midia">Seu cadastro on-line foi preenchido com sucesso</h2>
	<p class="caption">Seu cadastro será analisado e nossa equipe entrará em contato para efetivar o seu acesso.</p>
      <?php include('includes/canais.php');?>
    </div>
<?php
include ('includes/rodape.php');
