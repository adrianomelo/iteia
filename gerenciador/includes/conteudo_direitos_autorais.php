<div class="box" id="direitos">
        <fieldset>
        <legend>Direitos autorais</legend>
        <p class="dica">O iTEIA permite a licenca de conte&uacute;dos em copyleft utilizando licen&ccedil;as Creative Commons,  mantendo  direitos autorais e posibilitando a outros copiar e distribuir sua obra, contanto que atribuam cr&eacute;dito  e somente sob as condi&ccedil;&otilde;es especificadas. <a href="conteudo_licencas.php?height=400&amp;width=550" title="Licen&ccedil;as Creative Commons"  class="thickbox">Saiba mais sobre as licen&ccedil;as</a></p>
          <p>Qual licen&ccedil;a ser&aacute; usada para essa obra?</p>

        <p>
        <input name="direitos" type="radio" id="direitos_0" value="1" <?=($contbo->getValorCampo("direitos") == 1)?" checked=\"checked\"":""?> />
      	<label  for="direitos_0">Dom&iacute;nio p&uacute;blico</label>
      	<br />
          <small>(A obra ficar&aacute; livre para ser distribu&iacute;da sem fins comerciais)</small> </p>
    	<p>
      	<input name="direitos" type="radio" value="2" id="direitos_1" <?=($contbo->getValorCampo("direitos") == 2)?" checked=\"checked\"":""?> />
      	<label for="direitos_1">Todos direitos reservados (Copyright)</label>
      	<br />
            <small>(Apenas voc&ecirc; ter&aacute; autonomia para ceder ou comercializar esta obra. O arquivo desse conte&uacute;do <strong>n&atilde;o</strong> ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small></p>
      	<input name="direitos" type="radio" id="direitos_2" value="3"<?=($contbo->getValorCampo("direitos") == 3)?" checked=\"checked\"":""?> />
      	<label for="direitos_2" id="lbl-alguns-direitos">Alguns direitos reservados (Copyleft)</label><br />
      	<small>(Qualquer pessoa poder&aacute; copiar e distribuir essa obra, desde que atribuam o cr&eacute;dito da mesma. O arquivo desse conte&uacute;do ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small>
        <div id="alguns-direitos" class="display-none"> 
        <p><strong>Permitir o uso comercial da obra?</strong></p>
      <input name="cc_usocomercial" type="radio" id="radio" value="1"<?=($contbo->getValorCampo("cc_usocomercial") == 1)?" checked=\"checked\"":""?> />
      <label for="radio">Sim</label>
      <br />
      <input type="radio" name="cc_usocomercial" id="radio2" value="2"<?=($contbo->getValorCampo("cc_usocomercial") == 2)?" checked=\"checked\"":""?> />
      <label for="radio2">Não</label>
      <p><strong>Permitir modifica&ccedil;&otilde;es em sua obra?</strong></p>
      <input name="cc_obraderivada" type="radio" id="radio3" value="1"<?=($contbo->getValorCampo("cc_obraderivada") == 1)?" checked=\"checked\"":""?> />
      <label for="radio3">Sim</label>
      <br />
      <input type="radio" name="cc_obraderivada" id="radio4" value="2"<?=($contbo->getValorCampo("cc_obraderivada") == 2)?" checked=\"checked\"":""?> />
      <label for="radio4">Sim, contanto que outros compartilhem com a mesma licen&ccedil;a</label>
      <br />
      <input type="radio" name="cc_obraderivada" id="radio5" value="3"<?=($contbo->getValorCampo("cc_obraderivada") == 3)?" checked=\"checked\"":""?> />
      <label for="radio5">N&atilde;o</label>
      </div>
      </fieldset>
        <br />
        <div id="licencas"><strong>A licença desta obra ser&aacute;:</strong><br />
		<img src="img/cc/dp.gif" id="dp" alt="Dom&iacute;nio p&uacute;blico" title="Dom&iacute;nio p&uacute;blico" width="32" height="32" />
        <img src="img/cc/copyright.gif" id="copyright" alt="Todos direitos reservados (Copyright)" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
        <img src="img/cc/share.gif" id="share" alt="copiar, distribuir, exibir e executar a obra" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
        <img src="img/cc/remix.gif" id="remix" alt="criar obras derivadas" title="criar obras derivadas" width="32" height="32" />
        <img src="img/cc/by.gif" id="by" alt="Atribui&ccedil;&atilde;o" title="Atribui&ccedil;&atilde;o" width="32" height="32" />
        <img src="img/cc/nc.gif" id="nc" alt="Uso N&atilde;o Comercial" title="Uso N&atilde;o Comercial" width="32" height="32" />
        <img src="img/cc/sa.gif" id="sa" alt="Compartilhamento pela mesma Licen&ccedil;a" title="Compartilhamento pela mesma Licen&ccedil;a" width="32" height="32" />
        <img src="img/cc/nomod.gif" id="nomod" alt="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" title="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" width="32" height="32" />
          <div id="link"></div> 
          </div>
</div>