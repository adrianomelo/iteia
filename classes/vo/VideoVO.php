<?php
include_once("ConteudoVO.php");

class VideoVO extends ConteudoVO {

	protected $cod_formato = 4;
	private $arquivo = '';
	private $arquivo_original = '';
	private $tamanho = 0;
	private $link_video = '';

	public function setArquivo($arquivo) {
		$this->arquivo = $arquivo;
	}
	public function getArquivo() {
		return $this->arquivo;
	}

	public function setArquivoOriginal($arquivo_original) {
		$this->arquivo_original = $arquivo_original;
	}
	public function getArquivoOriginal() {
		return $this->arquivo_original;
	}

	public function setTamanho($tamanho) {
		$this->tamanho = $tamanho;
	}
	public function getTamanho() {
		return $this->tamanho;
	}

	public function setLinkVideo($link_video) {
		$this->link_video = $link_video;
	}
	public function getLinkVideo() {
		return $this->link_video;
	}

}
