<?php
class BannerVO {

	private $codbanner = 0;
	private $codcolaborador = 0;
	private $titulo = '';
	private $url = '';
	private $imagem = '';
	private $prioridade = 0;
	private $data_inicial = '';
	private $data_final = '';
	private $home = 0;
	private $colaborador = '';
	
	public function setCodBanner($codbanner) {
		$this->codbanner = $codbanner;
	}
	public function getCodBanner() {
		return $this->codbanner;
	}
	
	public function setCodColaborador($codcolaborador) {
		$this->codcolaborador = $codcolaborador;
	}
	public function getCodColaborador() {
		return $this->codcolaborador;
	}
	
	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getTitulo() {
		return $this->titulo;
	}

	public function setUrl($url) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}

	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}
	public function getImagem() {
		return $this->imagem;
	}

	public function setPrioridade($prioridade) {
		$this->prioridade = $prioridade;
	}
	public function getPrioridade() {
		return $this->prioridade;
	}

	public function setDataInicial($data_inicial) {
		$this->data_inicial = $data_inicial;
	}
	public function getDataInicial() {
		return $this->data_inicial;
	}

	public function setDataFinal($data_final) {
		$this->data_final = $data_final;
	}
	public function getDataFinal() {
		return $this->data_final;
	}
	
	public function setHome($home) {
		$this->home = $home;
	}
	public function getHome() {
		return $this->home;
	}
	
	public function setColaborador($colaborador) {
		$this->colaborador = $colaborador;
	}
	public function getColaborador() {
		return $this->colaborador;
	}

}