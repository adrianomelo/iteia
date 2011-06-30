<?php
include("verificalogin.php");

include_once("classes/util/NavegacaoUtil.php");
include_once("classes/bo/AudioBuscaBO.php");

$audbo = new AudioBuscaBO;

$lista_audios = $audbo->getListaAudios($this->dados_get);
$total = count($lista_audios);

if ($erro_mensagem) {
?>
<p class="error"><strong>Erro!</strong><br /><?=$erro_mensagem?></p>
<?php
}
if ($total) {
?>
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="tb-album">
          <thead>
            <tr>
              <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />              </th>
              <th class="col-titulo" scope="col">T&iacute;tulo da faixa</th>
              <th class="col-ico" scope="col">Tempo</th>
            </tr>
          </thead>
<?php
	foreach ($lista_audios as $audio) {
?>
			<tr>
              <td class="col-1"><input type="checkbox" name="codaudio" value="<?=$audio["cod_audio"]?>" class="check" id="checkbox4" /></td>
              
              <td class="col-titulo">
            	<input type="text" name="titulofaixaaud[<?=$audio["cod_audio"]?>]" id="audio_<?=$audio["cod_audio"]?>" class="txt" size="50" maxlength="60" value="<?=htmlentities(substr($audio["titulo"], 0, 60))?>" onkeyup="contarCaracteres(this, 'cont_titulo_<?=$audio["cod_audio"]?>', 60);" />
                <input type="text" disabled="disabled" id="cont_titulo_<?=$audio["cod_audio"]?>" class="txt counter" value="<?=Util::iif($audio["titulo"], 60 - strlen($audio["titulo"]), '60');?>" size="4" />
            
            <br />
            <?=htmlentities($audio['arquivo_original']);?></td>

              <td class="col-ico"><input type="text" name="tempoaud[<?=$audio["cod_audio"]?>]" value="<?=htmlentities($audio['tempo']);?>" id="hh" class="txt hour hh" /></td>
            </tr>
<?php
	}
?>
		</tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:removerAudioAlbum();">Apagar</a> | <a href="javascript:executaAcaoAudiosSelecionados(1)">Mover para cima</a> | <a href="javascript:executaAcaoAudiosSelecionados(2)">Mover para baixo</a></td>
            </tr>
          </tfoot>
        </table>
        <script type="text/javascript" src="jscripts/mini_scripts.js"></script>
<script language="javascript" type="text/javascript">
$(".hh").mask("99:99");
$("#possui_audios").val(1);
</script>
<?php
}
?>