<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/TagsVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/TagDAO.php");

class TagsBO {

	private $tagdao = null;
	private $tagvo = null;
	private $dadosform = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->tagdao = new TagDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codtag"] = (int)$this->dadosform["codtag"];
		$this->dadosform["tag"] = Util::geraTags($this->dadosform['tag']);
	}
	
	protected function setDadosVO() {
		$this->tagvo = new TagsVO;
		$this->tagvo->setCodTag((int)$this->dadosform["codtag"]);
		$this->tagvo->setTag($this->dadosform['tag']);
	}
	
	protected function validaDados() {
		if (!$this->dadosform["tag"]) $this->erro_campos[] = "tag";
		if ($this->tagdao->getTag($this->dadosform["tag"]) && !$this->dadosform["codtag"]) $this->erro_campos[] = "tag";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function editarDados() {
		if (!$this->tagvo->getCodTag()) {
			$cod_tag = $this->tagdao->cadastrar($this->tagvo);
		} else {
			$this->tagdao->atualizar($this->tagvo);
			$cod_tag = $this->tagvo->getCodTag();
		}
	
		return $cod_tag;	
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
	
	public function getListaTags($get) {
		return $this->tagdao->getListaTags($get);
	}
	
	public function setDadosCamposEdicao($codtag) {
		$tagvo = $this->tagdao->getTagVO($codtag);
		$this->dadosform["codtag"] = $tagvo->getCodTag();
		$this->dadosform["tag"] = $tagvo->getTag();
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
		$this->tagdao->apagar($cod);
	}
	
}