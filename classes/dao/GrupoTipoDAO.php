<?php
include_once("ConexaoDB.php");

class GrupoTipoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function cadastrar(&$tipovo) {
		$this->banco->sql_insert('Grupo_Tipos', array('tipo' => $tipovo->getTipo(), 'cod_sistema' => ConfigVO::getCodSistema()));
		return $this->banco->insertId();
	}
	
	public function atualizar(&$tipovo) {
		$this->banco->sql_update('Grupo_Tipos', array('tipo' => $tipovo->getTipo()), "cod_tipo='".$tipovo->getCodTipo()."'");
	}
	
	public function apagar($codtipo) {
		if (count($codtipo)) {
			$this->banco->executaQuery("UPDATE Grupo_Tipos SET excluido='1' WHERE cod_tipo IN (".implode(',', $codtipo).")");
		}
	}
	
	public function getTipoVO(&$codtipo) {
		$query = $this->banco->executaQuery("SELECT * FROM Grupo_Tipos WHERE cod_tipo='".$codtipo."'");
		$sql_row = $this->banco->fetchArray($query);
		$tipovo = new GrupoTipoVO;
		$tipovo->setCodTipo($sql_row["cod_tipo"]);
		$tipovo->setTipo($sql_row["tipo"]);
		return $tipovo;
	}
	
	public function getListaTipos($dados) {
		$array = array();
		
		if ($dados)
			$where = " AND tipo LIKE '%".$dados."%'";
		
		$query = $this->banco->sql_select('*', 'Grupo_Tipos', "excluido='0' $where", "", "tipo");
		while ($row = $this->banco->fetchArray($query))
			$array[$row['cod_tipo']] = array(
				'cod' => $row['cod_tipo'],
				'tipo' => $row['tipo']
			);
		return $array;
	}
	
	public function getTipoTitulo($tipo) {
		$query = $this->banco->sql_select('*', 'Grupo_Tipos', "tipo='".$tipo."'");
		return (bool)$this->banco->numRows($query);                                                              
	}
	
	public function getTipo($cod) {
		$query = $this->banco->sql_select('*', 'Grupo_Tipos', "cod_tipo='".$cod."'");
		$row = $this->banco->fetchArray();
		return $row['tipo'];
	}
	
}