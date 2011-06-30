<?php
include_once(dirname(__FILE__)."/../vo/ConfigVO.php");

class ImagemUtil {

	//indice = tamanho; item 0: largura; item 1: altura; item 2: crop
	private static $tamanhos_imagem = array(
		0 => array(50, 50, true),
		1 => array(50, 50, true),
		2 => array(177, 148, true),
		3 => array(265, 170, true),
		4 => array(60, 60, true),
		5 => array(119, 77, true),
		6 => array(124, 124, true),
		7 => array(173, 170, true),
		8 => array(200, 0, false),
		9 => array(160, 121, true),
		10 => array(89, 89, true),
		11 => array(448, 0, false),
		12 => array(164, 164, true),
		13 => array(148, 112, true),
		14 => array(60, 95, true),
		15 => array(100, 67, true),
		16 => array(350, 263, true),
		17 => array(170, 170, true),
		18 => array(190, 80, true),
		19 => array(201, 201, true),
		20 => array(250, 250, true),
		21 => array(265, 170, true),
		22 => array(300, 255, true),
		23 => array(120, 120, true),
		24 => array(87, 87, true),
		25 => array(400, 300, true),
		26 => array(120, 90, true),
		27 => array(60, 45, true),
		28 => array(150, 150, true),
		29 => array(40, 40, true),
		30 => array(180, 225, true),
		31 => array(80, 60, true),
		32 => array(517, 0, false),
		33 => array(288, 216, true),
		34 => array(100, 75, true),
		35 => array(140, 105, true),
		36 => array(269, 202, true),
		37 => array(180, 135, true),
		38 => array(90, 90, true),
		39 => array(180, 150, true),
		40 => array(300, 255, false),
	);

	private static function getDirImagem(&$tipo) {
		switch ($tipo) {
			case "2": return ConfigVO::getDirGaleria(); break;
			case "3":
			case "4":
			case "5":
			case "1":
			case "c":
			case "a": return ConfigVO::getDirFotos(); break;
			case "9": return ConfigVO::getDirArquivos(); break;
			case "15": return ConfigVO::getDirVideo().'convertidos/'; break;
		}
		return "";
	}

	public static function exibirImagem($arquivo, $tipo = "", $tamanho = 0) {

		$nome_img_cache = ConfigVO::getDirImgCache()."img_s".$tamanho."_".$tipo."_".$arquivo;
		$dir_img = self::getDirImagem($tipo);
		$mostra_original = false;
		if (file_exists($nome_img_cache) && (filesize($dir_img.$arquivo) == filesize($nome_img_cache)))
			$mostra_original = true;

		try {
			if (!$tamanho)
				$imagem = new Imagick($dir_img.$arquivo);
			elseif (file_exists($nome_img_cache))
				$imagem = new Imagick($nome_img_cache);
			else {
				$dados_img = &self::$tamanhos_imagem[$tamanho];
				$imagem = new Imagick($dir_img.$arquivo);
				$redimensionar = true;
				if (($imagem->getImageWidth() == $dados_img[0]) && ($imagem->getImageHeight() == $dados_img[1])) {
					$redimensionar = false;
					$mostra_original = true;
				}
				if ($redimensionar) {
					if ($dados_img[2])
						$imagem->cropThumbnailImage($dados_img[0], $dados_img[1]);
					else
						$imagem->thumbnailImage($dados_img[0], $dados_img[1]);
					
					if ($tipo == 2)
						$imagem->setCompressionQuality(80);
						
					$imagem->writeImage($nome_img_cache);
				}
				else
					copy($dir_img.$arquivo, $nome_img_cache);
			}
		} catch (Exception $e) {
			$imagem = new Imagick();
			$imagem->newImage(1, 1, new ImagickPixel("rgb(255,255,255)"));
			$imagem->setImageFormat("png");
		}
		
		$imagem->setImagePage($dados_img[0], $dados_img[1], 0, 0);

		header("Content-Type: image/".$imagem->getImageFormat());
		if (!$mostra_original)
			echo $imagem;
		else
			echo file_get_contents($dir_img.$arquivo);
	}

	public static function removerImagensCache($nomearq) {
		foreach (glob(ConfigVO::getDirImgCache()."*".$nomearq."*") as $arquivo)
			unlink($arquivo);
	}

	public function guardaTemp($imagem) {
		if (is_uploaded_file($imagem["tmp_name"])) {
			$extensao = strtolower(Util::getExtensaoArquivo($imagem["name"]));
			if ($imagem["size"] < 2200000) {
				if (($extensao == 'jpg') || ($extensao == 'jpeg') || ($extensao == 'gif') || ($extensao == 'png')) {
					$imagem_temp = md5(time()).'.'.$extensao;
					copy($imagem['tmp_name'], ConfigVO::getDirTemp().$imagem_temp);
				}
			}
		}
		return $imagem_temp;
	}

	public function guardaArquivoTemp($arquivo) {
		if (is_uploaded_file($arquivo["tmp_name"])) {
			$extensao = strtolower(Util::getExtensaoArquivo($arquivo["name"]));
			$arquivo_temp = md5(time()).'.'.$extensao;
			copy($arquivo['tmp_name'], ConfigVO::getDirTemp().$arquivo_temp);
		}
		return $arquivo_temp;
	}

}
