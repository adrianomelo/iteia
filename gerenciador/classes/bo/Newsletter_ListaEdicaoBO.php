<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'vo/Newsletter_ListaVO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');

class Newsletter_ListaEdicaoBO {
	
	private $listavo = null;
	private $listadao = null;
	protected $erro_campos = array();
	protected $erro_campos_complemento = array();
	
	public function __construct() {
		$this->listadao = new Newsletter_ListaDAO();
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codlista'] = (int)$dadosform['codlista'];
		$this->dadosform['titulo'] = strip_tags(trim($dadosform['titulo']));
	}
	
	protected function validaDados() {
		if (!$this->dadosform['titulo']) {
			$this->erro_campos[] = 'titulo';
			$this->erro_campos_complemento[] = 'Título';
		}
			
		if ($this->listadao->getCountLista($this->dadosform['titulo'])) {
			$this->erro_campos[] = 'titulo';
			$this->erro_campos_complemento[] = 'Lista já cadastrada';
		}
		
		if (count($this->erro_campos))
			throw new Exception("<em>".implode("</em>, <em>", $this->erro_campos_complemento)."</em>");
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
	
	protected function setDadosVO() {
		$this->listavo = new Newsletter_ListaVO;
		$this->listavo->setCodLista($this->dadosform['codlista']);
		//$this->listavo->setCodCliente($_SESSION['sess_cliente_cod']);
		$this->listavo->setTitulo($this->dadosform['titulo']);
		$this->listavo->setDataHora(date('Y-m-d H:i:s'));
		$this->listavo->setEmails($_SESSION['sessao_newsletter_lista_emails']);
	}
	
	protected function editarDados() {
		if ($this->listavo->getCodLista())
			$codlista = $this->listadao->editar($this->listavo);
		else
			$codlista = $this->listadao->cadastrar($this->listavo);
		
		$this->dadosform = array();
		//unset($_SESSION['sessao_newsletter_lista_emails']);
		return $codlista;
	}

	public function getListaUsuarios() {
		return $this->listadao->getListaUsuarios($_SESSION['sess_cliente_cod']);
	}
	
	public function getListas() {
		return $this->listadao->getListas();
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
	
}