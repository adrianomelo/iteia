<?php
include_once("UsuarioVO.php");

class ColaboradorVO extends UsuarioVO {
	
	protected $cod_tipo = 1;
	private $nome_completo = '';
	private $data_nascimento = '';
	private $cpf = '';
	private $entidade = '';
	private $rede = array();
	private $administrador = 0;
	private $lista_integrantes = array();
	private $responsavel = 0;
	private $cod_nivel = 0;
	
    public function setNomeCompleto($nome_completo) {
		$this->nome_completo = $nome_completo;
	}
	public function getNomeCompleto() {
		return $this->nome_completo;
	}
	
	public function setEntidade($entidade) {
		$this->entidade = $entidade;
	}
	public function getEntidade() {
		return $this->entidade;
	}
	
	public function setRede($rede) {
		$this->rede = $rede;
	}
	public function getRede() {
		return $this->rede;
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
	public function setDataNascimento($data_nascimento) {
		$this->data_nascimento = $data_nascimento;
	}
	public function getDataNascimento() {
		return $this->data_nascimento;
	}
	
	public function setAdministrador($administrador) {
		$this->administrador = $administrador;
	}
	public function getAdministrador() {
		return $this->administrador;
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
