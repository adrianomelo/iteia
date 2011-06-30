<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoRecenteDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class ConteudoRecenteBO {

	private $contrdao = null;
	private $contdao = null;

	public function __construct() {
		$this->contrdao = new ConteudoRecenteDAO;
		$this->contdao = new ConteudoDAO;
	}
	
	public function getListaConteudoRecente($inicial, $mostrar=6) {
		return $this->contrdao->getListaConteudoRecente($inicial, $mostrar);
	}
	
	public function getFormatoConteudo($codconteudo) {
		return $this->contdao->getFormatoConteudo($codconteudo);
	}
	
}
