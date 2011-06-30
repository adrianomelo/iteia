<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/AgendaDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');

class UsuarioBO {
	
	private $usedao = null;
	
	public function __construct() {
		$this->usedao = new UsuarioDAO;
	}
	
	public function getUsuarioMaisRecentes($tipo, $inicial=0, $mostrar=3) {
		if ($tipo == 2)
			return $this->usedao->getAtoresMaisRecentes($mostrar);
		return $this->usedao->getUsuarioMaisRecentes($tipo, $inicial, $mostrar);
	}
	
	public function getUsuarioMaisAtivos($tipo, $inicial=0, $mostrar=6) {
		if ($tipo == 2)
			return $this->usedao->getAtoresMaisAtivos($mostrar);
		return $this->usedao->getUsuarioMaisAtivos($tipo, $inicial, $mostrar);
	}

	public function getListaEstados() {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/EstadoDAO.php');
		$estdao = new EstadoDAO;
		return $estdao->getListaEstados();
	}
	
	public function getEstado($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/EstadoDAO.php');
		$estdao = new EstadoDAO;
		return $estdao->getNomeEstado($codestado);
	}

	public function getListaPaises() {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/PaisDAO.php');
		$paisdao = new PaisDAO;
		return $paisdao->getListaPaises();
	}
	
	public function getPais($codpais) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/PaisDAO.php');
		$paisdao = new PaisDAO;
		return $paisdao->getNomePais($codpais);
	}

	public function getListaCidades($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/CidadeDAO.php');
		$ciddao = new CidadeDAO;
		return $ciddao->getListaCidades($codestado);
	}
	
	public function getCidade($codcidade) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/CidadeDAO.php');
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}
	
}