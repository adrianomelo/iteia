<?php
include_once('ConteudoVO.php');

class AgendaVO extends ConteudoVO {
    
    protected $cod_formato = 6;
	private $local = "";
	private $endereco = "";
	private $cidade = "";
	private $telefone = "";
	private $valor = "";
	private $data_inicial = "";
	private $data_final = "";
	private $hora_inicial = "";
	private $hora_final = "";
	private $site = "";

	public function setLocal($local) {
		$this->local = $local;
	}
	public function getLocal() {
		return $this->local;
	}

	public function setEndereco($endereco) {
		$this->endereco = $endereco;
	}
	public function getEndereco() {
		return $this->endereco;
	}

	public function setCidade($cidade) {
		$this->cidade = $cidade;
	}
	public function getCidade() {
		return $this->cidade;
	}

    public function setTelefone($telefone) {
		$this->telefone = $telefone;
	}
	public function getTelefone() {
		return $this->telefone;
	}

    public function setValor($valor) {
		$this->valor = $valor;
	}
	public function getValor() {
		return $this->valor;
	}

    public function setDataInicial($datainicial) {
		$this->data_inicial = $datainicial;
	}
	public function getDataInicial() {
		return $this->data_inicial;
	}

    public function setDataFinal($datafinal) {
		$this->data_final = $datafinal;
	}
	public function getDataFinal() {
		return $this->data_final;
	}

    public function setHoraInicial($horainicial) {
		$this->hora_inicial = $horainicial;
	}
	public function getHoraInicial() {
		return $this->hora_inicial;
	}

    public function setHoraFinal($horafinal) {
		$this->hora_final = $horafinal;
	}
	public function getHoraFinal() {
		return $this->hora_final;
	}
	
	public function setSite($site) {
		$this->site = $site;
	}
	public function getSite() {
		return $this->site;
	}

}