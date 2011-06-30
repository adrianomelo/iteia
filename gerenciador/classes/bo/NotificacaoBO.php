<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NotificacaoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class NotificacaoBO {

	private $notdao = null;
	private $contdao = null;

	public function __construct() {
		$this->notdao = new NotificacaoDAO;
		$this->contdao = new ConteudoDAO;
	}
	
	public function getListaNotificacao($inicial, $mostrar=6) {
		return $this->notdao->getListaNotificacao($inicial, $mostrar);
	}
	
	public function getFormatoConteudo($codconteudo) {
		return $this->contdao->getFormatoConteudo($codconteudo);
	}
	
	public function getStatusConteudo($codconteudo) {
		return $this->contdao->getPublicacaoConteudo($codconteudo);
	}
	
	public function getMotivoReprovacao($codconteudo) {
		return $this->notdao->getMotivoReprovacao($codconteudo);
	}
		
}
