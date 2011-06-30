<?php
include('verificalogin.php');
if (count($resultado)):
?>
<div id="resultado">
      <div class="view">Exibindo
        <select id="exibir" onchange="javascript:mostrarTotal('#mostra_resultados_relacionamento', '<?=$resultado['link'];?>', 'pag_conteudo');">
          <option value="10"<?=($this->dados_get['mostrar'] == 10 ? ' selected="selected"':'');?>>10</option>
          <option value="20"<?=($this->dados_get['mostrar'] == 20 ? ' selected="selected"':'');?>>20</option>
          <option value="30"<?=($this->dados_get['mostrar'] == 30 ? ' selected="selected"':'');?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-autor" scope="col">Autor</th>
            <th class="col-ico" scope="col">Formato</th>
            <th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
            <th class="col-editar" scope="col">Editar</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          foreach ($resultado as $value):
            if ((int)$value['cod']): 
          ?>
          <tr>
          	<td class="col-1"><input name="codconteudo" type="checkbox" class="check" value="<?=$value['cod'];?>" /></td>
            <td class="col-titulo"><a href="<?=$value['url'];?>" title="Clique para visualizar"><?=$value['titulo'];?></td>
            <td class="col-autor"><?=$value['autores'];?></td>
            <td class="col-formato"><?=$value['formato'];?></td>
            <td class="col-situacao"><?=$value['situacao'];?></td>
            <td class="col-editar">
			<?php if ($_SESSION['logado_dados']['nivel'] == 2 || $_SESSION['logado_dados']['nivel'] == 5 || $_SESSION['logado_dados']['nivel'] == 6):
				if ($value['cod_autor_colaborador'] == $_SESSION['logado_dados']['cod']): ?>
					<a href="<?=$value['url_editar'];?>" title="Editar">Editar</a>
			<?php
				endif;
			else:
			?>
				<a href="<?=$value['url_editar'];?>" title="Editar">Editar</a>
			<?php
			endif;
			?>
			</td>
          </tr>
          <?php
            endif;
          endforeach;
          ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesConteudo(1, '#mostra_resultados_relacionamento', '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Apagar</a> | <a href="javascript:submeteAcoesConteudo(2, '#mostra_resultados_relacionamento', '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Ativar</a> | <a href="javascript:submeteAcoesConteudo(3, '#mostra_resultados_relacionamento', '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Desativar</a></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div>
<br /><br /><br />

<script type="text/javascript" src="jscripts/mini_scripts.js"></script>

<?php
endif;
?>
<?php if (count($resultado) == 3 && $resultado['carregar']): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>