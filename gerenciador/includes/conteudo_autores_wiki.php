<div class="box box-amarelo" id="ficha-tecnica">
        <fieldset>
		<input type="hidden" id="autor_selecionado" value="" />
		<input type="hidden" id="autor_selecionado_atualizar" value="" />
        <legend class="seta">Ficha t&eacute;cnica</legend>
<?php
	if (!count($_SESSION['sess_conteudo_autores_ficha'][$sessao_id])) {
?>
          <div class="fechada">
<?php
	}
	else {
?>
          <div>
<?php
	}
?>
			<div id="sou_autor_conteudo" style="display:none;">
				<label for="atividade">Qual a atividade exercida por voc&ecirc; na realiza&ccedil;&atilde;o deste conte&uacute;do?</label>
  <br />

            	<select name="select" id="ficha_atividade_pessoal">
            	<option value="0">Selecione</option>
              <?php foreach ($contbo->getListaAtividades() as $key => $value): ?>
            		<option value="<?=$value['cod'];?>"><?=$value['atividade'];?></option>
              <?php endforeach; ?>
            </select>
            <strong><a href="javascript:void(0);" onclick="javascript:adicionarAutorFichaPessoal();" class="add">[+] Adicionar</a></strong>
			</div>
			
          <p>Digite abaixo o nome da pessoa que voc&ecirc; deseja incluir como um dos autores desta   obra. Caso ele ainda n&atilde;o seja cadastrado, ser&aacute; necess&aacute;rio preencher todo o   formul&aacute;rio para adicion&aacute;-lo.</p>
            <div class="campos">
            <label for="textfield18">Nome* </label>
            <br />
            <input type="text" name="nome_autor_wiki" id="nome_autor_wiki" class="txt" size="60" />
          </div>
          <div class="campos">
            <label for="ficha_atividade">Atividade exercida*</label>
            <br />
            <select name="select" id="ficha_atividade">
            	<option value="0">Selecione</option>
              <?php foreach ($contbo->getListaAtividades() as $key => $value): ?>
            		<option value="<?=$value['cod'];?>"><?=$value['atividade'];?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="campos">
            <label for="ficha_nome_completo">Nome completo</label>
            <br />
            <input type="text" class="txt" id="ficha_nome_completo" size="60" />
          </div>
          <div class="campos"><br />
          <input type="checkbox" id="ficha_falecido" name="ficha_falecido" value="1" class="checkbox" />
            <label for="checkbox10">Falecido</label>
            </div>
          <div class="both">

			<div class="campos">
              <label for="select">Pa&iacute;s*</label>
              <br />
              <select name="codpais_autor_wiki" onchange="javascript:exibeEstadoCidade2();" id="pais">
              <?php foreach ($contbo->getListaPaises() as $key => $value): ?>
            		<option value="<?=$value['cod_pais'];?>"<?=($value['cod_pais'] == 2)?' selected="selected"':''?>><?=$value['pais'];?></option>
              <?php endforeach; ?>
              </select>
            </div>

            <div class="campos" id="mostraestado" style="display:inline;">
              <label for="estado">Estado*</label>
              <br />
              <select name="codestado_autor_wiki" id="estado" onchange="obterCidades(this, <?=(int)$codcidade?>)">
              <?php foreach ($contbo->getListaEstados() as $key => $value): ?>
            		<option value="<?=$value['cod_estado'];?>" <?=Util::iif($value['cod_estado'] == 17, 'selected="selected"');?>><?=$value['estado'];?></option>
              <?php endforeach; ?>
              </select>
            </div>

            <div class="campos" id="selectcidade2">
              <label for="selectcidade">Cidade*</label>
              <br />
              <select name="codcidade_autor_wiki" id="selectcidade">
	  		</select>
            </div>

            <div class="campos" id="cidade" style="display:none;">
            	<label for="select6">Cidade*</label>
              	<br /><input type="text" class="txt" name="cidade" id="campocidade" size="45" value="" maxlength="100" />
            </div>

          </div>
            <div class="both">
            <div class="campos">
              <label for="ficha_email">Email</label>
              <br />
              <input type="text" id="ficha_email" class="txt mail" />
            </div>
            <div class="campos">
              <label for="ficha_telefone">Telefone</label>
              <br />
              <input type="text" id="ficha_telefone" class="txt phone" />
              
			<!--
<strong style="display:none;"><a href="javascript:void(0);" id="btn_adicionar" onclick="javascript:adicionarAutorFicha();" class="add">[+] Adicionar</a></strong>
			
			<strong id="btn_editar" style="display:none;"><a href="javascript:void(0);" onclick="javascript:editarAutorFicha();" class="add">[+] Editar</a></strong>
			<strong id="btn_cancelar" style="display:none;"><a href="javascript:void(0);" onclick="javascript:cancelarAutorFicha();" class="add">[+] Cancelar</a></strong>
-->
			  
			  </div>
          </div>
          
          <div class="both">
              <label for="textfield3">Biografia*</label>
              <br />
              <textarea cols="60" name="ficha_descricao" id="ficha_descricao" rows="7" onkeyup="contarCaracteres(this, 'count_ficha_descricao', 250);"></textarea>
              <input type="text" disabled="disabled" class="txt counter" id="count_ficha_descricao" value="250" size="4"  /><br />

              <p><a href="javascript:void(0);" id="btn_adicionar" onclick="javascript:adicionarAutorFicha();" class="bt bt-remover">Adicionar</a></p>
              </div>

            <div id="mostra_autores_wiki_selecionados"></div>

          </div>
        </fieldset>
      </div>

<script type="text/javascript">
var nao_adiciona_wiki = false;
obterCidades(document.getElementById('estado'), 6330);
<?php
	if ($_SESSION['logado_dados']['nivel'] == 2) {
?>
nao_adiciona_wiki = true;
<?php
	}
	//if (count($_SESSION['sess_conteudo_autores_ficha'])) {
?>
exibeListaAutoresFicha();
<?php
	//}
?>
</script>
