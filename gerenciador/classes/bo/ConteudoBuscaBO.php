<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class ConteudoBuscaBO {

	private $contao = null;
	private $dadosform = array();

	public function __construct() {
		$this->contao = new ConteudoDAO();
	}

	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['formato'] = (int)$dados['formato'];
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);

		//if ($_SESSION["logado_como"] == 1)
		if ($_SESSION['logado_dados']['nivel'] == 2)
			$this->dadosform['codautor'] = $_SESSION['logado_cod'];
		//if ($_SESSION["logado_como"] == 2)
		if ($_SESSION['logado_dados']['nivel'] >= 5)
			//$this->dadosform['codcolaborador'] = $_SESSION['logado_cod'];
			$this->dadosform['codcolaborador'] = $_SESSION['logado_dados']['cod_colaborador'];

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codconteudo'] = (array)$dados['codconteudo'];
	}

    public function getListaConteudo($get='', $inicial='0', $mostrar='6') {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->contao->executaAcoes($this->dadosform['acao'], $this->dadosform['codconteudo']);
			Header('Location: conteudo.php');
			die;
		}

		return $this->contao->getListaConteudo($this->dadosform, $inicial, $mostrar);
	}

	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}

}