<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ComentariosDAO.php");

class ComentarioBO {

	private $comentdao = null;
	private $dadosform = array();

	public function __construct() {
		$this->comentdao = new ComentariosDAO;
	}
	
	public function atualizarOpcoes(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform['permitirpublicacao'] = (int)$this->dadosform['permitirpublicacao'];
		$this->dadosform['precisaaprovacao'] = (int)$this->dadosform['precisaaprovacao'];
		$this->dadosform['moderacao'] = strip_tags($this->dadosform['moderacao']);
		$this->dadosform['listanegra'] = strip_tags($this->dadosform['listanegra']);
		
		$this->comentdao->atualizarConfiguracoes($this->dadosform);
	}
	
	public function setDadosCamposEdicao() {
		$config = $this->comentdao->getConfiguracoes();
		$this->dadosform["permitirpublicacao"] = $config->PermitirPublicacao;
		$this->dadosform["precisaaprovacao"] = $config->PrecisaAprovacao;
		$this->dadosform["moderacao"] = $config->PalavrasModeracao;
		$this->dadosform["listanegra"] = $config->PalavrasListaNegra;
	}

	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}
	
	public function getTotalComentariosAprovacao() {
		return $this->comentdao->getTotalComentariosAprovacao();
	}
	
	public function getListaComentariosIndex() {
		return $this->comentdao->getListaComentarios(array('buscar' => 1), 0, 10);
	}
	
	public function executaAcoes($acao, $codcomentario) {
		return $this->comentdao->executaAcoes($acao, $codcomentario);
	}
	
}