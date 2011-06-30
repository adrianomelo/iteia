<?php
include_once('ConteudoVO.php');

class TextoVO extends ConteudoVO {

	protected $cod_formato = 1;
	private $arquivo = '';
	private $nome_arquivo_original = '';
	private $tamanho = 0;
	
	private $foto_credito = "";
	private $foto_legenda = "";

	public function setArquivo($arquivo) {
		$this->arquivo = $arquivo;
	}
	public function getArquivo() {
		return $this->arquivo;
	}

	public function setNomeArquivoOriginal($nome_arquivo_original) {
		$this->nome_arquivo_original = $nome_arquivo_original;
	}
	public function getNomeArquivoOriginal() {
		return $this->nome_arquivo_original;
	}

	public function setTamanho($tamanho) {
		$this->tamanho = $tamanho;
	}
	public function getTamanho() {
		return $this->tamanho;
	}
	
	public function setFotoCredito($foto_credito) {
		$this->foto_credito = $foto_credito;
	}
	public function getFotoCredito() {
		return $this->foto_credito;
	}

	public function setFotoLegenda($foto_legenda) {
		$this->foto_legenda = $foto_legenda;
	}
	public function getFotoLegenda() {
		return $this->foto_legenda;
	}

}