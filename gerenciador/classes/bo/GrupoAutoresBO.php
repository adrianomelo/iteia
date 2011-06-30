<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/GrupoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/GrupoDAO.php");

class GrupoAutoresBO extends UsuarioBO {

	private $grupovo = null;
	private $grupodao = null;

	public function __construct() {
		$this->grupodao = new GrupoDAO;
		parent::__construct();
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codgrupo'] = (int)$dadosform['codgrupo'];
		$this->dadosform['cod_autores_relacionado'] = $_SESSION['sessao_autores_relacionados'];
	}
	
	protected function validaDados() { }
	
	protected function setDadosVO() {
		$this->grupovo = new GrupoVO;
		$this->grupovo->setCodUsuario($this->dadosform['codgrupo']);
		$this->grupovo->setListaAutores($this->dadosform['cod_autores_relacionado']);
	}
	
	protected function editarDados() {
		if ($this->grupovo->getCodUsuario()) {
			$this->grupodao->atualizarGrupoAutores($this->grupovo);
		}
		return $this->grupovo->getCodUsuario();
	}
	
	public function carregarAutoresRelacionamento($codgrupo) {
		$autorelacionado = $this->grupodao->getAutoresGrupo($codgrupo);
		foreach ($autorelacionado as $key => $value) {
			if (!count($_SESSION['sessao_autores_relacionados'][$value['cod_usuario']])) {
				$_SESSION['sessao_autores_relacionados'][$value['cod_usuario']] = $value;
			}
		}
	}
	
}
