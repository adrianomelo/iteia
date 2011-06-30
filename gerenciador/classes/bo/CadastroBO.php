<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");

class CadastroBO {

	private $cadao = null;
	private $dadosform = array();

	public function __construct() {
		$this->cadao = new CadastroDAO();
	}

	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['situacao'] = trim($dados['situacao']);
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);
	
		$this->dadosform['tipo'] = (int)$dados['tipo'];
		$this->dadosform['tipogrupo'] = (int)$dados['tipogrupo'];
		$this->dadosform['usuario'] = (int)$dados['usuario'];

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codusuarios'] = (array)$dados['codusuario'];
	}

    public function getListaCadastros($get='', $inicial=0, $mostrar=6) {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->cadao->executaAcoes($this->dadosform['acao'], $this->dadosform['codusuarios']);
		}

		return $this->cadao->getListaCadastros($this->dadosform, $inicial, $mostrar);
	}

	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}

}