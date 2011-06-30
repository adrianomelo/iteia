<?php
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/VideoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class VideoExibicaoBO {

    private $textodao = null;

    public function __construct() {
        $this->videodao = new VideoDAO;
    }

    public function exibirConteudo(&$codconteudo, &$conteudo, &$comentbo) {
    	$conteudo['dados_arquivo'] = $this->videodao->getArquivoVideo($codconteudo);
        if ($conteudo['dados_arquivo']['arquivo']) {
            $conteudo['dados_arquivo']['video'] = $conteudo['dados_arquivo']['arquivo'];
        	$conteudo['dados_arquivo']['tipo'] = 1;
        	
        } else {
            $conteudo['dados_arquivo']['video'] = $conteudo['dados_arquivo']['link'];
            $conteudo['dados_arquivo']['tipo'] = 2;
        }
        include('includes/include_visualizacao_video.php');
    }

    public function DownloadArquivo($codconteudo) {
        $video = $this->videodao->getArquivoVideo($codconteudo);
        if ($video['arquivo'])
            Util::force_download(file_get_contents(ConfigVO::getDirVideo().'convertidos/'.$video['arquivo']), $video['arquivo'], '', $video['tamanho']);
        die;
    }

}