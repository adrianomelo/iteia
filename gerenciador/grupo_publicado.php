<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codgrupo = (int)$_GET['cod'];

include_once("classes/bo/GrupoEdicaoBO.php");
$grupobo = new GrupoEdicaoBO;

if ($codgrupo) {
	$grupobo->setDadosCamposEdicao($codgrupo);
	$comunicadores = $grupobo->getComunicadoresGrupo($codgrupo);
	$sitesrelacionados = $grupobo->getSitesGrupo($codgrupo);
	$autores = $grupobo->getAutoresGrupo($codgrupo);
	$arrayTipos = $grupobo->getListaTipos();
}

$item_menu = "grupo";
include('includes/topo.php');
?>
    <h2>Grupos</h2>
    <h3 class="titulo">Grupo cadastrado</h3>
    <div class="box">
      <div class="separador">
        <p><strong>Nome:</strong><br />
        <?=$grupobo->getValorCampo('nome')?></p>
        <p><strong>Descri&ccedil;&atilde;o:</strong><br />
          <?=nl2br(Util::autoLink($grupobo->getValorCampo('descricao'), 'both', true));?></p>
      </div>
     <div class="separador"><strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />

      <img src="<?=Util::iif($grupobo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$grupobo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/texto.jpg");?>" width="124" height="124" /></div>
	  
      <div class="separador">
        <p><strong>Endere&ccedil;o:</strong> <?=$grupobo->getValorCampo('endereco');?><br />
          <strong>Complemento:</strong> <?=$grupobo->getValorCampo('complemento');?><br />
          <strong>Bairro:</strong> <?=$grupobo->getValorCampo('bairro');?><br />
          <strong>Cidade:</strong> <?=Util::iif($grupobo->getValorCampo('codcidade'), $grupobo->getCidade($grupobo->getValorCampo('codcidade')), $grupobo->getValorCampo('cidade'));?><br />
          <strong>Estado:</strong> <?=$grupobo->getEstado($grupobo->getValorCampo('codestado'));?><br />
          <strong>Pa&iacute;s:</strong> <?=$grupobo->getPais($grupobo->getValorCampo('codpais'));?></p>
        <p><strong>Tipo:</strong> 
		<?php
		foreach($grupobo->getValorCampo('tipo') as $value):
			if ($value['cod'])
				echo $arrayTipos[$value]['tipo'].' ';
		endforeach;
		?>
		</p>
        <p><strong>Telefone:</strong>
          <?=$grupobo->getValorCampo('telefone');?><br />
          <strong>Celular:</strong> <?=$grupobo->getValorCampo('celular');?><br />
          <strong>E-mail:</strong> <?=$grupobo->getValorCampo('email');?><br />
          <strong>Site oficial:</strong> <?=Util::iif($grupobo->getValorCampo('site'), "<a href=\"http://".$grupobo->getValorCampo('site')."\" target=\"_blank\" class=\"ext\" title=\"Este link ser&aacute; aberto numa nova janela\">http://".$grupobo->getValorCampo('site')."</a>");?></p>
        <p><strong>Comunicadores:</strong><br />
        <?php foreach ($comunicadores as $key => $value): ?>
        	<?=$value['comunicador'];?> - <?=$value['nome_usuario'];?><br />
        <?php endforeach; ?></p>
        
        <p><strong>Sites relacionados:</strong><br />
        <?php foreach ($sitesrelacionados as $key => $value): ?>
        	<?=$value['site'];?> - <a href="<?=$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=$value['url'];?></a><br />
        <?php endforeach; ?></p>
      </div>
      
	  <div id="autores" class="separador"><strong>Autores participantes:</strong>
	  <?php foreach ($autores as $key => $value): ?>
        	<a href="<?=ConfigVO::URL_SITE.$value['titulo'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=$value['nome'];?></a>,&nbsp;
        <?php endforeach; ?><br />
      </div>
       <div class="separador">
      <strong>P&aacute;gina do grupo no PENC:</strong> <a href="<?=ConfigVO::URL_SITE.$grupobo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$grupobo->getValorCampo('finalendereco');?></a></div>
      <a href="grupo_edicao.php?cod=<?=$codgrupo;?>" title="Editar" class="bt">Editar</a>    </div>
    <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <li><a href="grupo_adicionar_autores.php?cod=<?=$codgrupo;?>">Adicionar  autores</a></li>
        <li>Cadastrar novos <a href="conteudo_tipo.php">conte&uacute;dos</a></li>
        <li><a href="<?=ConfigVO::URL_SITE.$grupobo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
      </ul>
    </div>
   
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
