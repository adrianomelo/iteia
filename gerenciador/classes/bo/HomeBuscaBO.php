<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/HomeDAO.php");

class HomeBuscaBO {

	private $homedao = null;
	private $dados_form = array();
	private $total = 0;

	public function __construct() {
		$this->homedao = new HomeDAO();
	}

	public function getListaConteudoHomeSelecionados($paginahome, $destaque) {
		return $this->homedao->getListaConteudoHomeSelecionados($paginahome, $destaque);
	}
	
	public function getListaConteudoHomeUsuario($codusuario, $tipo_usuario) {
		return $this->homedao->getListaConteudoHomeUsuario($codusuario, $tipo_usuario);
	}
	
	public function getDadosUsuario($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		return $usrdao->getUsuarioDados($codusuario);
	}
	
	public function atualiza() {
		return $this->homedao->atualiza();
	}

}
