<?php
include_once("ConexaoDB.php");

class AtividadeDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function cadastrar(&$ativo) {
		$this->banco->sql_insert('Usuarios_Atividades', array('atividade' => $ativo->getAtividade(), 'cod_sistema' => ConfigVO::getCodSistema()));
		return $this->banco->insertId();
	}
	
	public function atualizar(&$ativo) {
		$this->banco->sql_update('Usuarios_Atividades', array('atividade' => $ativo->getAtividade()), "cod_atividade='".$ativo->getCodAtividade()."'");
	}
	
	public function apagar($codatividade) {
		if (count($codatividade)) {
			$this->banco->executaQuery("UPDATE Usuarios_Atividades SET excluido='1' WHERE cod_atividade IN (".implode(',', $codatividade).")");
			$this->banco->executaQuery("DELETE FROM Conteudo_Autores_Ficha WHERE cod_atividade IN (".implode(',', $codatividade).")");
		}
	}
	
	public function getAtividadeVO(&$codatividade) {
		$query = $this->banco->executaQuery("SELECT * FROM Usuarios_Atividades WHERE cod_atividade='".$codatividade."'");
		$sql_row = $this->banco->fetchArray($query);
		$ativo = new AtividadeVO;
		$ativo->setCodAtividade($sql_row["cod_atividade"]);
		$ativo->setAtividade($sql_row["atividade"]);
		return $ativo;
	}
	
	public function getListaAtividades($dados) {
		$array = array();
		
		if ($dados)
			$where = " AND atividade LIKE '%".$dados."%'";
		
		$query = $this->banco->sql_select('*', 'Usuarios_Atividades', "excluido='0' $where and cod_sistema='".ConfigVO::getCodSistema()."'", "", "atividade");
		while ($row = $this->banco->fetchArray($query))
			$array[] = array(
				'cod' => $row['cod_atividade'],
				'atividade' => $row['atividade']
			);
		return $array;
	}
	
	public function getAtividadeTitulo($atividade) {
		$query = $this->banco->sql_select('*', 'Usuarios_Atividades', "atividade='".$atividade."'");
		return (bool)$this->banco->numRows($query);                                                              
	}
	
	public function getAtividade($cod) {
		$query = $this->banco->sql_select('*', 'Usuarios_Atividades', "cod_atividade='".$cod."'");
		$row = $this->banco->fetchArray();
		return $row['atividade'];
	}
	
}