<?php
class PlayListVO {

	private $cod_playlist = 0;
	private $cod_usuario = 0;
	private $data_inicio = '';
	private $hora_inicio = '';
	private $lista_itens = array();

	public function setCodPlayList($cod_playlist) {
		$this->cod_playlist = $cod_playlist;
	}
	public function getCodPlayList() {
		return $this->cod_playlist;
	}
	
	public function setCodUsuario($cod_usuario) {
		$this->cod_usuario = $cod_usuario;
	}
	public function getCodUsuario() {
		return $this->cod_usuario;
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
	
	public function setListaItens($lista_itens) {
		$this->lista_itens = $lista_itens;
	}
	public function getListaItens() {
		return $this->lista_itens;
	}
	
}