<?php if (count($_SESSION["sessao_autores_relacionados"])): ?>
<div class="box">
        <fieldset>
        <legend>Autores adicionados</legend>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-autores">
    <thead>
      <tr>
        <th class="col-img" scope="col">Imagem</th>
        <th class="col-msg" scope="col">Nome do Autor</th>
        <th class="col-remover" scope="col">Remover</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach ($_SESSION["sessao_autores_relacionados"] as $autor): ?>
      <tr>
        <td class="col-img"><?=Util::iif($autor["imagem"], "<img src=\"exibir_imagem.php?img=".$autor["imagem"]."&amp;tipo=a&s=1\" width=\"50\" height=\"50\" />", "<img src=\"img/imagens-padrao/mini-autor.jpg\" width=\"50\" height=\"50\" />");?></td>
        <td><strong>Nome:</strong> <a href="<?=ConfigVO::URL_SITE;?>autor/<?=$autor["url"];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=htmlentities($autor["nome"]);?></a> <?=Util::iif($autor["sigla"], '('.$autor["sigla"].')');?> <br />
          <strong>Nome:</strong> <?=htmlentities($autor["nome_completo"]);?></td>
		<td class="col-remover">
        	<?php if (($_SESSION['logado_dados']['nivel'] == 2) && ($_SESSION['logado_cod'] != $autor['cod_usuario'])): ?>
				<a href="javascript:removerAutor(<?=$autor["cod_usuario"]?>);" title="Remover">Remover</a>
			<?php elseif ($_SESSION['logado_dados']['nivel'] >= 5): ?>
				<a href="javascript:removerAutor(<?=$autor["cod_usuario"]?>);" title="Remover">Remover</a>
			<?php endif; ?>
		</td>
      </tr>
	<?php endforeach; ?>
    </tbody>
  </table>
        </fieldset>
      </div>
<?php endif; ?>
<script type="text/javascript">
<?php if (count($_SESSION["sessao_autores_relacionados"])): ?>
	$("#botoes").show();
<?php else: ?>
	$("#botoes").hide();
<?php endif; ?>
</script>