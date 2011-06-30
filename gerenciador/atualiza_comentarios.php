<?php
include_once('../classes/dao/ConexaoDB.php');

class Atualiza {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
		
		$sql = "SELECT DISTINCT(cod_conteudo) FROM Conteudo_Comentarios";
		$query = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($query)) {
			$this->banco->sql_insert('Conteudo_Opcoes', array('cod_conteudo' => $row['cod_conteudo'], 'permitir_comentarios' => 1));
			echo $row['cod_conteudo'].'<br />';
		}
		
	}

}

$c = new Atualiza;
