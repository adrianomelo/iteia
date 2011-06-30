<?php
include_once('ConteudoVO.php');

class NoticiaVO extends ConteudoVO {

	protected $cod_formato = 5;
	private $subtitulo = "";
	private $assinatura = "";
	private $foto_ampliada = "";
	private $foto_credito = "";
	private $foto_legenda = "";
	private $home = 0;
	private $home_titulo = "";
	private $home_resumo = "";
	private $home_foto = "";
	private $home_foto_credito = "";
	private $home_foto_legenda = "";
	private $home_posicao = 0;

	public function setSubtitulo($subtitulo) {
		$this->subtitulo = $subtitulo;
	}
	public function getSubtitulo() {
		return $this->subtitulo;
	}

	public function setAssinatura($assinatura) {
		$this->assinatura = $assinatura;
	}
	public function getAssinatura() {
		return $this->assinatura;
	}

	public function setFotoAmpliada($foto_ampliada) {
		$this->foto_ampliada = $foto_ampliada;
	}
	public function getFotoAmpliada() {
		return $this->foto_ampliada;
	}

	public function setFotoCredito($foto_credito) {
		$this->foto_credito = $foto_credito;
	}
	public function getFotoCredito() {
		return $this->foto_credito;
	}

	public function setFotoLegenda($foto_legenda) {
		$this->foto_legenda = $foto_legenda;
	}
	public function getFotoLegenda() {
		return $this->foto_legenda;
	}

	public function setHome($home) {
		$this->home = $home;
	}
	public function getHome() {
		return $this->home;
	}

	public function setHomeTitulo($home_titulo) {
		$this->home_titulo = $home_titulo;
	}
	public function getHomeTitulo() {
		return $this->home_titulo;
	}

	public function setHomeResumo($home_resumo) {
		$this->home_resumo = $home_resumo;
	}
	public function getHomeResumo() {
		return $this->home_resumo;
	}

	public function setHomeFoto($home_foto) {
		$this->home_foto = $home_foto;
	}
	public function getHomeFoto() {
		return $this->home_foto;
	}

	public function setHomeFotoCredito($home_foto_credito) {
		$this->home_foto_credito = $home_foto_credito;
	}
	public function getHomeFotoCredito() {
		return $this->home_foto_credito;
	}

	public function setHomeFotoLegenda($home_foto_legenda) {
		$this->home_foto_legenda = $home_foto_legenda;
	}
	public function getHomeFotoLegenda() {
		return $this->home_foto_legenda;
	}

	public function setHomePosicao($home_posicao) {
		$this->home_posicao = $home_posicao;
	}
	public function getHomePosicao() {
		return $this->home_posicao;
	}
}
