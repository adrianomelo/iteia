<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codnewsletter = (int)$_GET["cod"];
$editar = (int)$_POST['editar'];
$edicaodados = (int)$_POST["edicaodados"];

if ($codnewsletter)
	$edicaodados = 1;

include_once("classes/bo/NewsletterEdicaoBO.php");
$newsbo = new NewsletterEdicaoBO;
$exibir_form = true;

if (!$codnewsletter && !$editar) {
	unset($_SESSION["sessao_newsletter_itens"]);
	$newsbo->limpaItens();
}

if ($editar) {
	try {
		$codnewsletter = $newsbo->editar($_POST);
		$exibir_form = false;

		Header("Location: home_newsletter.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$paginatitulo = 'Destaque';
$item_menu = "home";
$item_submenu = "newsletter_inserir";
include('includes/topo.php');

if ($codnewsletter && !$editar)
	$newsbo->setDadosCamposEdicao($codnewsletter);

$codnewsletter = (int)$newsbo->getValorCampo("codnewsletter");
?>
<script type="text/javascript" src="jscripts/home_newsletter.js"></script>

<script type="text/javascript">
var cod_newsletter = <?=$codnewsletter;?>;
</script>

    <h2>Newsletter</h2>
    
	<form method="post" name="lightbox" id="lightbox_form" action="home_newsletter_inserir.php" onsubmit="return false;">
	<div id="op-comentario">
	 <a href="home_newsletter.php">In&iacute;cio</a> | <strong>Criar newsletter</strong> | <a href="home_newsletter_listas.php">Listas de emails</a> | <a href="home_newsletter_emails_cadastrar.php">Cadastrar emails</a></div>
    
    <fieldset>
		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
		<input type="hidden" name="codnewsletter" value="<?=$codnewsletter?>" />
		<input type="hidden" name="acao" id="acao" value="" />
	</fieldset>

      <h3 class="titulo">Criar  newsletter </h3>
      <div class="box" id="destaques">
      <p>Escolha a data e hora na qual será iniciado o envio da newsletter </p>
        
		<label for="textfield1">Título da Newsletter:*</label><br />
        <input type="text" class="txt" <?=$newsbo->verificaErroCampo('titulo')?> id="textfield1" name="titulo" size="75" onkeyup="contarCaracteres(this, 'cont_titulo', 255);" value="<?=(stripslashes($newsbo->getValorCampo('titulo')))?>" />
        <input type="text" id="cont_titulo" class="txt counter" value="<?=Util::iif($newsbo->getValorCampo('titulo'), 255 - strlen($newsbo->getValorCampo('titulo')), 255);?>" size="4" disabled="disabled" /><br />
																																	
        <label for="textfield2">De:</label><br />
        <input type="text" class="txt" id="textfield2" size="75" value="fundarpe@gmail.com" disabled="disabled"/>
       
        <br />
        
        <label for="select5">Listas:</label><br />
        <select id="select5" name="select2" class="select">
		<?php foreach($newsbo->getListasEnvio() as $value): ?>
			<option value="<?=$value['titulo'];?>"><?=$value['titulo'];?></option>
		<?php endforeach; ?>
		</select>
		<input type="submit" class="bt" value="Adicionar" onclick="javascript:addListaParaEnvio();" /><br />
			 
		<br />
			 
		<label for="area5">Para:*  (Digite os emails separados por virgula)</label><br />
		<textarea name="envio_para" cols="45" <?=$newsbo->verificaErroCampo('envio_para')?> rows="5" id="area5"><?=$newsbo->getValorCampo('envio_para')?></textarea>
		
		<br />
		
        <fieldset class="campos">
        <div>
          <label for="dFrom">Data:<span>*</span></label>
          <br />
          <input type="text" <?=$newsbo->verificaErroCampo("data_inicio")?> value="<?=htmlentities(stripslashes($newsbo->getValorCampo("data_inicio")))?>" name="data_inicio" class="txt calendario date" id="dFrom" />
          <em><small><br />
          dd/mm/aaaa</small></em></div>
        </fieldset>
        
        <fieldset class="campos">
        <div>
          <label for="textfield7">Hora:<span>*</span></label>
          <br />
          <input type="text" <?=$newsbo->verificaErroCampo("hora_inicio")?> value="<?=htmlentities(stripslashes($newsbo->getValorCampo("hora_inicio")))?>" name="hora_inicio" class="txt hour" id="textfield7" />
          <br />
          <em><small>hh:mm</small></em></div>
        </fieldset>
        
		<div class="separador-hr"></div>
      </div>
      <h3 class="titulo">Escolha os itens que deseja incluir</h3>
        <div id="adicionar" class="box">
          <fieldset>
          <p>Procure abaixo por conteúdos que estejam cadastrados no sistema</p>
            <div class="campos">
            <label for="textfield">Palavras-chave</label>
            <br />
            <input name="palavrachave" type="text" class="txt" id="relacionar_palavrachave"  />
          </div>
          <div class="campos">
            <label for="type">Filtrar por</label>
            <br />
            <select id="relacionar_buscarpor" name="buscarpor">
	          <option value="titulo" selected="selected">T&iacute;tulo</option>
	          <option value="autor">Autor</option>
	          <option value="ativo">Ativo</option>
	          <option value="inativo">Inativo</option>
        	</select>
          </div>
          <div class="campos">
            <label for="select3">Tipo </label>
            <br />
            <select name="formato" id="relacionar_formato">
	          <option value="0" selected="selected">Todos</option>
	          <option value="1">Texto</option>
	          <option value="3">&Aacute;udio</option>
	          <option value="4">V&iacute;deo</option>
	          <option value="2">Imagem</option>
	          <option value="5">Not&iacute;cia</option>
        	</select>
            <input type="button" class="bt-buscar" value="Buscar" onclick="javascript:buscaConteudoHome();" />
          </div>
          </fieldset>
          
          <div id="mostra_resultados_relacionamento"></div>
          
        </div>
        
        <div id="mostra_selecionadas_homeconteudo"></div>
        <div id="up_edicao"></div>
    
	</div>
	</form>
	
      <div id="botoes" class="box">
      	<a href="home_newsletter_previsao.php?cod=<?=$codnewsletter;?>" target="_blank" class="bt bt-cancelar">Ver</a>
		<input name="submit" onclick="javascript:acaoNewsletter(1);" type="button" class="bt bt-gravar" value="Gravar" />
	  	<!--<input name="submit" onclick="javascript:acaoNewsletter(2);" type="button" class="bt-gravar" value="Enviar" />-->
      </div>
    
    
    </div>
<?php if (count($_SESSION["sessao_newsletter_itens"])): ?>
<script language="javascript" type="text/javascript">
listarConteudoNewsletter();
</script>
<?php endif; ?>
<?php include('includes/rodape.php'); ?>
