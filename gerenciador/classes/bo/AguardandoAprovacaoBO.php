<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AguardandoAprovacaoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class AguardandoAprovacaoBO {

	private $agpdao = null;
	private $contdao = null;

	public function __construct() {
		$this->agpdao = new AguardandoAprovacaoDAO;
		$this->contdao = new ConteudoDAO;
	}
	
	public function getListaAguardandoAprovacao($inicial, $mostrar=6) {
		return $this->agpdao->getListaAguardandoAprovacao($inicial, $mostrar);
	}
	
	public function getFormatoConteudo($codconteudo) {
		return $this->contdao->getFormatoConteudo($codconteudo);
	}
	
}
