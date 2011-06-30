<?php
include("verificalogin.php");

include_once("classes/bo/HomeBuscaBO.php");
$homebuscabo = new HomeBuscaBO;

$lista_conteudo = $homebuscabo->getListaConteudoHomeUsuario($codusuario, $tipo_usuario);
if (count($lista_conteudo)) {
?>

        <h3 class="titulo">Conte&uacute;dos da Playlist</h3>
		<div class="box">
          <fieldset>
          
<script language="javascript" type="text/javascript">
var pagina_home = '<?=$tipo_usuario;?>';
var posicao_home = 0;
</script>

        <p>Ordem de exibi&ccedil;&atilde;o na home:<br />
          <label>
          <input type="radio" name="ordem" id="ordem1" value="1"<?=($novaordem == 1)?" checked=\"checked\"":""?> onclick="ordenarItensUsuario()" />
          Por per&iacute;odo</label>
          <br />
          <label>
          <input type="radio" name="ordem" id="ordem2" value="2"<?=($novaordem == 2)?" checked=\"checked\"":""?> />
          Por Ordem definida</label>
          <br />
        </p>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-playlist" >
          <thead>
            <tr>
              <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" /></th>
              <th class="col-imagem"  scope="col">Imagem</th>
              <th class="col-titulo"  scope="col">T&iacute;tulo</th>
              <th class="col-data" scope="col">Per&iacute;odo</th>
              <th class="col-tempo"  scope="col">Tempo de exibi&ccedil;&atilde;o </th>
              <th class="col-editar"  scope="col">Editar</th>
            </tr>
          </thead>
          <tbody>
<?php
	$contcheck = 1;
	foreach ($lista_conteudo as $noticia) {
		$html_img = "";
		if ($noticia["imagem"])
			$html_img = "<img src=\"exibir_imagem.php?img=".$noticia["imagem"]."&amp;tipo=".$noticia["tipo_img"]."&amp;s=1\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";
?>
            <tr>
              <td class="col-1"><input type="checkbox" class="check" name="coditem" value="<?=$noticia["cod_item"]?>" /></td>
              <td class="col-img"><?=$html_img?></td>
              <td class="col-titulo"><strong><?=htmlentities($noticia["titulo"])?></strong>
                <p><?=$noticia["resumo"]?></p></td>
              <td class="col-data"><?=date("d/m/Y H:i", strtotime($noticia["data_exibicao"]))?></td>
              <td class="col-tempo"><select name="select" onchange="homeMudaTempoConteudoUsuario('<?=$noticia["cod_item"]?>', this)">
                  <option value="15"<?=($noticia["tempo_exibicao"] == 15) ? " selected=\"selected\"" : ""?>>15 min</option>
                  <option value="30"<?=($noticia["tempo_exibicao"] == 30) ? " selected=\"selected\"" : ""?>>30 min</option>
                  <option value="45"<?=($noticia["tempo_exibicao"] == 45) ? " selected=\"selected\"" : ""?>>45 min</option>
                  <option value="60"<?=($noticia["tempo_exibicao"] == 60) ? " selected=\"selected\"" : ""?>>1 h</option>
                  <option value="180"<?=($noticia["tempo_exibicao"] == 180) ? " selected=\"selected\"" : ""?>>3 h</option>
                  <option value="300"<?=($noticia["tempo_exibicao"] == 300) ? " selected=\"selected\"" : ""?>>5 h</option>
                  <option value="480"<?=($noticia["tempo_exibicao"] == 480) ? " selected=\"selected\"" : ""?>>8 h</option>
                  <option value="600"<?=($noticia["tempo_exibicao"] == 600) ? " selected=\"selected\"" : ""?>>10 h</option>
                </select>
              </td>
              <td class="col-editar"><a href="<?=$noticia["url_editar"];?>">Editar</a></td>
            </tr>
<?php
		$contcheck++;
	}
?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:executaAcaoHomeConteudoUsuarioSelecionado(1)">Remover</a> | <a href="javascript:executaAcaoHomeConteudoUsuarioSelecionado(2)">Mover para cima</a> | <a href="javascript:executaAcaoHomeConteudoUsuarioSelecionado(3)">Mover para baixo</a></td>
            </tr>
          </tfoot>
        </table>
        <br /><input type="submit" id="botao_salvar" class="bt-buscar" onclick="javascript:salvaListaHomeDefinitiva(); return false;" value="Salvar" />
        </fieldset>
      </div>
      
<script type="text/javascript" src="jscripts/scripts.js"></script>
<?php
}
?>