<?php
include('verificalogin.php');

$paginatitulo = 'Classificar conte&uacute;do';
$item_menu = "conteudo";
$item_submenu = "inserir";
include('includes/topo.php');
?>
<h2>Conte&uacute;do &gt; Inserir</h2>
    <p>Voc&ecirc; pode incluir no portal iTEIA qualquer conte&uacute;do de sua autoria relacionado &agrave; cultura de Pernambuco.</p>
    <h3 class="titulo">O que voc&ecirc; deseja inserir?</h3>
    <div class="box" id="tipos-conteudo">
       <div>
        <ul>
        	<li><a href="conteudo_edicao_texto.php" title="Inserir novo texto" class="ico-texto ico">Novo Texto</a> 
       	  <p><strong>Texto</strong> - <a href="conteudo_edicao_texto.php">qualquer obra escrita: <em>como artigo, trabalho acad&ecirc;mico, cifra e letra de m&uacute;sica, receita culin&aacute;ria, poema, conto....</em></a> (*)	<br />
       	    <br />
       	    Formatos permitidos: .odt, .ogg, .ods, .doc, .pdf, .ppt, .pps, .rtf, .txt, .xls e .zip</p>
       	  </li>
          <li><a href="conteudo_edicao_imagem.php" title="Inserir nova imagem" class="ico-imagem ico">Nova imagem</a> 
       	  <p><strong>Imagem</strong> - <a href="conteudo_edicao_imagem.php">qualquer produto visual est&aacute;tico:  <em>incluindo fotografia, charge, desenho, pintura...</em></a><br /><br />
Formatos permitidos: JPG, GIF ou PNG.  Postado individualmente ou em galeria.  </p>
          </li>
          <li><a href="conteudo_edicao_video.php" title="Inserir novo v&iacute;deo" class="ico-video ico">Novo V&iacute;deo</a> 
       	  <p><strong>V&iacute;deo</strong> -  <a href="conteudo_edicao_video.php">qualquer tipo de v&iacute;deo: <em>seja document&aacute;rio, anima&ccedil;&atilde;o, fic&ccedil;&atilde;o, videoarte, cobertura de apresenta&ccedil;&atilde;o art&iacute;stica em geral...</em> </a><br />
       	    <br />
       	  Formatos permitidos:  flv, avi, wmv, asf e mpeg. </p>
          </li>
          <li><a href="conteudo_edicao_audio.php" title="Inserir novo &aacute;dio" class="ico-audio ico">Novo &Aacute;udio</a> 
       	  <p><strong>&Aacute;udio</strong> - <a href="conteudo_edicao_audio.php">grava&ccedil;&otilde;es sonoras: <em>como m&uacute;sica, entrevista, programa de r&aacute;dio, podcast...</em></a> <br /><br />

       	    Formato permitido: MP3. Postada individualmente ou em &aacute;lbum.  </p>
          </li>
        </ul>

     <div class="separador">
    <hr />
  </div>

		<small>
		<?php
		if ($_SESSION['logado_dados']['nivel'] == 2):
		?>
		*As not&iacute;cias não devem ser postadas aqui. Apenas os colaboradores podem cadastrar not&iacute;cias no Portal.
		<?php
		else:
		?>
		*As not&iacute;cias não devem ser postadas aqui. Elas possuem um <a href="noticias.php">espa&ccedil;o espec&iacute;fico no portal</a>.
		<?php
		endif;
		?>
		<br />
		</small></div>
    </div>
    </div>
<?php include('includes/rodape.php'); ?>