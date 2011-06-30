<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");

abstract class UsuarioBO {

	protected $dadosform = array();
	protected $arquivos = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
	}

	abstract protected function setDadosForm(&$dadosform);
	abstract protected function validaDados();
	abstract protected function setDadosVO();
	abstract protected function editarDados();

	protected function setArquivos(&$arquivos) {
		$this->arquivos = $arquivos;
	}

	public function editar(&$dadosform, &$arquivos) {
		$this->setDadosForm($dadosform);
		$this->setArquivos($arquivos);
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
	
	public function verificaErroCampoNovo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " error";
		else
			return "";
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}

	public function getListaEstados() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getListaEstados();
	}
	
	public function getEstado($codestado) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getNomeEstado($codestado);
	}

	public function getListaPaises() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getListaPaises();
	}
	
	public function getPais($codpais) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getNomePais($codpais);
	}

	public function getListaCidades($codestado) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getListaCidades($codestado);
	}
	
	public function getCidade($codcidade) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}
	
	public function removerImagensCache($nomearq) {
		foreach (glob(ConfigVO::getDirImgCache()."*".$nomearq."*") as $arquivo)
			unlink($arquivo);
	}
}
