<div id="op-comentario"><strong>Visualizar:</strong><br /> 
<?=$resultado['html_navegacao_superior'];?>        
</div>
      
    <h3 class="titulo"><?=$resultado['titulo_superior'];?></h3>
    <div id="resultado" class="box">
      <div class="view">Exibindo
        <select id="exibir" onchange="javascript:mostrarTotal('#mostra_resultados_comentarios', '<?=$resultado['link'];?>', 'pag_comentario');">
          <option value="10"<?=($this->dados_get['mostrar'] == 10 ? ' selected="selected"':'');?>>10</option>
          <option value="20"<?=($this->dados_get['mostrar'] == 20 ? ' selected="selected"':'');?>>20</option>
          <option value="30"<?=($this->dados_get['mostrar'] == 30 ? ' selected="selected"':'');?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-agenda">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" /></th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-acoes" scope="col">A&ccedil;&otilde;es</th>
            <th class="col-remover" scope="col">Apagar</th>
          </tr>
        </thead>
        <tbody>
        	<?php
          	foreach ($resultado as $value):
            	if ((int)$value['cod']): 
          	?>
          <tr>
            <td class="col-1"><input name="codcomentario" type="checkbox" class="check" value="<?=$value['cod'];?>" /></td>
            <td class="col-titulo">
            <p class="dash"><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" title="Este link ser&aacute; aberto numa nova janela" target="_blank" class="ext"><strong><?=$value['url'];?></strong></a></p>
            
            <p><strong><?=$value['autor'];?></strong> comentou em 
              <?=date('d.m.Y - H\\hi', strtotime($value['data']));?><br />
              <?=$value['email'];?> <?=Util::iif($value['site'], "| <a href=\"http://".$value['site']."\" target=\"_blank\">".$value['site']."</a>");?></p>
                <p><?=$value['comentario'];?></p></td>
            <td class="col-acoes"><?=$value['menu_acao'];?></td>
            <td class="col-remover"><a href="javascript:void(0);" onclick="javascript:submeteAcoesComentario(1, '<?=$resultado['link'];?>&codcomentario=<?=$value['cod'];?>&pagina=<?=$paginacao['pagina'];?>');">Apagar</a></td>
          </tr>
          	<?php
            	endif;
          	endforeach;
          	?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="selecionados"><strong>Selecionados:</strong> <span class="selecionados"><a href="javascript:submeteAcoesComentarioMulti(1, '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Apagar</a> | <a href="javascript:submeteAcoesComentarioMulti(2, '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Aprovar</a> | <a href="javascript:submeteAcoesComentarioMulti(3, '<?=$resultado['link'];?>&pagina=<?=$paginacao['pagina'];?>');">Rejeitar</a></span></td>
          </tr>
        </tfoot>
      </table>
      <div class="nav">P&aacute;ginas: <?=$paginacao['anterior'];?> <?=$paginacao['page_string'];?> <?=$paginacao['proxima'];?> </div>
      <hr class="both" />
    </div>
    
    <script type="text/javascript" src="jscripts/scripts.js"></script>
    <script type="text/javascript" src="jscripts/comentarios.js"></script>

<?php if (count($resultado) == 5 && $resultado['carregar']): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>