<?php
include_once("UsuarioVO.php");

class GrupoVO extends UsuarioVO {
	
	protected $cod_tipo = 3;
	private $tipo = '';
	private $lista_autores = array();
	private $lista_integrantes = array();
	private $responsavel = 0;
	
	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}
	public function getTipo() {
		return $this->tipo;
	}
	
	public function setListaAutores($lista_autores) {
		$this->lista_autores = $lista_autores;
	}
	public function getListaAutores() {
		return $this->lista_autores;
	}
	
	public function setListaIntegrantes($lista_integrantes) {
		$this->lista_integrantes = $lista_integrantes;
	}
	public function getListaIntegrantes() {
		return $this->lista_integrantes;
	}
	
	public function setResponsavel($responsavel) {
		$this->responsavel = $responsavel;
	}
	public function getResponsavel() {
		return $this->responsavel;
	}
	
}
