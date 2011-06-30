<?php
include_once("ConteudoDAO.php");
//include_once("inc_interjornal/classes/DataHora.kmf");

class AgendaDAO extends ConteudoDAO {

	public function cadastrar(&$agevo) {
	    $codconteudo = $this->cadastrarConteudo($agevo);
        $sql = "INSERT INTO Agenda (cod_conteudo, data_inicial, data_final, hora_inicial, hora_final, local, endereco, cidade, telefone, valor, site) VALUES ('".$codconteudo."', '".$agevo->getDataInicial()."', '".$agevo->getDataFinal()."', '".$agevo->getHoraInicial()."', '".$agevo->getHoraFinal()."', '".$agevo->getLocal()."', '".$agevo->getEndereco()."', '".$agevo->getCidade()."', '".$agevo->getTelefone()."', '".$agevo->getValor()."', '".$agevo->getSite()."')";
		$this->banco->executaQuery($sql);
		return $codconteudo;
	}

	public function atualizar(&$agevo) {
	    $this->atualizarConteudo($agevo);
		$sql = "UPDATE Agenda SET data_inicial = '".$agevo->getDataInicial()."', data_final = '".$agevo->getDataFinal()."', hora_inicial = '".$agevo->getHoraInicial()."', hora_final = '".$agevo->getHoraFinal()."', local = '".$agevo->getLocal()."', endereco = '".$agevo->getEndereco()."', cidade = '".$agevo->getCidade()."', telefone = '".$agevo->getTelefone()."', valor = '".$agevo->getValor()."', site = '".$agevo->getSite()."' WHERE cod_conteudo = '".$agevo->getCodConteudo()."'";
		$this->banco->executaQuery($sql);
	}


	public function getAgendaVO(&$codconteudo) {
	    $agevo = new AgendaVO;
        $this->getConteudoVO($codconteudo, $agevo);

		$sql = "select * from Agenda where cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();

		$agevo->setDataInicial($sql_row["data_inicial"]);
		$agevo->setDataFinal($sql_row["data_final"]);
		$agevo->setHoraInicial($sql_row["hora_inicial"]);
		$agevo->setHoraFinal($sql_row["hora_final"]);
		$agevo->setLocal($sql_row["local"]);
		$agevo->setEndereco($sql_row["endereco"]);
		$agevo->setCidade($sql_row["cidade"]);
		$agevo->setTelefone($sql_row["telefone"]);
		$agevo->setValor($sql_row["valor"]);
		$agevo->setSite($sql_row["site"]);
		return $agevo;
	}

    public function getListaAgenda($dados, $inicial, $mostrar) {
		$array = array();
		extract($dados);

		$array['link'] = "agenda.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;de=$de&amp;ate=$ate";
		$where = "WHERE t2.Excluido='0' and t1.cod_sistema = ".ConfigVO::getCodSistema();

		if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6))
		    $where .= " AND cod_colaborador='".$_SESSION['logado_dados']['cod_colaborador']."'";

		if ($buscar) {
			if ($palavrachave) {
				switch($buscarpor) {
					case "titulo":
						$where .= " AND t1.titulo LIKE '%$palavrachave%'";
					break;
                    case "local":
						$where .= " AND t1.local LIKE '%$palavrachave%'";
					break;
                    case "horario":
						$hora = (int)$palavrachave;
						$palavrachave = ($hora < 10) ? "0$hora" : $hora;
						$where .= " AND (t1.hora_inicial LIKE '".sprintf('%2s:', $palavrachave) . "%' OR t1.hora_final LIKE '".sprintf('%2s:', $palavrachave) . "%')";
					break;
                    case "valor":
						$where .= " AND t1.valor='%$palavrachave%'";
					break;
				}
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
					$where .= " AND (t1.data_inicial >= '$datainicial' AND t1.data_final <= '$datafinal')";
			}
		}

		$sql = "SELECT t1.*, t2.titulo FROM Agenda AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) $where";
		$array['total'] = $this->banco->numRows($this->banco->executaQuery("$sql"));
		$query = $this->banco->executaQuery("$sql ORDER BY t1.cod_conteudo DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchArray()) {
			$array[] = array(
				'cod' => $row['cod_conteudo'],
				'titulo' => $row['titulo'],
				'local' => $row['local'],
				'periodo' => date('d/m/y', strtotime($row['data_inicial'])) . ($row['data_final'] != '0000-00-00' ? ' - ' . date('d/m/Y', strtotime($row['data_final'])) : '')
			);
		}
		return $array;
	}

	public function executaAcoes($acao, $codagenda='') {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					$sql = "UPDATE Conteudo SET excluido='1' WHERE cod_conteudo IN ('".implode("', '", $codagenda)."') AND cod_formato = '6'";
		            $this->banco->executaQuery($sql);
				break;
			}
		}
		return true;
	}

	// ===================================================================
	// portal
	public function getListaAgendaPortal($get, $inicial, $mostrar, $home=false) {

	    $array = array();
	    $where = "WHERE t1.cod_formato = 6 and t1.excluido='0' and t1.cod_sistema = ".ConfigVO::getCodSistema();

        if ($get['somes']) {
            $where .= " and t2.data_inicial like '".substr($get['somes'], 0, 4)."-".substr($get['somes'], 4, 2)."%'";
        } elseif ($get['dia']) {
            $where .= " and t2.data_inicial = '".$get['dia']."'";
        } else {
            $inthoje = date('N');
			$diahoje = date('d');
			
			$iniciosemana = ($diahoje - $inthoje);
			$fimsemana = ($diahoje + (7 - $inthoje)) - 1;
			
			$inicio = date('Y-m-d', mktime(0, 0, 0, date('m'), $iniciosemana, date('Y')));
			$fim = date('Y-m-d', mktime(0, 0, 0, date('m'), $fimsemana, date('Y')));
			
			if ($home)
				$where .= " and (t2.data_inicial >= '".$inicio."' and t2.data_final <= '".$fim."')";
			else
				$where .= " and (t2.data_inicial >= '".date('Y-m-d')."' /*and t2.data_final <= '".date('Y-m-d')."') or t2.data_inicial like '".date('Y-m-')."%')*/)";
			//$where .= " and (t2.data_inicial like '".date('Y-m-')."%' and t2.data_final >= '".date('Y-m-d')."')";
        }

        $sql = "SELECT t1.*, t2.*, t3.titulo AS url_agenda FROM Conteudo AS t1 INNER JOIN Agenda AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) AND t3.tipo='4' $where";

        $array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));
        $query = $this->banco->executaQuery("$sql ORDER BY t2.data_inicial, t2.hora_inicial LIMIT $inicial,$mostrar");

        while ($row = $this->banco->fetchArray($query))
            $array[] = $row;
        return $array;
    }

    public function ExisteAgenda($data, $codusuario) {
		$sql = "select count(t1.cod_conteudo) from Agenda AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t2.excluido='0' ".Util::iif($codusuario, "AND t2.cod_colaborador='".$codusuario."'")." AND t1.data_inicial='".$data."' and t2.cod_sistema = ".ConfigVO::getCodSistema().";";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
		return (int)$row[0];
	}
	
	public function getDadosAgenda($codconteudo) {
    	$sql = "SELECT t1.*, t2.*, t5.nome AS colaborador, t9.titulo AS url_colaborador FROM Conteudo AS t1 INNER JOIN Agenda AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t5 ON (t1.cod_colaborador=t5.cod_usuario) LEFT JOIN Urls AS t9 ON (t1.cod_colaborador=t9.cod_item) WHERE t1.cod_conteudo='".$codconteudo."' AND t5.cod_tipo = 1 AND t9.tipo = 1";
    	$query = $this->banco->executaQuery($sql);
    	return $this->banco->fetchArray($query);
    }

}