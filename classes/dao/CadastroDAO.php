<?php
include_once("ConexaoDB.php");

class CadastroDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

    public function getListaCadastros($dados, $inicial, $mostrar) {
		$array = array();
		extract($dados);

		// "grupos" => 3, "meus grupos" => 4, default => usuarios
		switch ($tipo) {
			case 1: $paginalink = "cadastro_colaboradores.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate"; break;
			case 2: $paginalink = "cadastro_autores.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate"; break;
			default: $paginalink = "cadastro.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate";
		}
		
		if ($mostrar)
			$paginalink .= "&amp;mostrar=".$mostrar;

		//$paginalink = "cadastro.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate";

		switch ($tipogrupo) {
			case 1: $paginalink = "grupo.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate"; break;
			case 2: $paginalink = "grupo_meus_grupos.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate"; break;
		}

		$array['link'] = $paginalink;
		$where = "WHERE t1.disponivel='1' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";

		// se for grupos
		if ($tipogrupo) {
			$from = " LEFT JOIN Grupos AS t3 ON (t1.cod_usuario = t3.cod_usuario)";
			// se for colaborador logado
			//if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6)) {
			//	$where .= " AND t3.cod_colaborador='".$_SESSION['logado_dados']['cod_colaborador']."'";
			// se for logado como autor
			// mostra os grupos que cadastrei e os que faço parte
			//} else
			if ($_SESSION['logado_dados']['nivel'] < 7) {
				$where .= " AND (t3.cod_autor='".$_SESSION['logado_cod']."' OR t1.cod_usuario IN (SELECT t4.cod_grupo FROM Grupos_Autores AS t4 WHERE t4.cod_usuario='".$_SESSION['logado_cod']."'))";
				//$where .= " AND (t4.cod_usuario='".$_SESSION['logado_cod']."'";
			}

			// classe grupo dao
			include_once('GrupoDAO.php');
			$grupodao = new GrupoDAO;
		}

		if ($tipo)
			$where .= " AND t1.cod_tipo='$tipo'";
		else
			$where .= " AND t1.cod_tipo!='3'";
			
		// buscar apenas colaboradores que tenha membros integrantes
		if ($integrantes) {
			$where .= " AND t1.cod_usuario IN (SELECT cod_colaborador FROM Colaboradores_Integrantes WHERE cod_colaborador=t1.cod_usuario)";
		}

		if ($buscar) {

			if ($palavrachave && $palavrachave != 'Buscar') {
				switch($buscarpor) {
					case "nome":
						$where .= " AND (t1.nome LIKE '%".utf8_decode($palavrachave)."%' OR t1.cod_usuario IN (SELECT cod_usuario FROM Autores WHERE nome_completo LIKE '%".utf8_decode($palavrachave)."%'))";
					break;
                    case "estado":
						$where .= " AND (t2.estado LIKE '$palavrachave%' OR t2.sigla LIKE '$palavrachave%')";
					break;
				}
			}
			
			switch($buscarpor) {
				case "wiki":
					$where .= " AND t1.cod_usuario IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario=t1.cod_usuario AND nivel='1')";
				break;
				case "autor":
					$where .= " AND t1.cod_usuario IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario=t1.cod_usuario AND nivel='2')";
				break;
				case "colaborador":
					$where .= " AND t1.cod_usuario NOT IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario=t1.cod_usuario)";
				break;
			}

			if ($situacao)
				$where .= " AND t1.situacao='$situacao'";

			if ($de) {
				$data1 = explode('/', $de);
				if (checkdate($data1[1], $data1[0], $data1[2])) {
					$datainicial = $data1[2].'-'.$data1[1].'-'.$data1[0];
				}
				if ($ate) {
					$data2 = explode('/', $ate);
                    if (checkdate($data2[1], $data2[0], $data2[2])) {
						$datafinal = $data2[2].'-'.$data2[1].'-'.$data2[0];
					}
				}
				if ($datainicial && $datafinal)
					$where .= "AND (t1.datacadastro >= '$datainicial 00:00:00' AND t1.datacadastro <= '$datafinal 23:59:00')";
			}
		}

		if ((($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6)) && !$tipogrupo)
			$where .= " AND t1.cod_tipo='2'";

		if ((int)$integrar_colaborador) {
			// não pode buscar usuarios admin e root
			//$where .= " AND t1.cod_usuario IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE t1.cod_usuario=cod_usuario AND nivel < 7)";
			// não pode listar usuarios (autores) que já são participantes ou responsaveis por um colaborador
			$where .= " AND (t1.cod_usuario NOT IN (SELECT cod_autor FROM Colaboradores_Integrantes))";
		}

		if ((int)$integrar_codgrupo) {
			// não pode buscar usuarios admin e root
			$where .= " AND t1.cod_usuario IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE t1.cod_usuario=cod_usuario AND nivel < 7)";
			// não pode listar usuarios (autores) que já são participantes ou responsaveis por um grupo
			//$where .= " AND (t1.cod_usuario NOT IN (SELECT cod_autor FROM Grupo_Integrantes))";
		}

		if ((int)$buscar_autor_ficha) {
			// buscar apenas autores do tipo wiki
			//if ($_SESSION['logado_dados']['nivel'] == 2)
			//	$where .= " AND t1.cod_usuario IN (SELECT GI.cod_autor FROM Grupo_Integrantes GI WHERE GI.cod_grupo in ('".implode("','", $_SESSION['logado_dados']['cod_grupo'])."'))";
		}

		$sql = "SELECT t1.cod_usuario, t1.cod_tipo, t1.nome, t1.situacao, t1.descricao, t1.imagem, t2.sigla, t8.cidade FROM Usuarios AS t1 LEFT JOIN Estados AS t2 ON (t1.cod_estado=t2.cod_estado) LEFT JOIN Cidades AS t8 ON (t1.cod_cidade=t8.cod_cidade) $from $where";

		//echo $sql;
		
		$tipoarray = array(1 => 'Colaborador', 2 => 'Autor', 3 => 'Grupo');
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		if ($inicial || $mostrar)
			$limitacao = "LIMIT $inicial,$mostrar";

		//echo "$sql ORDER BY t1.cod_usuario DESC $limitacao";

		$query = $this->banco->executaQuery("$sql ORDER BY t1.cod_usuario DESC $limitacao");
		//echo mysql_error();
		
		while ($row = $this->banco->fetchArray($query)) {
			switch($row['cod_tipo']) {
				case 1: $url_editar = "cadastro_colaborador_publicado.php?cod=".$row['cod_usuario']; break;
                case 2: $url_editar = "cadastro_autor_publicado.php?cod=".$row['cod_usuario']; break;
                case 3: $url_editar = "grupo_publicado.php?cod=".$row['cod_usuario']; break;
			}
			switch($row['cod_tipo']) {
				case 1: $url_editar_2 = "cadastro_colaborador.php?cod=".$row['cod_usuario']; break;
                case 2: $url_editar_2 = "cadastro_autor.php?cod=".$row['cod_usuario']; break;
                case 3: $url_editar_2 = "grupo_edicao.php?cod=".$row['cod_usuario']; break;
			}
			switch($row['situacao']) {
				case 1:
					$situacao = '<span class="pendente" title="Pendente">Pendente</span>';
					$url_editar = "index_exibir_autor_pendente.php?cod=".$row['cod_usuario'];
				break;
                case 2:	$situacao = '<span class="inativo" title="Inativo">Inativo</span>'; break;
                case 3:	$situacao = '<span class="ativo" title="Ativo">Ativo</span>'; break;
			}

			$array[] = array(
				'cod' 		=> $row['cod_usuario'],
				'nome' 		=> $row['nome'],
				'descricao'	=> strip_tags($row['descricao']),
				'situacao' 	=> $situacao,
				'estado' 	=> $row['sigla'],
				'cidade' 	=> $row['cidade'],
				'tipo' 		=> $tipoarray[$row['cod_tipo']],
				'url' 		=> $url_editar,
				'url_editar' => $url_editar_2,
				'tipo_autor' => $row['cod_tipo'],
				'autor_wiki' => $this->checaAutorWiki($row['cod_usuario'])
			);

			if ($tipogrupo) { // grupos
				$num_indice = count($array) - 3;
				$array[$num_indice]['num_autores'] = (int)count($grupodao->getAutoresGrupo($row['cod_usuario']));
				$array[$num_indice]['imagem'] = $row['imagem'];
			}
		}
		//print_r($array);
		return $array;
	}
	
	public function getListaCadastrosFicha($dados, $mostrar) {
		$array = array();
		extract($dados);

		$where = "WHERE t1.disponivel = 1 AND t1.situacao = 3 AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";
		$where .= " AND t1.cod_tipo = 2 ";
		$where .= " AND (t1.nome LIKE '%".utf8_decode($palavrachave)."%'/* OR t1.cod_usuario IN (SELECT cod_usuario FROM Autores WHERE nome_completo LIKE '%".utf8_decode($palavrachave)."%')*/)";
		//$where .= " AND t1.cod_usuario IN (SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario=t1.cod_usuario AND nivel='2')";

		$sql = "SELECT t1.cod_usuario, t1.nome, t1.descricao, t2.sigla, t8.cidade FROM Usuarios AS t1 LEFT JOIN Estados AS t2 ON (t1.cod_estado=t2.cod_estado) LEFT JOIN Cidades AS t8 ON (t1.cod_cidade=t8.cod_cidade) ". $where;
		$query = $this->banco->executaQuery($sql . " ORDER BY t1.cod_usuario DESC LIMIT 30");

		while ($row = $this->banco->fetchArray($query)) {
			$array[] = array(
				'cod' 		=> $row['cod_usuario'],
				'nome' 		=> $row['nome'],
				'estado' 	=> $row['sigla'],
				'cidade' 	=> $row['cidade'],
				'descricao'	=> strip_tags($row['descricao'])
			);
		}
		//print_r($array);
		return $array;
	}
	
	public function checaAutorWiki($cod) {
		$sql = "SELECT cod_usuario FROM Usuarios_Niveis WHERE cod_usuario='".$cod."' AND nivel='1'";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		return (bool)$row['cod_usuario'];
	}

	public function executaAcoes($acao, $codusuarios) {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					if (count($codusuarios)) {
						$this->banco->executaQuery("UPDATE Usuarios SET disponivel='0' WHERE cod_usuario IN (".implode(',', $codusuarios).")");
						$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_autor IN (".implode(',', $codusuarios).") AND cod_tipo='150'");
						$this->banco->executaQuery("DELETE FROM Urls WHERE cod_item IN (".implode(',', $codusuarios).") AND (tipo='2' OR tipo='3') AND cod_sistema = '".ConfigVO::getCodSistema()."'");
						}
				break;
                case 2: // ativar
					if (count($codusuarios)) {
						$this->banco->executaQuery("UPDATE Usuarios SET situacao='3' WHERE cod_usuario IN (".implode(',', $codusuarios).")");
						$this->banco->executaQuery("UPDATE Usuarios_Niveis SET nivel='2' WHERE cod_usuario IN (".implode(',', $codusuarios).")");
					}
				break;
                case 3: // desativar
					if (count($codusuarios))
						$this->banco->executaQuery("UPDATE Usuarios SET situacao='2' WHERE cod_usuario IN (".implode(',', $codusuarios).")");
				break;
			}
		}
	}

}