<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="content-language" content="pt-br" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="Keywords" content="" />
<meta name="robots" content="all" />
<meta name="revisit-after" content="1 day" />
<meta name="author" content="Billy Blay" />
<meta name="rating" content="General" />
<meta name="DC.title" content="Instituto Intercidadania" />
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.png" />
<link  rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie6.css" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie7.css" />
<![endif]-->
<?php if (!$js_sem_jquery): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<?php endif; ?>
<?php if ($js_jquery_ui): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js"></script>
<?php endif; ?>
<?php if ($js_player): ?>
<script type="text/javascript" src="/js/flowplayer-3.1.5/flowplayer-3.1.4.min.js"></script>
<?php endif; ?>
<?php if ($js_galeria): ?>
<script type="text/javascript" src="/js/jcarousel/jquery.jcarousel.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jcarousel/jquery.jcarousel.css" />
<link rel="stylesheet" type="text/css" href="/css/carrossel.css" />
<?php endif; ?>
<?php if ($js_busca): ?>
<script type="text/javascript" src="/js/input_mask/jquery.maskedinput-1.1.2.js" ></script>
<?php endif; ?>
<script type="text/javascript" src="/js/script_achanoticias.js"></script>
<?php if($css_mapa): ?>
<link rel="stylesheet" type="text/css" href="/css/map.css" />
<?php endif; ?>
<?php if($jsthickbox): ?>
<script type="text/javascript" src="/js/thickbox/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="/js/thickbox/thickbox.css" />
<?php endif; ?>
<?php if($jsconteudo): ?>
<script type="text/javascript" src="/js/conteudo.js"></script>
<?php endif; ?>
<?php if($jsautores): ?>
<script type="text/javascript" src="/js/autores.js"></script>
<?php endif; ?>
<?php if($js_texto): ?>
<script type="text/javascript">
<!--
jQuery(document).ready(function() {
	<?php if ($js_galeria): ?>
	jQuery("#mycarousel").jcarousel({ scroll: 3 });
	<?php endif; ?>
	$("div#bookmarks").hide();
	$("#compartilhe").click(function(){
		$("div#bookmarks").toggle();
		return false;
	});
	/*$('.aviso a').click(function() {
		var target = $(this).attr("href");
		$(target).focus();
		return false;
	});*/

	$list = $("#criado li").size();
	if ($list > 2) {
		$("#criado li:gt(1)").hide();
		$("#criado .todos").html('<strong><a class="mostra" title="Ver todos autores deste conteúdo">Mostrar autores<\/a><\/strong>');
		$('.mostra').toggle(function() {
			$("#criado li:gt(0)").show();
			$(this).addClass("esconde").removeClass("mostra").text("Ocultar autores").attr('title','Esconder autores deste conteúdo');
		}, function() {
			$("#criado li:gt(1)").hide();
			$(this).addClass("mostra").removeClass("esconde").text("Mostrar autores").attr('title','Ver todos autores deste conteúdo');
		});
	};
	<?=$js_ready_complemento?>
});
-->
</script>
<? endif; ?>
<?php if($js_denuncie): ?>
<script type="text/javascript" src="/js/denuncie.js"></script>
<? endif; ?>
<?php if($js_contato): ?>
<script type="text/javascript" src="/js/contato.js">
</script>
<? endif; ?>
<?php if($js_usuariocontato): ?>
<script type="text/javascript" src="/js/usuariocontato.js"></script>
<? endif; ?>
<?php if($js_bookmarks): ?>
<script type="text/javascript">
$(function() {
$("div#bookmarks").hide();
$("#compartilhe").click(function(){
$("div#bookmarks").toggle();
return false;
});
})
</script>
<? endif; ?>
<?php if($js_index): ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
		<?php if ($js_galeria): ?>
		jQuery("#mycarousel").jcarousel({ scroll: 3 });
		<?php endif; ?>
	});
</script>
<link  rel="stylesheet" type="text/css" media="screen" href="/css/slider.css" />
<? endif; ?>
<title><?=Util::iif($titulopagina, $titulopagina . ' | ') . Util::iif($titulopagina_index, $titulopagina_index, 'iTEIA');?></title>
</head>
<body<?=($topo_class?' class="'.$topo_class.'"':'')?>>
<p id="descer"><a href="#conteudo" tabindex="1" title="Pular a navegação e ir direto para o conteúdo">Pular a navegação e ir direto para o conteúdo</a></p>
<div id="busca-achix">
	<a href="http://www.achix.com.br" id="achix" title="Ir para o site do Achix">Tecnologia Achix</a>
  <form method="get" action="/busca_action.php" id="searchform" name="searchform"><!-- onsubmit="return false;"-->
    <fieldset>
			<input type="hidden" name="buscar" value="1" />
			<input type="hidden" name="novabusca" value="1" />
			<input type="hidden" name="tipo_assunto" value="2" />
			<input type="hidden" name="formatos" id="buscarpor" value="0" />
    <legend class="none">Busca</legend>
    <div id="box-input">
      <label for="termo" class="none">Digite uma palavra-chave</label>
      <input type="text" name="palavra" class="termo" onfocus="this.value = ''" id="termo" tabindex="2" value="Ache tudo aqui" />
    </div>
    <div id="box-select">
      <label for="myselectbox" class="none">Buscar por</label>
      <select id="myselectbox" name="buscatopo_tipo">
        <option value="0" selected="selected">Todos</option>
        <option value="2">Áudios</option>
        <option value="3">Vídeos</option>
        <option value="4">Textos</option>
        <option value="5">Imagens</option>
        <option value="6">Notícias</option>
        <option value="7">Eventos</option>
        <option value="9">Autores</option>
        <option value="10">Colaboradores</option>
      </select>
    </div>
    </fieldset>
    <fieldset class="escolha">
    <label>
    <input type="checkbox" value="1" name="destbusca" id="destbusca" onclick="javascript:setBuscaOpc(1);" /> no Achix</label>
    </fieldset>
    <button type="submit" value="Buscar" onclick="javascript:EfetuarBusca(0);" name="botao">Buscar</button>
		<a href="javascript:;" onclick="javascript:BuscaAvanc();" id="busca-avancada">Busca Avançada</a>
  </form>
</div>
<div id="tudo">
<div id="login">
    <?php if ((!$_SESSION['logado']) && (!$_SESSION['logado_dados']['cod'])): ?>
    <a href="/gerenciador/login.php">Entrar</a> | <a href="participar">Não é cadastrado?</a>
	<?php else: ?>
    Olá, <a href="/gerenciador" id="autor-nome"><?=$_SESSION['logado_dados']['nome'];?></a>
    - <a href="/gerenciador/logout.php" id="sair">Sair</a>
	<?php endif; ?>
</div>
  <div id="topo" class="vcard"><strong class="fn" id="logo"><a href="/" tabindex="2" class="url" rel="me" title="Ir para página inicial">iTEIA</a></strong>
    <div id="descricao"></div>
    <div id="banner">
      <img src="/img/patrobras01.jpg" width="745" />
    </div>
    <div id="menu">
      <ul>
        <li class="canais<?=($ativa == 1 ? ' ativa':'')?>"><a href="/canais" tabindex="3" title="Canais">Canais</a></li>
        <li class="audios<?=($ativa == 2 ? ' ativa':'')?>"><a href="/audios" tabindex="4" title="Áudios">Áudios</a></li>
        <li class="videos<?=($ativa == 3 ? ' ativa':'')?>"><a href="/videos" tabindex="5" title="Vídeos">Vídeos</a></li>
        <li class="textos<?=($ativa == 4 ? ' ativa':'')?>"><a href="/textos" tabindex="6" title="Textos">Textos</a></li>
        <li class="imagens<?=($ativa == 5 ? ' ativa':'')?>"><a href="/imagens" tabindex="7" title="Imagens">Imagens</a></li>
        <li class="noticias<?=($ativa == 6 ? ' ativa':'')?>"><a href="/jornal" tabindex="8" title="Jornal">Jornal</a></li>
        <li class="eventos<?=($ativa == 7 ? ' ativa':'')?>"><a href="/eventos" tabindex="9" title="Eventos">Eventos</a></li>
        <li class="autores<?=($ativa == 8 ? ' ativa':'')?>"><a href="/autores" tabindex="10" title="Autores">Autores</a></li>
        <li class="colaboradores<?=($ativa == 9 ? ' ativa':'')?>"><a href="/colaboradores" tabindex="11" title="Colaboradores">Colaboradores</a></li>
        <li class="grupos<?=($ativa == 10 ? ' ativa':'')?>"><a href="/grupos" tabindex="12" title="Grupos">Grupos</a></li>
      </ul>
    </div>
  </div>
 <div id="conteiner">