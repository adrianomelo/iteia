<?php if (count($_SESSION["sessao_colaboradores_revisao"])): ?>
<div class="box">
        <fieldset>
        <legend>Colaboradores adicionados</legend>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-autores">
    <thead>
      <tr>
        <th class="col-img" scope="col">Imagem</th>
        <th class="col-msg" scope="col">Nome do Colaborador</th>
        <th class="col-remover" scope="col">Remover</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach ($_SESSION["sessao_colaboradores_revisao"] as $colab): ?>
      <tr>
        <td class="col-img"><?=Util::iif($colab["imagem"], "<img src=\"exibir_imagem.php?img=".$colab["imagem"]."&amp;tipo=a&s=1\" width=\"50\" height=\"50\" />", "<img src=\"img/imagens-padrao/mini-colaborador.jpg\" width=\"50\" height=\"50\" />");?></td>
        <td><strong>Nome:</strong> <a href="<?=ConfigVO::URL_SITE;?>colaborador/<?=$colab["url"];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste colaborador"><?=htmlentities($colab["nome"]);?></a><?=Util::iif($colab["sigla"], '<br />('.$colab["sigla"].')');?></td>
		<td class="col-remover">
			<a href="javascript:removerColaboradorRevisao(<?=$colab["cod_usuario"]?>);" title="Remover">Remover</a>
		</td>
      </tr>
	<?php endforeach; ?>
    </tbody>
  </table>
        </fieldset>
      </div>
<?php endif; ?>
<script type="text/javascript">
<?php if (count($_SESSION["sessao_colaboradores_revisao"])): ?>
	$("#botoes").show();
<?php else: ?>
	$("#botoes").hide();
<?php endif; ?>
</script>