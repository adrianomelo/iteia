<?php
include_once("ConexaoDB.php");

class EstadoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaEstados() {
		$lista_estados = array();
		$sql = "select cod_estado, estado, sigla from Estados order by estado;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_estados[] = $sql_row;
		return $lista_estados;
	}

	public function getNomeEstado($codestado) {
		$sql = "select estado from Estados where cod_estado = '".$codestado."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row["estado"];
	}

	public function getSiglaEstado($codestado) {
		$sql = "select sigla from Estados where cod_estado = '".$codestado."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row["sigla"];
	}
}