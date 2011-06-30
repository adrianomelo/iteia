<?php
include("verificalogin.php");

include_once("classes/bo/PlayListBO.php");
$homebuscabo = new PlayListBO;

$lista_itens = $homebuscabo->getListaConteudoSelecionados($codplaylist);
$tempo_total = $homebuscabo->getTempoTotalPlayList($codplaylist);
?>
<h3 class="titulo">Itens adicionados &agrave; lista</h3>
    <div class="box">
          <fieldset>
          <p>Quantidade de destaques: <strong><?=count($lista_itens);?></strong><br />

            Dura&ccedil;&atilde;o: <strong> <?=$tempo_total;?></strong></p>
          <table width="100%" border="1" cellspacing="0" cellpadding="0" >
            <thead>
              <tr>
                <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />
                </th>
                <th class="col-img"  scope="col">Imagem</th>

                <th class="col-titulo"  scope="col">Chamada</th>
                <th class="col-tempo"  scope="col">Tempo de exibi&ccedil;&atilde;o </th>
                <th class="col-editar"  scope="col">Editar</th>
                <th class="col-remover" scope="col">Remover</th>
              </tr>
            </thead>

            <tbody>
<?php
	$contcheck = 1;
	foreach ($lista_itens as $item) {
		$html_img = "";
		if ($item["imagem"])
			$html_img = $item["imagem"];
?>
              <tr>
                <td class="col-1"><input type="checkbox" class="check" name="coditem" value="<?=$item["cod_item"]?>" /></td>
                <td class="col-img"><?=$html_img?></td>
                <td class="col-titulo"><strong><?=utf8_encode($item["titulo"])?></strong><p><?=utf8_encode($item["descricao"])?></p></td>
                <td class="col-tempo">
                <select name="select" onchange="homeMudaTempoConteudo('<?=$item["cod_item"]?>', this)">
                  	<option value="60"<?=($item["tempo_exibicao"] == 60) ? " selected=\"selected\"" : ""?>>1 h</option>
                  	<option value="180"<?=($item["tempo_exibicao"] == 180) ? " selected=\"selected\"" : ""?>>3 h</option>
                  	<option value="300"<?=($item["tempo_exibicao"] == 300) ? " selected=\"selected\"" : ""?>>5 h</option>
                  	<option value="600"<?=($item["tempo_exibicao"] == 600) ? " selected=\"selected\"" : ""?>>10 h</option>
                </select>
                </td>

                <td class="col-editar"><a href="home_editar_destaque.php?height=430&amp;width=650&amp;cod=<?=$item["cod_item"]?>" title="Editar Chamada" class="thickbox">Editar</a></td>
                <td class="col-remover"><a href="javascript:removerItemPlayList(<?=$item["cod_item"]?>);" title="Remover">Remover</a></td>
              </tr>
<?php
		$contcheck++;
	}
?>    
            </tbody>
            <tfoot>
            <tr>
              <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:executaAcaoHomeSelecionados(1)">Remover</a> | <a href="javascript:executaAcaoHomeSelecionados(2)">Mover para cima</a> | <a href="javascript:executaAcaoHomeSelecionados(3)">Mover para baixo</a></td>
            </tr>
          </tfoot>

          </table>
          </fieldset>
        </div>

<script type="text/javascript" src="jscripts/thickbox/thickbox-compressed.js"></script>
<script type="text/javascript" src="jscripts/scripts.js"></script>