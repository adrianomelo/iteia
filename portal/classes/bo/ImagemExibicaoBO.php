<?php
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/ImagemDAO.php');

class ImagemExibicaoBO {

    private $imgdao = null;

    public function __construct() {
        $this->imgdao = new ImagemDAO;
    }

    public function exibirConteudo(&$codconteudo, &$conteudo, &$comentbo) {
        $conteudo['dados_imagens'] = $this->imgdao->getArquivoImagem($codconteudo);
        /*
		$w_max = 448;
        foreach ($conteudo['dados_imagens'] as $key => $value) {
            $valores = '';
            $valores = getimagesize(ConfigVO::getDirGaleria().$value['imagem']);

		    $altura = $valores[1];
            $largura = ($valores[0] > $w_max) ? $w_max : $valores[0] ;

    		$s = ($valores[0] > $w_max) ? 11 : 0;

		    if ($valores[0] > $w_max) {
		        $altura = (448 * $valores[1]) / $valores[0];
		    }

            $conteudo['dados_imagens'][$key]['s'] = $s;
            $conteudo['dados_imagens'][$key]['largura'] = $largura;
            $conteudo['dados_imagens'][$key]['altura'] = $altura;
        }
		*/
        include('includes/include_visualizacao_imagem.php');
    }
	
	public function getNavegacaoGaleria($codconteudo, $codimagem) {
		$array_imagens = $this->imgdao->getArquivoImagem($codconteudo);
		foreach($array_imagens as $key => $value) {
			if ($value['cod_imagem'] == $codimagem)
				$indice_atual = $key;
		}
		return array('anterior' => $array_imagens[$indice_atual - 1], 'atual' => $array_imagens[$indice_atual], 'proxima' => $array_imagens[$indice_atual + 1], 'indice' => $indice_atual + 1, 'total' => count($array_imagens));
	}

    public function DownloadArquivo($codconteudo, $codimagem) {
		$zip = new ZipArchive();	
		$arquivozip = '/tmp/fs_album_'.md5(time()).'.zip';
		$zip->open($arquivozip, ZIPARCHIVE::CREATE);
	
		foreach($this->imgdao->getArquivoImagem($codconteudo) as $value)
			$zip->addFile(ConfigVO::getDirGaleria().$value['imagem'], $value['imagem']);
	
		$zip->close();
		Util::force_download(file_get_contents($arquivozip), 'iteia_galeria_'.$codconteudo.'.zip');
        die;
    }

}