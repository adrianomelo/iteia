<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ListaPublicaDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class ListaPublicaBO {

	private $listadao = null;
	private $contdao = null;

	public function __construct() {
		$this->listadao = new ListaPublicaDAO;
		$this->contdao = new ConteudoDAO;
	}
	
	public function getListaPublica($inicial, $mostrar=6) {
		return $this->listadao->getListaPublica($inicial, $mostrar);
	}
	
	public function getFormatoConteudo($codconteudo) {
		return $this->contdao->getFormatoConteudo($codconteudo);
	}
	
	
	
	
	
}