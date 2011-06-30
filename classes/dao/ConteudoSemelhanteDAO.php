<?php
include_once("ConteudoDAO.php");

class ConteudoSemelhanteDAO extends ConteudoDAO {
	
	public function getDadosConteudoVO(&$codconteudo) {
		$contvo = new ConteudoVO;
		$this->getConteudoVO($codconteudo, $contvo);
		return $contvo;
	}
	
}
