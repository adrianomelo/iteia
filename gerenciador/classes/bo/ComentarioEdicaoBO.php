<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ComentariosVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ComentariosDAO.php");

class ComentarioEdicaoBO {

	private $comentvo = null;
	private $comentdao = null;
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->comentdao = new ComentariosDAO;
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codcomentario"] = (int)$this->dadosform["codcomentario"];
		$this->dadosform["nome"] = substr(trim($this->dadosform["nome"]), 0, 200);
		$this->dadosform["email"] = $this->dadosform["email"];
		$this->dadosform["comentario"] = substr(trim(strip_tags($this->dadosform["comentario"], '<b><i><ul><li><em><strong><p><br><ol><img><a>')), 0, 50000);
	}

	protected function validaDados() {
		if (!$this->dadosform["nome"]) $this->erro_campos[] = "nome";
		
		if (!$this->dadosform["email"]) $this->erro_campos[] = "email";
		
		if (!$this->dadosform["comentario"]) $this->erro_campos[] = "comentario";

		if ($this->dadosform["email"]) {
			if (!Util::checkEmail($this->dadosform["email"])) {
				$this->erro_campos[] = "email";
				$this->erro_mensagens[] = "E-mail está em formato incorreto";
			}
		}

        if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->comentvo = new ComentariosVO;
		$this->comentvo->setCodComentario((int)$this->dadosform["codcomentario"]);
		$this->comentvo->setNome($this->dadosform["nome"]);
		$this->comentvo->setEmail($this->dadosform["email"]);
		$this->comentvo->setSite($this->dadosform["site"]);
		$this->comentvo->setComentario($this->dadosform["comentario"]);
	}

	protected function editarDados() {
		$this->comentdao->atualizar($this->comentvo);
		$codcomentario = $this->comentvo->getCodComentario();

		$this->dadosform = array();
		return $codcomentario;
	}

	public function setDadosCamposEdicao($codcomentario) {
		$comentvo = $this->comentdao->getComentarioVO($codcomentario);
		$this->dadosform["codcomentario"] = $comentvo->getCodComentario();
		$this->dadosform["nome"] = $comentvo->getNome();
		$this->dadosform["site"] = $comentvo->getSite();
		$this->dadosform["email"] = $comentvo->getEmail();
		$this->dadosform["comentario"] = $comentvo->getComentario();
	}

	public function getValorCampo($nomecampo) {
		return $this->dadosform[$nomecampo];
	}

	public function editar(&$dadosform, &$arquivos) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (Exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		return $this->editarDados();
	}
	
	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " style=\"border:1px solid #FF0000; background:#FFDFDF;\"";
		else
			return "";
	}
	
	public function getUrlConteudo($codcomentario) {
		return $this->comentdao->getUrlConteudo($codcomentario);
	}
	
}
