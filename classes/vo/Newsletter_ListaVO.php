<?php
class Newsletter_ListaVO {
	
	private $codlista = 0;
	private $codcliente = 0;
	private $titulo = '';
	private $emails = array();
	private $datahora = '';
	private $excluido = 0;
	
	public function setCodLista($codlista) {
		$this->codlista = $codlista;
	}
	public function getCodLista() {
		return $this->codlista;
	}
	
	public function setCodCliente($codcliente) {
		$this->codcliente = $codcliente;
	}
	public function getCodCliente() {
		return $this->codcliente;
	}
	
	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getTitulo() {
		return $this->titulo;
	}
	
	public function setEmails($emails) {
		$this->emails = $emails;
	}
	public function getEmails() {
		return $this->emails;
	}
	
	public function setDataHora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDataHora() {
		return $this->datahora;
	}
	
	public function setExcluido($excluido) {
		$this->excluido = $excluido;
	}
	public function getExcluido() {
		return $this->excluido;
	}
	
}