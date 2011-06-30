<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PlayListDAO.php");

class PlayListBO {

	private $playdao = null;
	private $dados_form = array();
	private $total = 0;

	public function __construct() {
		$this->playdao = new PlayListDAO();
	}

	public function getListaConteudoSelecionados($codplay) {
		return $this->playdao->getListaConteudoSelecionados($codplay);
	}
	
	public function getTempoTotalPlayList($codplay) {
		return $this->playdao->getTempoTotalPlayList($codplay);
	}
	
	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codplaylist'] = (array)$dados['codplaylist'];
	}
	
	public function getListaPlayList($get, $inicial, $mostrar, $tempo) {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->playdao->executaAcoes($this->dadosform['acao'], $this->dadosform['codplaylist']);
			Header('Location: home.php');
			die;
		}
		
		return $this->playdao->getListaPlayList($get, $inicial, $mostrar, $tempo);
	}
	
	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}

}