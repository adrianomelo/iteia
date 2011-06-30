<?php
require_once("ConexaoDB.php");

class PlayListDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function cadastrarPlayList(&$playvo) {
		$this->banco->sql_insert('Home_Playlist', array('data_inicio' => $playvo->getDataInicio(), 'hora_inicio' => $playvo->getHoraInicio(), 'cod_usuario' => $playvo->getCodUsuario(), 'data_cadastro' => date('Y-m-d H:i:s'), 'cod_sistema' => ConfigVO::getCodSistema()));
		$codplaylist = $this->banco->insertId();
		
		$this->banco->sql_update('Home_Itens_Apresentacao', array('cod_playlist' => $codplaylist, 'disponivel' => 1), "cod_item IN (".implode(',', $playvo->getListaItens()).")");
		
		return $codplaylist;
	}
	
	public function atualizarPlayList(&$playvo) {
		$this->banco->sql_update('Home_Playlist', array('data_inicio' => $playvo->getDataInicio(), 'hora_inicio' => $playvo->getHoraInicio(), 'cod_usuario' => $playvo->getCodUsuario()), "cod_playlist='".$playvo->getCodPlayList()."'");
		
		$this->banco->sql_update('Home_Itens_Apresentacao', array('cod_playlist' => $playvo->getCodPlayList(), 'disponivel' => 1), "cod_item IN (".implode(',', $playvo->getListaItens()).")");
		
		return $playvo->getCodPlayList();
	}
	
	public function apagarPlayList($codplaylits) {
		$this->banco->sql_update('Home_Playlist', array('excluido' => 1), "cod_playlist='".$playvo->getCodPlayList()."'");
	}
	
	public function adicionarConteudoPlayList($cod, $codplay, $tempo) {
		$sql = "SELECT cod_item FROM Home_Itens_Apresentacao WHERE cod_conteudo='".$cod."' AND cod_playlist='".$codplay."' AND cod_usuario='0';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if (!$sql_row["cod_item"]) {
			$sql = "SELECT ordem, data_exibicao, tempo_exibicao FROM Home_Itens_Apresentacao WHERE cod_item IN (".implode(',', (array)$_SESSION['sessao_playlist_itens']).") ORDER BY ordem DESC LIMIT 1;";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			$max_ordem = (int)$sql_row[0];

			$data_inicio = $sql_row["data_exibicao"];
			if ($data_inicio == "0000-00-00 00:00:00")
				$data_inicio = date("Y-m-d H:i:s");

			$data = new DateTime($data_inicio);
			$data->modify("+".$sql_row["tempo_exibicao"]." minutes");
			$data_exibicao = $data->format("Y-m-d H:i:s");
			
			$sql = "SELECT titulo, descricao, imagem, cod_formato FROM Conteudo WHERE cod_conteudo='".$cod."'";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			
			if ($sql_row["cod_formato"] == 2) {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$cod."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				$sql_row['imagem'] = $sql_row2['imagem'];
			}

			$sql = "INSERT INTO Home_Itens_Apresentacao (cod_conteudo, cod_playlist, tempo_exibicao, ordem, data_exibicao, disponivel, titulo, descricao, imagem) VALUES ('".$cod."', '".$codplay."', '".$tempo."', '".($max_ordem + 10)."', '".$data_exibicao."', 0, '".addslashes(substr($sql_row['titulo'], 0, 100))."', '".strip_tags(addslashes(substr($sql_row['descricao'], 0, 200)))."', '".$sql_row['imagem']."');";
			$sql_result = $this->banco->executaQuery($sql);
			
			return $this->banco->insertId();
		}
	}
	
	public function atualizaOrdenacao($cod, $move) {
		$move = Util::iif($move == 3, '-15', '15');
		$sql = "update Home_Itens_Apresentacao set ordem = ordem + $move where cod_item = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function organizacaoFinal() {
		$sql = "SELECT ordem, cod_item FROM Home_Itens_Apresentacao WHERE cod_item IN (".implode(',', (array)$_SESSION["sessao_playlist_itens"]).") ORDER BY ordem ASC";
		$resultado = $this->banco->executaQuery($sql);
			
		$i = 10;
		while ($row = $this->banco->fetchArray($resultado)) {
			$sql = "UPDATE Home_Itens_Apresentacao SET ordem = '$i' WHERE cod_item = '".$row['cod_item']."'";
			$this->banco->executaQuery($sql);
			$i += 10;
		}
	}
	
	public function getListaConteudoSelecionados($codplaylist, $todos = true) {
		$lista_conteudo = array();
		$sql = "SELECT HI.cod_item, HI.cod_conteudo, HI.imagem, CON.cod_formato, HI.titulo, HI.descricao, CON.datahora, HI.tempo_exibicao, HI.data_exibicao, HI.ordem FROM Home_Itens_Apresentacao HI, Conteudo CON WHERE"; 
		
		$sql .= " HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo";
		
		$sql .= " AND HI.cod_playlist='".$codplaylist."' AND HI.cod_item IN (".implode(',', (array)$_SESSION['sessao_playlist_itens']).")";
			
		$sql .= " ORDER BY HI.ordem DESC;";

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$url_editar = "home_item_playlist_edicao.php?cod=".$sql_row['cod_item']."&pre=1";

			$inicio_img = substr($sql_row["imagem"], 0, 10);

			if ($sql_row["imagem"])
				$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row["imagem"]."&amp;tipo=5&amp;s=1&amp;rand=".md5(microtime())."\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";

			if ($sql_row["cod_formato"] == 2 && $sql_row["imagem"] && $inicio_img == 'imggaleria') {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row["cod_conteudo"]."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				if ($sql_row2["imagem"])
					$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row2["imagem"]."&amp;tipo=2&amp;s=1&amp;rand=".md5(microtime())."\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";
			}
			
			if (!$sql_row['imagem'])
				$sql_row['imagem'] = "<img src=\"img/imagens-padrao/mini-texto.jpg\" width=\"50\" height=\"50\" />";

			$sql_row['url_editar'] = $url_editar;
			$lista_conteudo[$sql_row['cod_item']] = $sql_row;
		}
		return $lista_conteudo;
	}
	
	public function limpaItens() {
		$sql = "DELETE FROM Home_Itens_Apresentacao WHERE disponivel='0';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql = "DELETE FROM Home_Itens_Apresentacao WHERE cod_playlist='0' AND cod_usuario='0';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function mudarTempoItemPlayList($coditem, $tempo) {
		$sql = "UPDATE Home_Itens_Apresentacao SET tempo_exibicao = '".$tempo."' WHERE cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		$this->atualizaDataExibicaoSeguintes($coditem, $tempo);
	}

	private function atualizaDataExibicaoSeguintes($coditem, $tempo) {
		$sql = "SELECT ordem, data_exibicao from Home_Itens_Apresentacao WHERE cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$data_anterior = $sql_row["data_exibicao"];

		$sql = "SELECT cod_item, data_exibicao, tempo_exibicao FROM Home_Itens_Apresentacao WHERE ordem > '".$sql_row["ordem"]."' AND cod_item IN (".implode(',', (array)$_SESSION['sessao_playlist_itens']).") ORDER BY ordem;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$data = new DateTime($data_anterior);
			$data->modify(sprintf("%+d minutes", (int)$tempo));
			$novadata = $data->format("Y-m-d H:i:s");
			$tempo = $sql_row["tempo_exibicao"];
			$data_anterior = $novadata;

			$sql = "UPDATE Home_Itens_Apresentacao SET data_exibicao = '".$novadata."' WHERE cod_item = '".$sql_row["cod_item"]."';";
			$sql_result2 = $this->banco->executaQuery($sql);
		}
	}
	
	public function removeConteudoSelecao($lista_selecionadas) {
		$sql = "DELETE FROM Home_Itens_Apresentacao WHERE cod_item IN ('".implode("', '", (array)$lista_selecionadas)."');";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function atualizaDatasExibicao() {
		$novadata = "";
		$tempo = "";
		$sql = "SELECT cod_item, tempo_exibicao, data_exibicao FROM Home_Itens_Apresentacao WHERE cod_item IN (".implode(',', (array)$_SESSION['sessao_playlist_itens']).") ORDER BY ordem;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			if (!$novadata) {
				$novadata = date("Y-m-d H:i:s");
				$tempo = $sql_row["tempo_exibicao"];
			}
			else {
				$data = new DateTime($novadata);
				$data->modify("+".$tempo." minutes");
				$novadata = $data->format("Y-m-d H:i:s");
			}
			$tempo = $sql_row["tempo_exibicao"];
			$sql = "UPDATE Home_Itens_Apresentacao SET data_exibicao = '".$novadata."' WHERE cod_item = '".$sql_row["cod_item"]."';";
			$sql_result2 = $this->banco->executaQuery($sql);
		}
	}
	
	public function getTempoTotalPlayList($codplay) {
		$tempo = '';
		$sql = "SELECT tempo_exibicao FROM Home_Itens_Apresentacao WHERE cod_item IN (".implode(',', (array)$_SESSION['sessao_playlist_itens']).") ORDER BY ordem;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$total_tempo += ($sql_row['tempo_exibicao'] * 60);
		}
		return Util::converteSegundos($total_tempo);
	}
	
	public function getPlayListVO($codplaylist) {
		$sql = "SELECT * FROM Home_Playlist WHERE cod_playlist='".$codplaylist."'";
		
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();

		$playvo = new PlayListVO;
		$playvo->setCodPlayList($sql_row["cod_playlist"]);
		$playvo->setCodUsuario($sql_row["cod_usuario"]);
		$playvo->setDataInicio($sql_row["data_inicio"]);
		$playvo->setHoraInicio($sql_row["hora_inicio"]);
		
		$arrayItens = array();
		
		$sql = "SELECT * FROM Home_Itens_Apresentacao WHERE cod_playlist='".$codplaylist."'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$arrayItens[$sql_row['cod_item']] = $sql_row;
		
		$playvo->setListaItens($arrayItens);
		
		return $playvo;
	}
	
	public function getDadosItem($coditem) {
		$sql = "SELECT cod_item, cod_conteudo FROM Home_Itens_Apresentacao WHERE cod_item='".$coditem."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row2 = $this->banco->fetchArray($sql_result);
		
		$sql = "SELECT cod_conteudo, titulo, descricao, imagem, cod_formato FROM Conteudo WHERE cod_conteudo='".$sql_row2['cod_conteudo']."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		
		$sql_row['cod_item'] = $sql_row2['cod_item'];
			
		if ($sql_row["cod_formato"] == 2) {
			$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row['cod_conteudo']."' and A.cod_imagem_capa = I.cod_imagem";
			$sql_result3 = $this->banco->executaQuery($sql);
			$sql_row3 = $this->banco->fetchArray($sql_result3);
			$sql_row['imagem'] = $sql_row3['imagem'];
		}
		
		return $sql_row;
	}
	
	public function getListaPlayList($dados, $inicial, $mostrar, $futuras) {
		$array = array();
		extract($dados);

		$array['link'] = "home.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;de=$de&amp;ate=$ate";
		$where = "WHERE t1.excluido='0' AND t1.cod_sistema = '".ConfigVO::getCodSistema()."'";

		if ($buscar) {
			if ($palavrachave && $palavrachave != 'Buscar') {
				//switch($buscarpor) {
				//	case "titulo":
						$where .= " AND t1.cod_playlist IN (SELECT cod_playlist FROM Home_Itens_Apresentacao WHERE titulo LIKE '%$palavrachave%' AND t1.cod_playlist=cod_playlist)";
				//	break;
				//}
			}
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
					$where .= " AND (t1.data_inicio >= '$datainicial' AND t1.data_inicio <= '$datafinal')";
			}
		}
		
		// se for autor comum
		// só lista as minhas listas
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			$where .= " AND t1.cod_usuario = '".$_SESSION['logado_dados']['cod']."'";
		}
		// se for autor com nivel colaborador e não for admin (admin exibe todos)
		else if ($_SESSION['logado_dados']['cod_colaborador'] && $_SESSION['logado_dados']['nivel'] < 7) {
			$where .= " AND (t1.cod_usuario = '".$_SESSION['logado_dados']['cod']."' OR t1.cod_usuario = '".$_SESSION['logado_dados']['cod_colaborador']."')";
		}
		// se for responsavel por algum grupo
		else if (count($_SESSION['logado_dados']['grupo_responsavel']) && $_SESSION['logado_dados']['nivel'] < 7) {
			$where .= " AND t1.cod_usuario IN (".implode(", ", array_keys($_SESSION['logado_dados']['grupo_responsavel'])).")";
		}
		
		// sempre mostrar as futuras datas
		if ($futuras)
			$where .= " AND CONCAT(t1.data_inicio, t1.hora_inicio) > '".date('Y-m-dH:i').":00'";
		else
			$where .= " AND CONCAT(t1.data_inicio, t1.hora_inicio) <= '".date('Y-m-dH:i').":00'";

		$sql = "SELECT t1.cod_playlist, t1.data_inicio, t1.hora_inicio, t1.cod_usuario, t2.cod_tipo, t2.nome FROM Home_Playlist AS t1 LEFT JOIN Usuarios AS t2 ON (t1.cod_usuario=t2.cod_usuario) $where";

		$array['total'] = $this->banco->numRows($this->banco->executaQuery("$sql"));

		//echo "$sql ORDER BY t1.data_inicio DESC, t1.hora_inicio ASC LIMIT $inicial,$mostrar";

		if ($mostrar)
			$limite = "LIMIT $inicial,$mostrar";

		$query = $this->banco->executaQuery("$sql ORDER BY t1.data_inicio DESC, t1.hora_inicio ASC $limite");
		while ($row = $this->banco->fetchArray($query)) {
			
			$total_itens = 0;
			$total_tempo = 0;
			
			$query_itens = $this->banco->executaQuery("SELECT cod_item, tempo_exibicao FROM Home_Itens_Apresentacao WHERE cod_playlist='".$row['cod_playlist']."'");
			$total_itens = $this->banco->numRows($query_itens);
			
			while ($row2 = $this->banco->fetchArray($query_itens))
				$total_tempo += ($row2['tempo_exibicao'] * 60);
				
			switch ($row['cod_tipo']) {
				case 1: $tipo = 'Colaborador - '; break;
				case 2: $tipo = 'Autor - '; break;
				case 3: $tipo = 'Grupo - '; break;
				default: $tipo = 'ADM - Home'; break;
			}
			
			$array[] = array(
				'cod' 	=> $row['cod_playlist'],
				'data'	=> $row['data_inicio'],
				'hora' 	=> $row['hora_inicio'],
				'conta' => $tipo . $row['nome'],
				'total' => $total_itens,
				'duracao' => Util::converteSegundos($total_tempo),
			);
		}
		return $array;
	}
	
	public function executaAcoes($acao, $codplaylist='') {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					if (count($codplaylist))
						$this->banco->executaQuery("UPDATE Home_Playlist SET excluido='1' WHERE cod_playlist IN (".implode(',', $codplaylist).")");
				break;
			}
		}
	}
	
}