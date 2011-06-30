<?php

class ImagemVO {

	private $cod_imagem = 0;
	private $cod_conteudo = 0;
	private $randomico = "";
	private $arquivo = "";
	private $legenda = "";
	private $credito = "";
	private $datahora = "";

	public function setCodImagem($cod_imagem) {
		$this->cod_imagem = $cod_imagem;
	}
	public function getCodImagem() {
		return $this->cod_imagem;
	}

	public function setCodConteudo($cod_conteudo) {
		$this->cod_conteudo = $cod_conteudo;
	}
	public function getCodConteudo() {
		return $this->cod_conteudo;
	}

	public function setRandomico($randomico) {
		$this->randomico = $randomico;
	}
	public function getRandomico() {
		return $this->randomico;
	}

	public function setArquivo($arquivo) {
		$this->arquivo = $arquivo;
	}
	public function getArquivo() {
		return $this->arquivo;
	}

	public function setLegenda($legenda) {
		$this->legenda = $legenda;
	}
	public function getLegenda() {
		return $this->legenda;
	}
	
	public function setCredito($credito) {
		$this->credito = $credito;
	}
	public function getCredito() {
		return $this->credito;
	}

	public function setDataHora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDataHora() {
		return $this->datahora;
	}

}
