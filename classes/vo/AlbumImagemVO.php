<?php
include_once("ConteudoVO.php");

class AlbumImagemVO extends ConteudoVO {

	protected $cod_formato = 2;
	private $cod_imagem_capa = 0;
	private $lista_imagens = array();

	public function setCodImagemCapa($cod_imagem_capa) {
		$this->cod_imagem_capa = $cod_imagem_capa;
	}
	public function getCodImagemCapa() {
		return $this->cod_imagem_capa;
	}

	public function setListaImagens($lista_imagens) {
		$this->lista_imagens = $lista_imagens;
	}
	public function getListaImagens() {
		return $this->lista_imagens;
	}

}