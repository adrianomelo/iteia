<?php if (count($_SESSION["sessao_autores_integrantes"])): ?>
<table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-cadastro">
          <caption>Autores</caption>
              <thead>
              <tr>
                <th class="col-img"  scope="col">Imagem</th>
                <th class="col-titulo" scope="col">Nome</th>
                <th class="col-tipo" scope="col">Respons&aacute;vel</th>
                <th class="col-remover" scope="col">Remover</th>
              </tr>
            </thead>
            <tbody>
    <?php foreach ($_SESSION["sessao_autores_integrantes"] as $autor): ?>
      <tr>
        <td class="col-img"><?=Util::iif($autor["imagem"], "<img src=\"exibir_imagem.php?img=".$autor["imagem"]."&amp;tipo=a&s=1\" width=\"50\" height=\"50\" />", "<img src=\"img/imagens-padrao/mini-autor.jpg\" width=\"50\" height=\"50\" />");?></td>
        <td class="col-titulo"><?=htmlentities($autor["nome"]);?></td>
        <td class="col-tipo">
		<?php if ($autor['responsavel']): ?>
			<span class="capa">Respons&aacute;vel</span>
		<?php else: ?>
			<a href="javascript:definirResponsavelIntegrante(<?=$autor["cod_usuario"]?>);">Tornar<br />respons&aacute;vel</a>
		<?php endif; ?>
		</td>
        <td class="col-remover">
			<a href="javascript:removerAutorIntegrante(<?=$autor["cod_usuario"]?>);" title="Remover">Remover</a>
		</td>
    </tr>
	<?php endforeach; ?>
            </tbody>
          </table>
          <script type="text/javascript" src="jscripts/mini_scripts.js"></script>
<?php endif; ?>