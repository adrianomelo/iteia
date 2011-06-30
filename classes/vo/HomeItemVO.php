<?php
class HomeItemVO {

	private $coditem = 0;
	private $titulo = "";
	private $resumo = "";
	private $imagem = "";
	private $cod_formato = 0;
	
	public function setCodItem($coditem) {
		$this->coditem = $coditem;
	}
	public function getCodItem() {
		return $this->coditem;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getTitulo() {
		return $this->titulo;
	}

	public function setResumo($resumo) {
		$this->resumo = $resumo;
	}
	public function getResumo() {
		return $this->resumo;
	}
	
	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}
	public function getImagem() {
		return $this->imagem;
	}
	
	public function setCodFormato($cod_formato) {
		$this->cod_formato = $cod_formato;
	}
	public function getCodFormato() {
		return $this->cod_formato;
	}

}