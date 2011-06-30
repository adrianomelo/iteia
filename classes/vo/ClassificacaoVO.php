<?php
class ClassificacaoVO {
	
	private $cod_classificao = 0;
	private $cod_formato = 0;
	private $nome = '';
	private $descricao = '';
	
	public function setCodClassificacao($cod_classificao) {
		$this->cod_classificao = $cod_classificao;
	}
	public function getCodClassificacao() {
		return $this->cod_classificao;
	}
	
	public function setCodFormato($cod_formato) {
		$this->cod_formato = $cod_formato;
	}
	public function getCodFormato() {
		return $this->cod_formato;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	public function getNome() {
		return $this->nome;
	}
	
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	
}
