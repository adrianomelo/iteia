<?php
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AudioDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class AudioExibicaoBO {

    private $audiodao = null;

    public function __construct() {
        $this->audiodao = new AudioDAO;
    }
    
    public function exibirConteudo(&$codconteudo, &$conteudo, &$comentbo) {
		$lista_audios = $this->audiodao->getAudiosAlbum($codconteudo);
		$conteudo['dados_faixas_audios'] = array();
        
        foreach ($lista_audios as $key => $value) {
			$conteudo['dados_faixas_audios'][$key] = array(
			 
  				'cod_audio' 		=> $value['cod_audio'],
            	'cod_conteudo' 		=> $value['cod_conteudo'],
            	'titulo' 			=> $value['titulo'],
            	'tempo' 			=> $value['tempo'],
            	'randomico' 		=> $value['randomico'],
            	'audio' 			=> PlayerUtil::urlArquivo($value['audio'], 2),
            	'arquivo_original' 	=> $value['arquivo_original'],
			
			);
        }
        
        include('includes/include_visualizacao_audio.php');
    }

    public function DownloadArquivo($codconteudo, $codfaixa) {
        if ($codfaixa) {
			$audio = $this->audiodao->getArquivoAudio($codfaixa);
        
        	if ($audio['audio'])
            	Util::force_download(file_get_contents(ConfigVO::getDirAudio().$audio['audio']), $audio['arquivo_original'], '', $audio['tamanho']);
        }
		if ($codconteudo) {
        	$faixas = $this->audiodao->getAudiosAlbum($codconteudo);
        	$arquivo = ConfigVO::getDirTemp() . 'galeria_' . $codconteudo . '.zip';
        	
			$zip = new ZipArchive;
        	$zip->open($arquivo, ZIPARCHIVE::CREATE);
        	
        	foreach ($faixas as $value)
        		$zip->addFile(ConfigVO::getDirAudio() . $value['audio'], $value['arquivo_original']);
        	
        	$zip->close();
        	Util::force_download(file_get_contents($arquivo), 'album_' . $codconteudo . '.zip');
        	@unlink($arquivo);

        }
		die;
    }

}