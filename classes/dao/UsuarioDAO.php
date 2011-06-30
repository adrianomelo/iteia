<?php
include_once("ConexaoDB.php");

class UsuarioDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function cadastrarUsuario(&$usuariovo) {
		// cadastra usuario
		$sql_result = $this->banco->sql_insert('Usuarios', array('cod_sistema' => ConfigVO::getCodSistema(), 'cod_tipo' => $usuariovo->getCodTipo(), 'nome' => $usuariovo->getNome(), 'descricao' => $usuariovo->getDescricao(), 'endereco' => $usuariovo->getEndereco(), 'complemento' => $usuariovo->getComplemento(), 'bairro' => $usuariovo->getBairro(), 'cep' => $usuariovo->getCEP(), 'cod_pais' => $usuariovo->getCodPais(), 'cod_estado' => $usuariovo->getCodEstado(), 'cod_cidade' => $usuariovo->getCodCidade(), 'cidade' => $usuariovo->getCidade(), 'telefone' => $usuariovo->getTelefone(), 'celular' => $usuariovo->getCelular(), 'email' => $usuariovo->getEmail(), 'mostrar_email' => $usuariovo->getMostrarEmail(), 'site' => $usuariovo->getSite(), 'login' => $usuariovo->getLogin(), 'senha' => $usuariovo->getSenha(), 'situacao' => $usuariovo->getSituacao(), 'datacadastro' => date('Y-m-d H:i:s'), 'disponivel' => 1));
		$cod_usuario = $this->banco->insertId();

		// cadastra sites relacionados
		foreach($usuariovo->getSitesRelacionados() as $key => $value) {
			$this->banco->sql_insert('Links', array('cod_usuario' => $cod_usuario, 'site' => utf8_decode($value['titulo']), 'url' => $value['endereco']));
		}

		// cadastra comunicadores
		foreach($usuariovo->getContatos() as $key => $value) {
			$this->banco->sql_insert('Comunicadores_Usuarios', array('cod_usuario' => $cod_usuario, 'cod_comunicador' => $value['tipo'], 'nome_usuario' => $value['nome_usuario']));
		}

		/*
		$i = 0;
		$url = $usuariovo->getUrl();
		do {
			if ($i)
				$url = $url.$i;
				$sql = "insert into Urls values ('".$url."', '".$cod_usuario."', '".$usuariovo->getCodTipo()."')";
				$tenta = $this->banco->executaQuery($sql);
				$i++;
		}
		while (!$tenta);
		*/

		switch ($usuariovo->getCodTipo()) {
			case 1: $pretitulo = 'colaboradores'; break;
			case 2: $pretitulo = 'autores'; break;
			case 3: $pretitulo = 'grupos'; break;
		}

		$url = $pretitulo.'/'.$usuariovo->getUrl();
		$sql = "insert into Urls values ('".$url."', '".$cod_usuario."', '".$usuariovo->getCodTipo()."', '".ConfigVO::getCodSistema()."')";
		$tenta = $this->banco->executaQuery($sql);

		$this->banco->sql_update('Usuarios', array('url' => $url), "cod_usuario='".$cod_usuario."'");

		return $cod_usuario;
	}

	public function editarUsuario(&$usuariovo) {
		// edita usuario
		$sql_result = $this->banco->sql_update('Usuarios', array('nome' => $usuariovo->getNome(), 'descricao' => $usuariovo->getDescricao(), 'endereco' => $usuariovo->getEndereco(), 'complemento' => $usuariovo->getComplemento(), 'bairro' => $usuariovo->getBairro(), 'cep' => $usuariovo->getCEP(), 'cod_pais' => $usuariovo->getCodPais(), 'cod_estado' => $usuariovo->getCodEstado(), 'cod_cidade' => $usuariovo->getCodCidade(), 'cidade' => $usuariovo->getCidade(), 'telefone' => $usuariovo->getTelefone(), 'celular' => $usuariovo->getCelular(), 'email' => $usuariovo->getEmail(), 'mostrar_email' => $usuariovo->getMostrarEmail(), 'site' => $usuariovo->getSite()), "cod_usuario='".$usuariovo->getCodUsuario()."'");

		// cadastra sites relacionados
		$this->banco->sql_delete('Links', "cod_usuario='".$usuariovo->getCodUsuario()."'");
		foreach($usuariovo->getSitesRelacionados() as $key => $value) {
			$this->banco->sql_insert('Links', array('cod_usuario' => $usuariovo->getCodUsuario(), 'site' => utf8_decode($value['titulo']), 'url' => $value['endereco']));
		}

		// cadastra comunicadores
		$this->banco->sql_delete('Comunicadores_Usuarios', "cod_usuario='".$usuariovo->getCodUsuario()."'");
		foreach($usuariovo->getContatos() as $key => $value) {
			$this->banco->sql_insert('Comunicadores_Usuarios', array('cod_usuario' => $usuariovo->getCodUsuario(), 'cod_comunicador' => $value['tipo'], 'nome_usuario' => $value['nome_usuario']));
		}

		/*
		$i = 0;
		$url = $usuariovo->getUrl();
		if ($url) {
			do {
				if ($i)
					$url = $url.$i;
					$sql = "UPDATE Urls SET titulo='".$url."' WHERE cod_item='".$usuariovo->getCodUsuario()."' AND tipo='".$usuariovo->getCodTipo()."'";
					$tenta = $this->banco->executaQuery($sql);
					$i++;
			}
			while (!$tenta);
			$this->banco->sql_update('Usuarios', array('url' => $url), "cod_usuario='".$usuariovo->getCodUsuario()."'");
		}
		*/

		switch ($usuariovo->getCodTipo()) {
			case 1: $pretitulo = 'colaboradores'; break;
			case 2: $pretitulo = 'autores'; break;
			case 3: $pretitulo = 'grupos'; break;
		}

		$pasta_titulo = '';
		$sql = "SELECT titulo from Urls where cod_item='".$usuariovo->getCodUsuario()."' AND tipo='".$usuariovo->getCodTipo()."' and cod_sistema = '".ConfigVO::getCodSistema()."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = mysql_fetch_array($sql_result);
		$titulo_atual = $sql_row[0];
		$titulo_partes = explode('/', $titulo_atual);
		if ($titulo_partes[1])
			$pasta_titulo = $pretitulo.'/';

		if ($usuariovo->getUrl()) {
			$url = $usuariovo->getUrl();
			
			$url = explode('/', $url);
			if (count($url))
				$url = end($url);
			
			$sql = "update Urls set titulo='".$pasta_titulo.$url."' where cod_item='".$usuariovo->getCodUsuario()."' and tipo='".$usuariovo->getCodTipo()."' and cod_sistema = '".ConfigVO::getCodSistema()."'";
			$tenta = $this->banco->executaQuery($sql);
			$this->banco->sql_update('Usuarios', array('url' => $pasta_titulo.$url), "cod_usuario='".$usuariovo->getCodUsuario()."'");
		}

		return $usuariovo->getCodUsuario();
	}

	public function getUsuarioVO(&$codusuario, &$usuariovo) {
		$sql = "SELECT * FROM Usuarios WHERE cod_usuario='".$codusuario."' AND cod_usuario!='0' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();

		$usuariovo->setCodUsuario($sql_row->cod_usuario);
		$usuariovo->setCodTipo($sql_row->cod_tipo);
		$usuariovo->setNome($sql_row->nome);
		$usuariovo->setDescricao($sql_row->descricao);
		$usuariovo->setEndereco($sql_row->endereco);
		$usuariovo->setComplemento($sql_row->complemento);
		$usuariovo->setBairro($sql_row->bairro);
		$usuariovo->setCEP($sql_row->cep);
		$usuariovo->setCodPais($sql_row->cod_pais);
		$usuariovo->setCodEstado($sql_row->cod_estado);
		$usuariovo->setCodCidade($sql_row->cod_cidade);
		$usuariovo->setCidade($sql_row->cidade);
		$usuariovo->setTelefone($sql_row->telefone);
		$usuariovo->setCelular($sql_row->celular);
		$usuariovo->setEmail($sql_row->email);
		$usuariovo->setMostrarEmail($sql_row->mostrar_email);
		$usuariovo->setSite($sql_row->site);
		$usuariovo->setImagem($sql_row->imagem);
		$usuariovo->setLogin($sql_row->login);
		$usuariovo->setDisponivel($sql_row->disponivel);
		$usuariovo->setDataCadastro($sql_row->datacadastro);

		$arraySites = $arrayContatos = array();
		$sql = "SELECT * FROM Links WHERE cod_usuario='".$codusuario."' AND cod_usuario!='0'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray())
			$arraySites[] = $sql_row;

		$usuariovo->setSitesRelacionados($arraySites);

		$sql = "SELECT * FROM Comunicadores_Usuarios WHERE cod_usuario='".$codusuario."' AND cod_usuario!='0'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray())
			$arrayContatos[] = $sql_row;

		$usuariovo->setContatos($arrayContatos);
	}

	public function existeEmail($email, $codtipo = 1, $codusuario = 0) {
		$sql = "select cod_usuario from Usuarios where email = '".$email."' and cod_tipo='".$codtipo."' and cod_sistema='".ConfigVO::getCodSistema()."' and disponivel='1'";
		if ($codusuario)
			$sql .= " and cod_usuario != '".$codusuario."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();
		return (bool)$sql_row["cod_usuario"];
	}

	public function existeCpf($cpf, $codusuario = 0) {
		$sql = "select t1.cod_usuario from Autores as t1 inner join Usuarios as t2 on (t1.cod_usuario=t2.cod_usuario) where t1.cpf = '".$cpf."' and t2.cod_sistema='".ConfigVO::getCodSistema()." and t2.disponivel='1''";
		if ($codusuario)
			$sql .= " and t1.cod_usuario != '".$codusuario."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();
		return (bool)$sql_row["cod_usuario"];
	}

	public function existeFinalEndereco($finalendereco, $codusuario = 0, $tipo = 0) {
		//$sql = "select cod_usuario from Usuarios where url = '".$finalendereco."' and disponivel = 1 and cod_tipo='".$codtipo."'";
		$titulo_pasta = '';
		if ($codusuario) {
			$sql = "select titulo from Urls where cod_item = '".$codusuario."' and tipo = '".$tipo."' and cod_sistema='".ConfigVO::getCodSistema()."';";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray();
			$titulo_partes = explode('/', $sql_row[0]);
			if ($titulo_partes[1]) {
				switch ($tipo) {
					case 1: $titulo_pasta = 'colaboradores/'; break;
					case 2: $titulo_pasta = 'autores/'; break;
					case 3: $titulo_pasta = 'grupos/'; break;
				}
			}
		}

		$sql = "select cod_item from Urls where titulo = '".$titulo_pasta.$finalendereco."' and cod_sistema='".ConfigVO::getCodSistema()."'";
		if ($codusuario)
			$sql .= " and cod_item != '".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();
		return (bool)$sql_row["cod_item"];
	}

	public function getComunicadoresUsuario($codusuario) {
		$sql = "SELECT t1.*, t2.* FROM Comunicadores AS t1 INNER JOIN Comunicadores_Usuarios AS t2 ON (t1.cod_comunicador=t2.cod_comunicador) WHERE t2.cod_usuario='".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
		$arrayContatos = array();
		while ($sql_row = $this->banco->fetchArray())
			$arrayContatos[] = $sql_row;
		return $arrayContatos;
	}


	public function getTipoUsuarioHome($codusuario) {

	$sql = "Select cod_usuario from Autores where cod_usuario='".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);

		while ($sql_row = $this->banco->fetchArray())
			return $sql_row['cod_usuario'];//$arrayContatos[] = $sql_row;
		//return $arrayContatos;

	}

	public function getSitesUsuario($codusuario) {
		$sql = "SELECT * FROM Links WHERE cod_usuario='".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
		$arraySite = array();
		while ($sql_row = $this->banco->fetchArray())
			$arraySite[] = $sql_row;
		return $arraySite;
	}

	public function verificarSenha($codusuario) {
		$sql = "SELECT senha FROM Usuarios WHERE cod_usuario='".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();
		return $sql_row["senha"];
	}

	public function atualizaSenha(&$usuariovo) {
		$sql = "UPDATE Usuarios SET senha = '".$usuariovo->getSenha()."' WHERE cod_usuario='".$usuariovo->getCodUsuario()."'";
		$this->banco->executaQuery($sql);
	}

	public function atualizarFoto($nomearquivo, $codusuario) {
		$sql = "UPDATE Usuarios SET imagem = '".$nomearquivo."' WHERE cod_usuario = '".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function excluirImagem($codusuario) {
		$sql = "UPDATE Usuarios SET imagem = '' WHERE cod_usuario = '".$codusuario."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getEstatisticasUsuario($codtipo) {
		if ($codtipo == 11) {
			$sql = "SELECT cod_usuario FROM Usuarios WHERE disponivel='1' AND cod_tipo='2' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		} elseif ($codtipo == 10) {
			$sql = "SELECT cod_usuario FROM Usuarios WHERE disponivel='1' AND cod_tipo='1' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		/*} elseif ($_SESSION['logado_como'] == 2) {
			$sql = "SELECT cod_conteudo FROM Conteudo WHERE cod_formato='".$codtipo."' AND excluido='0' AND cod_colaborador='".$_SESSION['logado_cod']."' AND publicado!='2'";*/
		} elseif ($_SESSION['logado_dados']['nivel'] >= 5 && $_SESSION['logado_dados']['nivel'] < 7) {
			$sql = "SELECT cod_conteudo FROM Conteudo WHERE cod_formato='".$codtipo."' AND excluido='0' AND cod_colaborador='".$_SESSION['logado_dados']['cod_colaborador']."' AND publicado!='2' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		//} elseif ($_SESSION['logado_como'] == 1) {
		} elseif ($_SESSION['logado_dados']['nivel'] == 2) {
			$sql = "SELECT t1.cod_conteudo FROM Conteudo AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_formato='".$codtipo."' AND t1.excluido='0' AND t1.publicado!=2 AND t2.cod_usuario='".$_SESSION['logado_cod']."' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";
		} else {
			$sql = "SELECT cod_conteudo FROM Conteudo WHERE cod_formato='".$codtipo."' AND excluido='0' AND publicado!='2' AND cod_sistema='".ConfigVO::getCodSistema()."'";
		}
		//echo $sql;
		$query = $this->banco->executaQuery($sql);
		return $this->banco->numRows($query);
	}

	public function getUsuarioDados($codusuario) {
		$sql = "SELECT t1.*, t3.sigla, t3.cod_estado, t4.cod_cidade, t4.cidade, t5.titulo AS url FROM Usuarios AS t1 LEFT JOIN Estados AS t3 ON (t1.cod_estado=t3.cod_estado) LEFT JOIN Cidades AS t4 ON (t1.cod_cidade=t4.cod_cidade) LEFT JOIN Urls AS t5 ON (t1.cod_usuario=t5.cod_item) WHERE t1.cod_usuario='".$codusuario."' AND t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t5.tipo=t1.cod_tipo;";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		$row['num_conteudo'] = (int)(!$this->checaAutorWiki($codusuario) ? $this->getNumeroConteudoAutorSemFicha($codusuario, 0) : $this->getNumeroConteudoUsuario($codusuario, 0, 2));
		$row['autor_num_conteudo'] = $this->getTotalConteudoAutor($codusuario);
		return $row;
	}
	
	public function getTotalConteudoAutor($codusuario) {
		$sql = "SELECT COUNT(t1.cod_conteudo) AS total FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t2.excluido='0' AND t2.publicado='1' AND t2.situacao='1' AND t2.cod_formato IN (1,2,3,4) AND t2.cod_sistema='".ConfigVO::getCodSistema()."' AND t1.cod_usuario='".$codusuario."' GROUP BY t1.cod_usuario";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return (int)$sql_row[0];
	}

	public function getCidadeUsuario($codusuario) {
		$sql = "SELECT cidade FROM Usuarios WHERE cod_usuario='".$codusuario."';";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		return $row['cidade'];
	}

	public function getPaisUsuario($codusuario) {
		$sql = "SELECT t1.pais FROM Paises AS t1 INNER JOIN Usuarios t2 ON (t1.cod_pais=t2.cod_pais) WHERE t2.cod_usuario='".$codusuario."';";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		return $row['pais'];
	}

	// ===================================================================
	// funções do portal
	// ===================================================================

	public function getUsuarioMaisRecentes($tipo, $inicial, $mostrar) {

		switch ($tipo) {
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;colaboradores=1&amp;ordem=recentes"; break;
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;autores=1&amp;ordem=recentes"; break;
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;grupos=1&amp;ordem=recentes"; break;
		}

		$array['link'] = $paginalink;

		switch ($tipo) {
			case 1: $visao = 'v_colaboradores'; break;
			case 2: $visao = 'v_autores'; break;
			case 3: $visao = 'v_grupos'; break;
		}

		$tipoarray = array(1 => 'Colaborador', 2 => 'Autor', 3 => 'Grupo');
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		$query = $this->banco->executaQuery("SELECT * FROM $visao WHERE cod_tipo='".$tipo."' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY cod_usuario DESC LIMIT $inicial,$mostrar");
		//$query = $this->banco->executaQuery("SELECT t1.* FROM $visao AS t1 LEFT JOIN Usuarios_Niveis AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t1.cod_tipo='".$tipo."' /*AND t2.nivel > 1*/ AND t1.cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY t1.cod_usuario DESC LIMIT $inicial,$mostrar");

		while ($row = $this->banco->fetchArray($query)) {
			$array[] = array(
				'cod' 			=> $row['cod_usuario'],
				'nome' 			=> $row['nome'],
				'imagem' 		=> $row['imagem'],
				'descricao'		=> strip_tags($row['descricao']),
				'estado' 		=> $row['sigla'],
				'cod_estado' 	=> $row['cod_estado'],
				'cod_cidade'	=> $row['cod_cidade'],
				'cidade' 		=> $row['cidade'],
				'tipo' 			=> $tipoarray[$row['cod_tipo']],
				'url' 			=> $row['url'],
				'num_conteudo'	=> (int)(!$this->checaAutorWiki($row['cod_usuario']) ? ($tipo == 1 ? $this->getNumeroConteudoUsuario($row['cod_usuario'], 0, $tipo) : $this->getNumeroConteudoAutorSemFicha($row['cod_usuario'], 0)) : $this->getNumeroConteudoUsuario($row['cod_usuario'], 0, $tipo))
			);
		}
		return $array;
	}
	
	public function getAtoresMaisRecentes($quantidade) {
		$sql = "SELECT DISTINCT(t1.cod_usuario), COUNT(t1.cod_conteudo) AS total FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t2.excluido='0' AND t2.publicado='1' AND t2.situacao='1' AND t2.cod_formato IN (1,2,3,4) AND t2.cod_sistema='".ConfigVO::getCodSistema()."' GROUP BY t1.cod_usuario ORDER BY cod_usuario DESC LIMIT " .  $quantidade;
		
		$query = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($query)) {
			$dados = $this->getUsuarioDados($row['cod_usuario']);
			$array[] = array(
				'cod' 			=> $dados['cod_usuario'],
				'nome' 			=> $dados['nome'],
				'imagem' 		=> $dados['imagem'],
				'descricao'		=> strip_tags($dados['descricao']),
				'estado' 		=> $dados['sigla'],
				'cod_cidade'	=> $dados['cod_cidade'],
				'cidade' 		=> $dados['cidade'],
				'cod_estado' 	=> $dados['cod_estado'],
				'url' 			=> $dados['url'],
				'num_conteudo'	=> (int)$row['total']
			);
		}
		return $array;
	}

	public function getAtoresMaisAtivos($quantidade) {
		$sql = "SELECT DISTINCT(t1.cod_usuario), COUNT(t1.cod_conteudo) AS total FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t2.excluido='0' AND t2.publicado='1' AND t2.situacao='1' AND t2.cod_formato IN (1,2,3,4) AND t2.cod_sistema='".ConfigVO::getCodSistema()."' GROUP BY t1.cod_usuario ORDER BY total DESC LIMIT " .  $quantidade;
		
		$query = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($query)) {
			$dados = $this->getUsuarioDados($row['cod_usuario']);
			$array[] = array(
				'cod' 			=> $dados['cod_usuario'],
				'nome' 			=> $dados['nome'],
				'imagem' 		=> $dados['imagem'],
				'descricao'		=> strip_tags($dados['descricao']),
				'estado' 		=> $dados['sigla'],
				'cod_cidade'	=> $dados['cod_cidade'],
				'cidade' 		=> $dados['cidade'],
				'cod_estado' 	=> $dados['cod_estado'],
				'url' 			=> $dados['url'],
				'num_conteudo'	=> (int)$row['total']
			);
		}
		return $array;
	}

	public function getUsuarioMaisAtivos($tipo, $inicial, $mostrar) {

		switch ($tipo) {
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;colaboradores=1&amp;ordem=ativo"; break;
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;autores=1&amp;ordem=ativo"; break;
			case 1: $paginalink = "busca_resultado.php?buscar=1&amp;grupos=1&amp;ordem=ativo"; break;
		}

		$array['link'] = $paginalink;

		switch ($tipo) {
			case 1: $visao = 'v_colaboradores'; break;
			case 2: $visao = 'v_autores'; break;
			case 3: $visao = 'v_grupos'; break;
		}

		$tipoarray = array(1 => 'Colaborador', 2 => 'Autor', 3 => 'Grupo');
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		$query = $this->banco->executaQuery("SELECT * FROM $visao WHERE cod_tipo='".$tipo."' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY geral DESC LIMIT $inicial,$mostrar");
		//$query = $this->banco->executaQuery("SELECT t1.* FROM $visao AS t1 LEFT JOIN Usuarios_Niveis AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t1.cod_tipo='".$tipo."' /*AND t2.nivel > 1*/ AND t1.cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY t1.geral DESC LIMIT $inicial,$mostrar");

		while ($row = $this->banco->fetchArray($query)) {
			$array[] = array(
				'cod' 			=> $row['cod_usuario'],
				'nome' 			=> $row['nome'],
				'imagem' 		=> $row['imagem'],
				'descricao'		=> strip_tags($row['descricao']),
				'estado' 		=> $row['sigla'],
				'cod_cidade'	=> $row['cod_cidade'],
				'cidade' 		=> $row['cidade'],
				'cod_estado' 	=> $row['cod_estado'],
				'tipo' 			=> $tipoarray[$row['cod_tipo']],
				'url' 			=> $row['url'],
				'num_conteudo'	=> (int)(!$this->checaAutorWiki($row['cod_usuario']) ? ($tipo == 1 ? $this->getNumeroConteudoUsuario($row['cod_usuario'], 0, $tipo) : $this->getNumeroConteudoAutorSemFicha($row['cod_usuario'], 0)) : $this->getNumeroConteudoUsuario($row['cod_usuario'], 0, $tipo))
			);
		}
		return $array;
	}

	public function getNumeroConteudoUsuario($codusuario, $codformato, $tipo) {
		if ($tipo == 1) {
			if ($codformato)
				$where = " AND cod_formato='$codformato'";
        	$sql = "SELECT cod_conteudo FROM Conteudo WHERE cod_colaborador='$codusuario' $where AND excluido='0' AND situacao='1' AND publicado='1' AND cod_sistema='".ConfigVO::getCodSistema()."'";
        } elseif ($tipo == 2) {
			if ($codformato)
    			$where = "AND t2.cod_formato='$codformato'";
        	$sql = "SELECT DISTINCT(t1.cod_conteudo) FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_usuario='$codusuario' $where AND t2.excluido='0' AND t2.situacao='1' AND t2.publicado='1'";
		} elseif ($tipo == 3) {
			if ($codformato)
    			$where = "AND t2.cod_formato='$codformato'";
        	$sql = "SELECT DISTINCT(t1.cod_conteudo) FROM Conteudo_Grupos AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_grupo='$codusuario' $where AND t2.excluido='0' AND t2.situacao='1' AND t2.publicado='1'";
		}
        $query = $this->banco->executaQuery($sql);
        return (int)$this->banco->numRows($query);
	}

	public function getNumeroConteudoAutorSemFicha($codusuario, $codformato) {
		if ($codformato)
    		$where = "AND t2.cod_formato='".$codformato."'";

		$sql = "SELECT t1.cod_conteudo FROM Conteudo_Autores AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_usuario='".$codusuario."' ".$where." AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=t1.cod_conteudo) AND t2.excluido='0' AND t2.situacao='1' AND t2.publicado='1'";
        $query = $this->banco->executaQuery($sql);
        return (int)$this->banco->numRows($query);
	}

	public function getAutoresRelacionadosColaborador($codusuario, $limit=2) {
        $sql = "SELECT DISTINCT(t2.cod_usuario) FROM Conteudo AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t2.cod_usuario=t3.cod_item) WHERE t1.cod_colaborador='$codusuario' AND t3.tipo='2' ORDER BY RAND() LIMIT $limit;";
        $sql_result = $this->banco->executaQuery($sql);
		$array = array();
        while ($sql_row = $this->banco->fetchArray($sql_result)) {
		    $array[] = $this->getUsuarioDados($sql_row['cod_usuario']);
		}
		return $array;
    }

    public function getColaboradorRelacionadoAutor($codusuario, $limit=1) {
        $sql = "SELECT DISTINCT(t1.cod_colaborador) FROM Conteudo AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_colaborador=t3.cod_item) WHERE t2.cod_usuario='$codusuario' AND t3.tipo='1' ORDER BY RAND() LIMIT $limit;";
        $sql_result = $this->banco->executaQuery($sql);
		$array = array();
        while ($sql_row = $this->banco->fetchArray($sql_result)) {
		    $array[] = $this->getUsuarioDados($sql_row['cod_colaborador']);
		}
		return $array;
    }

    public function getGrupoRelacionadoAutor($codusuario, $limit=1) {
        $sql = "SELECT t1.cod_usuario, t1.nome, t1.imagem, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Grupos_Autores AS t2 ON (t1.cod_usuario=t2.cod_grupo) INNER JOIN Urls AS t3 ON (t2.cod_grupo=t3.cod_item) WHERE t2.cod_usuario='$codusuario' AND t3.tipo='3' LIMIT $limit;";
        $array = array();
        $sql_result = $this->banco->executaQuery($sql);
		while($row = $this->banco->fetchArray($sql_result))
			$array[] = $row;
		return $array;
    }

    public function getColaboradoresRelacionadoGrupo($codusuario, $limit=1) {
    	$sql = "SELECT DISTINCT(t1.cod_usuario), t1.nome, t1.imagem, t2.titulo AS url FROM Usuarios AS t1 INNER JOIN Urls AS t2 ON (t1.cod_usuario=t2.cod_item) LEFT JOIN Conteudo AS t3 ON (t1.cod_usuario=t3.cod_colaborador) LEFT JOIN Conteudo_Grupos AS t4 ON (t3.cod_conteudo=t4.cod_conteudo) WHERE t4.cod_grupo='".$codusuario."' AND t2.tipo='1' LIMIT $limit";
    	$array = array();
		$sql_result = $this->banco->executaQuery($sql);
		while($row = $this->banco->fetchArray($sql_result))
			$array[] = $row;
		return $array;
    }

    public function getAutoresRelacionadoGrupo($codusuario, $limit=1) {
        $sql = "SELECT t1.cod_usuario, t1.nome, t1.imagem, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Grupos_Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_grupo='$codusuario' AND t3.tipo='2' LIMIT 2;";
        $array = array();
		$sql_result = $this->banco->executaQuery($sql);
		while($row = $this->banco->fetchArray($sql_result))
			$array[] = $row;
		return $array;
    }

    public function getBuscaDadosUsuarioNome(&$nome_usuario, $tipo=2) {
		$sql = "select t2.cod_usuario, t2.nome, t2.imagem, t3.titulo AS url, t4.sigla FROM Usuarios AS t2 LEFT JOIN Urls AS t3 ON (t2.cod_usuario=t3.cod_item) LEFT JOIN Estados AS t4 ON (t2.cod_estado=t4.cod_estado) WHERE t2.disponivel='1' AND t2.nome = '".trim($nome_usuario)."' AND t3.tipo='".$tipo."' AND t2.cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY t2.nome";
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($sql_result);
	}

	public function getCheckColabadoresAprovacao($colaboradores_lista) {
		$colaboradores = explode(';', $colaboradores_lista);
		$arrayColabCheck = array();

		foreach ($colaboradores as $nome) {
			if ($nome) {
				$usuario = $this->getBuscaDadosUsuarioNome($nome, 1);
				if ($usuario['cod_usuario'])
					$arrayColabCheck[$usuario['cod_usuario']] = array('cod_usuario' => $usuario['cod_usuario']);
			}
		}
		return $arrayColabCheck;
	}

	public function checaAutorWiki($cod) {
		$sql = "SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario='".$cod."' AND nivel='1'";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		return (bool)$row['cod_usuario'];
	}

	public function getUsuariosRandom($limit=10) {
        $sql = "SELECT t1.cod_usuario, t1.nome, t1.imagem, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t1.disponivel='1' AND t1.situacao = 3 AND t1.imagem!='' AND t3.titulo!='' AND t1.imagem!='' AND t1.cod_sistema='".ConfigVO::getCodSistema()."' AND (t3.tipo=1 OR t3.tipo=2) ORDER BY RAND() LIMIT ".$limit;
        $array = array();
		$sql_result = $this->banco->executaQuery($sql);
		while($row = $this->banco->fetchArray($sql_result))
			$array[$row['cod_usuario']] = $row;
		return $array;
    }

	public function getEmailsClipmail() {
		$array = array();
		$sql = "select cod_usuario, nome, email from Usuarios where cod_sistema = '".ConfigVO::getCodSistema()."' and disponivel = 1 and situacao = 3 and email != '' order by cod_usuario;";
		$sql_result = $this->banco->executaQuery($sql);
		while($row = $this->banco->fetchArray($sql_result))
			$array[] = $row;
		return $array;
	}

	public function inseriDadosEndereco($codusuario, $codtipo, $numero, $orgao) {
		$this->banco->sql_insert('Usuarios_Documentos', array('cod_usuario' => $codusuario, 'cod_tipo' => $codtipo, 'numero' => $numero, 'orgao' => $orgao));
	}

}
