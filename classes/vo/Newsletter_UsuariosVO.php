<?php
class Newsletter_UsuariosVO {
	
	private $cod_usuario = 0;
	private $nome = '';	
	private $email = '';
	private $lista = array();
	
	public function setCodUsuario($cod_usuario) {
		$this->cod_usuario = $cod_usuario;
	}
	public function getCodUsuario() {
		return $this->cod_usuario;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	public function getNome() {
		return $this->nome;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	public function getEmail() {
		return $this->email;
	}
	
	public function setLista($lista) {
		$this->lista = $lista;
	}
	public function getLista() {
		return $this->lista;
	}
	
}