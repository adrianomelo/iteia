<?php
include_once("UsuarioVO.php");

class FormularioVO extends UsuarioVO {
	
	private $interesses = array();
	private $cpf = '';
	
	public function setInteresses($interesses) {
		$this->interesses = $interesses;
	}
	public function getInteresses() {
		return $this->interesses;
	}
	
	public function setCPF($cpf) {
		$this->cpf = $cpf;
	}
	public function getCPF() {
		return $this->cpf;
	}
	
}
