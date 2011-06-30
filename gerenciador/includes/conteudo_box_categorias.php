<script type="text/javascript">
function listarSubCanais(codsubcanal) {
	$('#codsubcanal').load('ajax_conteudo.php?get=subcanais&codcanal=' + $('#codcanal').val() + '&codsubcanal=' + codsubcanal);
}
</script>
<div class="box"  id="classificar">
    <fieldset>
        <legend>Classifica&ccedil;&atilde;o</legend>
        <div>
			<p>Para classificar melhor este conteúdo selecione o canal e o sub-canal que melhor representam este trabalho.</p>
          
			<input type="hidden" name="contsubarea" value="<?=count($contbo->getListaSubAreas())?>" />
			<input type="hidden" name="contsegmento" value="<?=count($contbo->getListaSegmentos())?>" />
          
			<?php if (count($contbo->getListaSegmentos())): ?>
			<div class="campos">
				<label>Canal* </label><br />
				<select name="codsegmento" <?=$contbo->verificaErroCampo("codsegmento")?> id="codcanal" onchange="javascript:listarSubCanais();">
					<option value="0">Selecione</option>
					<?php foreach ($contbo->getListaSegmentos() as $value): ?>
					<option value="<?=$value['cod_segmento'];?>" <?=Util::iif($contbo->getValorCampo("codsegmento") == $value['cod_segmento'], 'selected="selected"');?>><?=$value['nome'];?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
		  
			<?php if (count($contbo->getListaSubAreas())): ?>
			<div class="campos">
				<label>Sub-canal</label><br />
				<select name="codsubarea" class="select-list" id="codsubcanal">
					<option value="0">Selecione</option>
				</select>
			</div>
			<?php endif; ?>
          
			<div class="separador-hr"></div>
        </div>
    </fieldset>
    
	<div>
        <label for="label">Tipo da obra</label><br />
        <select id="label" name="codclassificacao">
          	<option value="0">Selecione</option>
          	<?php foreach ($contbo->getListaClassificacao($codformato_class) as $value): ?>
              	<option value="<?=$value['cod_classificacao'];?>" <?=Util::iif($contbo->getValorCampo("codclassificacao") == $value['cod_classificacao'], 'selected="selected"');?>><?=$value['nome'];?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
	<div>
          <label for="label4">Tags (palavras-chave)</label><br />
          <input type="text" name="tags" id="tags" value="<?=$contbo->getValorCampo("tags")?>" class="txt" size="80" />
          <small>Separe por ponto-e-v&iacute;rgula &quot;;&quot;</small></div>
    </div>