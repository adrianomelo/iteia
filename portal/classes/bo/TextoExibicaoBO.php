<?php
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/TextoDAO.php");

class TextoExibicaoBO {

    private $textodao = null;

    public function __construct() {
        $this->textodao = new TextoDAO;
    }

    public function exibirConteudo(&$codconteudo, &$conteudo, &$comentbo) {
        $conteudo['dados_arquivo'] = $this->textodao->getArquivoTexto($codconteudo);
        
        include('includes/include_visualizacao_texto.php');
    }

    /*

    public function DownloadArquivo($codconteudo, $conteudo) {
        require_once(ConfigPortalVO::getDirClassesRaiz()."util/dompdf/dompdf_config.inc.php");
        $conteudo['dados_arquivo'] = $this->textodao->getArquivoTexto($codconteudo);

        $html  = "<span class=\"data\">".date("d/m/Y - H:i", strtotime($conteudo['conteudo']['data_cadastro']))."</span><br />\n";
        $html .= "<h3>".$conteudo['conteudo']['titulo']."</h3><br />\n";
        $html .= "<small><strong>Autor(es)</strong></small><br />\n";
        $html .= "<small>".$conteudo['autores']."</small><br /><br />\n";
        $html .= "<small><strong>Enviado por</strong></small><br />\n";
        $html .= "<small>".$conteudo['conteudo']['colaborador']."</small><br /><br />\n";

        $html .= Util::iif($conteudo['conteudo']['imagem'], '<img src="'.ConfigVO::getDirFotos().$conteudo['conteudo']['imagem'].'" width="124" height="124" alt="imagem" class="img-borda img-center" />', '');
        $html .= nl2br($conteudo['conteudo']['descricao'])."\n";

        $template = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_pdf.html');
        $tam_img  = getimagesize(ConfigVO::DIR_SITE.'portal/img/logo-iteia.gif');
        $template = eregi_replace('<!--%IMG_TOPO%-->', ConfigVO::DIR_SITE.'/portal/img/logo-iteia.gif', $template);
        $template = eregi_replace('<!--%WIDTH_IMG_TOPO%-->', (string)$tam_img[0], $template);
        $template = eregi_replace('<!--%HEIGHT_IMG_TOPO%-->', (string)$tam_img[1], $template);
        $template = eregi_replace('<!--%CONTEUDO%-->', $html, $template);

        $dompdf = new DOMPDF();
        $dompdf->load_html($template);
        $dompdf->render();
        $dompdf->stream('texto_'.$conteudo['conteudo']['cod_conteudo'].'.pdf');
    }

    */

    public function DownloadArquivo($codconteudo, $conteudo) {
        require_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
        require_once(ConfigPortalVO::getDirClassesRaiz()."util/dompdf/dompdf_config.inc.php");
		$conteudo['dados_arquivo'] = $this->textodao->getArquivoTexto($codconteudo);

        //if ($conteudo['dados_arquivo']['arquivo']) {
			Util::force_download(file_get_contents(ConfigVO::getDirArquivos().$conteudo['dados_arquivo']['arquivo']), $conteudo['dados_arquivo']['nome_arquivo_original'], '', $conteudo['dados_arquivo']['tamanho']);
			die;
		/*
		} else {
			$html  = "<span class=\"data\">".date("d/m/Y - H:i", strtotime($conteudo['conteudo']['data_cadastro']))."</span><br />\n";
	        $html .= "<h3>".$conteudo['conteudo']['titulo']."</h3><br />\n";
	        $html .= "<small><strong>Autor(es)</strong></small><br />\n";
	        $html .= "<small>".$conteudo['autores']."</small><br /><br />\n";
	        $html .= "<small><strong>Enviado por</strong></small><br />\n";
	        $html .= "<small>".$conteudo['conteudo']['colaborador']."</small><br /><br />\n";

	        $html .= Util::iif($conteudo['conteudo']['imagem'], '<img src="'.ConfigVO::getDirFotos().$conteudo['conteudo']['imagem'].'" width="124" height="124" alt="imagem" class="img-borda img-center" />', '');
	        $html .= nl2br($conteudo['conteudo']['descricao'])."\n";

	        $template = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_pdf.html');
	        $tam_img  = getimagesize(ConfigVO::DIR_SITE.'portal/img/logo-iteia.gif');
	        $template = eregi_replace('<!--%IMG_TOPO%-->', ConfigVO::DIR_SITE.'/portal/img/logo-iteia.gif', $template);
	        $template = eregi_replace('<!--%WIDTH_IMG_TOPO%-->', (string)$tam_img[0], $template);
	        $template = eregi_replace('<!--%HEIGHT_IMG_TOPO%-->', (string)$tam_img[1], $template);
	        $template = eregi_replace('<!--%CONTEUDO%-->', $html, $template);

	        $dompdf = new DOMPDF();
	        $dompdf->load_html($template);
	        $dompdf->render();
	        $dompdf->stream('texto_'.$conteudo['conteudo']['cod_conteudo'].'.pdf');
			die;
		}
		*/
    }


}