<?php
include_once('classes/bo/UsuarioBO.php');
$tipo = (int)$_GET['t'];
$topo_class = 'cat-faq iteia';
$titulopagina = 'Confirma��o de cadastro';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <a href="/<?=($tipo == 1 ? 'autores' : 'colaboradores')?>.php"><?=($tipo == 1 ? 'Autores' : 'Colaboradores')?></a> <span class="marcador">&raquo;</span> <a href="/cadastro_autor.php">Cadastro de <?=($tipo == 1 ? 'autor' : 'colaborador')?></a> <span class="marcador">&raquo;</span> <span class="atual">Confirma��o de cadastro</span></div>
    <div id="conteudo">

      <h2 class="midia">Seu cadastro on-line foi preenchido com sucesso</h2>
	<p class="caption">Seu cadastro ser� analisado e nossa equipe entrar� em contato para efetivar o seu acesso.</p>
      <?php include('includes/canais.php');?>
    </div>
<?php
include ('includes/rodape.php');
