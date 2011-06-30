<?php
include_once('ConteudoDAO.php');

class NoticiaDAO extends ConteudoDAO {

	public function cadastrar(&$notvo) {
		$codconteudo = $this->cadastrarConteudo($notvo);

		if ($codconteudo) {
			$sql = "insert into Noticias (cod_conteudo, subtitulo, assinatura, foto_credito, foto_legenda, home) values (".$codconteudo.", '".$notvo->getSubtitulo()."', '".$notvo->getAssinatura()."', '".$notvo->getFotoCredito()."', '".$notvo->getFotoLegenda()."', '".$notvo->getHome()."');";
			$sql_result = $this->banco->executaQuery($sql);

			if ($notvo->getHomeTitulo() || $notvo->getHomeResumo() || $notvo->getHomeFoto() || $notvo->getHomeFotoCredito() || $notvo->getHomeFotoLegenda()) {
				$sql = "insert into Home_Noticias (cod_conteudo, titulo, resumo, foto_credito, foto_legenda) values ('".$codconteudo."', '".$notvo->getHomeTitulo()."', '".$notvo->getHomeResumo()."', '".$notvo->getHomeFotoCredito()."', '".$notvo->getHomeFotoLegenda()."');";
				$sql_result = $this->banco->executaQuery($sql);
			}
		}

		$sql = "update Estatistica_Colaborador set quant_noticias = quant_noticias + 1 where cod_colaborador = '".$notvo->getCodColaborador()."';";
		$sql_result = $this->banco->executaQuery($sql);

		return $codconteudo;
	}

	public function atualizar(&$notvo) {
		$this->atualizarConteudo($notvo);

		$sql = "update Noticias set subtitulo = '".$notvo->getSubtitulo()."', assinatura = '".$notvo->getAssinatura()."', foto_credito = '".$notvo->getFotoCredito()."', foto_legenda = '".$notvo->getFotoLegenda()."', home = '".$notvo->getHome()."' where cod_conteudo = '".$notvo->getCodConteudo()."';";
		$sql_result = $this->banco->executaQuery($sql);

		$this->banco->sql_update('Conteudo', array('datahora' => $notvo->getDataHora()), "cod_conteudo='".$notvo->getCodConteudo()."'");

		if ($notvo->getHomeTitulo() || $notvo->getHomeResumo() || $notvo->getHomeFoto() || $notvo->getHomeFotoCredito() || $notvo->getHomeFotoLegenda()) {
			$sql = "select cod_conteudo from Home_Noticias where cod_conteudo = ".$notvo->getCodConteudo().";";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			if ($sql_row["cod_conteudo"]) {
				$sql = "update Home_Noticias set titulo = '".$notvo->getHomeTitulo()."', resumo = '".$notvo->getHomeResumo()."', foto_credito = '".$notvo->getHomeFotoCredito()."', foto_legenda = '".$notvo->getHomeFotoLegenda()."' where cod_conteudo = '".$notvo->getCodConteudo()."';";
				$sql_result = $this->banco->executaQuery($sql);
			}
			else {
				$sql = "insert into Home_Noticias (cod_conteudo, titulo, resumo, foto_credito, foto_legenda) values ('".$notvo->getCodConteudo()."', '".$notvo->getHomeTitulo()."', '".$notvo->getHomeResumo()."', '".$notvo->getHomeFotoCredito()."', '".$notvo->getHomeFotoLegenda()."');";
				$sql_result = $this->banco->executaQuery($sql);
			}
		}
	}

	public function atualizarFotoHome($nomearquivo, $codconteudo) {
		$sql = "select cod_conteudo from Home_Noticias where cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if ($sql_row["cod_conteudo"]) {
			$sql = "update Home_Noticias set foto = '".$nomearquivo."' where cod_conteudo = '".$codconteudo."';";
			$sql_result = $this->banco->executaQuery($sql);
		}
		else {
			$sql = "insert into Home_Noticias (cod_conteudo, foto) values ('".$codconteudo."', '".$nomearquivo."');";
			$sql_result = $this->banco->executaQuery($sql);
		}
	}

	public function getNoticiaVO(&$codconteudo) {
		$notvo = new NoticiaVO;
		$this->getConteudoVO($codconteudo, $notvo);

		$sql = "select * from Noticias where cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);

		$notvo->setSubtitulo($sql_row["subtitulo"]);
		$notvo->setAssinatura($sql_row["assinatura"]);
		$notvo->setFotoCredito($sql_row["foto_credito"]);
		$notvo->setFotoLegenda($sql_row["foto_legenda"]);
		$notvo->setHome($sql_row["home"]);
		$notvo->setCodColaborador($sql_row["cod_colaborador"]);

		$sql = "select * from Home_Noticias where cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if ($sql_row["cod_conteudo"]) {
			$notvo->setHomeTitulo($sql_row["titulo"]);
			$notvo->setHomeResumo($sql_row["resumo"]);
			$notvo->setHomeFoto($sql_row["foto"]);
			$notvo->setHomeFotoCredito($sql_row["foto_credito"]);
			$notvo->setHomeFotoLegenda($sql_row["foto_legenda"]);
		}

		return $notvo;
	}

	public function getNoticiaDados($cod) {
		$select = "SELECT * FROM Noticias WHERE cod_conteudo='$cod'";
		return $this->banco->fetchArray($this->banco->executaQuery($select));
	}

	public function getNoticiaDadosPDF($cod) {
		$select = "SELECT t1.*, t2.* FROM Noticias AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='$cod'";
		return $this->banco->fetchArray($this->banco->executaQuery($select));
	}

	public function excluirNoticia($cod) {
		$sql = "update Conteudo set excluido = 1 where cod_conteudo = ".$cod." and cod_formato = 5;";
		$this->banco->executaQuery($sql);
	}

	public function excluirImagemHome($cod) {
		$sql = "update Home_Noticias set foto = '' where cod_noticia = '".$cod."';";
		$this->banco->executaQuery($sql);
	}

	public function buscarNoticias(&$dados_form, $pagina, $intervalo, $gettotal = false) {

		$sql_where = "CON.excluido = 0 and CON.cod_formato = 5 and CON.cod_conteudo = N.cod_conteudo and CON.cod_colaborador = COL.cod_colaborador";

		if ($dados_form["palavrachave"]) {
			if ($dados_form["campotexto"] == 1)
				$sql_where .= " and CON.titulo like '%".$dados_form["palavrachave"]."%'";
			elseif ($dados_form["campotexto"] == 2)
				$sql_where .= " and COL.nome like '%".$dados_form["palavrachave"]."%'";
			else
				$sql_where .= " and (CON.titulo like '%".$dados_form["palavrachave"]."%' or COL.nome like '%".$dados_form["palavrachave"]."%')";
		}
		if ($dados_form["situacao"] == 1)
			$sql_where .= " and CON.situacao = 1";
		elseif ($dados_form["situacao"] == 2)
			$sql_where .= " and CON.situacao = 2";
		elseif ($dados_form["situacao"] == 3)
			$sql_where .= " and CON.situacao = 0";

		if ($dados_form["data1"])
			$sql_where .= " and CON.datahora >= '".substr($dados_form["data1"], 6, 4)."-".substr($dados_form["data1"], 3, 2)."-".substr($dados_form["data1"], 0, 2)." 00:00:00'";
		if ($dados_form["data2"])
			$sql_where .= " and CON.datahora <= '".substr($dados_form["data2"], 6, 4)."-".substr($dados_form["data2"], 3, 2)."-".substr($dados_form["data2"], 0, 2)." 23:59:59'";

		if (!$gettotal) {
			if (!$pagina)
				$pagina = 1;
			$indice = ($pagina - 1) * $intervalo;
			$lista_noticias = array();
			$sql = "select CON.cod_conteudo, CON.titulo, CON.datahora, COL.nome, CON.situacao, N.secao from Conteudo CON, Noticias N, Ponto_Colaborador COL where ".$sql_where." order by CON.datahora desc limit ".$indice.", ".$intervalo.";";
			$sql_result = $this->banco->executaQuery($sql);
			while ($sql_row = $this->banco->fetchArray($sql_result))
				$lista_noticias[] = $sql_row;
			return $lista_noticias;
		}
		else {
			$sql = "select count(CON.cod_conteudo) from Conteudo CON, Noticias N, Ponto_Colaborador COL where ".$sql_where.";";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			return (int)$sql_row[0];
		}
	}

	public function apagarNoticias($lista) {
		$sql = "update Conteudo set excluido = 1 where cod_conteudo in ('".implode("', '", $lista)."') and cod_formato = 5;";
		$this->banco->executaQuery($sql);
		return true;
	}

	public function ativarNoticias($lista) {
		$sql = "update Conteudo set situacao = 1 where cod_conteudo in ('".implode("', '", $lista)."') and cod_formato = 5;";
		$this->banco->executaQuery($sql);
		return true;
	}

	public function desativarNoticias($lista) {
		$sql = "update Conteudo set situacao = 2 where cod_conteudo in ('".implode("', '", $lista)."') and cod_formato = 5;";
		$this->banco->executaQuery($sql);
		return true;
	}

	public function mudarSecaoNoticias($lista, $secao) {
		$sql = "update Noticias set secao='$secao' where cod_conteudo in ('".implode("', '", $lista)."');";
		$this->banco->executaQuery($sql);
	}

	// ===================================================================
	// portal

    public function getNoticiaConteudo($cod_colaborador) {
	    $select = "SELECT t1.cod_conteudo, t1.titulo, t1.datahora, t2.titulo AS url FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.cod_colaborador='".$cod_colaborador."' AND t1.excluido='0' AND t1.cod_formato='5' and t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t2.tipo='4' ORDER BY t1.cod_conteudo DESC LIMIT 2";
		$array = array();
		$this->banco->executaQuery($select);
		while ($row = $this->banco->fetchArray())
		     $array[] = $row;
        return $array;
	}

	public function getGroupNoticias($inicial, $mostrar, $codconteudo = 0) {
		$arrayDatas = array();

		$sql = "SELECT t1.cod_conteudo, t1.datahora, t1.titulo, t2.titulo AS url FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.excluido='0' AND t1.datahora <= '".date('Y-m-d H:i')."' AND t1.cod_formato='5' AND t2.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema();
		if ($codconteudo)
			$sql .= " AND t1.cod_conteudo != '".$codconteudo."'";

		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));
		$query = $this->banco->executaQuery("$sql ORDER BY t1.datahora DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchArray()) {
    		$array[] = array(
    			'cod'       => $row['cod_conteudo'],
    			'titulo'    => $row['titulo'],
    			'url'    	=> $row['url'],
    			'periodo'   => date('d.m.Y - H:i', strtotime($row['datahora']))
    		);
    	}

        return $array;
	}

	public function getGroupNoticiasDatas($data_anterior) {
		$arrayDatas = array();
		$noticias = array();

		$sql_formato = "SELECT %s FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.excluido='0' AND t1.datahora <= '".date('Y-m-d H:i')."' AND t1.cod_formato='5' AND t2.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema()." %s";

		$campos = 't1.cod_conteudo, t1.datahora, t1.titulo, t2.titulo AS url';

		if (!$data_anterior) {
			$query = $this->banco->executaQuery(sprintf($sql_formato, 'max(datahora)', ''));
			$row = $this->banco->fetchArray($query);
			$data_anterior = substr($row[0], 0, 10);
		}

		$noticias['total'] = 1;

		$sql1 = sprintf($sql_formato, $campos, "and t1.datahora like '".$data_anterior."%' ORDER BY t1.datahora DESC");
		$result = $this->banco->executaQuery($sql1);
		while ($row = $this->banco->fetchArray()) {
			$noticias[$data_anterior][] = array(
    			'cod'       => $row['cod_conteudo'],
    			'titulo'    => $row['titulo'],
    			'url'    	=> $row['url'],
    			'periodo'   => date('d.m.Y - H:i', strtotime($row['datahora']))
    		);
		}

		$query = $this->banco->executaQuery(sprintf($sql_formato, 'max(datahora)', " and t1.datahora < '".$data_anterior." 00:00:00'"));
		$row = $this->banco->fetchArray($query);
		$data_anterior = substr($row[0], 0, 10);

		$sql2 = sprintf($sql_formato, $campos, "and t1.datahora like '".$data_anterior."%' ORDER BY t1.datahora DESC");
		$result = $this->banco->executaQuery($sql2);
		while ($row = $this->banco->fetchArray()) {
			$noticias[$data_anterior][] = array(
    			'cod'       => $row['cod_conteudo'],
    			'titulo'    => $row['titulo'],
    			'url'    	=> $row['url'],
    			'periodo'   => date('d.m.Y - H:i', strtotime($row['datahora']))
    		);
		}

		return $noticias;
	}

	public function getPaginasNoticias() {
		$lista = array(0 => '');
		$sql = "SELECT SUBSTRING(t1.datahora, 1, 10) AS datas FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.excluido='0' AND t1.datahora <= '".date('Y-m-d H:i')."' AND t1.cod_formato='5' AND t2.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema()." group by  datas;";
		$result = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray())
			$listatemp[] = $row[0];
		$listatemp = array_reverse($listatemp);
		foreach ($listatemp as $key => $data) {
			if (!($key % 2))
				$lista[] = $data;
		}
		return $lista;
	}

	public function getUltimasNoticias($lista_ignorar, $total, $secao = 0) {
		$ultimas = array();
		$sql = "select CON.cod_conteudo, CON.titulo, CON.datahora, U.titulo as url, N.subtitulo from Conteudo CON, Noticias N, Urls U where CON.cod_conteudo not in ('".implode("','", $lista_ignorar)."') and CON.cod_conteudo = N.cod_conteudo and CON.cod_formato = 5 AND CON.datahora <= '".date('Y-m-d H:i')."' and CON.cod_conteudo = U.cod_item and U.tipo = 4 and CON.Excluido = 0 and CON.cod_sistema = ".ConfigVO::getCodSistema();
		if ($secao)
			$sql .= " and N.secao = '".$secao."'";
		$sql .= " order by CON.datahora desc limit ".$total.";";
		$sql_result = $this->banco->executaQuery($sql);

		while ($sql_row = $this->banco->fetchArray($sql_result))
			$ultimas[] = $sql_row;
		return $ultimas;

	}

	public function getUltimaNoticiaComImagem() {
		$sql = "select CON.cod_conteudo, CON.titulo, CON.datahora, CON.imagem, U.titulo as url, N.subtitulo from Conteudo CON, Noticias N, Urls U where CON.cod_conteudo = N.cod_conteudo and CON.cod_formato = 5 AND CON.datahora <= '".date('Y-m-d H:i')."' and CON.cod_conteudo = U.cod_item and U.tipo = 4 and CON.imagem!='' and CON.Excluido = 0 and CON.cod_sistema = ".ConfigVO::getCodSistema()." order by CON.datahora desc limit 1";
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($sql_result);
	}

	public function getNoticiaEmail($codnoticia) {
		$sql = "select CON.*, N.* from Conteudo CON, Noticias N where CON.cod_conteudo = '".$codnoticia."' and CON.cod_conteudo = N.cod_conteudo and CON.cod_formato = 5 and CON.Excluido = 0";
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($sql_result);
	}
}