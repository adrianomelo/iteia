<?php
include("verificalogin.php");

include_once("classes/bo/NewsletterBO.php");
$newsbo = new NewsletterBO;

$lista_itens = $newsbo->getListaConteudoSelecionados($codnewsletter);
?>
<h3 class="titulo">Itens adicionados &agrave; lista</h3>
    <div class="box">
          <fieldset>
          <table width="100%" border="1" cellspacing="0" cellpadding="0" >
            <thead>
              <tr>
                <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />
                </th>
                <th class="col-img"  scope="col">Imagem</th>
                <th class="col-titulo"  scope="col">T&iacute;tulo</th>
                <th class="col-tempo"  scope="col">Destaque</th>
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
                <?php
			  	if ($item['destaque']) {
				?>
			  		<span class="capa">Destaque</span>
				<?php
				} else {
				?>
					<a href="javascript:definirDestaque(<?=$item["cod_item"]?>);" title="Remover">Tornar<br />destaque</a>
				<?php
				}
				?>
                </td>

                <td class="col-editar"><a href="newsletter_editar_destaque.php?height=540&amp;width=650&amp;cod=<?=$item["cod_item"]?>" title="Editar Chamada" class="thickbox">Editar</a></td>
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