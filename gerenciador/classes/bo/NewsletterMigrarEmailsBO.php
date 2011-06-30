<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterMigrarEmailsDAO.php");

class NewsletterMigrarEmailsBO {
	
	public function __construct() {
		$newsdao = new NewsletterMigrarEmailsDAO;
	}
	
}