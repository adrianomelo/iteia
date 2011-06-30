<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/BannerDAO.php");

class BannersBO {

	private $bandao = null;
	private $dadosform = array();

	public function __construct() {
		$this->bandao = new BannerDAO();
	}

	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['situacao'] = trim($dados['situacao']);
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codbanner'] = (array)$dados['codbanner'];
		$this->dadosform['mudar_prioridade'] = (int)$dados['mudar_prioridade'];
	}

    public function getListaBanners($get='', $inicial='0', $mostrar='6') {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->bandao->executaAcoes($this->dadosform['acao'], $this->dadosform['codbanner'], $this->dadosform['mudar_prioridade']);
			Header('Location: banners.php');
			die;
		}

		return $this->bandao->getListaBanners($this->dadosform, $inicial, $mostrar);
	}

	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}

}