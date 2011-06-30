<?php

class UsuarioVO {
	
	private $cod_usuario = 0;
	private $cod_tipo = 0;
	private $nome = '';
	private $descricao = '';
	private $endereco = '';
	private $complemento = '';
	private $bairro = '';
	private $cep = '';
	private $cod_pais = 0;
	private $cod_estado = 0;
	private $cod_cidade = 0;
	private $cidade = '';
	private $telefone = '';
	private $celular = '';
	private $email = '';
	private $mostrar_email = '';
	private $site = '';
	private $imagem = '';
	private $url = '';
	private $login = '';
	private $senha = '';
	private $situacao = 0;
	private $disponivel = 1;
	private $sites_relacionados = array();
	private $contatos = array();
	private $datacadastro = '';
	
	public function setCodUsuario($cod_usuario) {
		$this->cod_usuario = $cod_usuario;
	}
	public function getCodUsuario() {
		return $this->cod_usuario;
	}
	
	public function setCodTipo($cod_tipo) {
		$this->cod_tipo = $cod_tipo;
	}
	public function getCodTipo() {
		return $this->cod_tipo;
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
	
	public function setEndereco($endereco) {
		$this->endereco = $endereco;
	}
	public function getEndereco() {
		return $this->endereco;
	}
	
	public function setComplemento($complemento) {
		$this->complemento = $complemento;
	}
	public function getComplemento() {
		return $this->complemento;
	}
	
	public function setBairro($bairro) {
		$this->bairro = $bairro;
	}
	public function getBairro() {
		return $this->bairro;
	}
	
	public function setCEP($cep) {
		$this->cep = $cep;
	}
	public function getCEP() {
		return $this->cep;
	}
	
	public function setCodPais($cod_pais) {
		$this->cod_pais = $cod_pais;
	}
	public function getCodPais() {
		return $this->cod_pais;
	}
	
	public function setCodEstado($cod_estado) {
		$this->cod_estado = $cod_estado;
	}
	public function getCodEstado() {
		return $this->cod_estado;
	}
	
	public function setCodCidade($cod_cidade) {
		$this->cod_cidade = $cod_cidade;
	}
	public function getCodCidade() {
		return $this->cod_cidade;
	}
	
	public function setCidade($cidade) {
		$this->cidade = $cidade;
	}
	public function getCidade() {
		return $this->cidade;
	}
	
	public function setTelefone($telefone) {
		$this->telefone = $telefone;
	}
	public function getTelefone() {
		return $this->telefone;
	}
	
	public function setCelular($celular) {
		$this->celular = $celular;
	}
	public function getCelular() {
		return $this->celular;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	public function getEmail() {
		return $this->email;
	}
	
	public function setSite($site) {
		$this->site = $site;
	}
	public function getSite() {
		return $this->site;
	}
	
	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}
	public function getImagem() {
		return $this->imagem;
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}
	
	public function setLogin($login) {
		$this->login = $login;
	}
	public function getLogin() {
		return $this->login;
	}
	
	public function setSenha($senha) {
		$this->senha = $senha;
	}
	public function getSenha() {
		return $this->senha;
	}
	
	public function setSituacao($situacao) {
		$this->situacao = $situacao;
	}
	public function getSituacao() {
		return $this->situacao;
	}
	
	public function setDisponivel($disponivel) {
		$this->disponivel = $disponivel;
	}
	public function getDisponivel() {
		return $this->disponivel;
	}
	
	public function setSitesRelacionados($sites_relacionados) {
		$this->sites_relacionados = $sites_relacionados;
	}
	public function getSitesRelacionados() {
		return $this->sites_relacionados;
	}
	
	public function setContatos($contatos) {
		$this->contatos = $contatos;
	}
	public function getContatos() {
		return $this->contatos;
	}
	
	public function setMostrarEmail($mostrar_email) {
		$this->mostrar_email = $mostrar_email;
	}
	public function getMostrarEmail() {
		return $this->mostrar_email;
	}
	
	public function setDataCadastro($datacadastro) {
		$this->datacadastro = $datacadastro;
	}
	public function getDataCadastro() {
		return $this->datacadastro;
	}
	
}
