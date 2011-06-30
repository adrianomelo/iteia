<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/GrupoTipoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/GrupoTipoDAO.php");

class GrupoTipoBO {

	private $tipodao = null;
	private $tipovo = null;
	private $dadosform = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->tipodao = new GrupoTipoDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codtipo"] = (int)$this->dadosform["codtipo"];
		$this->dadosform["tipo"] = strip_tags($this->dadosform['tipo']);
	}
	
	protected function setDadosVO() {
		$this->tipovo = new GrupoTipoVO;
		$this->tipovo->setCodTipo((int)$this->dadosform["codtipo"]);
		$this->tipovo->setTipo($this->dadosform['tipo']);
	}
	
	protected function validaDados() {
		if (!$this->dadosform["tipo"]) $this->erro_campos[] = "tipo";
		if ($this->tipodao->getTipoTitulo($this->dadosform["tipo"]) && !$this->dadosform["codtipo"]) $this->erro_campos[] = "tipo";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function editarDados() {
		if (!$this->tipovo->getCodTipo()) {
			$cod_tipo = $this->tipodao->cadastrar($this->tipovo);
		} else {
			$this->tipodao->atualizar($this->tipovo);
			$cod_tipo = $this->tipovo->getCodTipo();
		}
	
		return $cod_tipo;	
	}
	
	public function editar(&$dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (Exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		return $this->editarDados();
	}
	
	public function getListaTipos($get) {
		return $this->tipodao->getListaTipos($get);
	}
	
	public function setDadosCamposEdicao($codtipo) {
		$tipovo = $this->tipodao->getTipoVO($codtipo);
		$this->dadosform["codtipo"] = $tipovo->getCodTipo();
		$this->dadosform["tipo"] = $tipovo->getTipo();
	}
	
	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}
	
	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " style=\"border:1px solid #FF0000; background:#FFDFDF;\"";
		else
			return "";
	}
	
	public function executaAcao($cod, $acao) {
		$this->tipodao->apagar($cod);
	}
	
}