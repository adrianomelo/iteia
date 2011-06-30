<?php
include_once("ConexaoDB.php");

class Newsletter_UsuariosDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getCountEmailUsuario($email, $cod_usuario=0) {
		$query = $this->banco->executaQuery("SELECT COUNT(1) FROM Home_Newsletter_Usuarios WHERE email='".$email."' AND excluido='0' AND cod_usuario!='".$cod_usuario."'");
		$row = mysql_fetch_row($query);
		return $row[0];
	}
	
	public function cadastrar(&$newsvo) {
		$this->banco->sql_insert('Home_Newsletter_Usuarios', array(
			'nome'			=> $newsvo->getNome(),
			'email'			=> $newsvo->getEmail(),
			'data_hora'		=> date('Y-m-d H:i:s')
		));

		$codusuario = mysql_insert_id();
		
		foreach($newsvo->getLista() as $key => $value)
			$this->banco->sql_insert('Home_Newsletter_Usuarios_Lista', array('cod_usuario' => $codusuario, 'cod_lista' => $key));

		return $codusuario;
	}
	
	public function editar(&$newsvo) {
		$this->banco->sql_update('Home_Newsletter_Usuarios', array(
			'nome'	=> $newsvo->getNome(),
			'email' => $newsvo->getEmail()
		),	"cod_usuario='".$newsvo->getCodUsuario()."'");
		
		$this->banco->sql_delete('Home_Newsletter_Usuarios_Lista', "cod_usuario='".$newsvo->getCodUsuario()."'");
		
		foreach($newsvo->getLista() as $key => $value)
			$this->banco->sql_insert('Home_Newsletter_Usuarios_Lista', array('cod_usuario' => $newsvo->getCodUsuario(), 'cod_lista' => $key));
		
		return $newsvo->getCodUsuario();
	}
	
	public function getDadosEdicaoVO($codusuario) {
		$dados = $this->banco->fetchArray($this->banco->executaQuery("SELECT * FROM Home_Newsletter_Usuarios WHERE cod_usuario='".$codusuario."'"));
		$newsvo = new Newsletter_UsuariosVO;
		$newsvo->setCodUsuario($dados['cod_usuario']);
		$newsvo->setNome($dados['nome']);
		$newsvo->setEmail($dados['email']);
		
		$arrayLista = array();
		$dados = $this->banco->executaQuery("SELECT t1.cod_lista, t1.titulo FROM Home_Newsletter_Listas AS t1 LEFT JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_lista=t2.cod_lista) WHERE t2.cod_usuario='".$codusuario."'");
		while ($value = $this->banco->fetchArray($dados))
			$arrayLista[$value['cod_lista']] = $value['titulo'];
		
		$newsvo->setLista($arrayLista);
		return $newsvo;
	}
	
	/*
	public function getNewsletterUsuariosBusca(&$bdadosvo, &$sql_complemento, &$sql_comentario) {

		$lista = array();
		$sql_from = " from Newsletter_Usuarios NU";
		$sql_where = " where NU.excluido = 0 and NU.data_hora >= '".$bdadosvo->getDataInicial()." 00:00:00' and NU.data_hora <= '".$bdadosvo->getDataFinal()." 23:59:59' and ".$sql_complemento;

		$sql = "select /*buscanewsletterusuarios".$sql_comentario."/* NU.cod_usuario, NU.data_hora ".$sql_from.$sql_where.";";

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchRow($sql_result))
			$lista[] = array('data' => $sql_row['data_hora'], 'coditem' => $sql_row['cod_usuario']);

		return $lista;
	}
	*/
	
	public function excluir($codusuario) {
		$this->banco->executaUpdate('Home_Newsletter_Usuarios', array('excluido' => 1), "cod_usuario='".$codusuario."'");
		//$this->banco->executaInsert('Newsletter_Usuarios_Historico', array('cod_usuario' => $codusuario, 'cod_acao' => 2, 'data_hora' => date('Y-m-d H:i:s')));
	}
	
	public function getDadosUsuario($codusuario) {
		return $this->banco->executaQueryfetchRow("SELECT * FROM Newsletter_Usuarios WHERE cod_usuario='".$codusuario."'");
	}
	
	public function getResultadoBuscaItem($codusuario) {
		return $this->getDadosUsuario($codusuario);
	}
	
}