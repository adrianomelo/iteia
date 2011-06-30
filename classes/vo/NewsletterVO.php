<?php
class NewsletterVO {

	private $cod_newsletter = 0;
	private $destaque = 0;
	private $titulo = '';
	private $data_inicio = '';
	private $hora_inicio = '';
	private $lista_itens = array();
	private $enviopara = '';
	private $data_envio = '';

	public function setCodNewsletter($cod_newsletter) {
		$this->cod_newsletter = $cod_newsletter;
	}
	public function getCodNewsletter() {
		return $this->cod_newsletter;
	}
	
	public function setDestaque($destaque) {
		$this->destaque = $destaque;
	}
	public function getDestaque() {
		return $this->destaque;
	}
	
	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getTitulo() {
		return $this->titulo;
	}
	
	public function setDataInicio($data_inicio) {
		$this->data_inicio = $data_inicio;
	}
	public function getDataInicio() {
		return $this->data_inicio;
	}
	
	public function setHoraInicio($hora_inicio) {
		$this->hora_inicio = $hora_inicio;
	}
	public function getHoraInicio() {
		return $this->hora_inicio;
	}
	
	public function setEnvioPara($enviopara) {
		$this->enviopara = $enviopara;
	}
	public function getEnvioPara() {
		return $this->enviopara;
	}
	
	public function setDataEnvio($data_envio) {
		$this->data_envio = $data_envio;
	}
	public function getDataEnvio() {
		return $this->data_envio;
	}
	
	public function setListaItens($lista_itens) {
		$this->lista_itens = $lista_itens;
	}
	public function getListaItens() {
		return $this->lista_itens;
	}
	
}