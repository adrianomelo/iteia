<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AtividadeVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");

class AtividadeBO {

	private $atidao = null;
	private $ativo = null;
	private $dadosform = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->atidao = new AtividadeDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codatividade"] = (int)$this->dadosform["codatividade"];
		$this->dadosform["atividade"] = strip_tags($this->dadosform['atividade']);
	}
	
	protected function setDadosVO() {
		$this->ativo = new AtividadeVO;
		$this->ativo->setCodAtividade((int)$this->dadosform["codatividade"]);
		$this->ativo->setAtividade($this->dadosform['atividade']);
	}
	
	protected function validaDados() {
		if (!$this->dadosform["atividade"]) $this->erro_campos[] = "atividade";
		if ($this->atidao->getAtividadeTitulo($this->dadosform["atividade"]) && !$this->dadosform["codatividade"]) $this->erro_campos[] = "atividade";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function editarDados() {
		if (!$this->ativo->getCodAtividade()) {
			$cod_atividade = $this->atidao->cadastrar($this->ativo);
		} else {
			$this->atidao->atualizar($this->ativo);
			$cod_atividade = $this->ativo->getCodAtividade();
		}
	
		return $cod_atividade;	
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
	
	public function getListaAtividades($get) {
		return $this->atidao->getListaAtividades($get);
	}
	
	public function setDadosCamposEdicao($codatividade) {
		$ativo = $this->atidao->getAtividadeVO($codatividade);
		$this->dadosform["codatividade"] = $ativo->getCodAtividade();
		$this->dadosform["atividade"] = $ativo->getAtividade();
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
		$this->atidao->apagar($cod);
	}
	
}