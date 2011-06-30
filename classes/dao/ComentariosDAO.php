<?php
include_once("ConexaoDB.php");

class ComentariosDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getComentarios($codconteudo) {
        $sql = "SELECT * FROM Conteudo_Comentarios WHERE cod_conteudo='$codconteudo' AND disponivel='1' ORDER BY data_cadastro";
        $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray())
            $array[] = $row;
        return $array;
    }

	public function cadastrarComentario(&$comentvo) {
		// pego as opções dos comentários
		$configs = $this->getConfiguracoes();

		// se precisar de aprovação
		if ($configs->PrecisaAprovacao) $disponivel = 2;
		else $disponivel = 1;

		// pego as palavras da moderação
		$palavrasmoderacao = $configs->PalavrasModeracao;
		$palavrasmoderacao = explode(',', $palavrasmoderacao);
		
		$palavrascomentario = explode(' ', $comentvo->getComentario());
			
		foreach ($palavrascomentario as $palavrac) {
			$palavrac = str_replace(array(',', '.', '"', '\''), '', $palavrac);
			$sql = "SELECT CodSistema FROM Comentarios_Opcoes WHERE FIND_IN_SET('".stripslashes(strtolower($palavrac))."', REPLACE(LOWER(PalavrasModeracao), ' ', ''))";
			if ($this->banco->numRows($this->banco->executaQuery($sql))) {
				$disponivel = 2;
				break;
			}
			//foreach ($palavrasmoderacao as $palavra) {
			//	if (@eregi($palavrac, $palavra)) { $disponivel = 2; break 2; }	
			//}
		}
		
		
		/*
		foreach ($palavrasmoderacao as $palavra) {
			
			if (@eregi(trim($palavra), $comentvo->getComentario())) 	{ $disponivel = 2; break; }
			elseif (@eregi(trim($palavra), $comentvo->getNome())) 		{ $disponivel = 2; break; }
			elseif (@eregi(trim($palavra), $comentvo->getSite())) 		{ $disponivel = 2; break; }
			elseif (@eregi(trim($palavra), $comentvo->getEmail())) 		{ $disponivel = 2; break; }
			elseif (@eregi(trim($palavra), $_SERVER['REMOTE_ADDR'])) 	{ $disponivel = 2; break; }
			
			//if (@strstr($comentvo->getComentario(), $palavra) || @strstr($comentvo->getNome(), $palavra) || @strstr($comentvo->getSite(), $palavra) || @strstr($comentvo->getEmail(), $palavra) || @strstr($_SERVER['REMOTE_ADDR'], $palavra)) {
			//	echo $palavra;
			//if ((strpos($palavra, $comentvo->getComentario()))) {
			//	$disponivel = 2;
			//	break;
			//}
		}
		*/
		
		//echo $disponivel;
		//echo $palavra; die;
		
		//teste comentario eduardo kmf lalala sei la coisa

		// pego as palavras da lista negra
		$palavraslistanegra = $configs->PalavrasListaNegra;
		$palavraslistanegra = explode(',', $palavraslistanegra);
		
		$palavrascomentario = explode(' ', $comentvo->getComentario());
		
		foreach ($palavrascomentario as $palavrac) {
			$palavrac = str_replace(array(',', '.', '"', '\''), '', $palavrac);
			$sql = "SELECT CodSistema FROM Comentarios_Opcoes WHERE FIND_IN_SET('".stripslashes(strtolower($palavrac))."', REPLACE(LOWER(PalavrasListaNegra), ' ', ''))";
			if ($this->banco->numRows($this->banco->executaQuery($sql))) {
				$disponivel = 3;
				break;
			}
		}
		
		/*
			
		foreach ($palavrascomentario as $palavrac) {
			foreach ($palavraslistanegra as $palavra) {
				if (@eregi($palavrac, $palavra)) { $disponivel = 3; break 2; }	
			}
		}
		
		*/
		
		/*
		foreach ($palavraslistanegra as $palavra) {
			//if (@eregi($palavra, $comentvo->getComentario()) || @eregi($palavra, $comentvo->getNome()) || @eregi($palavra, $comentvo->getSite()) || @eregi($palavra, $comentvo->getEmail()) || @eregi($palavra, $_SERVER['REMOTE_ADDR'])) {
			//if ((@strpos($palavra, $comentvo->getComentario())) || (@strpos($palavra, $comentvo->getNome())) || (@strpos($palavra, $comentvo->getSite())) || (@strpos($palavra, $comentvo->getEmail())) || (@strpos($palavra, $_SERVER['REMOTE_ADDR']))) {
			//if (@strstr($comentvo->getComentario(), $palavra) || @strstr($comentvo->getNome(), $palavra) || @strstr($comentvo->getSite(), $palavra) || @strstr($comentvo->getEmail(), $palavra) || @strstr($_SERVER['REMOTE_ADDR'], $palavra)) {
			//	$disponivel = 3;
			//	break;
			//}
			
			if (@eregi(trim($palavra), $comentvo->getComentario())) 	{ $disponivel = 3; break; }
			elseif (@eregi(trim($palavra), $comentvo->getNome())) 		{ $disponivel = 3; break; }
			elseif (@eregi(trim($palavra), $comentvo->getSite())) 		{ $disponivel = 3; break; }
			elseif (@eregi(trim($palavra), $comentvo->getEmail())) 		{ $disponivel = 3; break; }
			elseif (@eregi(trim($palavra), $_SERVER['REMOTE_ADDR'])) 	{ $disponivel = 3; break; }
			
		}
		
		*/
		
		//echo $disponivel; die;

        $sql = "INSERT INTO Conteudo_Comentarios VALUES (NULL, '".$comentvo->getCodConteudo()."', '".$comentvo->getNome()."', '".$comentvo->getEmail()."', '".$comentvo->getSite()."', '".$comentvo->getComentario()."', NOW(), ".$disponivel.")";
        $this->banco->executaQuery($sql);
        return $this->banco->insertID();
    }

	public function apagarComentario($codcomentario) {
        $sql = "UPDATE Conteudo_Comentarios SET disponivel='0' WHERE cod_comentario='$codcomentario'";
        $this->banco->executaQuery($sql);
    }

    public function disponibilizarComentario($codcomentario, $disponivel) {
        $sql = "UPDATE Conteudo_Comentarios SET disponivel='$disponivel' WHERE cod_comentario='$codcomentario'";
        $this->banco->executaQuery($sql);
    }

    public function atualizarConfiguracoes($config) {
    	$this->banco->executaQuery("UPDATE Comentarios_Opcoes SET PermitirPublicacao='".$config['permitirpublicacao']."', PrecisaAprovacao='".$config['precisaaprovacao']."', PalavrasModeracao='".$config['moderacao']."', PalavrasListaNegra='".$config['listanegra']."' WHERE CodSistema='".ConfigVO::getCodSistema()."'");
    }

    public function getConfiguracoes() {
    	$query = $this->banco->sql_select('*', 'Comentarios_Opcoes', "CodSistema='".ConfigVO::getCodSistema()."'");
    	return $this->banco->fetchObject();
    }

    public function getTotalComentariosAprovacao() {
    	$where = "WHERE t1.disponivel='2'";

		if ($_SESSION['logado_dados']['nivel'] == 2)
			$codautor = $_SESSION['logado_cod'];
		if ($_SESSION['logado_dados']['nivel'] >= 5)
			$codcolaborador = $_SESSION['logado_dados']['cod_colaborador'];

		if ($codautor)
			$where .= " AND t2.cod_autor='".$codautor."'";
		if ($codcolaborador)
			$where .= " AND t2.cod_colaborador='".$codcolaborador."'";

		$sql = "SELECT t1.cod_conteudo FROM Conteudo_Comentarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) $where AND t2.publicado='1' AND t2.excluido='0'";

		$querycont = $this->banco->executaQuery($sql);
		$cont = $this->banco->numRows($querycont);

		return (int)$cont;
	}

	public function getListaComentarios($dados, $inicial, $mostrar) {
		$array = array();
		extract($dados);

		$array['link'] = "ajax_conteudo.php?get=buscar_comentarios&buscar=1&palavrachave=$palavrachave&buscarpor=$buscarpor&de=$de&ate=$ate&mostrar=$mostrar&cod=$cod";

		$where = "WHERE t1.disponivel != '0'";

		if ((int)$cod) {
			$where .= " AND t1.cod_conteudo='".$cod."'";
			$where2 = " AND cod_conteudo='".$cod."'";
		}

		if ($buscar) {
			if ($palavrachave && $palavrachave != 'Buscar') {
				switch($buscarpor) {
					case "comentario":
						$where .= " AND t1.comentario LIKE '%$palavrachave%'";
					break;
				}
			}

			switch($buscarpor) {
                case "aprovado": $where .= " AND t1.disponivel='1'"; break;
				case "aguardando": $where .= " AND t1.disponivel='2'"; break;
				case "rejeitado": $where .= " AND t1.disponivel='3'"; break;
			}

			if ($de) {
				$data1 = explode('/', $de);
				if ((int)$data1[1]) {
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
						$where .= " AND (t1.data_cadastro >= '$datainicial 00:00:00' AND t1.data_cadastro <= '$datafinal 23:59:00')";
				}
			}
		}

		if ($_SESSION['logado_dados']['nivel'] == 2)
			$codautor = $_SESSION['logado_cod'];
		if (($_SESSION['logado_dados']['nivel'] >= 5 && $_SESSION['logado_dados']['nivel'] < 7))
			$codcolaborador = $_SESSION['logado_dados']['cod_colaborador'];

		if ($codautor)
			$where .= " AND t2.cod_autor='".$codautor."'";
		if ($codcolaborador)
			$where .= " AND t2.cod_colaborador='".$codcolaborador."'";

		$sql = "SELECT t1.*, t3.titulo AS url FROM Conteudo_Comentarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t2.cod_conteudo=t3.cod_item) $where AND t2.publicado='1' AND t2.excluido='0' AND t3.tipo='4' and t2.cod_sistema='".ConfigVO::getCodSistema()."'";

		//echo $sql;

		$array['carregar'] = (int)$carregar;
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		$query = $this->banco->executaQuery("$sql ORDER BY t1.cod_comentario DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchArray($query)) {
			$array[] = array(
				'cod' => $row['cod_comentario'],
				'cod_conteudo' => $row['cod_conteudo'],
				'autor'	=> htmlentities($row['autor']),
				'email'	=> $row['email'],
				'site' => $row['site'],
				'data' 	=> $row['data_cadastro'],
				'url' => $row['url'],
				'comentario' => htmlentities($row['comentario']),
				'menu_acao' => $this->menuComentario($row['disponivel'], $array['link'], $row['cod_comentario'], $pagina),
				'html_index' => $this->menuIndex($row['disponivel'], $row['cod_comentario'])
			);
			
		}

		if ($codautor)
			$where2 .= " AND t2.cod_autor='".$codautor."'";
		if ($codcolaborador)
			$where2 .= " AND t2.cod_colaborador='".$codcolaborador."'";

		$sql = "SELECT t1.cod_conteudo FROM Conteudo_Comentarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.disponivel='2' $where2 AND t2.publicado='1' AND t2.excluido='0' and t2.cod_sistema='".ConfigVO::getCodSistema()."'";

		//echo $sql;

		$querycont = $this->banco->executaQuery($sql);
		$cont = $this->banco->numRows($querycont);

		// cima
		if ($buscarpor)
			$htmlcima = '<a href="javascript:void(0);" onclick="mudaPagina(\'\');">Todos coment&aacute;rios</a>';
		else
			$htmlcima = '<strong>Todos coment&aacute;rios</strong>';
		$htmlcima .= ' | ';

		if ($buscarpor == 'aprovado')
			$htmlcima .= '<strong>Aprovados</strong>';
		else
			$htmlcima .= '<a href="javascript:void(0);" onclick="mudaPagina(\'aprovado\');">Aprovados</a>';
		$htmlcima .= ' | ';

        if ($buscarpor == 'rejeitado')
			$htmlcima .= '<strong>Rejeitados</strong>';
		else
			$htmlcima .= '<a href="javascript:void(0);" onclick="mudaPagina(\'rejeitado\');">Rejeitados</a>';
		$htmlcima .= ' | ';

		if ($buscarpor == 'aguardando')
			$htmlcima .= '<strong>Aguardando aprova&ccedil;&atilde;o</strong> <strong>'.$cont.'</strong>';
		else
			$htmlcima .= '<a <a href="javascript:void(0);" onclick="mudaPagina(\'aguardando\');">Aguardando aprova&ccedil;&atilde;o</a> <strong>'.$cont.'</strong>';
		$array['html_navegacao_superior'] = $htmlcima;

		switch($buscarpor) {
            case "aprovado": $array['titulo_superior'] = 'Coment&aacute;rios Aprovados'; break;
			case "aguardando": $array['titulo_superior'] = 'Aguardando aprova&ccedil;&atilde;o'; break;
			case "rejeitado": $array['titulo_superior'] = 'Coment&aacute;rios Rejeitados'; break;
			default: $array['titulo_superior'] = 'Todos coment&aacute;rios';
		}
		
		return $array;
	}
	
	private function menuIndex($situacao, $cod) {
		if ($situacao == 1) // aprovado
			$html_menu[] = "<a href=\"javascript:acaoComentario(3, ".$cod.");\" class=\"reject-link\">Rejeitar este coment&aacute;rio</a>";
		if ($situacao == 2 || $situacao == 3) // aguardando
			$html_menu[] = "<a href=\"javascript:acaoComentario(2, ".$cod.");\" class=\"approve-link\">Aprovar esse coment&aacute;rio</a>";
			return implode(' | ', $html_menu);
	}

	private function menuComentario($situacao, $url, $cod, $pagina) {
		if ($situacao == 1) // aprovado
			$html_menu[] = "<a href=\"javascript:void(0);\" onclick=\"javascript:submeteAcoesComentario(3, '".$url."&codcomentario=".$cod."&pagina=".$pagina."');\" class=\"rejeitar\" title=\"Rejeitar\">Rejeitar</a>\n";
		if ($situacao == 2) { // aguardando
			$html_menu[] = "<a href=\"javascript:void(0);\" onclick=\"javascript:submeteAcoesComentario(2, '".$url."&codcomentario=".$cod."&pagina=".$pagina."');\" class=\"aprovar\" title=\"Aprovar\">Aprovar</a>\n";
			$html_menu[] = "<a href=\"javascript:void(0);\" onclick=\"javascript:submeteAcoesComentario(3, '".$url."&codcomentario=".$cod."&pagina=".$pagina."');\" class=\"rejeitar\" title=\"Rejeitar\">Rejeitar</a>\n";
		}
		if ($situacao == 3) // rejeitado
			$html_menu[] = "<a href=\"javascript:void(0);\" onclick=\"javascript:submeteAcoesComentario(2, '".$url."&codcomentario=".$cod."&pagina=".$pagina."');\" class=\"aprovar\" title=\"Aprovar\">Aprovar</a>\n";

		//$html_menu[] = "<a href=\"javascript:void(0);\" onclick=\"javascript:submeteAcoesComentario(1, '".$url."&codcomentario=".$cod."&pagina=".$pagina."');\">Apagar</a>\n";
		return implode(' | ', $html_menu);
	}

	public function executaAcoes($acao, $codcomentario) {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					if (count($codcomentario))
						$this->banco->executaQuery("UPDATE Conteudo_Comentarios SET disponivel='0' WHERE cod_comentario IN (".implode(',', $codcomentario).")");
				break;
                case 2: // aprovar
					if (count($codcomentario))
						$this->banco->executaQuery("UPDATE Conteudo_Comentarios SET disponivel='1' WHERE cod_comentario IN (".implode(',', $codcomentario).")");
				break;
                case 3: // rejeitar
					if (count($codcomentario))
						$this->banco->executaQuery("UPDATE Conteudo_Comentarios SET disponivel='3' WHERE cod_comentario IN (".implode(',', $codcomentario).")");
				break;
			}
		}
	}

	public function getComentarioVO($codcomentario) {
		$sql = "SELECT * FROM Conteudo_Comentarios WHERE cod_comentario='$codcomentario'";
		$query = $this->banco->executaQuery($sql);
    	$row = $this->banco->fetchObject($query);
    	$comentvo = new ComentariosVO;
    	$comentvo->setCodComentario($row->cod_comentario);
    	$comentvo->setNome($row->autor);
    	$comentvo->setEmail($row->email);
    	$comentvo->setSite($row->site);
    	$comentvo->setComentario($row->comentario);
    	return $comentvo;
	}

	public function getUrlConteudo($codcomentario) {
		$sql = "SELECT t3.titulo AS url FROM Conteudo_Comentarios AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t2.cod_conteudo=t3.cod_item) WHERE t1.cod_comentario='".$codcomentario."' AND t3.tipo='4'";
		$query = $this->banco->executaQuery($sql);
    	$row = $this->banco->fetchArray($query);
    	return $row['url'];
	}

	public function atualizar(&$comentvo) {
		$this->banco->sql_update('Conteudo_Comentarios', array('autor' => $comentvo->getNome(), 'email' => $comentvo->getEmail(), 'site' => $comentvo->getSite(), 'comentario' => $comentvo->getComentario()), "cod_comentario='".$comentvo->getCodComentario()."'");
	}
	
	public function getQtsComentarios($codconteudo) {
		$sql = "SELECT COUNT(cod_conteudo) FROM Conteudo_Comentarios WHERE cod_conteudo='".$codconteudo."' AND disponivel='1'";
		$query = $this->banco->executaQuery($sql);
    	$row = $this->banco->fetchArray($query);
    	return (int)$row[0];
	}

}
