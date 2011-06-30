<?php
include_once("ConexaoDB.php");

class HomeDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function adicionarConteudoListaHome($cod, $pagina, $posicao, $tempo) {
		$sql = "select cod_item from Home_Itens_Apresentacao where cod_conteudo = '".$cod."' and pagina = '".$pagina."' and destaque_posicao = '".$posicao."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if (!$sql_row["cod_item"]) {
			$sql = "select ordem, data_exibicao, tempo_exibicao from Home_Itens_Apresentacao where pagina = '".$pagina."' and destaque_posicao = '".$posicao."' order by ordem desc limit 1;";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			$max_ordem = (int)$sql_row[0];

			$data_inicio = $sql_row["data_exibicao"];
			if ($data_inicio == "0000-00-00 00:00:00")
				$data_inicio = date("Y-m-d H:i:s");

			$data = new DateTime($data_inicio);
			$data->modify("+".$sql_row["tempo_exibicao"]." minutes");
			$data_exibicao = $data->format("Y-m-d H:i:s");
			
			$sql = "SELECT titulo, descricao FROM Conteudo WHERE cod_conteudo='".$cod."'";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);

			$sql = "insert into Home_Itens_Apresentacao (cod_conteudo, pagina, destaque_posicao, tempo_exibicao, ordem, data_exibicao, disponivel, titulo, descricao) values ('".$cod."', '".$pagina."', '".$posicao."', '".$tempo."', '".($max_ordem + 1)."', '".$data_exibicao."', 0, '".$sql_row['titulo']."', '".strip_tags($sql_row['descricao'])."');";
			//echo $sql;
			$sql_result = $this->banco->executaQuery($sql);
		}
	}

	public function getListaConteudoHomeSelecionados($paginahome, $destaque, $somente_coditem = false, $ordem = 1) {
		$lista_conteudo = array();
		$sql = "select ";
		if (!$somente_coditem)
			$sql .= "HI.cod_item, HI.cod_conteudo, CON.cod_formato, HI.titulo, HI.descricao, CON.imagem, CON.datahora, HI.tempo_exibicao, HI.data_exibicao, HI.ordem";
		else
			$sql .= "HI.cod_item";
		$sql .= " from Home_Itens_Apresentacao HI, Conteudo CON where HI.pagina = '".$paginahome."' and HI.destaque_posicao = '".$destaque."' and HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo";
		if ($ordem == 1)
			$sql .= " order by HI.ordem DESC;";
		else
			$sql .= " order by HI.data_exibicao DESC;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {

			//switch($sql_row['cod_formato']) {
			//	case 1: $url_editar = "conteudo_publicado_texto.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 2: $url_editar = "conteudo_publicado_imagem.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 3: $url_editar = "conteudo_publicado_audio.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 4: $url_editar = "conteudo_publicado_video.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 5: $url_editar = "noticia_publicado.php?cod=".$sql_row['cod_conteudo']; break;
			//	case 5: $url_editar = "agenda_publicado.php?cod=".$sql_row['cod_conteudo']; break;
			//}
			
			$url_editar = "home_item_edicao.php?cod=".$sql_row['cod_item']."&pre=1";

			if ($sql_row["imagem"])
				$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row["imagem"]."&amp;tipo=5&amp;s=1\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";

			if ($sql_row["cod_formato"] == 2) {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row["cod_conteudo"]."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				if ($sql_row2["imagem"])
					$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row2["imagem"]."&amp;tipo=2&amp;s=1\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";
			}

			$sql_row['url_editar'] = $url_editar;

			if (!$somente_coditem) {
				$lista_conteudo[] = $sql_row;
			}
			else
				$lista_conteudo[] = $sql_row["cod_item"];
		}
		return $lista_conteudo;
	}
	
	public function salvarItens(&$coditens) {
		$sql = "update Home_Itens_Apresentacao set disponivel = 1 where cod_item IN (".$coditens.");";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function apagaItensInicial() {
		$sql = "delete from Home_Itens_Apresentacao where disponivel = 0;";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function mudarTempoItemHome($coditem, $tempo) {
		$sql = "update Home_Itens_Apresentacao set tempo_exibicao = '".$tempo."' where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		$this->atualizaDataExibicaoSeguintes($coditem, $tempo);
	}

	private function atualizaDataExibicaoSeguintes($coditem, $tempo) {
		$sql = "select ordem, pagina, destaque_posicao, data_exibicao from Home_Itens_Apresentacao where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$data_anterior = $sql_row["data_exibicao"];

		$sql = "select cod_item, data_exibicao, tempo_exibicao from Home_Itens_Apresentacao where ordem > '".$sql_row["ordem"]."' and pagina = '".$sql_row["pagina"]."' and destaque_posicao = '".$sql_row["destaque_posicao"]."' order by ordem;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$data = new DateTime($data_anterior);
			$data->modify(sprintf("%+d minutes", (int)$tempo));
			$novadata = $data->format("Y-m-d H:i:s");
			$tempo = $sql_row["tempo_exibicao"];
			$data_anterior = $novadata;

			$sql = "update Home_Itens_Apresentacao set data_exibicao = '".$novadata."' where cod_item = '".$sql_row["cod_item"]."';";
			$sql_result2 = $this->banco->executaQuery($sql);
		}
	}

	public function removeConteudoSelecao($lista_selecionadas) {
		$sql = "delete from Home_Itens_Apresentacao where cod_item in ('".implode("', '", $lista_selecionadas)."');";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function atualizaOrdenacao($cod, $ordem) {
		$sql = "update Home_Itens_Apresentacao set ordem = '".$ordem."' where cod_item = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function atualizaDatasExibicao($paginahome, $destaque) {
		$novadata = "";
		$tempo = "";
		$sql = "select cod_item, tempo_exibicao, data_exibicao from Home_Itens_Apresentacao where pagina = '".$paginahome."' and destaque_posicao = '".$destaque."' order by ordem;";
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
			$sql = "update Home_Itens_Apresentacao set data_exibicao = '".$novadata."' where cod_item = '".$sql_row["cod_item"]."';";
			$sql_result2 = $this->banco->executaQuery($sql);
		}
	}

	public function trocaDestaqueItens(&$lista_selecionadas, $novasecao) {
		$sql = "update Home_Itens_Apresentacao set destaque_posicao = '".$novasecao."' where cod_item in ('".implode("', '", $lista_selecionadas)."');";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getListaConteudoHomeUsuario($codusuario, $tipo_usuario, $somente_coditem = false, $ordem = 1) {
		$lista_conteudo = array();
		$sql = "select ";
		if (!$somente_coditem)
			$sql .= "HI.cod_item, HI.cod_conteudo, CON.cod_formato, HI.titulo, HI.descricao, CON.imagem, CON.datahora, HI.tempo_exibicao, HI.data_exibicao, HI.ordem";
		else
			$sql .= "HI.cod_item";
		//$sql .= " from Home_Itens_Apresentacao HI, Conteudo CON where HI.pagina = '".$tipo_usuario."' and HI.cod_usuario = '".$codusuario."' and HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo";
		$sql .= " from Home_Itens_Apresentacao HI, Conteudo CON where HI.cod_usuario = '".$codusuario."' and HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo";
		

		$sql .= " and CON.excluido=0 and CON.publicado=1 and CON.situacao=1";

		if ($ordem == 1)
			$sql .= " order by HI.ordem DESC;";
		else
			$sql .= " order by HI.data_exibicao DESC;";

		//echo $sql;

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {

			//switch($sql_row['cod_formato']) {
			//	case 1: $url_editar = "conteudo_publicado_texto.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 2: $url_editar = "conteudo_publicado_imagem.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 3: $url_editar = "conteudo_publicado_audio.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 4: $url_editar = "conteudo_publicado_video.php?cod=".$sql_row['cod_conteudo']; break;
            //    case 5: $url_editar = "noticia_publicado.php?cod=".$sql_row['cod_conteudo']; break;
			//	case 5: $url_editar = "agenda_publicado.php?cod=".$sql_row['cod_conteudo']; break;
			//}
			
			$url_editar = "home_item_edicao.php?cod=".$sql_row['cod_item']."&pre=1&codu=".$codusuario;

			//if ($sql_row["imagem"])
			//	$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row["imagem"]."&amp;tipo=5&amp;s=1\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";

			$sql_row['tipo_img'] = 5;

			if ($sql_row["cod_formato"] == 2) {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row["cod_conteudo"]."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				if ($sql_row2["imagem"])
					$sql_row['imagem'] = $sql_row2["imagem"];
					$sql_row['tipo_img'] = 2;
					//$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row2["imagem"]."&amp;tipo=2&amp;s=1\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";
			}

			$sql_row['url_editar'] = $url_editar;

			if (!$somente_coditem) {
				$lista_conteudo[] = $sql_row;
			}
			else
				$lista_conteudo[] = $sql_row["cod_item"];
		}
		return $lista_conteudo;
	}

	public function adicionarConteudoListaHomeUsuario($cod_conteudo, $tipousuario, $codusuario, $tempo) {
		$sql = "select cod_item from Home_Itens_Apresentacao where cod_conteudo = '".$cod_conteudo."' and pagina = '".$tipousuario."' and cod_usuario = '".$codusuario."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if (!$sql_row["cod_item"]) {
			$sql = "select ordem, data_exibicao, tempo_exibicao from Home_Itens_Apresentacao where pagina = '".$tipousuario."' and cod_usuario = '".$codusuario."' order by ordem desc limit 1;";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			$max_ordem = (int)$sql_row[0];

			$data_inicio = $sql_row["data_exibicao"];
			if ($data_inicio == "0000-00-00 00:00:00")
				$data_inicio = date("Y-m-d H:i:s");

			$data = new DateTime($data_inicio);
			$data->modify("+".$sql_row["tempo_exibicao"]." minutes");
			$data_exibicao = $data->format("Y-m-d H:i:s");
			
			$sql = "SELECT titulo, descricao FROM Conteudo WHERE cod_conteudo='".$cod_conteudo."'";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);

			$sql = "insert into Home_Itens_Apresentacao (cod_conteudo, cod_usuario, pagina, tempo_exibicao, ordem, data_exibicao, disponivel, titulo, descricao) values ('".$cod_conteudo."', '".$codusuario."', '".$tipousuario."', '".$tempo."', '".($max_ordem + 1)."', '".$data_exibicao."', 0, '".addslashes($sql_row['titulo'])."', '".addslashes($sql_row['descricao'])."');";
			
			//echo $sql; die;
			$sql_result = $this->banco->executaQuery($sql);
		}
	}
	
// ===================================================================
// modificar ainda
// ===================================================================
	
	public function getDadosHomeAtual($destaque, $paginahome, $total = 1) {
		$dadoshome = array();
		if ($destaque != 2)
			$and = "and HI.data_exibicao <= '".date("Y-m-d H:i:s")."'";
		$sql = "select HI.cod_item, HN.cod_conteudo, HN.titulo, HN.resumo, HN.foto, HN.foto_credito, HN.foto_legenda, HI.tempo_exibicao, HI.data_exibicao, HI.ordem, U.titulo as url from Home_Itens HI, Home_Noticias HN, Conteudo CON, Urls U where HI.pagina = '".$paginahome."' and HI.destaque_posicao = '".$destaque."' and HI.excluido = 0 and HI.cod_conteudo = HN.cod_conteudo and HN.cod_conteudo = CON.cod_conteudo $and and CON.cod_conteudo = U.cod_item and U.tipo = 1 order by HI.data_exibicao desc limit ".$total.";";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$dadoshome[] = $sql_row;
		return $dadoshome;
	}
	
	public function atualiza() {
		$sql = "select HI.*, C.titulo, C.descricao, C.cod_formato from Home_Itens HI, Conteudo C where HI.pagina = 1 and HI.cod_usuario = 0 and HI.cod_conteudo = C.cod_conteudo and C.excluido='0' and C.publicado='1' and C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' order by HI.data_exibicao;";
		
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$sql = "insert into Home_Itens_Apresentacao values (NULL, '$sql_row[cod_conteudo]', '$sql_row[cod_usuario]', '$sql_row[titulo]', '$sql_row[descricao]', '$sql_row[pagina]', '$sql_row[destaque_posicao]', '$sql_row[tempo_exibicao]', '$sql_row[ordem]', '$sql_row[data_exibicao]', '1', '0')";
			$this->banco->executaQuery($sql);
		}
	}

	public function getListaHomeConteudo($codusuario=0) {
		$lista_conteudo = array();
		$sql = "SELECT cod_playlist FROM Home_Playlist WHERE CONCAT(data_inicio, hora_inicio) <= '".date("Y-m-dH:i:")."00' AND excluido=0 AND cod_usuario=".$codusuario." AND cod_sistema = '".ConfigVO::getCodSistema()."' ORDER BY CONCAT(data_inicio, hora_inicio) DESC LIMIT 1";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		
		$sql = "SELECT HI.cod_conteudo, HI.titulo, HI.descricao, HI.imagem, HI.cod_playlist, C.cod_formato, C.datahora from Home_Itens_Apresentacao HI, Conteudo C WHERE HI.cod_playlist = ".$sql_row['cod_playlist']." AND HI.cod_usuario = 0 AND HI.cod_conteudo = C.cod_conteudo AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' AND C.cod_sistema = '".ConfigVO::getCodSistema()."' ".Util::iif(!$codusuario, "AND HI.data_exibicao <= '".date("Y-m-d H:i:s")."'")." AND HI.disponivel=1 ORDER BY HI.ordem;";
		$sql_result = $this->banco->executaQuery($sql);
		$a = 1;
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista_conteudo[$a] = array('codconteudo' => $sql_row['cod_conteudo'], 'codformato' => $sql_row['cod_formato'], 'titulo' => $sql_row['titulo'], 'descricao' => $sql_row['descricao'], 'imagem' => $sql_row['imagem'], 'datahora' => $sql_row['datahora'], 'cod_playlist' => $sql_row['cod_playlist']);
			$a++;
		}
		return $lista_conteudo;
	}
	
	public function getListaHomeConteudoIteia($codusuario=0) {
		$lista_conteudo = array();
		$sql = "SELECT cod_playlist FROM Home_Playlist WHERE CONCAT(data_inicio, hora_inicio) <= '".date("Y-m-dH:i:")."00' AND excluido=0 AND cod_usuario=".$codusuario." AND cod_sistema = '".ConfigVO::getCodSistema()."' ORDER BY CONCAT(data_inicio, hora_inicio) DESC LIMIT 1";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		
		$sql = "SELECT HI.cod_conteudo, HI.titulo, HI.descricao, HI.imagem, HI.cod_playlist, C.cod_formato, C.datahora from Home_Itens_Apresentacao HI, Conteudo C WHERE HI.cod_playlist = ".$sql_row['cod_playlist']." AND HI.cod_usuario = 0 AND HI.cod_conteudo = C.cod_conteudo AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' AND C.cod_sistema = '".ConfigVO::getCodSistema()."' /*".Util::iif(!$codusuario, "AND HI.data_exibicao <= '".date("Y-m-d H:i:s")."'")."*/ AND HI.disponivel=1 ORDER BY HI.ordem LIMIT 4;";
		$sql_result = $this->banco->executaQuery($sql);
		$a = 1;
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista_conteudo[$a] = array('codconteudo' => $sql_row['cod_conteudo'], 'codformato' => $sql_row['cod_formato'], 'titulo' => $sql_row['titulo'], 'descricao' => $sql_row['descricao'], 'imagem' => $sql_row['imagem'], 'datahora' => $sql_row['datahora'], 'cod_playlist' => $sql_row['cod_playlist']);
			$a++;
		}
		return $lista_conteudo;
	}

}
