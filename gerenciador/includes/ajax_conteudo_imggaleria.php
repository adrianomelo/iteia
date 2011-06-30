<?php
include("verificalogin.php");

include_once("classes/util/NavegacaoUtil.php");
include_once("classes/bo/ImagemBuscaBO.php");

$imgbo = new ImagemBuscaBO;

$capa = (int)$this->dados_get["capa"];
$pagina = (int)$this->dados_get["pagina"];

if ($pagina < 0)
	$pagina = 0;
$intervalo = (int)$this->dados_get["intervalo"];
if (($intervalo != 20) && ($intervalo != 30))
	$intervalo = 10;
$total = (int)$_GET["total"];

if ($total)
	$imgbo->setTotal($total);
$lista_imagens = $imgbo->getListaImagens($this->dados_get, $pagina, $intervalo);
$html_paginas = $imgbo->getHtmlNavegacao();
$total = $imgbo->getTotal();

if ($erro_mensagem) {
?>
<p class="error"><strong>Erro!</strong><br /><?=$erro_mensagem?></p>
<?php
}

if ($total) {
?>

	<div>
	<input type="hidden" id="parametros" value="<?=$imgbo->getParametros()?>" />
	<input type="hidden" id="pagina" value="<?=$pagina?>" />
	<input type="hidden" id="intervalo" value="<?=$intervalo?>" />
	<input type="hidden" id="total" value="<?=$total?>" />
	</div>
      <fieldset>
      <div class="view">Exibindo
        <select id="select3" name="sel_intervalo" onchange="buscaMudaIntervaloImagem(this.options[this.selectedIndex].value);">
          <option value="10"<?=($intervalo == 10) ? " selected=\"selected\"" : ""?>>10</option>
          <option value="20"<?=($intervalo == 20) ? " selected=\"selected\"" : ""?>>20</option>
          <option value="30"<?=($intervalo == 30) ? " selected=\"selected\"" : ""?>>30</option>
        </select>
        por p&aacute;gina</div>
      <br />
      <div class="nav">P&aacute;ginas: <?=$html_paginas?></div>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="tb-album">
          <thead>
            <tr>
              <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />
              </th>
              <th class="col-img" scope="col">Imagem</th>
              <th class="col-titulo" scope="col">Cr&eacute;dito/Legenda</th>
              <th class="col-acoes" scope="col">Capa do &aacute;lbum</th>
            </tr>
          </thead>
          <tbody>
<?php
	foreach ($lista_imagens as $imagem) {
?>
			<tr>
              <td class="col-1"><input type="checkbox" name="codimagem" value="<?=$imagem["cod_imagem"]?>" class="check" id="checkbox2" /></td>
              <td class="col-img"><img src="exibir_imagem.php?img=<?=$imagem["imagem"]?>&amp;tipo=2&s=1" width="50" height="50" alt="" /></td>
              <td class="col-titulo"><label for="cred-1">Cr&eacute;dito</label>
                <br />
                <input type="text" name="creditoimg[<?=$imagem["cod_imagem"]?>]" id="credito_<?=$imagem["cod_imagem"]?>" class="txt" size="50" maxlength="200" value="<?=substr(htmlentities(stripslashes($imagem["credito"])), 0, 60)?>" onkeyup="contarCaracteres(this, 'cont_credito_<?=$imagem["cod_imagem"]?>', 60);" />
                <input type="text" disabled="disabled" id="cont_credito_<?=$imagem["cod_imagem"]?>" class="txt counter" value="<?=Util::iif($imagem["credito"], 60 - strlen($imagem["credito"]), '60');?>" size="4" />
                <br />
                <label for="leg-1">Legenda</label>
                <br />
                <input type="text" name="legendaimg[<?=$imagem["cod_imagem"]?>]" id="legenda_<?=$imagem["cod_imagem"]?>" class="txt" size="50" maxlength="200" value="<?=substr(htmlentities(stripslashes($imagem["legenda"])), 0, 60)?>" onkeyup="contarCaracteres(this, 'cont_legenda_<?=$imagem["cod_imagem"]?>', 60);" />
                <input type="text" id="cont_legenda_<?=$imagem["cod_imagem"]?>" disabled="disabled" class="txt counter" value="<?=Util::iif($imagem["legenda"], 60 - strlen($imagem["legenda"]), '60');?>" size="4" /></td>
              <td class="col-acoes">
			  	<?php
			  	if ($imagem['cod_imagem'] == $capa) {
				?>
			  		<span class="capa">Capa</span>
				<?php
				}
				?>
				</td>
            </tr>
<?php
	}
?>
		</tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:removerImagemAlbum();">Apagar</a> | <a href="javascript:executaAcaoImagemSelecionadas(1)">Mover para cima</a> | <a href="javascript:executaAcaoImagemSelecionadas(2)">Mover para baixo</a> | <a href="javascript:definirCapaAlbum();">Capa do &aacute;lbum</a> </td>
            </tr>
          </tfoot>
        </table>
<?php
}
?>