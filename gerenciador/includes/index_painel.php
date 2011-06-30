    <div id="info">

      <div class="user-name"><?=$usuariodados['nome'];?><br />
          <span class="user-description"><?=$usuariodados['cidade'];?>/<?=$usuariodados['estado'];?></span></div>

      <p class="changepic"><img src="<?=$usuariodados['imagem'];?>" id="img_exibicao" width="124" height="124" /><br />
        <a href="trocar_imagem_index.php?cod=<?=$usuariodados['cod_usuario'];?>&amp;height=210&amp;width=305" title="Imagem ilustrativa" class="thickbox">trocar</a></p>

	<div id="user-body">
      <ul id="editar">
        <li><a href="<?=ConfigVO::URL_SITE.$usuariodados['url'];?>" class="pagina">Ver minha p&aacute;gina</a></li>
        <li><a href="cadastro_meu.php" class="cadastro">Editar meu cadastro</a></li>
        <!--
<li><a href="home_destaque.php" class="destaques">Gerenciar destaques</a></li>
-->
		<?php if ($_SESSION['logado_dados']['nivel'] > 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
		<li><span class="cadastro">Editar colaborador(es):</span>
		<ul>
          <?php foreach($indexbo->getListaColaboradoresEdicao() as $value): ?>
          	<li><strong>&rsaquo;</strong> <a href="cadastro_colaborador_publicado.php?cod=<?=$value['cod_usuario'];?>"><?=$value['nome'];?></a></li>
          <?php endforeach; ?>
        </ul>
        </li>
      </ul>
      <?php endif; ?>

      <div id="user-data">
      <ul>
      	<li class="ins-content-title"><strong>Conte&uacute;dos vinculados</strong></li>
      	<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): /*if ($_SESSION['logado_como'] == 3):*/ ?>
        	<li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(10);?> Colaboradores</li>
        	<li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(11);?> Autores</li>
        <?php endif; ?>
        <li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(1);?> Textos</li>
        <li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(4);?> V&iacute;deos</li>
        <li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(3);?> &Aacute;udios</li>
        <li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(2);?> Imagens</li>
        <?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
        	<li><strong>&rsaquo;</strong> <?=$indexbo->getEstatisticasUsuario(5);?> Not&iacute;cias</li>
        <?php endif; ?>
      </ul>
    </div>
</div>
	<div id="user-bottom"></div>
</div>