<?php
class AudioVO {

	private $cod_audio = 0;
	private $cod_conteudo = 0;
	private $faixa = "";
	private $randomico = "";
	private $arquivo = "";
	private $tempo = "";
	private $datahora = "";
	
	private $dados_arquivos = array();

	public function setDadosArquivo($dados_arquivos) {
		$this->dados_arquivos = $dados_arquivos;
	}
	public function getDadosArquivo() {
		return $this->dados_arquivos;
	}

	public function setCodAudio($cod_audio) {
		$this->cod_audio = $cod_audio;
	}
	public function getCodAudio() {
		return $this->cod_audio;
	}
	
	public function setCodConteudo($cod_conteudo) {
		$this->cod_conteudo = $cod_conteudo;
	}
	public function getCodConteudo() {
		return $this->cod_conteudo;
	}
	
	public function setFaixa($faixa) {
		$this->faixa = $faixa;
	}
	public function getFaixa() {
		return $this->faixa;
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

	public function setTempo($tempo) {
		$this->tempo = $tempo;
	}
	public function getTempo() {
		return $this->tempo;
	}
	
	public function setDataHora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDataHora() {
		return $this->datahora;
	}
	
}