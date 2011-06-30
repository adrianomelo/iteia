<?php
class SegmentoVO {
	
	private $cod_segmento = 0;
	private $cod_pai = 0;
	private $nome = '';
	private $descricao = '';
	private $imagem = '';
	private $verbete = 0;
	
	public function setCodSegmento($cod_segmento) {
		$this->cod_segmento = $cod_segmento;
	}
	public function getCodSegmento() {
		return $this->cod_segmento;
	}
	
	public function setCodPai($cod_pai) {
		$this->cod_pai = $cod_pai;
	}
	public function getCodPai() {
		return $this->cod_pai;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	public function getNome() {
		return $this->nome;
	}
	
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	
	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}
	public function getImagem() {
		return $this->imagem;
	}
	
	public function setVerbete($verbete) {
		$this->verbete = $verbete;
	}
	public function getVerbete() {
		return $this->verbete;
	}
	
}
