<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once("classes/bo/TextoEdicaoBO.php");
$contbo = new TextoEdicaoBO;

$cod = (int)$_GET['cod'];
?>

<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>
<script type="text/javascript" src="jscripts/funcoes.js"></script>
<script type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>

<script language="javascript" type="text/javascript">
abreEdicaoAutorFicha(<?=$cod;?>);
</script>

<form action="" method="post" id="ficha-tecnica">
  <fieldset>
  
  <input type="hidden" id="cod_autor_edicao" value="<?=$cod;?>">

<div class="campos">
    <label for="textfield18">Nome*</label>
    <br />
    <input name="text2" type="text" class="txt" id="nome_autor_wiki_edicao" size="60" />
  </div>
  
  <div class="campos">
    <label for="atividade_1">Atividade exercida*</label>
    <br />
    <select name="select" id="ficha_atividade_edicao">
            	<option value="0">Selecione</option>
              <?php foreach ($contbo->getListaAtividades() as $key => $value): ?>
            		<option value="<?=$value['cod'];?>"><?=htmlentities($value['atividade']);?></option>
              <?php endforeach; ?>
            </select>
  </div>
  
  <div class="campos">
    <label for="textfield2">Nome completo</label>
    <br />
    <input name="text2" type="text" class="txt" id="ficha_nome_completo_edicao" size="60" />
  </div>
  
  <div class="campos"><br />
    <input type="checkbox" id="ficha_falecido_edicao" value="1" class="checkbox" />
    <label for="falecido">Falecido</label>
  </div>
  
  <div class="both">
    
    <div class="campos">
        <label for="select">Pa&iacute;s*</label>
        <br />
        <select name="codpais_autor_wiki_edicao" onchange="javascript:exibeEstadoCidade3();" id="pais_edicao">
        <?php foreach ($contbo->getListaPaises() as $key => $value): ?>
            <option value="<?=$value['cod_pais'];?>"<?=($value['cod_pais'] == 2)?' selected="selected"':''?>><?=htmlentities($value['pais']);?></option>
        <?php endforeach; ?>
        </select>
    </div>
    
    <div class="campos" id="mostraestado_edicao" style="display:inline;">
              <label for="estado">Estado*</label>
              <br />
              <select name="codestado_autor_wiki" id="estado_edicao" onchange="obterCidades3(this, <?=(int)$codcidade?>, 'selectcidade_edicao')">
              <?php foreach ($contbo->getListaEstados() as $key => $value): ?>
            		<option value="<?=$value['cod_estado'];?>" <?=Util::iif($value['cod_estado'] == 17, 'selected="selected"');?>><?=htmlentities($value['estado']);?></option>
              <?php endforeach; ?>
              </select>
            </div>
    
	<div class="campos" id="selectcidade3_edicao">
              <label for="selectcidade">Cidade*</label>
              <br />
              <select name="codcidade_autor_wiki" id="selectcidade_edicao">
	  		</select>
            </div>

            <div class="campos" id="cidade_edicao" style="display:none;">
            	<label for="select6">Cidade*</label>
              	<br /><input type="text" class="txt" style="width: 300px;" name="cidade" id="campocidade_edicao" size="30" value="" maxlength="100" />
            </div>
  </div>
  
  <div class="both dados">
    <div class="campos">
              <label for="ficha_email">Email</label>
              <br />
              <input type="text" id="ficha_email_edicao" class="txt mail" />
            </div>
    <div class="campos">
              <label for="ficha_telefone">Telefone</label>
              <br />
              <input type="text"  id="ficha_telefone_edicao" class="txt phone" />
              
			  
			  </div>
  </div>
  <div class="both">
              <label for="textfield3">Biografia*</label>
              <br />
              <textarea cols="60" name="ficha_descricao_edicao" id="ficha_descricao_edicao" rows="7" onkeyup="contarCaracteres(this, 'count_ficha_descricao_edicao', 250);"></textarea>
              <input type="text" disabled="disabled" class="txt counter" id="count_ficha_descricao_edicao" value="250" size="4"  /><br />

              </div>
  </fieldset>
  <input type="button" onclick="javascript:editarAutorFicha();" class="bt-gravar" value="Gravar" />
</form>
<script language="javascript" type="text/javascript">
$("#ficha_telefone_edicao").mask("(99) 9999-9999");
</script>