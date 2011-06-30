<div class="box">
        <fieldset>
        <legend>Autoriza&ccedil;&atilde;o</legend>
        <p>Para ser publicado, esse conte&uacute;do ainda precisa d&aacute; autoriza&ccedil;&atilde;o de um  dos colaboradores que fazem parte do portal iTEIA: </p>
        <p>
          <input type="radio" name="pedir_autorizacao" value="1" id="aut_0" <?=Util::iif($contbo->getValorCampo('pedir_autorizacao') == 1, 'checked="checked"');?> class="radio" />
                    <label for="aut_0">solicite  a autoriza&ccedil;&atilde;o de qualquer colaborador, enviando esse conte&uacute;do para a <strong>lista p&uacute;blica de autoriza&ccedil;&otilde;es</strong>; ou</label>

          <br />
          <input type="radio" name="pedir_autorizacao" <?=Util::iif($contbo->getValorCampo('pedir_autorizacao') == 2, 'checked="checked"');?> value="2" id="aut_1" class="radio"  />
          <label for="aut_1">solicite a autoriza&ccedil;&atilde;o de um <strong>colaborador</strong> espec&iacute;fico</label>
 
          
        </p>
          Este conte&uacute;do ser&aacute; enviado para:
          <input type="text" value="<?=$contbo->getValorCampo("colaboradores_lista")?>" name="colaboradores_lista" id="nome_colaborador" class="txt" <?=$contbo->verificaErroCampo("colaboradores_lista")?> />
        </fieldset>
      </div>