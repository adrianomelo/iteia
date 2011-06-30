<?php
class BuscaDadosVO {

	private $palavra = '';
	private $datainicial = '';
	private $datafinal = '';
	private $codformato = 0;
	private $codlicenca = 0;
	private $codestado = 0;
	private $codcidade = 0;
	private $listaformatos = array();
	private $parametros_extra = array();

	public function __construct() {
	}

	public function setPalavraChave($palavra) {
		$this->palavra = $palavra;
	}
	public function getPalavraChave() {
		return $this->palavra;
	}

	public function setDataInicial($datainicial) {
		$this->datainicial = $datainicial;
	}
	public function getDataInicial() {
		return $this->datainicial;
	}

	public function setDataFinal($datafinal) {
		$this->datafinal = $datafinal;
	}
	public function getDataFinal() {
		return $this->datafinal;
	}

	public function setCodFormato($codformato) {
		$this->codformato = $codformato;
	}
	public function getCodFormato() {
		return $this->codformato;
	}
	
	public function setCodLicenca($codlicenca) {
		$this->codlicenca = $codlicenca;
	}
	public function getCodLicenca() {
		return $this->codlicenca;
	}
	
	public function setCodEstado($codestado) {
		$this->codestado = $codestado;
	}
	public function getCodEstado() {
		return $this->codestado;
	}
	
	public function setCodCidade($codcidade) {
		$this->codcidade = $codcidade;
	}
	public function getCodCidade() {
		return $this->codcidade;
	}

	public function setListaFormatos($listaformatos) {
		$this->listaformatos = $listaformatos;
	}
	public function getListaFormatos() {
		return $this->listaformatos;
	}

	public function setParametrosExtra($parametros_extra) {
		$this->parametros_extra = $parametros_extra;
	}
	public function getParametrosExtra() {
		return $this->parametros_extra;
	}
}
