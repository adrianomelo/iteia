<?php
include_once("ConexaoDB.php");

class NotificacaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaNotificacao($inicial, $mostrar) {
		$array = array();

		$array['link'] = "index_lista_notificacao.php?buscar=1";
		//$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";

		// se eu for colaborador/admin irar exibir as minhas notificações para aprovação
		/*
		if ($_SESSION['logado_dados']['nivel'] >= 5) {
			$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t2.cod_notificacao, t2.cod_tipo, t3.cod_usuario, t3.nome, t4.titulo AS url, t5.sigla FROM Conteudo AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t3 ON (t2.cod_autor=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) LEFT JOIN Estados AS t5 ON (t3.cod_estado=t5.cod_estado) $where AND (t2.cod_tipo='2' OR t2.cod_tipo='5') AND t2.cod_colaborador='".$_SESSION['logado_dados']['cod_colaborador']."' AND t4.tipo='2'";
		}
		// se eu for autor irar exibir minhas notificações de aprovação ou reprovação
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t2.cod_notificacao, t2.cod_tipo, t3.cod_usuario, t3.nome, t4.titulo AS url, t5.sigla FROM Conteudo AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t3 ON (t2.cod_colaborador=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) LEFT JOIN Estados AS t5 ON (t3.cod_estado=t5.cod_estado) $where AND (t2.cod_tipo='3' OR t2.cod_tipo='4') AND t2.cod_autor='".$_SESSION['logado_cod']."' AND t4.tipo='1'";
		}
		*/
		// busco todas as notificações (autorizações) que está destinadas a mim e as que estão na lista publica (cod_colaborador = 0)
		//if ($_SESSION['logado_dados']['nivel'] >= 5) {
			if ($_SESSION['logado_dados']['nivel'] < 6)
				$where .= " AND (t2.cod_colaborador='".$_SESSION['logado_dados']['cod']."' OR t2.cod_colaborador='0')";
			//$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t1.titulo, t2.cod_notificacao, t2.cod_tipo, t2.cod_colaborador, t3.cod_usuario, t3.nome, t4.titulo AS url, t5.sigla FROM Conteudo AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t3 ON (t2.cod_autor=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) LEFT JOIN Estados AS t5 ON (t3.cod_estado=t5.cod_estado) $where AND (t2.cod_tipo='2' OR t2.cod_tipo='5') AND t4.tipo='2'";

			// pega todas as notificações
			//$sql = "SELECT * FROM Conteudo_Notificacoes WHERE cod_tipo='2' OR cod_tipo='5' OR cod_tipo='150' $where";
			
			//$sql = "(SELECT t1.cod_conteudo AS cod, t2.cod_tipo, t2.cod_autor, t2.cod_notificacao, t2.cod_colaborador, 1 AS tipo FROM Conteudo AS t1 INNER JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' AND (t2.cod_tipo='2' OR t2.cod_tipo='5') $where) UNION (SELECT t1.cod_usuario AS cod, t2.cod_tipo, t2.cod_autor, t2.cod_notificacao, t2.cod_colaborador, 2 AS tipo FROM Usuarios AS t1 INNER JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_usuario=t2.cod_autor) WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t2.cod_tipo='150')";
			
			// 2 => aprovação
			// 5 => re-aprovação
			// 150 => aprovação autor
			// 4 => reprovado
			
			// se for admin agrupa itens
			if ($_SESSION['logado_dados']['nivel'] > 6)
				$orderby = " GROUP BY cod";
			
			$sql = "
			(SELECT t1.cod_conteudo AS cod, t2.cod_tipo, t2.cod_autor, t2.cod_notificacao, t2.cod_colaborador, 1 AS tipo FROM Conteudo AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t2.cod_tipo IN(2,5,250) $where $orderby) 
			UNION 
			(SELECT t1.cod_usuario AS cod , t2.cod_tipo, t2.cod_autor, t2.cod_notificacao, t2.cod_colaborador, 2 AS tipo FROM Usuarios AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_usuario=t2.cod_autor) OR (t1.cod_usuario=t2.cod_colaborador) WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t2.cod_tipo IN(150,250) $orderby)";
			
		//}

		//echo $sql;

		include_once("ConteudoExibicaoDAO.php");
		include_once("UsuarioDAO.php");
		$contdao = new ConteudoExibicaoDAO;
		$usrdao = new UsuarioDAO;

		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		//echo "$sql ORDER BY cod_notificacao ASC LIMIT $inicial,$mostrar";

		//$query = $this->banco->executaQuery("$sql ORDER BY cod_conteudo DESC LIMIT $inicial,$mostrar");
		$query = $this->banco->executaQuery("$sql ORDER BY cod_notificacao ASC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchObject($query)) {

			//$dados_conteudo = $contdao->getConteudoResumido($row->cod_conteudo, false);
			//$dados_autor = $usrdao->getUsuarioDados($row->cod_autor);
			
			if ($row->tipo == 1) // conteudo
				$dados_conteudo = $contdao->getConteudoResumido($row->cod, false);
			
			if ($row->tipo == 1) // conteudo
				$dados_autor = $usrdao->getUsuarioDados($dados_conteudo['cod_autor']);
			else
				$dados_autor = $usrdao->getUsuarioDados($row->cod);
			
			if ((int)$row->cod) {

			//if (($dados_conteudo['cod_sistema'] == ConfigVO::getCodSistema()) || ($dados_autor['cod_sistema'] == ConfigVO::getCodSistema())) {

				switch($row->cod_tipo) {
					case 2:
					case 5:
					//	if ($_SESSION['logado_dados']['nivel'] == 2)
					//		$url_arquivo = 'index_exibir_aguardando.php?cod=';
					//	else
							//$url_arquivo = 'index_exibir_notificacao.php?cod='.$row->cod_conteudo;
							$url_arquivo = 'index_exibir_notificacao.php?cod='.$row->cod;
					break;
					//case 3: $url_arquivo = 'index_exibir_aprovado.php?cod='; break;
					//case 4: $url_arquivo = 'index_exibir_reprovado.php?cod='; break;
					//case 150: $url_arquivo = 'index_exibir_autor_pendente.php?cod='.$row->cod_autor; break;
					case 150: $url_arquivo = 'index_exibir_autor_pendente.php?cod='.$row->cod; break;
				}
			
			//$row->tipo = 'a';
	        //if ($dados_conteudo['cod_formato'] == 2) {
			//	$sql1 = "SELECT t1.imagem FROM Imagens AS t1 LEFT JOIN Albuns AS t2 ON (t1.cod_imagem=t2.cod_imagem_capa) WHERE t2.cod_conteudo='".$row->cod."'";
			//	$query1 = $this->banco->executaQuery($sql1);
	        //	$row1 = $this->banco->fetchArray($query1);
	        //	$row->imagem = $row1['imagem'];
	        //	$row->tipo = '2';
			//}

				switch($dados_conteudo['cod_formato']) {
					case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
					case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
					case 3: $formato = '<span class="audio" title="Áudio">Áudio</span>'; break;
					case 4: $formato = '<span class="video" title="Vídeo">Vídeo</span>'; break;
					case 250: $formato = '<span class="colaborador" title="Colaborador">Colaborador</span>'; break;
					default: $formato = '<span class="autor">Autor</span>'; break;
				}

				$array[$row->cod_notificacao] = array(
					'cod_conteudo' => $dados_conteudo['cod_conteudo'],
					'cod_notificacao' => $row->cod_notificacao,
					'cod_tipo' => $row->cod_tipo,
					'cod_formato' => $dados_conteudo['cod_formato'],
					'nome' => $dados_autor['nome'],
					'formato' => $dados_conteudo['cod_formato'],
					'cod_colaborador' => $row->cod_colaborador,
					'img_formato' => $formato,
					'imagem' => $dados_conteudo['imagem'],
					'titulo' => $dados_conteudo['titulo'],
					'tipo' => $row->tipo,
					'url' => $dados_autor['url'],
					'url_arquivo' => $url_arquivo,
					'sigla' => $dados_autor['sigla']
				);
				
			}


			/*
			$row->tipo = 'a';
	        if ($dados_conteudo['cod_formato'] == 2) {
				$sql1 = "SELECT t1.imagem FROM Imagens AS t1 LEFT JOIN Albuns AS t2 ON (t1.cod_imagem=t2.cod_imagem_capa) WHERE t2.cod_conteudo='".$row->cod_conteudo."'";
				$query1 = $this->banco->executaQuery($sql1);
	        	$row1 = $this->banco->fetchArray($query1);
	        	$row->imagem = $row1['imagem'];
	        	$row->tipo = '2';
			}

			switch($dados_conteudo['cod_formato']) {
				case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
				case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
				case 3: $formato = '<span class="audio" title="Áudio">Áudio</span>'; break;
				case 4: $formato = '<span class="video" title="Vídeo">Vídeo</span>'; break;
				default: $formato = '<span class="autor">Autor</span>'; break;
			}

			$array[$row->cod_notificacao] = array(
				'cod_conteudo' => $dados_conteudo['cod_conteudo'],
				'cod_notificacao' => $row->cod_notificacao,
				'cod_tipo' => $row->cod_tipo,
				'cod_formato' => $dados_conteudo['cod_formato'],
				'nome' => $dados_autor['nome'],
				'formato' => $dados_conteudo['cod_formato'],
				'cod_colaborador' => $row->cod_colaborador,
				'img_formato' => $formato,
				'imagem' => $dados_conteudo['imagem'],
				'titulo' => $dados_conteudo['titulo'],
				'tipo' => $row->tipo,
				'url' => $dados_autor['url'],
				'url_arquivo' => $url_arquivo,
				'sigla' => $dados_autor['sigla']
			);
			*/
		}

		//}

		return $array;
	}

	public function apagarNotificacao($codnotificacao) {
		$this->banco->sql_delete('Conteudo_Notificacoes', "cod_notificacao='".$codnotificacao."'");
	}

	public function getMotivoReprovacao($codconteudo) {
		$query = $this->banco->sql_select('cod_colaborador, comentario', 'Conteudo_Notificacoes', "cod_conteudo='".$codconteudo."' AND cod_tipo='4'");
		$row = $this->banco->fetchObject($query);

		if ($row->cod_colaborador) {
			$query = $this->banco->sql_select('nome', 'Usuarios', "cod_usuario='".$row->cod_colaborador."'");
			$colab = $this->banco->fetchObject($query);
		}

		return array('comentario' => $row->comentario, 'colaborador' => $colab->nome);
	}

}
