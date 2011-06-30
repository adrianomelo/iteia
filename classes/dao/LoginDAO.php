<?php
require_once("ConexaoDB.php");

class LoginDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function lembrarAcesso($buscarpor, $lembrar) {
		
		if ($lembrar == 'senha')
			$where = " AND login='".$buscarpor."'";
			
		if ($lembrar == 'login')
			$where = " AND email='".$buscarpor."'";
		
		if (!$buscarpor)
			return false;
		
		$sql = "SELECT cod_usuario, email, nome, login, senha FROM Usuarios WHERE disponivel='1' AND situacao='3' AND email != '' AND login != '' AND cod_sistema='".ConfigVO::getCodSistema()."' $where";
		$query = $this->banco->executaQuery($sql);
		
		return $row = $this->banco->fetchArray();
	}

	public function checaLogin($login, $senha) {
		//$sql = "SELECT cod_usuario, nome, login, senha, cod_tipo FROM Usuarios WHERE login='".$login."' AND senha='".$senha."' AND disponivel='1' AND situacao='3' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		$sql = "SELECT U.cod_usuario, U.nome, U.login, U.senha, U.cod_tipo, UN.nivel FROM Usuarios U, Usuarios_Niveis UN WHERE U.cod_usuario = UN.cod_usuario and U.login='".$login."' AND U.senha='".$senha."' AND U.disponivel='1' AND U.situacao='3' AND U.cod_sistema='".ConfigVO::getCodSistema()."' AND U.cod_tipo = 2 and UN.nivel in (2, 5, 6, 7, 8);";
		$query = $this->banco->executaQuery($sql);
		if ($this->banco->numRows()) {
			$row = $this->banco->fetchObject();

			$return['cod'] = $row->cod_usuario;
			$return['login'] = $row->login;
			$return['senha'] = $row->senha;
			$return['nome'] = $row->nome;
			$return['nivel'] = $row->nivel;

			/*$sql = "select cod_colaborador from Colaboradores_Autores where cod_autor = '".$row->cod_usuario."';";
			$query = $this->banco->executaQuery($sql);
			$row = $this->banco->fetchArray();
			$return['cod_colaborador'] = $row['cod_colaborador'];*/
			$sql = "select t1.cod_colaborador, t1.responsavel from Colaboradores_Integrantes AS t1 LEFT JOIN Usuarios AS t2 ON (t1.cod_colaborador=t2.cod_usuario) where t1.cod_autor = '".$return['cod']."' AND t2.disponivel='1' AND t2.situacao='3' LIMIT 1;";
			$query = $this->banco->executaQuery($sql);
			$row = $this->banco->fetchArray();
			$return['cod_colaborador'] = $row['cod_colaborador'];
			$return['colaborador_responsavel'] = $row['responsavel'];

			// dados de grupo
			$return['cod_grupo'] = array();
			$return['grupo_responsavel'] = array();
			$sql = "SELECT cod_grupo, responsavel FROM Grupo_Integrantes WHERE cod_autor = '".$return['cod']."'";
			$query = $this->banco->executaQuery($sql);
			while ($row = $this->banco->fetchArray()) {
				$return['cod_grupo'][] = $row['cod_grupo'];
				if ($row['responsavel'])
					$return['grupo_responsavel'][$row['cod_grupo']] = true;
			}

			/*if ($row->cod_tipo == 1) {
				$query = $this->banco->executaQuery("SELECT administrador FROM Colaboradores WHERE cod_usuario='".$row->cod_usuario."'");
				$usuario = $this->banco->fetchObject();
				$return['logado_como'] = ($usuario->administrador ? 3 : 2);
			} else {
				$return['logado_como'] = 1;
			}*/
			return $return;
		}
	}

}
