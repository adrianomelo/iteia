<div class="box">
        <fieldset>
        <legend>Esse conte&uacute;do pertence a voc&ecirc;?<span>*</span></legend>
          <p>
            
            <input type="radio" class="radio" onmouseup="javascript:conteudoPertenceVoceMostra();" name="pertence_voce" value="1" <?=Util::iif($contbo->getValorCampo('pertence_voce') == 1, 'checked="checked"');?> id="pertence_voce_sim" />
            <label for="op_0">Sim</label>
            <br />
            
              <input  type="radio" class="radio" onclick="javascript:conteudoPertenceVoceOculta();" name="pertence_voce" id="pertence_voce_nao" value="2" <?=Util::iif($contbo->getValorCampo('pertence_voce') == 0, 'checked="checked"');?> />
              <label for="op_1">N&atilde;o</label> 
            <span>(Voc&ecirc; precisa preencher a ficha t&eacute;cnica obrigatoriamente)</span></p>
        </fieldset>
      </div>