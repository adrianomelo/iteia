<?php
include_once("ConexaoDB.php");

class CidadeDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaCidades($codestado = 0) {
		$lista_cidades = array();
		$sql = "select cod_cidade, cidade from Cidades";
		if ($codestado)
			$sql .= " where cod_estado = ".$codestado;
		$sql .= " order by cidade;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_cidades[] = $sql_row;
		return $lista_cidades;
	}

	public function getNomeCidade($codcidade) {
		$sql = "select cidade from Cidades where cod_cidade = '".$codcidade."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row["cidade"];
	}
}