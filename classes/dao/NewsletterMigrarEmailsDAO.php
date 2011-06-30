<?php
require_once("ConexaoDB.php");

class NewsletterMigrarEmailsDAO {

	protected $caminho_fundarpe = '/home/ramom/fundarpe2/classes/dao/';

	public function __construct() {
		include_once($this->caminho_fundarpe.'ConexaoDBExterna.php');
		$banco_fundarpe = new ConexaoDBExterna;
		$lista_emails = $banco_fundarpe->getEmails();

		$banco = ConexaoDB::singleton();
		
		foreach ($lista_emails as $e) {
			$query = $banco->executaQuery("SELECT cod_email FROM Newsletters_Usuarios_Fundarpe WHERE email='".$e."'");
			if (!$banco->numRows($query))
				$banco->executaQuery("INSERT INTO Newsletters_Usuarios_Fundarpe VALUES (NULL, '".$e."')");
		}
	}

}