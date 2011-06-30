<?php
include_once("UsuarioVO.php");

class AutorVO extends UsuarioVO {
	
	protected $cod_tipo = 2;
	private $nome_completo = '';
	private $data_nascimento = '';
	private $data_falecimento = '';
	private $cpf = '';
	private $tipo_autor = 0;
	private $cod_nivel = 0;
	
	public function setNomeCompleto($nome_completo) {
		$this->nome_completo = $nome_completo;
	}
	public function getNomeCompleto() {
		return $this->nome_completo;
	}
	
	public function setDataNascimento($data_nascimento) {
		$this->data_nascimento = $data_nascimento;
	}
	public function getDataNascimento() {
		return $this->data_nascimento;
	}
	
	public function setDataFalecimento($data_falecimento) {
		$this->data_falecimento = $data_falecimento;
	}
	public function getDataFalecimento() {
		return $this->data_falecimento;
	}
	
	public function setCPF($cpf) {
		$this->cpf = $cpf;
	}
	public function getCPF() {
		return $this->cpf;
	}
	
	public function setCodNivel($cod_nivel) {
		$this->cod_nivel = $cod_nivel;
	}
	public function getCodNivel() {
		return $this->cod_nivel;
	}
	
	public function setTipoAutor($tipo_autor) {
		$this->tipo_autor = $tipo_autor;
	}
	public function getTipoAutor() {
		return $this->tipo_autor;
	}
	
}
