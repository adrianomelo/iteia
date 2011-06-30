<?php
include_once("ConexaoDB.php");

class PaisDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaPaises() {
		$lista_paises = array();
		$sql = "select cod_pais, pais from Paises order by pais;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_paises[] = $sql_row;
		return $lista_paises;
	}
	
	public function getNomePais($codpais) {
		$sql = "select pais from Paises where cod_pais = '".$codpais."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row["pais"];
	}
}