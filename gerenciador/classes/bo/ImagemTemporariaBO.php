<?php

class ImagemTemporariaBO {

	//private static $dir_imagens_temporarias = '/tmp/conteudo/';
	private static $dir_imagens_temporarias = '/tmp/';

	public static function criar(&$arquivo) {
		$nome_imagem = 'pencimgtemp_'.Util::gera_randomico();
		copy($arquivo["tmp_name"], self::$dir_imagens_temporarias.$nome_imagem);
		return $nome_imagem;
	}

	public static function exibir($nome, $width, $height, $crop = true) {
		if (file_exists(self::$dir_imagens_temporarias.$nome)) {
			try {
				$imagem = new Imagick(self::$dir_imagens_temporarias.$nome);
				if ($crop)
					$imagem->cropThumbnailImage($width, $height);
				else
					$imagem->thumbnailImage($width, $height);
				header("Content-Type: image/".$imagem->getImageFormat());
				echo $imagem;
			} catch (Exception $e) {
				$imagem = new Imagick();
				$imagem->newImage(1, 1, new ImagickPixel("rgb(255,255,255)"));
				$imagem->setImageFormat("png");
			}
		}
	}

	public static function criaDefinitiva($nometemp, $nomearquivo_parcial, $dir_destino) {
		$infoimg = getimagesize(self::$dir_imagens_temporarias.$nometemp);
		$nomearquivo = $nomearquivo_parcial.image_type_to_extension($infoimg[2], true);
		copy(self::$dir_imagens_temporarias.$nometemp, $dir_destino.$nomearquivo);
		return $nomearquivo;
	}
}
