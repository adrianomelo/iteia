<?php
class ConteudoVO {

    private $cod_conteudo = 0;
	protected $cod_formato = 0;
	private $randomico = "";
	private $cod_classificacao = 0;
	private $cod_segmento = 0;
	private $cod_subarea = 0;
	private $cod_canal = 0;
	private $cod_licenca = 0;
	private $cod_colaborador = 0;
	private $cod_autor = 0;
	private $titulo = "";
	private $descricao = "";
	private $imagem = "";
	private $datahora = "";
	private $url = "";
	private $tags = "";
	private $situacao = 0;
	private $publicado = 0;
	private $lista_autores = array();
	private $lista_grupos = array();
	private $lista_colaboradores_revisao = array();
	private $lista_conteudo_relacionado = array();
	private $permitir_comentarios = 0;
	private $lista_publica = 0;

	public function setCodConteudo($cod_conteudo) {
		$this->cod_conteudo = $cod_conteudo;
	}
	public function getCodConteudo() {
		return $this->cod_conteudo;
	}

    public function setCodFormato($cod_formato) {
		$this->cod_formato = $cod_formato;
	}
	public function getCodFormato() {
		return $this->cod_formato;
	}

    public function setRandomico($randomico) {
		$this->randomico = $randomico;
	}
	public function getRandomico() {
		return $this->randomico;
	}

	public function setCodClassificacao($cod_classificacao) {
		$this->cod_classificacao = $cod_classificacao;
	}
	public function getCodClassificacao() {
		return $this->cod_classificacao;
	}

	public function setCodSegmento($cod_segmento) {
		$this->cod_segmento = $cod_segmento;
	}
	public function getCodSegmento() {
		return $this->cod_segmento;
	}
	
	public function setCodSubArea($cod_subarea) {
		$this->cod_subarea = $cod_subarea;
	}
	public function getCodSubArea() {
		return $this->cod_subarea;
	}

	public function setCodCanal($cod_canal) {
		$this->cod_canal = $cod_canal;
	}
	public function getCodCanal() {
		return $this->cod_canal;
	}

	public function setCodLicenca($cod_licenca) {
		$this->cod_licenca = $cod_licenca;
	}
	public function getCodLicenca() {
		return $this->cod_licenca;
	}

	public function setCodColaborador($cod_colaborador) {
		$this->cod_colaborador = $cod_colaborador;
	}
	public function getCodColaborador() {
		return $this->cod_colaborador;
	}
	
	public function setCodAutor($cod_autor) {
		$this->cod_autor = $cod_autor;
	}
	public function getCodAutor() {
		return $this->cod_autor;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getTitulo() {
		return $this->titulo;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	public function getDescricao() {
		return $this->descricao;
	}

	public function setImagem($imagem) {
		$this->imagem = $imagem;
	}
	public function getImagem() {
		return $this->imagem;
	}

	public function setDataHora($datahora) {
		$this->datahora = $datahora;
	}
	public function getDataHora() {
		return $this->datahora;
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}

	public function setTags($tags) {
		$this->tags = $tags;
	}
	public function getTags() {
		return $this->tags;
	}

	public function setSituacao($situacao) {
		$this->situacao = $situacao;
	}
	public function getSituacao() {
		return $this->situacao;
	}

	public function setPublicado($publicado) {
		$this->publicado = $publicado;
	}
	public function getPublicado() {
		return $this->publicado;
	}

	public function setListaAutores($lista_autores) {
		$this->lista_autores = $lista_autores;
	}
	public function getListaAutores() {
		return $this->lista_autores;
	}
	
	public function setListaGrupos($lista_grupos) {
		$this->lista_grupos = $lista_grupos;
	}
	public function getListaGrupos() {
		return $this->lista_grupos;
	}

	public function setListaColaboradoresRevisao($lista_colaboradores_revisao) {
		$this->lista_colaboradores_revisao = $lista_colaboradores_revisao;
	}
	public function getListaColaboradoresRevisao() {
		return $this->lista_colaboradores_revisao;
	}

	public function setListaConteudoRelacionado($lista_conteudo_relacionado) {
		$this->lista_conteudo_relacionado = $lista_conteudo_relacionado;
	}
	public function getListaConteudoRelacionado() {
		return $this->lista_conteudo_relacionado;
	}
	
	public function setPermitirComentarios($permitir_comentarios) {
		$this->permitir_comentarios = $permitir_comentarios;
	}
	public function getPermitirComentarios() {
		return $this->permitir_comentarios;
	}
	
	public function setPedirAutorizacao($lista_publica) {
		$this->lista_publica = $lista_publica;
	}
	public function getPedirAutorizacao() {
		return $this->lista_publica;
	}

}
