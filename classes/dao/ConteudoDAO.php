<?php
include_once('ConexaoDB.php');

class ConteudoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function cadastrarConteudo(&$conteudovo) {
		// cadastra conteudo
		$this->banco->sql_insert('Conteudo', array('cod_formato' => $conteudovo->getCodFormato(), 'cod_sistema' => ConfigVO::getCodSistema(), 'cod_classificacao' => $conteudovo->getCodClassificacao(), 'cod_segmento' => $conteudovo->getCodSegmento(), 'cod_subarea' => $conteudovo->getCodSubArea(), 'cod_canal' => $conteudovo->getCodCanal(), 'cod_licenca' => $conteudovo->getCodLicenca(), 'cod_colaborador' => $conteudovo->getCodColaborador(), 'cod_autor' => $conteudovo->getCodAutor(), 'randomico' => $conteudovo->getRandomico(), 'titulo' => $conteudovo->getTitulo(), 'descricao' => $conteudovo->getDescricao(), 'datahora' => $conteudovo->getDataHora(), 'data_cadastro' => date('Y-m-d H:i:s'), 'situacao' => $conteudovo->getSituacao(), 'publicado' => $conteudovo->getPublicado()));
		$codconteudo = $this->banco->insertId();

		// permitir comentarios
		$this->banco->sql_insert('Conteudo_Opcoes', array('cod_conteudo' => $codconteudo, 'permitir_comentarios' => $conteudovo->getPermitirComentarios()));

		switch ($conteudovo->getCodFormato()) {
			case 1: $campo = 'textos'; break;
			case 2: $campo = 'imagens'; break;
			case 3: $campo = 'audios'; break;
			case 4: $campo = 'videos'; break;
			case 5: $campo = 'jornal'; break;
			case 6: $campo = 'eventos'; break;
		}

		if ($codconteudo) {
			$i = 0;
			$urltitulo = $campo.'/'.$conteudovo->getUrl();
			do {
				if ($i)
					$urltitulo = $campo.'/'.$conteudovo->getUrl().$i;
				$sql = "INSERT INTO Urls VALUES ('".$urltitulo."', '".$codconteudo."', 4, '".ConfigVO::getCodSistema()."')";
				$tenta = $this->banco->executaQuery($sql);
				$i++;
			}
			while (!$tenta);

            $this->cadastrarTags($conteudovo, $codconteudo);
		}

		//$this->cadastrarNotificacao(1, $codconteudo, $conteudovo->getCodAutor(), $conteudovo->getCodColaborador(), 0, '');
		// atualiza quantidade de conteudo do usuario
		$this->banco->executaQuery("UPDATE Usuarios_Estatisticas SET $campo = $campo + 1 WHERE cod_usuario='".$_SESSION['logado_cod']."'");

		// se o cadastro for feito por um autor/colaborador participante ou responsavel automaticamente ele é incluido como autor do conteudo
		if ($_SESSION['logado_dados']['nivel'] == 2 || $_SESSION['logado_dados']['nivel'] >= 5) {
			$this->banco->executaQuery("INSERT INTO Conteudo_Autores VALUES ('".$codconteudo."', '".$_SESSION['logado_cod']."')");
		}

		if (count($conteudovo->getListaAutores())) {
			foreach ($conteudovo->getListaAutores() as $autorficha)
				$this->banco->executaQuery("INSERT INTO Conteudo_Autores_Ficha VALUES (NULL, '".$codconteudo."', '".$autorficha['codautor']."', '".$autorficha['atividade']."')");
			if ($_SESSION['logado_dados']['nivel'] == 2)
				$this->banco->executaQuery("INSERT INTO Conteudo_Autores_Ficha VALUES (NULL, '".$codconteudo."', '".$_SESSION['logado_cod']."', 1)");
		}

		// autorizações
		// apenas pra usuarios de nivel 2
		if ($_SESSION['logado_dados']['nivel'] == 2) {

			// mandar pra lista publica
			if ($conteudovo->getPedirAutorizacao() == 1) {
				$this->enviarConteudoListaPublica($codconteudo);
			}

			// mandar pra colaboradores selecionados
			if ($conteudovo->getPedirAutorizacao() == 2) {
				$this->atualizarConteudoColaboradoresRevisaoNova($conteudovo, $codconteudo);
			}

		}

		return $codconteudo;
	}

	public function atualizarConteudo(&$conteudovo) {
		// atualiza conteudo
		$this->banco->sql_update('Conteudo', array('cod_classificacao' => $conteudovo->getCodClassificacao(),'cod_segmento' => $conteudovo->getCodSegmento(), 'cod_subarea' => $conteudovo->getCodSubArea(), 'cod_canal' => $conteudovo->getCodCanal(), 'cod_licenca' => $conteudovo->getCodLicenca(), 'titulo' => $conteudovo->getTitulo(), 'descricao' => $conteudovo->getDescricao()), "cod_conteudo='".$conteudovo->getCodConteudo()."'");

		// permitir comentarios
		$query = $this->banco->sql_select('cod_conteudo', 'Conteudo_Opcoes', "cod_conteudo='".$conteudovo->getCodConteudo()."'");
		if ($this->banco->numRows($query))
			$this->banco->sql_update('Conteudo_Opcoes', array('permitir_comentarios' => $conteudovo->getPermitirComentarios()), "cod_conteudo='".$conteudovo->getCodConteudo()."'");
		else
			$this->banco->sql_insert('Conteudo_Opcoes', array('cod_conteudo' => $conteudovo->getCodConteudo(), 'permitir_comentarios' => $conteudovo->getPermitirComentarios()));

		switch ($conteudovo->getCodFormato()) {
			case 1: $campo = 'textos'; break;
			case 2: $campo = 'imagens'; break;
			case 3: $campo = 'audios'; break;
			case 4: $campo = 'videos'; break;
			case 5: $campo = 'jornal'; break;
			case 6: $campo = 'eventos'; break;
		}

		$pasta_titulo = '';
		$sql = "SELECT titulo from Urls where cod_item='".$conteudovo->getCodConteudo()."' AND tipo='4' and cod_sistema = '".ConfigVO::getCodSistema()."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = mysql_fetch_array($sql_result);
		$titulo_atual = $sql_row[0];
		$titulo_partes = explode('/', $titulo_atual);
		if ($titulo_partes[1])
			$pasta_titulo = $campo.'/';

		$i = 0;
		$urltitulo = $pasta_titulo.$conteudovo->getUrl();
		do {
			if ($i)
				$urltitulo = $pasta_titulo.$conteudovo->getUrl().$i;
			$sql = "UPDATE Urls SET titulo='".$urltitulo."' WHERE cod_item='".$conteudovo->getCodConteudo()."' AND tipo='4' and cod_sistema = '".ConfigVO::getCodSistema()."'";
			$tenta = $this->banco->executaQuery($sql);
			$i++;
		}
		while (!$tenta);

		// se a edição for feita por um autor o conteudo dele é recolocado como inativo
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			// defino como inativo e pendente
			$this->banco->sql_update('Conteudo', array('situacao' => 0, 'publicado' => 0), "cod_conteudo='".$conteudovo->getCodConteudo()."'");
			// cadastro ele na tabela de edição
			$this->banco->sql_insert('Conteudo_Edicao', array('cod_conteudo' => $conteudovo->getCodConteudo(), 'data_edicao' => date('Y-m-d H:i:s')));
			// se o conteudo não tiver autor nativo e (eu) edita-lo fico com ele p/ mim
			$query = $this->banco->sql_select('cod_autor', 'Conteudo', "cod_conteudo='".$conteudovo->getCodConteudo()."'");
        	$row = $this->banco->fetchObject();
        	if (!$row->cod_autor)
        		$this->banco->sql_update('Conteudo', array('cod_autor' => $_SESSION['logado_cod']), "cod_conteudo='".$conteudovo->getCodConteudo()."'");
        }

        // autorizações
		// apenas pra usuarios de nivel 2
		if ($_SESSION['logado_dados']['nivel'] == 2) {

			// mandar pra lista publica
			if ($conteudovo->getPedirAutorizacao() == 1) {
				$this->enviarConteudoListaPublica($conteudovo->getCodConteudo());
			}

			// mandar pra colaboradores selecionados
			if ($conteudovo->getPedirAutorizacao() == 2) {
				$this->atualizarConteudoColaboradoresRevisaoNova($conteudovo, $conteudovo->getCodConteudo());
			}

		}

		if (($_SESSION['logado_dados']['nivel'] >= 5) || count($_SESSION['logado_dados']['cod_grupo'])) {
			$this->banco->executaQuery("delete from Conteudo_Autores_Ficha where cod_conteudo = '".$conteudovo->getCodConteudo()."'");
			if (count($conteudovo->getListaAutores())) {
				foreach ($conteudovo->getListaAutores() as $autorficha)
					$this->banco->executaQuery("INSERT INTO Conteudo_Autores_Ficha VALUES (NULL, '".$conteudovo->getCodConteudo()."', '".$autorficha['codautor']."', '".$autorficha['atividade']."')");
				if ($_SESSION['logado_dados']['nivel'] == 2)
					$this->banco->executaQuery("INSERT INTO Conteudo_Autores_Ficha VALUES (NULL, '".$conteudovo->getCodConteudo()."', '".$_SESSION['logado_cod']."', 1)");
			}
		}

        $this->atualizarTags($conteudovo);
	}

	public function cadastrarTags(&$conteudovo, $codconteudo) {
        if ($conteudovo->getTags()) {
            $lista_tags = explode(';', $conteudovo->getTags());
            foreach ($lista_tags as $tag) {
            	if ($tag != '') {
	                $query = $this->banco->executaQuery("SELECT cod_tag FROM Tags WHERE tag='".trim($tag)."' AND cod_sistema='".ConfigVO::getCodSistema()."'");
	                if ($this->banco->numRows($query)) {
	                    $row = $this->banco->fetchArray($query);
	                    $this->banco->executaQuery("INSERT INTO Conteudo_Tags VALUES ('".$codconteudo."', '".$row['cod_tag']."')");
	                } else {
	                    $this->banco->executaQuery("INSERT INTO Tags VALUES ('NULL', '".ConfigVO::getCodSistema()."', '".trim($tag)."')");
	                    $cod_tag = $this->banco->insertID();
	                    $this->banco->executaQuery("INSERT INTO Conteudo_Tags VALUES ('".$codconteudo."', '".$cod_tag."')");
	                }
	            }
            }
        }
    }

    public function atualizarTags(&$conteudovo) {
        if ($conteudovo->getTags()) {
            $lista_tags = explode(';', $conteudovo->getTags());
            $this->banco->executaQuery("DELETE FROM Conteudo_Tags WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");
            foreach ($lista_tags as $tag) {
            	if ($tag != '') {
	                $query = $this->banco->executaQuery("SELECT cod_tag FROM Tags WHERE tag='".trim($tag)."' AND cod_sistema='".ConfigVO::getCodSistema()."'");
	                if ($this->banco->numRows($query)) {
	                    $row = $this->banco->fetchArray($query);
	                    $this->banco->executaQuery("INSERT INTO Conteudo_Tags VALUES ('".$conteudovo->getCodConteudo()."', '".$row['cod_tag']."')");
	                } else {
	                    $this->banco->executaQuery("INSERT INTO Tags VALUES ('NULL', '".ConfigVO::getCodSistema()."', '".trim($tag)."')");
	                    $cod_tag = $this->banco->insertID();
	                    $this->banco->executaQuery("INSERT INTO Conteudo_Tags VALUES ('".$conteudovo->getCodConteudo()."', '".$cod_tag."')");
	                }
                }
            }
        }
    }

    public function atualizarConteudoRelacionado(&$conteudovo) {
        if (count($conteudovo->getListaConteudoRelacionado())) {
            $this->banco->executaQuery("DELETE FROM Conteudo_Relacionados WHERE cod_conteudo1='".$conteudovo->getCodConteudo()."'");
            foreach ($conteudovo->getListaConteudoRelacionado() as $cod) {
                if ($conteudovo->getCodConteudo() != $cod)
                    $this->banco->executaQuery("INSERT INTO Conteudo_Relacionados VALUES ('".$conteudovo->getCodConteudo()."', '".$cod."')");
            }
        }
    }

    public function atualizarConteudoAutores(&$conteudovo) {
        if (count($conteudovo->getListaAutores())) {
            $this->banco->executaQuery("DELETE FROM Conteudo_Autores WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");

            switch ($this->getFormatoConteudo($conteudovo->getCodConteudo())) {
				case 1: $campo = 'textos'; break;
				case 2: $campo = 'imagens'; break;
				case 3: $campo = 'audios'; break;
				case 4: $campo = 'videos'; break;
				case 5: $campo = 'jornal'; break;
				case 6: $campo = 'eventos'; break;
			}

            foreach ($conteudovo->getListaAutores() as $value) {
            	// adiciona autor ao conteudo
			    $this->banco->executaQuery("INSERT INTO Conteudo_Autores VALUES ('".$conteudovo->getCodConteudo()."', '".$value['cod_usuario']."')");
			    // atualiza estatisticas do autor
			    $this->banco->executaQuery("UPDATE Usuarios_Estatisticas SET $campo = $campo + 1 WHERE cod_usuario='".$value['cod_usuario']."'");
			}
        }

        // se o conteudo ainda não foi publicado
        // e o inserção de autores for feito por um colaborador ou admin
        // é definido como publicado
		$query = $this->banco->sql_select('cod_autor, publicado', 'Conteudo', "cod_conteudo='".$conteudovo->getCodConteudo()."'");
        $row = $this->banco->fetchObject();

        // se não foi publicado e não é autor logado
        if (($row->publicado == 0) && ($_SESSION['logado_dados']['nivel'] >= 5) && (isset($_SESSION['logado_dados']['cod_colaborador']))) {
        	// se foi cadastrado por um autor
			if ($row->cod_autor) {
        		// envio notificação para o autor informado que foi aprovado
        		$this->cadastrarNotificacao(3, $conteudovo->getCodConteudo(), $row->cod_autor, $_SESSION['logado_dados']['cod_colaborador'], 0, 0);
        		// defino que o conteudo é do colaborador que efetuou esta ação
        		$this->vincularColaborador($_SESSION['logado_dados']['cod_colaborador'], $conteudovo->getCodConteudo());
			}
			// publico o conteudo
			$this->banco->sql_update('Conteudo', array('publicado' => 1), "cod_conteudo='".$conteudovo->getCodConteudo()."'");
			// excluo da lista publica caso esteja na mesma
			$this->banco->executaQuery("DELETE FROM Conteudo_ListaPublica WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");
			// e excluido da aprovação de colaboradores
			$this->banco->executaQuery("DELETE FROM Conteudo_Revisao WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");
			// e excluido das notificações de aprovação
			$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$conteudovo->getCodConteudo()."' AND cod_tipo='2' AND cod_autor='".$row->cod_autor."'");
		}
    }

    public function vincularColaborador($codusuario, $codconteudo) {
		//if ($_SESSION['logado_como'] == 3) { // só pra segurar que o update ta sendo feito pelo admin
			$this->banco->sql_update('Conteudo', array('cod_colaborador' => $codusuario), "cod_conteudo='".$codconteudo."'");
		//}
	}

	public function associarGruposConteudo($codgrupos, $codconteudo) {
		if (count($codgrupos)) {
            $this->banco->executaQuery("DELETE FROM Conteudo_Grupos WHERE cod_conteudo='".$codconteudo."'");
            foreach ($codgrupos as $cod)
                $this->banco->executaQuery("INSERT INTO Conteudo_Grupos VALUES ('".$codconteudo."', '".$cod."')");
        }
	}

	public function aprovarConteudo($codconteudo) {
		// se o conteudo ainda não foi publicado
        $query = $this->banco->sql_select('cod_autor, publicado', 'Conteudo', "cod_conteudo='".$codconteudo."'");
        $row = $this->banco->fetchObject();

        // se não foi publicado e não é autor logado
        if (($row->publicado == 0) && ($_SESSION['logado_dados']['nivel'] >= 5) && (isset($_SESSION['logado_dados']['cod_colaborador']))) {
        	// se foi cadastrado por um autor
			if ($row->cod_autor) {
        		// envio notificação para o autor informado que foi aprovado
        		//$this->cadastrarNotificacao(3, $codconteudo, $row->cod_autor, $_SESSION['logado_dados']['cod_colaborador'], 0, 0);
        		// defino que o conteudo é do colaborador que efetuou esta ação
        		$this->vincularColaborador($_SESSION['logado_dados']['cod_colaborador'], $codconteudo);
			}
			// publico o conteudo
			$this->banco->sql_update('Conteudo', array('publicado' => 1), "cod_conteudo='".$codconteudo."'");
			// excluo da lista publica caso esteja na mesma
			$this->banco->executaQuery("DELETE FROM Conteudo_ListaPublica WHERE cod_conteudo='".$codconteudo."'");
			// e excluido da aprovação de colaboradores
			$this->banco->executaQuery("DELETE FROM Conteudo_Revisao WHERE cod_conteudo='".$codconteudo."'");
			// e excluido da lista de edições
			$this->banco->executaQuery("DELETE FROM Conteudo_Edicao WHERE cod_conteudo='".$codconteudo."'");
			// e excluido das notificações de aprovação
			$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$codconteudo."' AND (cod_tipo='2' OR cod_tipo='5') AND cod_autor='".$row->cod_autor."'");

			// ativa conteudo
			$this->banco->sql_update('Conteudo', array('situacao' => 1), "cod_conteudo='".$codconteudo."'");
		}
	}

	public function reprovarConteudo($codconteudo, $comentario) {
		// se o conteudo ainda não foi publicado
        $query = $this->banco->sql_select('cod_autor, publicado', 'Conteudo', "cod_conteudo='".$codconteudo."'");
        $row = $this->banco->fetchObject();

        // se não foi publicado e não é autor logado
        if (($row->publicado == 0) && ($_SESSION['logado_dados']['nivel'] >= 5) && (isset($_SESSION['logado_dados']['cod_colaborador']))) {
        	// se foi cadastrado por um autor
			if ($row->cod_autor) {
        		// envio notificação para o autor informado que foi reprovado
        		$this->cadastrarNotificacao(4, $codconteudo, $row->cod_autor, $_SESSION['logado_dados']['cod'], 0, strip_tags(addslashes($comentario)));
        		// defino que o conteudo é do colaborador que efetuou esta ação
        		$this->vincularColaborador($_SESSION['logado_dados']['cod_colaborador'], $codconteudo);
			}
			// defino como reprovado
			$this->banco->sql_update('Conteudo', array('publicado' => 2), "cod_conteudo='".$codconteudo."'");
			// excluo da lista publica caso esteja na mesma
			$this->banco->executaQuery("DELETE FROM Conteudo_ListaPublica WHERE cod_conteudo='".$codconteudo."'");
			// e excluido da aprovação de colaboradores
			$this->banco->executaQuery("DELETE FROM Conteudo_Revisao WHERE cod_conteudo='".$codconteudo."'");
			// e excluido da lista de edições
			$this->banco->executaQuery("DELETE FROM Conteudo_Edicao WHERE cod_conteudo='".$codconteudo."'");
			// e excluido das notificações de aprovação
			$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$codconteudo."' AND (cod_tipo='2' OR cod_tipo='5' OR cod_tipo='3') AND cod_autor='".$row->cod_autor."'");
			// insiro na lista de reprovados
			$this->banco->sql_insert('Conteudo_Reprovado', array('cod_conteudo' => $codconteudo, 'cod_colaborador' => $_SESSION['logado_dados']['cod_colaborador'], 'comentario' => $comentario));
		}
	}

	public function getFormatoConteudo($codconteudo) {
		$query = $this->banco->sql_select('cod_formato', 'Conteudo', "cod_conteudo='".$codconteudo."'");
		$row = $this->banco->fetchObject($query);
		return $row->cod_formato;
	}

	public function getPublicacaoConteudo($codconteudo) {
		$query = $this->banco->sql_select('publicado', 'Conteudo', "cod_conteudo='".$codconteudo."'");
		$row = $this->banco->fetchObject($query);
		return $row->publicado;
	}

	public function enviarConteudoListaPublica($codconteudo) {
		/*
		$query = $this->banco->sql_select('cod_conteudo', 'Conteudo_ListaPublica', "cod_conteudo='".$codconteudo."'");
        if (!$this->banco->numRows($query)) {
			$this->banco->sql_insert('Conteudo_ListaPublica', array('cod_conteudo' => $codconteudo, 'data_cadastro' => date('Y-m-d H:i:s')));
		} else {
			$this->banco->sql_update('Conteudo_ListaPublica', array('data_cadastro' => date('Y-m-d H:i:s')), "cod_conteudo='".$codconteudo."'");
		}
		*/

		$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$codconteudo."'");

		$query = $this->banco->sql_select('cod_conteudo', 'Conteudo_Notificacoes', "cod_conteudo='".$codconteudo."' AND cod_colaborador='0'");
        //if (!$this->banco->numRows($query)) {
        	// busco para ver se o conteudo não é uma edição
            $query = $this->banco->sql_select('cod_conteudo', 'Conteudo_Edicao', "cod_conteudo='".$codconteudo."'");

			$cod_tipo = Util::iif($this->banco->numRows($query), 5, 2);

			$this->cadastrarNotificacao($cod_tipo, $codconteudo, $_SESSION['logado_cod'], 0, 0, 0);
		//}
	}

	public function atualizarConteudoColaboradoresRevisaoNova(&$conteudovo, $codconteudo) {
		if (count($conteudovo->getListaColaboradoresRevisao())) {
			$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$codconteudo."'");
            $this->banco->executaQuery("DELETE FROM Conteudo_Revisao WHERE cod_conteudo='".$codconteudo."'");
            // busco para ver se o conteudo não é uma edição
            $query = $this->banco->sql_select('cod_conteudo', 'Conteudo_Edicao', "cod_conteudo='".$codconteudo."'");

			$cod_tipo = Util::iif($this->banco->numRows($query), 5, 2);

            foreach ($conteudovo->getListaColaboradoresRevisao() as $value) {
            	// busco quais os autores participantes/responsavel pelo colaborador selecionado
            	$sql = "SELECT cod_autor FROM Colaboradores_Integrantes WHERE cod_colaborador = '".$value['cod_usuario']."'";
            	$query = $this->banco->executaQuery($sql);
            	while ($row = $this->banco->fetchObject($query)) {
            		// cadastra conteudo pra revisão dos autores responsaveis
					$this->banco->executaQuery("INSERT INTO Conteudo_Revisao VALUES ('".$codconteudo."', '".$row->cod_autor."')");
                	// cadastra notificação pedindo aprovação de um autor
                	$this->cadastrarNotificacao($cod_tipo, $codconteudo, $_SESSION['logado_cod'], $row->cod_autor, 0, 0);
            	}
            }
        }
	}

	public function getListaColaboradoresAprovacao($codconteudo) {
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1 LEFT JOIN Conteudo_Notificacoes AS t2 ON (t1.cod_usuario=t2.cod_colaborador) WHERE t1.cod_tipo='2' AND t2.cod_conteudo='".$codconteudo."' AND t1.cod_sistema='".ConfigVO::getCodSistema()."' AND (t2.cod_tipo='5' OR t2.cod_tipo='2') AND t2.cod_colaborador!='0'";
		$query = $this->banco->executaQuery($sql);
		return (bool)$this->banco->fetchArray($query);
	}

	public function atualizarConteudoColaboradoresRevisao(&$conteudovo) {
		if (count($conteudovo->getListaColaboradoresRevisao())) {
			$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");
            $this->banco->executaQuery("DELETE FROM Conteudo_Revisao WHERE cod_conteudo='".$conteudovo->getCodConteudo()."'");
            // busco para ver se o conteudo não é uma edição
            $query = $this->banco->sql_select('cod_conteudo', 'Conteudo_Edicao', "cod_conteudo='".$conteudovo->getCodConteudo()."'");

			$cod_tipo = Util::iif($this->banco->numRows($query), 5, 2);

            foreach ($conteudovo->getListaColaboradoresRevisao() as $value) {
            	// busco quais os autores participantes/responsavel pelo colaborador selecionado
            	$sql = "SELECT cod_autor FROM Colaboradores_Integrantes WHERE cod_colaborador = '".$value['cod_usuario']."'";
            	$query = $this->banco->executaQuery($sql);
            	while ($row = $this->banco->fetchObject($query)) {
            		// cadastra conteudo pra revisão dos autores responsaveis
					$this->banco->executaQuery("INSERT INTO Conteudo_Revisao VALUES ('".$conteudovo->getCodConteudo()."', '".$row->cod_autor."')");
                	// cadastra notificação pedindo aprovação de um autor
                	$this->cadastrarNotificacao($cod_tipo, $conteudovo->getCodConteudo(), $_SESSION['logado_cod'], $row->cod_autor, 0, 0);
            	}
            }
        }
	}

    public function cadastrarNotificacao($codtipo, $codconteudo, $codautor, $codcolaborador, $codgrupo, $comentario) {
		$this->banco->sql_insert('Conteudo_Notificacoes', array('cod_tipo' => $codtipo, 'cod_conteudo' => $codconteudo, 'cod_autor' => $codautor, 'cod_colaborador' => $codcolaborador, 'cod_grupo' => $codgrupo, 'comentario' => $comentario, 'data_cadastro' => date('Y-m-d H:i:s')));
	}

	public function atualizarFoto($nomearquivo, $codconteudo) {
		$sql = "UPDATE Conteudo SET imagem = '".$nomearquivo."' WHERE cod_conteudo='".$codconteudo."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function excluirImagem($cod) {
		$sql = "UPDATE Conteudo SET imagem = '' WHERE cod_conteudo='".$cod."'";
		$this->banco->executaQuery($sql);
	}

	public function getConteudoVO($codconteudo, $contvo) {
		$sql = "SELECT t1.*, t2.titulo AS url FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.cod_conteudo = '".$codconteudo."' AND t2.tipo='4'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();

		$contvo->setCodConteudo($sql_row->cod_conteudo);
		$contvo->setCodFormato($sql_row->cod_formato);
		$contvo->setCodLicenca($sql_row->cod_licenca);
		$contvo->setCodColaborador($sql_row->cod_colaborador);
		$contvo->setCodAutor($sql_row->cod_autor);
		$contvo->setRandomico($sql_row->randomico);
		$contvo->setCodClassificacao($sql_row->cod_classificacao);
		$contvo->setCodSegmento($sql_row->cod_segmento);
		$contvo->setCodSubArea($sql_row->cod_subarea);
		$contvo->setCodCanal($sql_row->cod_canal);
		$contvo->setTitulo($sql_row->titulo);
		$contvo->setDescricao($sql_row->descricao);
		$contvo->setImagem($sql_row->imagem);
		$contvo->setDataHora($sql_row->datahora);
		$contvo->setSituacao($sql_row->situacao);
		$contvo->setPublicado($sql_row->publicado);
		$contvo->setUrl($sql_row->url);

		$tags = array();
		$sql = "SELECT t1.tag FROM Tags AS t1 INNER JOIN Conteudo_Tags AS t2 ON (t1.cod_tag=t2.cod_tag) WHERE t2.cod_conteudo='".$codconteudo."'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchObject())
			$tags[] = $sql_row->tag;
		$contvo->setTags(implode('; ', $tags));

		$sql = "SELECT permitir_comentarios FROM Conteudo_Opcoes WHERE cod_conteudo='".$codconteudo."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();
		$contvo->setPermitirComentarios($sql_row->permitir_comentarios);
	}

	public function getAutoresConteudo($codconteudo) {
        $sql = "SELECT t1.*, t3.titulo, t4.*, t5.*, t6.* FROM Usuarios AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) LEFT JOIN Estados AS t5 ON (t1.cod_estado=t5.cod_estado) LEFT JOIN Cidades AS t6 ON (t1.cod_cidade=t6.cod_cidade) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='2'";
        $lista = array();
        $query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray())
    		$lista[] = $row;
        return $lista;
    }

    public function getColaboradoresConteudoRevisao($codconteudo) {
        $sql = "SELECT t1.*, t3.titulo FROM Usuarios AS t1 INNER JOIN Conteudo_Revisao AS t2 ON (t1.cod_usuario=t2.cod_colaborador) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='1'";
        $lista = array();
        $query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray())
    		$lista[] = $row;
        return $lista;
    }

    public function getColaboradorConteudo($codconteudo) {
		$sql = "SELECT t1.*, t3.titulo FROM Usuarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_usuario=t2.cod_colaborador) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='1'";
        $query = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
	}

	public function getPostadorConteudo($codconteudo) {
		$sql = "SELECT t1.*, t3.titulo FROM Usuarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_usuario=t2.cod_autor) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='2'";
        $query = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
	}

	public function getCategoriaConteudo($codconteudo) {
		$sql = "SELECT t1.* FROM Conteudo_Classificacao AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_classificacao=t2.cod_classificacao) WHERE t2.cod_conteudo='$codconteudo'";
		$query = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
	}

	public function getSegmentoConteudo($codconteudo) {
		$sql = "SELECT t1.* FROM Conteudo_Segmento AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_segmento=t2.cod_segmento) WHERE t2.cod_conteudo='$codconteudo'";
        $query = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
	}

	public function getSubAreaConteudo($codconteudo) {
		$sql = "SELECT t1.* FROM Conteudo_Segmento AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_segmento=t2.cod_subarea) WHERE t2.cod_conteudo='$codconteudo'";
        $query = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
	}

	public function getConteudoRelacionadoConteudo($codconteudo) {
        $sql = "SELECT t1.*, t3.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_Relacionados t2 ON (t1.cod_conteudo=t2.cod_conteudo2) LEFT JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) WHERE t2.cod_conteudo1='$codconteudo' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t3.tipo='4'";

       	$query = $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray($query))
            $array[] = $row;
        return $array;
    }

    public function getGrupoRelacionadoConteudo($codconteudo) {
        $sql = "SELECT t1.*, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Conteudo_Grupos t2 ON (t1.cod_usuario=t2.cod_grupo) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='3'";
       	$query = $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray($query))
            $array[] = $row;
        return $array;
    }

    public function getLicenca($cod) {
		$array = array();
		$query = $this->banco->executaQuery("SELECT * FROM Licencas WHERE cod_licenca='".$cod."' ORDER BY ordem");
        while ($row = $this->banco->fetchArray($query))
            $array[] = $row;
        return $array;
	}

	public function getListaConteudo($dados, $inicial, $mostrar) {
		$array = array();
		extract($dados);

		if (!$ajax)
			$array['link'] = "conteudo.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;formato=$formato&amp;de=$de&amp;ate=$ate";
		else
			$array['link'] = "ajax_conteudo.php?get=conteudo_relacionado&buscar=1&palavrachave=$palavrachave&buscarpor=$buscarpor&formato=$formato&situacao=$situacao&de=$de&ate=$ate&mostrar=$mostrar".($navegacao ? "&navegacao=".$navegacao : "").($relacionamento ? "&relacionamento=".$relacionamento : "").($home ? "&home=".$home : "").($evento ? "&evento=".$evento : "");

		$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";

		if ((int)$formato)
			$where .= " AND t1.cod_formato='".$formato."'";
		elseif ($home)
			$where .= " AND t1.cod_formato IN (1, 2, 3, 4, 5)";
		elseif ($evento)
			$where .= " AND t1.cod_formato IN (1, 2, 3, 4, 5, 6)";
		else
			$where .= " AND t1.cod_formato IN (1, 2, 3, 4)";

		if ($buscar) {

			if ($palavrachave && $palavrachave != 'Buscar') {
				switch($buscarpor) {
					case "titulo":
						$where .= " AND t1.titulo LIKE '%$palavrachave%'";
					break;
					case "autor":
						$arraycod = array();
						$sqla = "SELECT cod_usuario FROM Usuarios WHERE nome LIKE '%".$palavrachave."%' AND disponivel='1'";
						$querya = $this->banco->executaQuery($sqla);
						while ($row = $this->banco->fetchArray($querya))
							$arraycod[] = $row['cod_usuario'];
						$where .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Autores WHERE cod_usuario IN (".implode(',', $arraycod)."))";
					break;
					case "colaborador":
						$arraycod = array();
						$sqla = "SELECT cod_usuario FROM Usuarios WHERE nome LIKE '%".$palavrachave."%' AND disponivel='1'";
						$querya = $this->banco->executaQuery($sqla);
						while ($row = $this->banco->fetchArray($querya))
							$arraycod[] = $row['cod_usuario'];
						$where .= " AND t1.cod_colaborador IN (".implode(',', $arraycod).")";
					break;
				}
			}

			if ($situacao)
				$buscarpor = $situacao;

			switch($buscarpor) {
                case "ativo":
					$where .= " AND t1.situacao='1' AND t1.publicado='1'";
				break;
				case "inativo":
					$where .= " AND t1.situacao='0'";
				break;
				case "pendente":
					$where .= " AND t1.publicado='0'";
				break;
			}

			// buscar conteudo pra indexar a playlist
			if ($home)
				$where .= " AND t1.publicado!='2' AND publicado!='0'";

			// complemento busca da agenda
			if ($formato == 6) {
				$from = ", Agenda AS t2";
				$where .= " AND t1.cod_conteudo = t2.cod_conteudo";
				if ($palavrachave) {
					switch ($buscarpor) {
						case "local":
							$where .= " AND t2.local LIKE '%".$palavrachave."%'";
						break;
						case "valor":
							$where .= " AND t2.valor LIKE '%".$palavrachave."%'";
						break;
					}
				}
			}

			// noticias
			if ($home && (($formato == 5) || !$formato)) {
				//$where .= " AND ((t1.cod_conteudo = (SELECT t3.cod_conteudo FROM Noticias AS t3 WHERE t3.home='1' AND t3.cod_conteudo=t1.cod_conteudo) AND t1.cod_formato='5') OR t1.cod_formato!='5')";
				$where .= " AND ((t1.cod_conteudo = (SELECT t3.cod_conteudo FROM Noticias AS t3 WHERE t3.cod_conteudo=t1.cod_conteudo) AND t1.cod_formato='5') OR t1.cod_formato!='5')";
			}

			if ($de) {
				$data1 = explode('/', $de);
				if ((int)$data1[0]) {
					if (checkdate($data1[1], $data1[0], $data1[2])) {
						$datainicial = $data1[2].'-'.$data1[1].'-'.$data1[0];
					}
					if ($ate) {
						$data2 = explode('/', $ate);
	                    if (checkdate($data2[1], $data2[0], $data2[2])) {
							$datafinal = $data2[2].'-'.$data2[1].'-'.$data2[0];
						}
					}
					if ($datainicial && $datafinal && $formato != 6) {
						$where .= " AND (t1.datahora >= '$datainicial 00:00:00' AND t1.datahora <= '$datafinal 23:59:00')";
					} elseif ($formato == 6) {
						$where .= " AND (t2.data_inicial >= '$datainicial' AND t2.data_final <= '$datafinal')";
					}
				}
			}
		}

		if ($_SESSION['logado_dados']['nivel'] == 2)
			$codautor = $_SESSION['logado_dados']['cod'];
		if ($_SESSION['logado_dados']['nivel'] == 5 || $_SESSION['logado_dados']['nivel'] == 6)
			$codcolaborador = $_SESSION['logado_dados']['cod_colaborador'];

		if ($relacionamento)
			$where .= " AND t1.situacao='1' AND t1.publicado='1'";

		if ($codautor) {
			//$from = ", Conteudo_Autores AS t2";
			//$where .= " AND t1.cod_conteudo = t2.cod_conteudo AND t2.cod_usuario='".$codautor."'";
			//$where .= " AND t1.cod_autor='".$codautor."'";
			$where .= " AND (t1.cod_autor = '".$codautor."' OR t1.cod_conteudo IN (SELECT t3.cod_conteudo FROM Conteudo_Autores AS t3 WHERE t3.cod_usuario = '".$codautor."'))";
		}

		if ($codcolaborador)
			$where .= " AND (t1.cod_colaborador = '".$codcolaborador."' OR t1.cod_conteudo IN (SELECT t3.cod_conteudo FROM Conteudo_Revisao AS t3 WHERE t3.cod_colaborador = '".$codcolaborador."'))";

		$sql = "SELECT t1.cod_conteudo, t1.cod_formato, t1.titulo, t1.situacao, t1.publicado, t1.datahora, t1.cod_autor FROM Conteudo AS t1 $from $where";

		//echo $sql;

		$orderby = "t1.cod_conteudo DESC";

		if ($navegacao == 1)
			$orderby = "t1.publicado ASC, t1.cod_conteudo DESC";

		//echo "$sql ORDER BY $orderby LIMIT $inicial,$mostrar";

		$array['carregar'] = (int)$carregar;
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		//echo $sql;

		$query = $this->banco->executaQuery("$sql ORDER BY $orderby LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchArray($query)) {
			switch($row['cod_formato']) {
				case 1: $url_editar = "conteudo_publicado_texto.php?cod=".$row['cod_conteudo']; break;
                case 2: $url_editar = "conteudo_publicado_imagem.php?cod=".$row['cod_conteudo']; break;
                case 3: $url_editar = "conteudo_publicado_audio.php?cod=".$row['cod_conteudo']; break;
                case 4: $url_editar = "conteudo_publicado_video.php?cod=".$row['cod_conteudo']; break;
                case 5: $url_editar = "noticia_publicado.php?cod=".$row['cod_conteudo']; break;
				case 6: $url_editar = "agenda_publicado.php?cod=".$row['cod_conteudo']; break;
			}
			switch($row['cod_formato']) {
				case 1: $url_editar_2 = "conteudo_edicao_texto.php?cod=".$row['cod_conteudo']; break;
                case 2: $url_editar_2 = "conteudo_edicao_imagem.php?cod=".$row['cod_conteudo']; break;
                case 3: $url_editar_2 = "conteudo_edicao_audio.php?cod=".$row['cod_conteudo']; break;
                case 4: $url_editar_2 = "conteudo_edicao_video.php?cod=".$row['cod_conteudo']; break;
                case 5: $url_editar_2 = "noticia_edicao.php?cod=".$row['cod_conteudo']; break;
				case 6: $url_editar_2 = "agenda_edicao.php?cod=".$row['cod_conteudo']; break;
			}
			switch($row['situacao']) {
                case 0:	$situacao = '<span class="inativo" title="Inativo">Inativo</span>'; break;
                case 1:	$situacao = '<span class="ativo" title="Ativo">Ativo</span>'; break;
			}
			switch($row['cod_formato']) {
				case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
				case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
				case 3: $formato = '<span class="audio" title="&Aacute;udio">&Aacute;udio</span>'; break;
				case 4: $formato = '<span class="video" title="V&iacute;deo">V&iacute;deo</span>'; break;
				case 5: $formato = '<span class="noticia" title="Not&iacute;cia">Jornal</span>'; break;
				case 6: $formato = '<span class="eventos" title="Eventos">Eventos</span>'; break;
			}

			if (!$row['publicado'])
				$situacao = '<span class="pendente" title="Pendente">Pendente</span>';

			if ($row['publicado'] == 2)
				$situacao = '<span class="rejeitado" title="Rejeitado">Rejeitado</span>';

			$arrayAutores = array();
			foreach ($this->getAutoresConteudo($row['cod_conteudo']) as $value)
				$arrayAutores[] = htmlentities($value['nome']);

			$dados_colab = $this->getColaboradorConteudo($row['cod_conteudo']);

			if ($formato == 6) { //agenda
				include_once('AgendaDAO.php');
				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AgendaVO.php");
				$agdao = new AgendaDAO;
				$agvo = $agdao->getAgendaVO($row['cod_conteudo']);
			}

			$array[] = array(
				'cod' 		=> $row['cod_conteudo'],
				'titulo'	=> htmlentities($row['titulo']),
				'situacao' 	=> $situacao,
				'formato' 	=> $formato,
				'url' 		=> $url_editar,
				'url_editar' => $url_editar_2,
				'autores'	=> implode(', ', $arrayAutores),
				'datahora' 	=> $row['datahora'],
				'colaborador' => $dados_colab['nome'],
				'cod_autor_colaborador' => $row['cod_autor'],
			);

			if ($formato == 6) { //agenda
				$num_indice = count($array) - 4;
				$array[$num_indice]['agenda_local'] = htmlentities($agvo->getLocal());
				$ag_data1 = date('d/m/y', strtotime($agvo->getDataInicial()));
				$ag_data2 = date('d/m/y', strtotime($agvo->getDataFinal()));
				$agenda_periodo = $ag_data1;
				if ($ag_data1 != $ag_data2) {
					$agenda_periodo = $ag_data1;
					if ($agvo->getDataFinal() != '0000-00-00')
						$agenda_periodo .= ' - '.$ag_data2;
				}
				$array[$num_indice]['agenda_periodo'] = $agenda_periodo;
			}
		}
		return $array;
	}

	public function executaAcoes($acao, $codconteudo) {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					if (count($codconteudo))
						$this->banco->executaQuery("UPDATE Conteudo SET excluido='1' WHERE cod_conteudo IN (".implode(',', $codconteudo).")");
				break;
                case 2: // ativar
					if (count($codconteudo))
						$this->banco->executaQuery("UPDATE Conteudo SET situacao='1' WHERE cod_conteudo IN (".implode(',', $codconteudo).")");
				break;
                case 3: // desativar
					if (count($codconteudo))
						$this->banco->executaQuery("UPDATE Conteudo SET situacao='0' WHERE cod_conteudo IN (".implode(',', $codconteudo).")");
				break;
			}
		}
	}

	public function getRandomico($codconteudo) {
		$sql = "SELECT randomico FROM Conteudo WHERE cod_conteudo='".$codconteudo."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();
		return $sql_row["randomico"];
	}

	public function getUltimasDoFormato($codformato, $total = 1) {
		$lista = array();
		//TODO:ajustar

		//if ($codformato == 4)
		//	$comp = " AND (IF ((SELECT COUNT(1) FROM Videos WHERE cod_conteudo = t1.cod_conteudo AND arquivo!=''), t1.cod_conteudo IN (SELECT cod_video FROM Videos_Conversao WHERE cod_video=t1.cod_conteudo AND status=1), 1=1))";

		$sql = "SELECT t1.cod_conteudo FROM Conteudo AS t1 WHERE t1.cod_formato = '".$codformato."' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' and t1.cod_colaborador != 0 and t1.cod_sistema = '".ConfigVO::getCodSistema()."' ".$comp." order by t1.datahora desc limit ".$total.";";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista[] = (int)$sql_row["cod_conteudo"];
		return $lista;
	}

	public function getEstatisticas($codconteudo) {
		$sql = "select num_recomendacoes, num_acessos from Conteudo_Estatisticas where cod_conteudo = '".$codconteudo."';";
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($sql_result);
	}

	public function getMaisRecomendadasDoFormato($codformato, $qtd = 3) {
		$lista_foto = array();
		$sql_formato = "SELECT C.cod_conteudo, (CE.num_recomendacoes / TIMESTAMPDIFF(DAY, C.data_cadastro, NOW())) AS ordenacao FROM Conteudo C, Conteudo_Estatisticas CE WHERE C.cod_formato = '".$codformato."' AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' and C.cod_conteudo = CE.cod_conteudo %s order by ordenacao desc, CE.num_recomendacoes desc, C.datahora desc limit %s;";

		//$sql = "SELECT C.cod_conteudo, (CE.num_acessos / TIMESTAMPDIFF(DAY, C.data_cadastro, NOW())) AS ordenacao FROM Conteudo C, Conteudo_Estatisticas CE WHERE C.cod_formato = '".$codformato."' AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' and C.cod_conteudo = CE.cod_conteudo order by ordenacao desc, C.datahora desc limit ".$indice.", ".$limite.";";

		//echo sprintf($sql_formato, "", 3);

		$sql_result = $this->banco->executaQuery(sprintf($sql_formato, "", $qtd));
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_foto[] = (int)$sql_row["cod_conteudo"];

		$lista_outras = array();
		$sql_result = $this->banco->executaQuery(sprintf($sql_formato, " and C.cod_conteudo not in ('".implode("', '", $lista_foto)."')", 4));
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_outras[] = (int)$sql_row["cod_conteudo"];

		return array(0 => $lista_foto, 1 => $lista_outras);
	}

	public function getMaisAcessadasDoFormato($codformato, $indice = 0, $limite = 4) {
		$lista = array();
		//$sql = "SELECT C.cod_conteudo FROM Conteudo C, Conteudo_Estatisticas CE WHERE C.cod_formato = '".$codformato."' AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' and C.cod_conteudo = CE.cod_conteudo order by CE.num_acessos desc, C.datahora desc limit ".$indice.", ".$limite.";";

		$sql = "SELECT C.cod_conteudo, (CE.num_acessos / TIMESTAMPDIFF(DAY, C.data_cadastro, NOW())) AS ordenacao FROM Conteudo C, Conteudo_Estatisticas CE WHERE C.cod_formato = '".$codformato."' AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' and C.cod_conteudo = CE.cod_conteudo order by ordenacao desc, C.datahora desc limit ".$indice.", ".$limite.";";

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista[] = (int)$sql_row["cod_conteudo"];
		return $lista;
	}

	public function getTotalAcessadasDoFormato($codformato) {
		$sql = "SELECT count(C.cod_conteudo) FROM Conteudo C, Conteudo_Estatisticas CE WHERE C.cod_formato = '".$codformato."' AND C.excluido='0' AND C.publicado='1' AND C.situacao='1' and C.cod_sistema = '".ConfigVO::getCodSistema()."' and C.cod_conteudo = CE.cod_conteudo;";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return (int)$sql_row[0];
	}

	public function getImagemFormato($cod) {
		$sql = "select cod_formato, imagem from Conteudo where cod_conteudo = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if ($sql_row["cod_formato"] == 2) {
			$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$cod."' and A.cod_imagem_capa = I.cod_imagem";
			$sql_result2 = $this->banco->executaQuery($sql);
			$sql_row2 = $this->banco->fetchArray($sql_result2);
			return array("cod_formato" => 2, "imagem" => $sql_row2["imagem"]);
		}
		else
			return $sql_row;
	}

	public function getMaisAcessados($ordem, $codcolaborador='', $codautor='', $codgrupo='', $cod_formato='', $limit='') {
	    if ($ordem == 'recentes') {
            $orderby = ' t1.cod_conteudo';
        } elseif ($ordem == 'votos') {
            $orderby = ' t2.num_recomendacoes';
        } elseif ($ordem == 'acessos') {
            $orderby = ' t2.num_acessos';
        }

        if (!$cod_formato)
            $formato = "(t1.cod_formato != '5' AND t1.cod_formato != '6' AND t1.cod_formato != '7')";
        else
            $formato = "t1.cod_formato='$cod_formato'";

        if (!$limit)
            $limit = '4';

        if ($codcolaborador)
            $colaborador = " AND t1.cod_colaborador='$codcolaborador'";

        if ($codautor) {
            //$sql_extra = "LEFT JOIN Conteudo_Autores AS t5 ON (t1.cod_conteudo=t5.cod_conteudo)";
            $sql_extra = "LEFT JOIN Conteudo_Autores_Ficha AS t5 ON (t1.cod_conteudo=t5.cod_conteudo)";
            $where = "AND (t5.cod_usuario='$codautor'";

            // adicional
            $sql = "SELECT t1.cod_conteudo FROM Conteudo_Autores AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_usuario='".$codautor."' AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=t1.cod_conteudo) AND t2.excluido='0' AND t2.situacao='1' AND t2.publicado='1'";
        	$query = $this->banco->executaQuery($sql);
            $arrayCod = array();
        	while ($sql_row = $this->banco->fetchArray($sql_result)) {
		    	$arrayCod[$sql_row['cod_conteudo']] = $sql_row['cod_conteudo'];
			}

			//print_r($arrayCod);

			if (count($arrayCod))
				$where .= " OR t1.cod_conteudo IN (".implode(', ', $arrayCod).")";

			$where .= ")";

        }

        if ($codgrupo) {
            $sql_extra = "LEFT JOIN Conteudo_Grupos AS t5 ON (t1.cod_conteudo=t5.cod_conteudo)";
            $where = "AND t5.cod_grupo='$codgrupo'";
        }

        $sql = "SELECT t1.cod_conteudo, t1.cod_formato, t1.titulo, t1.data_cadastro, t1.datahora, t1.descricao, t1.imagem, t1.cod_colaborador, t1.cod_autor, t1.cod_segmento, t2.num_acessos, t2.num_recomendacoes, t3.titulo AS url FROM Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) $sql_extra WHERE t1.cod_conteudo!='0' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t3.tipo='4' $colaborador $where AND $formato ORDER BY $orderby DESC LIMIT $limit";

        //echo $sql;

        $query = $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray($query)) {

            if ($row['cod_formato'] == 2) {
                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_imagem_capa=t2.cod_imagem) WHERE t1.cod_conteudo='$row[cod_conteudo]' LIMIT 1";
                $query1 = $this->banco->executaQuery($sql1);
                $row1 = $this->banco->fetchArray($query1);
                $row['imagem_album'] = $row1['imagem'];
            }

            $sqlcolab = "SELECT t1.nome, t2.titulo AS url_colaborador FROM Usuarios AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_usuario=t2.cod_item) WHERE t1.cod_usuario='$row[cod_colaborador]' AND t2.tipo='1'";
            $querycolab = $this->banco->executaQuery($sqlcolab);
            $rowcolab = $this->banco->fetchArray($querycolab);
            $row['colaborador'] = $rowcolab['nome'];
            $row['url_colaborador'] = $rowcolab['url_colaborador'];
            $array[] = $row;
        }
        return $array;
    }

    public function getUrl($cod) {
		$sql = "select titulo from Urls where cod_item = '".$cod."' and tipo = 4;";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row["titulo"];
	}

	public function getDadosColaboradorConteudo($codconteudo) {
		$sql = "SELECT t1.*, t3.titulo AS url FROM Usuarios AS t1 LEFT JOIN Conteudo AS t2 ON (t1.cod_usuario=t2.cod_colaborador) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_conteudo='".$codconteudo."' AND t3.tipo='1';";
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($sql_result);
	}

	public function getTagsPopulares($limite, $ordem='total desc') {
		$lista_tags = array();
		$sql = "select count(T.cod_tag) as total, T.cod_tag, T.tag from Tags T, Conteudo_Tags CT, Conteudo C where T.cod_tag = CT.cod_tag and CT.cod_conteudo = C.cod_conteudo and C.cod_sistema = '".ConfigVO::getCodSistema()."' group by T.cod_tag order by ".$ordem." limit ".$limite.";";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista_tags[] = $sql_row;
		return $lista_tags;
	}

	public function getBuscaTag($tag) {
		$lista_tags = array();
		if ($tag) {
			$sql = "SELECT tag FROM Tags WHERE tag LIKE '".trim($tag)."%' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY tag";
			$query = $this->banco->executaQuery($sql);
			while ($sql_row = $this->banco->fetchArray($query))
				$lista_tags[] = $sql_row;
		}
		return $lista_tags;
	}

	public function getAutoresFicha($codconteudo) {
		$lista = array();
		$sql = "select cod_usuario, cod_atividade from Conteudo_Autores_Ficha where cod_conteudo = '".$codconteudo."' ORDER BY cod_increment ASC;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista[] = $sql_row;
		return $lista;
	}

	public function getAutoresFichaTecnicaConteudo($codconteudo) {
		$sql = "SELECT t1.cod_usuario, t1.nome, t5.titulo AS url FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) INNER JOIN Urls AS t5 ON (t1.cod_usuario=t5.cod_item) WHERE t2.cod_conteudo='".$codconteudo."' AND t5.tipo='2' AND t5.cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY t2.cod_increment ASC";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query1))
    		$lista[] = $row;
			//$lista .= (($lista != '') ? ', ' : ' ').$row['nome'];
    	//}
    	return $lista;
    }

    public function getAutoresFichaTecnicaCompletaConteudo($codconteudo) {
    	$sql = "SELECT t1.cod_usuario, t1.nome, t3.atividade, t5.titulo FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) LEFT JOIN Usuarios_Atividades AS t3 ON (t2.cod_atividade=t3.cod_atividade) LEFT JOIN Urls AS t5 ON (t1.cod_usuario=t5.cod_item) WHERE t2.cod_conteudo='".$codconteudo."' AND t5.tipo='2' AND t5.titulo != '' AND t5.cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY t2.cod_increment ASC";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query1))
    		$lista[] = $row;
			//$lista .= (($lista != '') ? ', ' : ' ').$row['nome'];
    	//}
    	return $lista;
    }

}