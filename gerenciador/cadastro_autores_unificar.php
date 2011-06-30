<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$editar = (int)$_POST['editar'];
$codautor1 = (int)$_GET['cod1'];
$codautor2 = (int)$_GET['cod2'];

include_once("classes/bo/AutorUnificarBO.php");
$autorbo = new AutorUnificarBO;

if ($editar) {
	try {
		$cod_usuario = $autorbo->editar($_POST, $_FILES);
		$exibir_form = false;
		Header("Location: cadastro_autor_publicado.php?cod=".$cod_usuario);
		die;
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($codautor1)
	$autorbo->setValorCampo('codautor1', $codautor1);

if ($codautor2)
	$autorbo->setValorCampo('codautor2', $codautor2);

$codautor1 = (int)$autorbo->getValorCampo('codautor1');
$codautor2 = (int)$autorbo->getValorCampo('codautor2');

if ($codautor1 && $codautor2) {
	$autorbo->getDadosAutorUm($codautor1);
	$autorbo->getDadosAutorDois($codautor2);
}

$item_menu = 'cadastro';
$item_submenu = 'inicio';
$paginatitulo = 'Usu&aacute;rios';
include('includes/topo.php');
?>
    <h2>Usu&aacute;rios</h2>

<?php if (count($erro_mensagens)): ?>
	<p class="box-erro">Preenha os campos obrigat&oacute;rios</p>
<?php endif; ?>

    <form action="cadastro_autores_unificar.php" method="post">
    
    <input type="hidden" name="editar" value="1" />
	<input type="hidden" name="codautor1" value="<?=$codautor1?>" />
    <input type="hidden" name="codautor2" value="<?=$codautor2?>" />
    
    <h3 class="titulo">Unificar de cadastros</h3>
    <div class="box">
      <table border="0" id="unificar">
        <tr>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>Cadastrado em <?=$autorbo->getValorCampoAutorUm('data_cadastro');?></th>
          <th>&nbsp;</th>
          <th>Cadastrado em <?=$autorbo->getValorCampoAutorDois('data_cadastro');?></th>
        </tr>
        
		<tr>
          <td>Nome art&iacute;stico*</td>
          <td><input type="radio" name="nomeartistico" class="radio" id="op1" value="1" <?=Util::iif($autorbo->getValorCampo("nomeartistico") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op1"><?=$autorbo->getValorCampoAutorUm('nomeartistico');?></label> </td>
          <td><input name="nomeartistico" type="radio" class="radio" id="op1-1" value="2" <?=Util::iif($autorbo->getValorCampo("nomeartistico") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op1-1"><?=$autorbo->getValorCampoAutorDois('nomeartistico');?></label> </td>
        </tr>
        
        <tr>
          <td>Nome Completo*</td>
		  <td><input type="radio" name="nomecompleto" class="radio" id="op2" value="1" <?=Util::iif($autorbo->getValorCampo("nomecompleto") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><?=$autorbo->getValorCampoAutorUm('nomecompleto');?></td>
		  <td><input type="radio" name="nomecompleto" class="radio" id="op2-1" value="2" <?=Util::iif($autorbo->getValorCampo("nomecompleto") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op2-1"><?=$autorbo->getValorCampoAutorDois('nomecompleto');?></label></td>
        </tr>
        
        <tr>
          <td>CPF</td>
          <td><input name="cpf" type="radio" class="radio" id="op3" value="1" <?=Util::iif($autorbo->getValorCampo("cpf") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op3"><?=$autorbo->getValorCampoAutorUm('cpf');?></label></td>
          <td><input type="radio" name="cpf" class="radio" id="op3-1" value="2" <?=Util::iif($autorbo->getValorCampo("cpf") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op3-1"><?=$autorbo->getValorCampoAutorDois('cpf');?></label></td>
        </tr>
        
        <tr>
          <td>Data de nascimento</td>
          <td><input name="datanascimento" type="radio" class="radio" id="op4" value="1" <?=Util::iif($autorbo->getValorCampo("datanascimento") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op4"><?=$autorbo->getValorCampoAutorUm('datanascimento');?></label></td>
          <td><input type="radio" name="datanascimento" class="radio" id="op4-1" value="2" <?=Util::iif($autorbo->getValorCampo("datanascimento") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op4-1"><?=$autorbo->getValorCampoAutorDois('datanascimento');?></label></td>
        </tr>
        
        <tr>
          <td>Falecido</td>
		  <td><input name="falecido" type="radio" class="radio" id="op5" value="1" <?=Util::iif($autorbo->getValorCampo("falecido") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op5">N&atilde;o</label></td>
		  <td><input type="radio" name="falecido" class="radio" id="op5-1" value="2" <?=Util::iif($autorbo->getValorCampo("falecido") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op5-1">Sim</label></td>
        </tr>
        
        <tr>
          <td>Biografia*</td>
          <td><input name="biografia" type="radio" class="radio" id="op6" value="1" <?=Util::iif($autorbo->getValorCampo("biografia") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op6"><?=$autorbo->getValorCampoAutorUm('biografia');?></label></td>
          <td><input type="radio" name="biografia" class="radio" id="op6-1" value="2" <?=Util::iif($autorbo->getValorCampo("biografia") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op6-1"><?=$autorbo->getValorCampoAutorDois('biografia');?></label></td>
        </tr>
        
        <tr>
          <td>Imagem</td>
          <td><input type="radio" name="imagem" class="radio" id="op22" value="1" <?=Util::iif($autorbo->getValorCampo("imagem") == 1, "checked=\"checked\"")?> /></td>
          <td valign="top"><label for="op22">
		  <?php if (!$autorbo->getValorCampoAutorUm('imagem_visualizacao')): ?>
		  <img src="img/imagens-padrao/autor.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
		  <?php else: ?>
		  <img src="exibir_imagem.php?img=<?=$autorbo->getValorCampoAutorUm('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" /></label></td>
          <?php endif; ?>
          
          <td><input type="radio" name="imagem" class="radio" id="op22-1" value="2" <?=Util::iif($autorbo->getValorCampo("imagem") == 2, "checked=\"checked\"")?> /></td>
          <td><label for="op22-1">
		  <?php if (!$autorbo->getValorCampoAutorDois('imagem_visualizacao')): ?>
		  <img src="img/imagens-padrao/autor.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
		  <?php else: ?>
		  <img src="exibir_imagem.php?img=<?=$autorbo->getValorCampoAutorDois('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" /></label></td>
          <?php endif; ?>
		  </label></td>
        </tr>
        
        <tr>
          <td>Endere&ccedil;o</td>
          <td><input type="radio" name="endereco" class="radio" id="op7" value="1" <?=Util::iif($autorbo->getValorCampo("endereco") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><?=$autorbo->getValorCampoAutorUm('endereco')?></td>
		  <td><input type="radio" name="endereco" class="radio" id="op7-1" value="2" <?=Util::iif($autorbo->getValorCampo("endereco") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op7-1"><?=$autorbo->getValorCampoAutorDois('endereco')?></label></td>
        </tr>
        
        <tr>
          <td>Complemento</td>
		  <td><input type="radio" name="complemento" class="radio" id="op8" value="1" <?=Util::iif($autorbo->getValorCampo("complemento") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op8"><?=$autorbo->getValorCampoAutorUm('complemento')?></label></td>
		  <td><input name="complemento" type="radio" class="radio" id="op8-1" value="2" <?=Util::iif($autorbo->getValorCampo("complemento") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op8-1"><?=$autorbo->getValorCampoAutorDois('complemento')?></label></td>
        </tr>
        
        <tr>
          <td>Bairro</td>
          <td><input name="bairro" type="radio" class="radio" id="op9" value="1" <?=Util::iif($autorbo->getValorCampo("bairro") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op9"><?=$autorbo->getValorCampoAutorUm('bairro')?></label></td>
          <td><input type="radio" name="bairro" class="radio" id="op9-1" value="2" <?=Util::iif($autorbo->getValorCampo("bairro") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('bairro')?></td>
        </tr>
        
        <tr>
          <td>CEP</td>
          <td><input name="cep" type="radio" class="radio" id="op10" value="1" <?=Util::iif($autorbo->getValorCampo("cep") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op10"><?=$autorbo->getValorCampoAutorUm('cep')?></label></td>
          <td><input type="radio" name="cep" class="radio" id="op10-1" value="2" <?=Util::iif($autorbo->getValorCampo("cep") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('cep')?></td>
        </tr>
        
        <tr>
          <td>Pa&iacute;s*</td>
          <td><input type="radio" name="pais" class="radio" id="op11" value="1" <?=Util::iif($autorbo->getValorCampo("pais") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><?=$autorbo->getValorCampoAutorUm('pais')?></td>
          <td><input name="pais" type="radio" class="radio" id="op11-1" value="2" <?=Util::iif($autorbo->getValorCampo("pais") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op11-1"><?=$autorbo->getValorCampoAutorDois('pais')?></label></td>
        </tr>
        
		<tr>
          <td>Estado*</td>
          <td><input name="estado" type="radio" class="radio" id="op12" value="1" <?=Util::iif($autorbo->getValorCampo("estado") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op12"><?=$autorbo->getValorCampoAutorUm('estado')?></label></td>
          <td><input type="radio" name="estado" class="radio" id="op12-1" value="2" <?=Util::iif($autorbo->getValorCampo("estado") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('estado')?></td>
        </tr>
        
        <tr>
          <td>Cidade*</td>
          <td><input name="cidade" type="radio" class="radio" id="op13" value="1" <?=Util::iif($autorbo->getValorCampo("cidade") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op13"><?=$autorbo->getValorCampoAutorUm('cidade')?></label></td>
          <td><input type="radio" name="cidade" class="radio" id="op13-1" value="2" <?=Util::iif($autorbo->getValorCampo("cidade") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('cidade')?></td>
        </tr>
        
		<tr>
          <td>Telefone</td>
          <td><input name="telefone" type="radio" class="radio" id="op14" value="1" <?=Util::iif($autorbo->getValorCampo("telefone") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op14"><?=$autorbo->getValorCampoAutorUm('telefone')?></label></td>
          <td><input type="radio" name="telefone" class="radio" id="op14-1" value="2" <?=Util::iif($autorbo->getValorCampo("telefone") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('telefone')?></td>
        </tr>
        
        <tr>
          <td>Celular</td>
          <td><input name="celular" type="radio" class="radio" id="op15" value="1" <?=Util::iif($autorbo->getValorCampo("celular") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op15"><?=$autorbo->getValorCampoAutorUm('celular')?></label></td>
          <td><input type="radio" name="celular" class="radio" id="op15-1" value="2" <?=Util::iif($autorbo->getValorCampo("celular") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><?=$autorbo->getValorCampoAutorDois('celular')?></td>
        </tr>
        
        <tr>
          <td>Email</td>
          <td><input type="radio" name="email" class="radio" id="op16" value="1" <?=Util::iif($autorbo->getValorCampo("email") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><?=$autorbo->getValorCampoAutorUm('email')?></td>
          <td><input name="email" type="radio" class="radio" id="op16-1" value="2" <?=Util::iif($autorbo->getValorCampo("email") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op16-1"><?=$autorbo->getValorCampoAutorDois('email')?></label></td>
        </tr>
        
        <tr>
          <td>Site</td>
          <td><input type="radio" name="site" class="radio" id="op17" value="1" <?=Util::iif($autorbo->getValorCampo("site") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top">http://<?=$autorbo->getValorCampoAutorUm('site')?></td>
          <td><input name="site" type="radio" class="radio" id="op17-1" value="2" <?=Util::iif($autorbo->getValorCampo("site") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op17-1">http://<?=$autorbo->getValorCampoAutorDois('site')?></label></td>
        </tr>
        
        <tr>
          <td>Comunicadores</td>
          <td><input name="contato" type="radio" class="radio" id="op18" value="1" <?=Util::iif($autorbo->getValorCampo("contato") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top">
          <label for="op18">
		  <?php
		  foreach($autorbo->getValorCampoAutorUm('contato') as $key => $value):
		  ?>
		  <?=Util::getTipoContato($value['cod_comunicador']);?> - <?=$value['nome_usuario'];?><br />
		  <?
		  endforeach;
		  ?>
		  </label>
		  </td>
          <td><input type="radio" name="contato" class="radio" id="op18-1" value="2" <?=Util::iif($autorbo->getValorCampo("contato") == 2, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top">
          <label for="op18">
		  <?php
		  foreach($autorbo->getValorCampoAutorDois('contato') as $key => $value):
		  ?>
		  <?=Util::getTipoContato($value['cod_comunicador']);?> - <?=$value['nome_usuario'];?><br />
		  <?
		  endforeach;
		  ?>
		  </label>
		  </td>
        </tr>
        
		<tr>
          <td>Links relacionados</td>
          <td><input type="radio" name="sitesrelacionados" class="radio" id="op19" value="1" <?=Util::iif($autorbo->getValorCampo("sitesrelacionados") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top">
		  <label for="op19">
		  <?php
		  foreach($autorbo->getValorCampoAutorUm('sitesrelacionados') as $key => $value):
		  ?>
		  <?=$value['site'];?> - <?=$value['url'];?><br />
		  <?
		  endforeach;
		  ?>
		  </label>
		  </td>
          <td><input name="sitesrelacionados" type="radio" class="radio" id="op19-1" value="2" <?=Util::iif($autorbo->getValorCampo("sitesrelacionados") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op19-1">
		  <?php
		  foreach($autorbo->getValorCampoAutorDois('sitesrelacionados') as $key => $value):
		  ?>
		  <?=$value['site'];?> - <?=$value['url'];?><br />
		  <?
		  endforeach;
		  ?>
			</label>
		</td>
        </tr>
        
		<tr>
          <td>P&aacute;gina pessoal*</td>
          <td><input name="finalendereco" type="radio" class="radio" id="op20" value="1" <?=Util::iif($autorbo->getValorCampo("finalendereco") == 1, "checked=\"checked\"")?> /></td>
          <td width="300" valign="top"><label for="op20">www.iteia.org.br/<strong><?=$autorbo->getValorCampoAutorUm('finalendereco')?></strong></label></td>
          <td><input type="radio" name="finalendereco" class="radio" id="op20-1" value="2" <?=Util::iif($autorbo->getValorCampo("finalendereco") == 2, "checked=\"checked\"")?> /></td>
          <td width="300"><label for="op20-1">www.iteia.org.br/<strong><?=$autorbo->getValorCampoAutorDois('finalendereco')?></strong></label></td>
        </tr>
        
        <tr>
          <td>Tipo*</td>
          <td><input name="tipo_autor" type="radio" class="radio" id="op21" value="1" <?=Util::iif($autorbo->getValorCampo("tipo_autor") == 1, "checked=\"checked\"")?> /></td>
          <td valign="top"><label for="op21">Wiki</label></td>
          <td><input name="tipo_autor" type="radio" class="radio" id="op21-1" value="2" <?=Util::iif($autorbo->getValorCampo("tipo_autor") == 2, "checked=\"checked\"")?> />
		  </td>
          <td><label for="op21-1">Autor</label></td>
        </tr>
      </table>
    </div>

      <div id="botoes" class="box">
	  	<a href="cadastro.php" class="bt bt-cancelar">Cancelar</a>
        <input type="submit" class="bt-gravar" value="Salvar" />
      </div>
      
    </form>
  
<?php
include('includes/rodape.php');