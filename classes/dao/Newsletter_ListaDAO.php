<?php
include_once("ConexaoDB.php");

class Newsletter_ListaDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getCountLista($titulo, $codlista=0) {
		$query = $this->banco->executaQuery("SELECT titulo FROM Home_Newsletter_Listas WHERE titulo='".$titulo."' AND excluido='0' AND cod_lista!='".$codlista."'");
		$row = mysql_fetch_row($query);
		return $row[0];
	}
	
	public function cadastrar(&$newsvo) {
		$this->banco->sql_insert('Home_Newsletter_Listas', array(
			'titulo'		=> $newsvo->getTitulo(),
			'data_hora'		=> $newsvo->getDataHora()
		));
		
		$codlista = mysql_insert_id();
		
		return $codlista;
	}
	
	public function editar(&$newsvo) {
	
	}
	
	public function getNewsletterUsuariosListasBusca(&$dadosform, $inicial, $mostrar) {
		$lista = array();
		
		if ($dadosform['codlista'])
			$where = " AND t2.cod_lista='".$dadosform['codlista']."'";
		if ($dadosform['email'])
			$where .= " AND t1.email LIKE '%".$dadosform['email']."%'";
		if ($dadosform['titulo'])
			$where .= " AND t3.titulo LIKE '%".$dadosform['titulo']."%'";
		
		$sql = "FROM Home_Newsletter_Usuarios AS t1 LEFT JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_usuario=t2.cod_usuario) RIGHT JOIN Home_Newsletter_Listas AS t3 ON (t2.cod_lista=t3.cod_lista) WHERE t1.excluido='0' ".$where." ORDER BY t3.titulo ASC";
		
		$query = $this->banco->executaQuery('SELECT COUNT(1) '.$sql);
		$row = mysql_fetch_row($query);
		$lista['total'] = $row[0];
		
		$sql_result = $this->banco->executaQuery("SELECT t1.cod_usuario, t1.nome, t1.email, t2.cod_lista, t3.titulo ".$sql." LIMIT ".$inicial.",".$mostrar);
		$lista['resultado'] = array();
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista['resultado'][$sql_row['cod_lista']][] = array(
						'cod_usuario' 	=> $sql_row['cod_usuario'],
						'cod_lista' 	=> $sql_row['cod_lista'],
						'lista'			=> $sql_row['titulo'],
						'nome' 			=> $sql_row['nome'],
						'email' 		=> $sql_row['email'],
					);
		}	
		
		return $lista;
	}
	
	public function getEmailsLista($nomelista) {
		$array = array();
		$sql = "SELECT t1.email FROM Home_Newsletter_Usuarios AS t1 INNER JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_usuario=t2.cod_usuario) LEFT JOIN Home_Newsletter_Listas AS t3 ON (t2.cod_lista=t3.cod_lista) WHERE t1.excluido='0' AND t3.excluido='0' AND t3.titulo='".$nomelista."'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$array[] = $sql_row['email'];
		return $array;
	}
	
	public function getListas() {
		$array = array();
		$query = $this->banco->executaQuery("SELECT * FROM Home_Newsletter_Listas WHERE excluido='0' ORDER BY titulo");
		while ($row = $this->banco->fetchArray($query))
			$array[] = $row;
		return $array;
	}
	
	public function getListaUsuarios($codcliente) {
		return $this->banco->executaQueryFetchRowSet($this->banco->executaQuery("SELECT * FROM Newsletter_Usuarios WHERE cod_cliente='".$codcliente."' AND excluido='0' ORDER BY email"));
	}
	
	public function apagarTodaLista($codlista) {
		$this->banco->sql_delete('Home_Newsletter_Usuarios_Lista', "cod_lista='".$codlista."'");
	}
	
	public function apagarUsuarioLista($codlista, $codusuario) {
		$this->banco->sql_delete('Home_Newsletter_Usuarios_Lista', "cod_usuario='".$codusuario."' AND cod_lista='".$codlista."'");
	}
	
}
