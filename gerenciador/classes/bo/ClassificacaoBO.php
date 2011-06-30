<?php
include_once('ConteudoBO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ClassificacaoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ClassificacaoDAO.php");

class ClassificacaoEdicaoBO extends ConteudoBO {

	private $classvo = null;
	private $classdao = null;

	public function __construct() {
		$this->classdao = new ClassificacaoDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codclassificacao"] = (int)$this->dadosform["codclassificacao"];
		$this->dadosform["nome"] = substr(trim($this->dadosform["nome"]), 0, 100);
		$this->dadosform["descricao"] = substr(trim(Util::removeTags($this->dadosform["descricao"])), 0, 50000);
		$this->dadosform["codformato"] = (int)$this->dadosform["codformato"];
	}
	
	protected function validaDados() {
		if (!$this->dadosform["nome"]) $this->erro_campos[] = "nome";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function setDadosVO() {
		$this->classvo = new ClassificacaoVO;
		$this->classvo->setCodClassificacao((int)$this->dadosform["codclassificacao"]);
		$this->classvo->setCodFormato($this->dadosform['codformato']);
		$this->classvo->setNome($this->dadosform["nome"]);
		$this->classvo->setDescricao($this->dadosform["descricao"]);
	}
	
	protected function editarDados() {
		if (!$this->classvo->getCodClassificacao()) {
			$codclass = $this->classdao->cadastrar($this->classvo);
		} else {
			$this->classdao->atualizar($this->classvo);
			$codclass = $this->classvo->getCodClassificacao();
		}
		$this->dadosform = array();
		$this->arquivos = array();
		return $codclass;
	}
	
	public function setDadosCamposEdicao($codclass) {
		$classvo = $this->classdao->getClassificacaoVO($codclass);
		$this->dadosform["codclassificacao"] = $classvo->getCodClassificacao();
		$this->dadosform["codformato"] = $classvo->getCodFormato();
		$this->dadosform["nome"] = $classvo->getNome();
		$this->dadosform["descricao"] = $classvo->getDescricao();
	}
	
	public function getListaClassificacao($get) {
		if ($get['acao']) {
			$this->classdao->executaAcao($get['codclassificacao']);
			Header('Location: conteudo_formatos.php');
			die;
		}
		
		return $this->classdao->getListaClassificacao();
	}
	
}