<?php
class ConteudoExibicaoFactory {

	public static function getFactory($codformato) {
		switch ($codformato) {
			case 1:
				include_once('TextoExibicaoBO.php');
				return new TextoExibicaoBO;
				break;
			case 2:
				include_once('ImagemExibicaoBO.php');
				return new ImagemExibicaoBO;
				break;
			case 3:
				include_once('AudioExibicaoBO.php');
				return new AudioExibicaoBO;
				break;
			case 4:
				include_once('VideoExibicaoBO.php');
				return new VideoExibicaoBO;
				break;
			case 5:
				include_once('NoticiaExibicaoBO.php');
				return new NoticiaExibicaoBO;
				break;
			case 6:
				include_once('AgendaBO.php');
				return new AgendaBO;
				break;
		}
	}

}
