<?php
include_once("ConteudoDAO.php");

class ImagemDAO extends ConteudoDAO {

	public function cadastrarAlbum(&$albumvo) {
		$codconteudo = $this->cadastrarConteudo($albumvo);

		if ($codconteudo) {
			$sql = "insert into Albuns (cod_conteudo, cod_imagem_capa) values ('".$codconteudo."', '".$albumvo->getCodImagemCapa()."');";
			$sql_result = $this->banco->executaQuery($sql);

			$lista_imagens = $albumvo->getListaImagens();

			if (!$albumvo->getCodImagemCapa()) {
				$sql = "update Albuns set cod_imagem_capa ='".$lista_imagens['0']."' where cod_conteudo='".$codconteudo."'";
				$this->banco->executaQuery($sql);
			}

			$sql = "update Imagens set cod_conteudo = '".$codconteudo."' where cod_imagem in ('".implode("','", $lista_imagens)."');";
			$sql_result = $this->banco->executaQuery($sql);
		}

		return $codconteudo;
	}

	public function atualizarAlbum(&$albumvo) {
		$this->atualizarConteudo($albumvo);

		$lista_imagens = $albumvo->getListaImagens();

		if (in_array($albumvo->getCodImagemCapa(), $lista_imagens)) {
			$sql = "update Albuns set cod_imagem_capa = '".$albumvo->getCodImagemCapa()."' where cod_conteudo = '".$albumvo->getCodConteudo()."';";
		} else {
			$sql = "update Albuns set cod_imagem_capa ='".$lista_imagens['0']."' where cod_conteudo='".$albumvo->getCodConteudo()."'";
		}

		$sql_result = $this->banco->executaQuery($sql);

		$sql = "update Imagens set cod_conteudo = '".$albumvo->getCodConteudo()."' where cod_imagem in ('".implode("','", $albumvo->getListaImagens())."');";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getAlbumVO(&$codconteudo) {
		$albumvo = new AlbumImagemVO;
		$this->getConteudoVO($codconteudo, $albumvo);

		$sql = "select * from Albuns where cod_conteudo = '".$codconteudo."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);

		$albumvo->setCodImagemCapa($sql_row["cod_imagem_capa"]);
		$codcapa = $sql_row["cod_imagem_capa"];

		$lista_imagens = array();
		$sql = "select cod_imagem, imagem from Imagens where cod_conteudo = '".$codconteudo."' and excluido = 0 order by ordem asc;";
		
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista_imagens[] = $sql_row["cod_imagem"];
			if ($sql_row["cod_imagem"] == $codcapa)
				$albumvo->setImagem($sql_row["imagem"]);
		}
		$albumvo->setListaImagens($lista_imagens);

		return $albumvo;
	}

	public function cadastrarImagem(&$imgvo, $sessao_id) {
		$sql = "select ordem from Imagens where cod_imagem in (".implode(',', (array)$_SESSION["sess_conteudo_imagens_album"][$sessao_id]).") order by ordem desc limit 1;";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$ordem = ((int)$sql_row[0] + 10);
		
		$sql = "insert into Imagens (cod_conteudo, randomico, legenda, credito, ordem, datahora) values ('".$imgvo->getCodConteudo()."', '".$imgvo->getRandomico()."', '".$imgvo->getLegenda()."', '".$imgvo->getCredito()."', '".$ordem."', now());";
		$sql_result = $this->banco->executaQuery($sql);
		$codimagem = (int)$this->banco->insertId();
		return $codimagem;
	}

	public function atualizarImagem(&$imgvo) {
		$sql = "update Imagens set legenda = '".$imgvo->getLegenda()."', credito = '".$imgvo->getCredito()."' where cod_imagem = '".$imgvo->getCodImagem()."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function atualizarCapa($imagens, $codimagem) {
		$sql = "update Imagens set capa = 0 where cod_imagem in (".implode(',', (array)$imagens).");";
		$sql_result = $this->banco->executaQuery($sql);
		
		$sql = "update Imagens set capa = 1 where cod_imagem = '".$codimagem."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function atualizaOrdenacao($cod, $move) {
		$move = Util::iif($move == 1, '-15', '15');
		$sql = "update Imagens set ordem = ordem + $move where cod_imagem = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function organizacaoFinal($sessao_id) {
		$sql = "SELECT ordem, cod_imagem FROM Imagens WHERE cod_imagem IN (".implode(',', (array)$_SESSION["sess_conteudo_imagens_album"][$sessao_id]).") ORDER BY ordem ASC";
		$resultado = $this->banco->executaQuery($sql);
			
		$i = 10;
		while ($row = $this->banco->fetchArray($resultado)) {
			$sql = "UPDATE Imagens SET ordem = '$i' WHERE cod_imagem = '".$row['cod_imagem']."'";
			$this->banco->executaQuery($sql);
			$i += 10;
		}
	}

	public function getImagemVO(&$codimagem) {
		$sql = "select * from Imagens where cod_imagem='".$codimagem."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);

		$imgvo = new ImagemVO;
		$imgvo->setCodImagem($sql_row["cod_imagem"]);
		$imgvo->setCodConteudo($sql_row["cod_conteudo"]);
		$imgvo->setRandomico($sql_row["randomico"]);
		$imgvo->setArquivo($sql_row["imagem"]);
		$imgvo->setLegenda($sql_row["legenda"]);
		$imgvo->setCredito($sql_row["credito"]);
		$imgvo->setDataHora($sql_row["datahora"]);
		return $imgvo;
	}

	public function atualizarArquivo($arquivo, $codimagem) {
		$sql = "update Imagens set imagem = '".$arquivo."' where cod_imagem = '".$codimagem."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function buscarImagens(&$dados_form, $pagina, $intervalo, $gettotal = false) {
		$sql_from = "Imagens I";
		$sql_where = "I.excluido = 0";
		$ok = false;

		if (is_array($dados_form["lista_imagens"]) && count($dados_form["lista_imagens"])) {
			$sql_where .= " and I.cod_imagem in ('".implode("','", $dados_form["lista_imagens"])."')";
			$ok = true;
		}
		if ($dados_form["codconteudo"]) {
			$sql_where .= " and I.cod_conteudo = '".$dados_form["codconteudo"]."'";
			$ok = true;
		}

		if (!$gettotal) {
			if (!$pagina)
				$pagina = 1;
			$indice = ($pagina - 1) * $intervalo;
			$lista_imagens = array();
			if ($ok) {
				$sql = "select I.cod_imagem, I.randomico, I.imagem, I.legenda, I.credito, I.ordem, I.capa, I.datahora from ".$sql_from." where ".$sql_where." order by I.ordem desc limit ".$indice.", ".$intervalo.";";
				$sql_result = $this->banco->executaQuery($sql);
				while ($sql_row = $this->banco->fetchArray($sql_result))
					$lista_imagens[] = $sql_row;
			}
			return $lista_imagens;
		}
		else {
			if ($ok) {
				$sql = "select count(I.cod_imagem) from ".$sql_from." where ".$sql_where.";";
				$sql_result = $this->banco->executaQuery($sql);
				$sql_row = $this->banco->fetchArray($sql_result);
				return (int)$sql_row[0];
			}
			return 0;
		}
	}
	
	public function getTotalItensAlbum(&$dados_form) {
		$sql_from = "Imagens";
		$sql_where = "excluido = 0";
		$ok = false;
		
		if (is_array($dados_form["lista_imagens"]) && count($dados_form["lista_imagens"])) {
			$sql_where .= " and cod_imagem in ('".implode("','", $dados_form["lista_imagens"])."')";
			$ok = true;
		}
		if ($dados_form["codconteudo"]) {
			$sql_where .= " and cod_conteudo = '".$dados_form["codconteudo"]."'";
			$ok = true;
		}
		
		$lista_imagens = array();
		if ($ok) {
		$sql = "select cod_imagem from ".$sql_from." where ".$sql_where.";";
			$sql_result = $this->banco->executaQuery($sql);
			while ($sql_row = $this->banco->fetchArray($sql_result))
				$lista_imagens[] = $sql_row['cod_imagem'];
		}
		return $lista_imagens;	
	}

	public function excluirImagem($codimg) {
		$sql = "update Imagens set excluido = 1 where cod_imagem = '".$codimg."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getImagensAlbum($codconteudo) {
        $sql = "SELECT * FROM Imagens WHERE cod_conteudo='$codconteudo' AND excluido='0' order by ordem asc";
        $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray())
            $array[] = $row;
        return $array;
    }

    public function getImagemCapaAlbum($codconteudo) {
		$sql = "SELECT t1.* FROM Imagens AS t1 INNER JOIN Albuns AS t2 ON (t1.cod_imagem=t2.cod_imagem_capa) WHERE t2.cod_conteudo='$codconteudo' AND t1.excluido='0'";
        $this->banco->executaQuery($sql);
        return $row = $this->banco->fetchArray();
	}

	public function getArquivoImagem($codconteudo) {
        $sql = "SELECT t1.cod_imagem_capa, t2.cod_imagem, t2.imagem, t2.legenda, t2.credito, t2.datahora FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='$codconteudo' AND t2.excluido='0' ORDER BY t2.ordem ASC";
        $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray())
            $array[] = $row;
        return $array;
    }

    public function getDadosImagem($codimagem) {
		$sql = "SELECT * FROM Imagens WHERE cod_imagem='$codimagem'";
		$this->banco->executaQuery($sql);
		return $this->banco->fetchArray();
	}

}