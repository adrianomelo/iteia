<?php
include_once("UsuarioDAO.php");

class GrupoDAO extends UsuarioDAO {

	public function cadastrar(&$usuariovo) {
		// cadastra usuario normal
	    $codusuario = $this->cadastrarUsuario($usuariovo);
		// cadastro de grupo
		// se o cadastro está vindo de um autor ou colaborador
		//if ($_SESSION['logado_como'] == 1)
			$codautor = $_SESSION['logado_cod'];
		
		if ($_SESSION['logado_dados']['nivel'] >= 5) {
			$codcolab = $_SESSION['logado_dados']['cod_colaborador'];
			$this->banco->executaQuery("UPDATE Usuarios SET situacao='3' WHERE cod_usuario = '".$codusuario."'");
		}
		
		if ($codusuario) {
			sort($usuariovo->getTipo());
			$this->banco->sql_insert('Grupos', array('cod_usuario' => $codusuario, 'cod_colaborador' => $codcolab, 'cod_autor' => $codautor, 'tipo' => implode(',', $usuariovo->getTipo())));
			// se o cadastro for feito por um autor automaticamente ele é incluido como autor do grupo
			//if ($_SESSION['logado_como'] == 1) {
				$this->banco->executaQuery("INSERT INTO Grupos_Autores VALUES ('".$codusuario."', '".$_SESSION['logado_cod']."')");
			//}
			
			//$this->atualizarUsuariosNiveis($usuariovo, $codusuario);
			$this->atualizarIntegrantes($usuariovo, $codusuario);
		}
		return $codusuario;
	}

	public function atualizar(&$usuariovo) {
		// atualizar usuario normal
	    $codusuario = $this->editarUsuario($usuariovo);
		// atualizar grupo
		if ($codusuario) {
			sort($usuariovo->getTipo());
			$this->banco->sql_update('Grupos', array('tipo' => implode(',', $usuariovo->getTipo())), "cod_usuario='".$codusuario."'");
			
			//$this->atualizarUsuariosNiveis($usuariovo, $codusuario);
			$this->atualizarIntegrantes($usuariovo, $codusuario);
		}
		return $codusuario;
	}
	
	private function atualizarIntegrantes(&$usuariovo, $codusuario) {
    	$this->banco->sql_delete('Grupo_Integrantes', "cod_grupo='".$codusuario."'");
		if (count($usuariovo->getListaIntegrantes())) {
			foreach($usuariovo->getListaIntegrantes() as $key => $value) {
				$this->banco->sql_insert('Grupo_Integrantes', array('cod_grupo' => $codusuario, 'cod_autor' => $value['cod_usuario'], 'responsavel' => $value['responsavel']));
			}
		}
		// cadastra o autor que fez o cadastro do grupo como sendo responsavel pelo mesmo
		if ($_SESSION['logado_dados']['nivel'] < 7)
			$this->banco->executaQuery("REPLACE INTO Grupo_Integrantes VALUES ('".$codusuario."', '".$_SESSION['logado_cod']."', 1)");
    }
    
    /*
    private function atualizarUsuariosNiveis(&$usuariovo, $codusuario) {
    	// pego todos os intergrantes (autores) do colaborador
    	$query = $this->banco->executaQuery("SELECT cod_autor FROM Grupo_Integrantes WHERE cod_grupo='".$codusuario."'");
    	$arrayId = array();
    	while ($row = $this->banco->fetchObject($query))
    		$arrayId[$row->cod_autor] = $row->cod_autor;
    		
    	foreach ($arrayId as $cod)
    		$this->banco->executaQuery("REPLACE INTO Usuarios_Niveis VALUES ('".$cod."', 2)");
    	
		// aqui adiciono os novos autores com niveis 5 e 6
    	if (count($usuariovo->getListaIntegrantes())) {
			foreach($usuariovo->getListaIntegrantes() as $key => $value) {
				$this->banco->executaQuery("REPLACE INTO Usuarios_Niveis VALUES ('".$value['cod_usuario']."', '".Util::iif($value['responsavel'], 6, 5)."')");
			}
		}
    }
    */

	public function atualizarGrupoAutores(&$usuariovo) {
        if (count($usuariovo->getListaAutores())) {
            $this->banco->executaQuery("DELETE FROM Grupos_Autores WHERE cod_grupo='".$usuariovo->getCodUsuario()."'");
            $this->banco->executaQuery("UPDATE Grupos SET num_autores='0' WHERE cod_usuario='".$usuariovo->getCodUsuario()."'");
            foreach ($usuariovo->getListaAutores() as $value) {
            	// adiciona autor ao grupo
			    $this->banco->executaQuery("INSERT INTO Grupos_Autores VALUES ('".$usuariovo->getCodUsuario()."', '".$value['cod_usuario']."')");
				$this->banco->executaQuery("UPDATE Grupos SET num_autores=num_autores+1 WHERE cod_usuario='".$usuariovo->getCodUsuario()."'");
			}
		}
	}

	public function getComunicadoresGrupo($codgrupo) {
		return $this->getComunicadoresUsuario($codgrupo);
	}

	public function getSitesGrupo($codgrupo) {
		return $this->getSitesUsuario($codgrupo);
	}

	public function getGrupoVO($codgrupo) {
		$grupovo = new GrupoVO;

		$this->getUsuarioVO($codgrupo, $grupovo);

		$sql = "SELECT t1.*, t2.titulo FROM Grupos AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_usuario=t2.cod_item) WHERE t2.tipo='3' AND t1.cod_usuario='".$codgrupo."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject($sql_result);

		$grupovo->setTipo($sql_row->tipo);
		$grupovo->setUrl($sql_row->titulo);
		
		$sql = "SELECT t1.cod_autor, t1.responsavel, t2.nome, t2.imagem FROM Grupo_Integrantes AS t1 INNER JOIN Usuarios AS t2 ON (t1.cod_autor=t2.cod_usuario) WHERE t1.cod_grupo='".$codgrupo."'";
		$sql_result = $this->banco->executaQuery($sql);
		$arrayIntegrantes = array();
		while ($sql_row = $this->banco->fetchObject($sql_result)) {
			$arrayIntegrantes[] = array(
				'cod_usuario' 	=> $sql_row->cod_autor,
				'responsavel' 	=> $sql_row->responsavel,
				'nome' 			=> $sql_row->nome,
				'imagem' 		=> $sql_row->imagem
			);
		}
		$grupovo->setListaIntegrantes($arrayIntegrantes);

		return $grupovo;
	}

	public function getAutoresGrupo($codgrupo) {
        //$sql = "SELECT t1.*, t3.titulo, t4.*, t5.*, t6.* FROM Usuarios AS t1 INNER JOIN Grupos_Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) LEFT JOIN Estados AS t5 ON (t1.cod_estado=t5.cod_estado) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t2.cod_grupo='$codgrupo' AND t3.tipo='2'";
        $sql = "SELECT t1.*, t3.titulo, t4.nome_completo, t4.cpf, t4.data_nascimento, t4.data_falecimento, t5.*, t6.* FROM Usuarios AS t1 INNER JOIN Grupo_Integrantes AS t2 ON (t1.cod_usuario=t2.cod_autor) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) LEFT JOIN Estados AS t5 ON (t1.cod_estado=t5.cod_estado) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t2.cod_grupo='$codgrupo' AND t3.tipo='2' AND t3.cod_sistema='".ConfigVO::getCodSistema()."'";
        $lista = array();
        $query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray($query))
    		$lista[] = $row;
        return $lista;
    }

}
