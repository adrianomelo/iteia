<?php
include('verificalogin.php');
if (count($resultado)):
?>
<h3 class="titulo">Resultado da busca</h3>
	<div id="resultado" class="box">
      <div class="view">Exibindo
        <select id="exibir" onchange="javascript:mostrarTotal('#mostra_resultados_relacionamento', '<?=$resultado['link'];?>');">
          <option value="10"<?=($this->dados_get['mostrar'] == 10 ? ' selected="selected"':'');?>>10</option>
          <option value="20"<?=($this->dados_get['mostrar'] == 20 ? ' selected="selected"':'');?>>20</option>
          <option value="30"<?=($this->dados_get['mostrar'] == 30 ? ' selected="selected"':'');?>>30</option>
        </select>
        por p&aacute;gina</div>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-autor"  scope="col">Autor</th>
            <th class="col-ico" scope="col">Formato</th>
            <th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
            <th class="col-adicionar" scope="col">Adicionar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($resultado as $value):
            if ((int)$value['cod']): 
          ?>
          <tr>
            <td class="col-titulo"><a href="<?=$value['url'];?>" title="Clique para visualizar"><?=$value['titulo'];?></td>
            <td class="col-autor"><?=$value['autores'];?></td>
            <td class="col-formato"><?=$value['formato'];?></td>
            <td class="col-situacao"><?=$value['situacao'];?></td>
            <td class="col-adicionar"><a href="javascript:adicionarConteudoHomeUsuario(<?=$value['cod'];?>);">Adicionar</a></td>
          </tr>
          <?php
            endif;
          endforeach;
          ?>
        </tbody>
      </table>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div><hr class="both" />
      </div>

<script type="text/javascript" src="jscripts/scripts.js"></script>

<?php endif; ?>
<?php if (count($resultado) == 3): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>