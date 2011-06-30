<?php
include_once("ConexaoDB.php");

class ConteudoExibicaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getLicenca($cod) {
		$array = array();
		$query = $this->banco->executaQuery("SELECT * FROM Licencas WHERE cod_licenca='".$cod."' ORDER BY ordem");
		if ($this->banco->numRows($query)) {
			while ($row = $this->banco->fetchArray($query)) {
				$array[] = array(
					'imagem'    => 1,
					'cod_licenca' => $row['cod_licenca'],
					'ordem' => $row['ordem'],
					'titulo'    => $row['titulo'],
					'imagem'    => $row['imagem'],
					'descricao' => $row['descricao']
				);
			}
		}
		return $array;
	}

	public function getTipoConteudo($endereco, $tipo) {
		if (!$tipo)
			$tipo = 4;
        //$sql = "SELECT titulo, cod_item, tipo FROM Urls WHERE titulo='".$endereco."' and tipo = '".$tipo."';";
		$sql = "SELECT titulo, cod_item, tipo FROM Urls WHERE titulo='".$endereco."' and cod_sistema = '".ConfigVO::getCodSistema()."'";
        $query = $this->banco->executaQuery($sql);
        $row = $this->banco->fetchArray($query);

        if ($row['tipo'] == 4) {
            $sql = "SELECT cod_formato FROM Conteudo WHERE cod_conteudo='$row[cod_item]'";
            $query1 = $this->banco->executaQuery($sql);
            $row1 = $this->banco->fetchArray($query1);
            $row['cod_formato'] = $row1['cod_formato'];
        }
        return $row;
    }

	public function getDadosConteudo($codconteudo) {
    	$sql = "SELECT t1.*, t2.formato, t3.nome AS classificacao, t4.nome AS segmento, t5.nome AS colaborador, t6.num_recomendacoes, t6.num_acessos, t7.titulo AS url, t8.titulo AS licenca_titulo, t8.descricao AS licenca_descricao, t9.titulo AS url_colaborador, t10.num_negativos FROM Conteudo AS t1 INNER JOIN Conteudo_Formato AS t2 ON (t1.cod_formato=t2.cod_formato) LEFT JOIN Conteudo_Classificacao AS t3 ON (t1.cod_classificacao=t3.cod_classificacao AND t2.cod_formato=t3.cod_formato) LEFT JOIN Conteudo_Segmento AS t4 ON (t1.cod_segmento=t4.cod_segmento AND t2.cod_formato=t4.cod_pai) LEFT JOIN Usuarios AS t5 ON (t1.cod_colaborador=t5.cod_usuario) LEFT JOIN Conteudo_Estatisticas AS t6 ON (t1.cod_conteudo=t6.cod_conteudo) INNER JOIN Urls AS t7 ON (t1.cod_conteudo=t7.cod_item) LEFT JOIN Licencas AS t8 ON (t1.cod_licenca=t8.cod_licenca) LEFT JOIN Urls AS t9 ON (t1.cod_colaborador=t9.cod_item) LEFT JOIN Conteudo_Estatisticas_VotosNegativos AS t10 ON (t1.cod_conteudo=t10.cod_conteudo) WHERE t1.cod_conteudo='".$codconteudo."' AND t1.excluido='0' AND t5.cod_tipo = 1 AND t7.tipo = 4 AND t9.tipo = 1 AND t1.situacao='1' AND t1.publicado='1' AND t1.cod_sistema = ".ConfigVO::getCodSistema();
        $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
    }

    public function getPermissaoComentario($codconteudo) {
    	$sql = "SELECT permitir_comentarios FROM Conteudo_Opcoes WHERE cod_conteudo='".$codconteudo."'";
    	$this->banco->executaQuery($sql);
    	$row = $this->banco->fetchArray();
        return (bool)$row['permitir_comentarios'];
    }

    public function getConteudoResumido($codconteudo, $publicado=true) {
    	if ($publicado)
    		$and = "AND t1.publicado='1'";
			$sql = "SELECT t1.*, t2.titulo AS url_titulo FROM Conteudo AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_conteudo=t2.cod_item) WHERE t1.cod_conteudo='".$codconteudo."' AND t1.excluido='0' $and AND t2.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema();
		$query = $this->banco->executaQuery($sql);
        $row = $this->banco->fetchArray($query);

        $row['tipo'] = 'a';
        if ($row['cod_formato'] == 2) {
			$sql1 = "SELECT t1.imagem FROM Imagens AS t1 LEFT JOIN Albuns AS t2 ON (t1.cod_imagem=t2.cod_imagem_capa) WHERE t2.cod_conteudo='".$codconteudo."'";
			$query1 = $this->banco->executaQuery($sql1);
        	$row1 = $this->banco->fetchArray($query1);
        	$row['imagem'] = $row1['imagem'];
        	$row['tipo'] = '2';
		}
		return $row;
	}

	public function getAutoresConteudo($codconteudo, $style='') {
		$sql = "SELECT t1.cod_usuario, t1.nome, t3.titulo FROM Usuarios AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='2' AND t1.situacao='3' AND t1.disponivel='1'";
        $query1 = $this->banco->executaQuery($sql);
        $lista = '';
    	while ($row = $this->banco->fetchArray($query1)) {
    		$lista .= (($lista != '') ? ', ' : ' ').'<a href="'.ConfigVO::URL_SITE.$row['titulo'].'/"'.$style.'>'.$row['nome'].'</a>';
    	}
    	return $lista;
    }

	public function getAutoresConteudoNovo($codconteudo) {
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) WHERE t2.cod_conteudo='$codconteudo' AND t3.tipo='2' AND t1.situacao='3' AND t1.disponivel='1'";
        $query = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query))
			$lista[] = $row;
    	return $lista;
    }

    public function getConteudoAutores($codconteudo) {
    	$arrayAutores = $arrayConteudos = $dadosConteudo = array();

		$sql = "SELECT cod_usuario FROM Conteudo_Autores WHERE cod_conteudo='".$codconteudo."'";
        $query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];

    	$sql = "SELECT t1.cod_conteudo FROM Conteudo_Autores AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) WHERE t1.cod_conteudo!='".$codconteudo."' AND t1.cod_usuario IN (".implode(',', $arrayAutores).") AND (t2.cod_formato != 5 AND t2.cod_formato != 6) AND t2.situacao='1' AND t2.publicado='1' AND t3.tipo = 4 ORDER BY RAND() LIMIT 4";
        $query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray($query))
    		$arrayConteudos[$row['cod_conteudo']] = $row['cod_conteudo'];

    	$arrayConteudos = array_unique($arrayConteudos);

    	foreach ($arrayConteudos as $codconteudo) {
    		$dados = $this->getDadosConteudo($codconteudo);
    		if ((int)$dados['cod_conteudo']) {

				if ($dados['cod_formato'] == 2) {
	                //$sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='".$dados['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
	                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) AND (t2.cod_imagem=t1.cod_imagem_capa) WHERE t1.cod_conteudo='".$dados['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
	                $query1 = $this->banco->executaQuery($sql1);
	                $row1 = $this->banco->fetchArray($query1);
            		$dados['imagem_album'] = $row1['imagem'];
				}

				$dadosConteudo[] = $dados;
    		}
    	}

    	return $dadosConteudo;
    }

    public function getConteudoAutoresFichaTecnica($codconteudo) {
    	$arrayAutores = $arrayConteudos = $dadosConteudo = array();

		$sql = "SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='".$codconteudo."'";
        $query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];

    	$sql = "SELECT DISTINCT(t1.cod_conteudo) FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) WHERE t1.cod_conteudo!='".$codconteudo."' AND t1.cod_usuario IN (".implode(',', $arrayAutores).") AND (t2.cod_formato != 5 AND t2.cod_formato != 6) AND t2.situacao='1' AND t2.publicado='1' AND t3.tipo = 4 ORDER BY RAND() LIMIT 4";
        $query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray($query))
    		$arrayConteudos[$row['cod_conteudo']] = $row['cod_conteudo'];

    	foreach ($arrayConteudos as $codconteudo) {
    		$dados = $this->getDadosConteudo($codconteudo);
    		if ((int)$dados['cod_conteudo']) {

				if ($dados['cod_formato'] == 2) {
	                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) AND (t2.cod_imagem=t1.cod_imagem_capa) WHERE t1.cod_conteudo='".$dados['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
	                $query1 = $this->banco->executaQuery($sql1);
	                $row1 = $this->banco->fetchArray($query1);
            		$dados['imagem_album'] = $row1['imagem'];
				}

				$dadosConteudo[] = $dados;
    		}
    	}

    	return $dadosConteudo;
    }

    public function getAutoresFichaTecnicaConteudo($codconteudo) {
		//$sql = "SELECT t1.cod_usuario, t1.nome FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Autores AS t4 ON (t1.cod_usuario=t4.cod_usuario) WHERE t2.cod_conteudo='".$codconteudo."'";
		$sql = "SELECT t1.cod_usuario, t1.nome, t5.titulo AS url, t6.cod_atividade, t6.atividade FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t5 ON (t1.cod_usuario=t5.cod_item) LEFT JOIN Usuarios_Atividades AS t6 ON (t2.cod_atividade=t6.cod_atividade) WHERE t2.cod_conteudo='".$codconteudo."' AND t5.tipo='2' AND t5.titulo != '' AND t5.cod_sistema='".ConfigVO::getCodSistema()."' AND t1.situacao='3' AND t1.disponivel='1' ORDER BY t2.cod_increment ASC";

        $query1 = $this->banco->executaQuery($sql);
        $lista = '';
    	while ($row = $this->banco->fetchArray($query1)) {
    		//$lista .= (($lista != '') ? ', ' : ' ').$row['nome'];
    		$lista .= Util::iif($lista != '', ', ')."<a href=\"/".$row['url']."\">".Util::cortaTexto($row['nome'], 35)."</a> ".Util::iif($row['atividade'], "(<a href=\"/busca_resultado.php?buscar=1&amp;autores=1&amp;todos_autores=".$codconteudo."&amp;tipo_atividade=".$row['cod_atividade']."\">".strtolower($row['atividade'])."</a>)", "");
    	}
    	return $lista;
    }
	// Foi criado por:
	 public function getAutoresFichaTecnicaConteudoNovo($codconteudo) {
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t2.cod_conteudo='".$codconteudo."' AND t1.situacao='3' AND t1.disponivel='1' ORDER BY t2.cod_increment ASC ";
        //echo $sql;
		$query = $this->banco->executaQuery($sql);
        $lista = array();

    	while ($row = $this->banco->fetchArray($query))//{
			 $lista[] = $row;

    	return $lista;
    }

		 public function getAutoresFichaTecnicaConteudoMaisautores($codconteudo) {
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t2.cod_conteudo='".$codconteudo."' AND t1.situacao='3' AND t1.disponivel='1' ORDER BY t2.cod_increment ASC LIMIT 3,10";
      //  echo $sql;
		$query = $this->banco->executaQuery($sql);
        $lista = array();

    	while ($row = $this->banco->fetchArray($query))//{
			 $lista[] = $row;

    	return $lista;
    }

    public function getCodAutoresFichaTecnicaConteudo($codconteudo) {
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1 INNER JOIN Conteudo_Autores_Ficha AS t2 ON (t1.cod_usuario=t2.cod_usuario) WHERE t2.cod_conteudo='".$codconteudo."' AND t1.disponivel='1' AND t1.situacao='3'";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();

		while ($row = $this->banco->fetchArray($query1))
    		$lista[$row['cod_usuario']] = $row['cod_usuario'];

		return $lista;
    }

    public function getTagsConteudo($codconteudo) {
        $sql = "SELECT t1.tag FROM Tags AS t1 INNER JOIN Conteudo_Tags AS t2 ON (t1.cod_tag=t2.cod_tag) WHERE t2.cod_conteudo='$codconteudo'";
        $query = $this->banco->executaQuery($sql);
        $lista = '';
    	while ($row = $this->banco->fetchArray($query)) {
    		$lista .= (($lista != '') ? ', ' : ' ').'<a href="/busca_resultado.php?buscar=1&amp;tag='.urlencode($row['tag']).'">'.$row['tag'].'</a>';
    	}
    	return $lista;
    }

	public function getTagsConteudoNovo($codconteudo) {
        $sql = "SELECT t1.tag FROM Tags AS t1 INNER JOIN Conteudo_Tags AS t2 ON (t1.cod_tag=t2.cod_tag) WHERE t2.cod_conteudo='$codconteudo'";
        $query = $this->banco->executaQuery($sql);
        $lista = array();

		while ($row = $this->banco->fetchArray($query))
			$lista[] = $row;

    	return $lista;
    }

    public function getCanalConteudo($codconteudo) {
        $sql = "SELECT cod_canal FROM Conteudo WHERE cod_conteudo='$codconteudo'";
        $query = $this->banco->executaQuery($sql);
        $row = $this->banco->fetchArray($query);
        $arrayCanal = array('1' => 'Teatro', '2' => 'Biblioteca', '3' => 'Centro de Convenções');
        if ($row['cod_canal'])
        	return "<a href=\"/busca_resultado.php?buscar=1&amp;canal=".$row['cod_canal']."\">".$arrayCanal[$row['cod_canal']]."</a>";
    }

    public function getConteudoRelacionado($codconteudo, $inicial=0, $mostrar=4) {
    	if ($mostrar)
		   	$limit = "LIMIT $inicial, $mostrar";
        $sql = "SELECT t1.cod_conteudo, t1.cod_formato, t1.titulo, t1.imagem, t2.formato, t3.num_recomendacoes, t3.num_acessos, t5.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_Formato AS t2 ON (t1.cod_formato=t2.cod_formato) LEFT JOIN Conteudo_Estatisticas AS t3 ON (t1.cod_conteudo=t3.cod_conteudo) INNER JOIN Conteudo_Relacionados AS t4 ON (t1.cod_conteudo=t4.cod_conteudo2) LEFT JOIN Urls AS t5 ON (t1.cod_conteudo=t5.cod_item) WHERE t4.cod_conteudo1='$codconteudo' AND t1.excluido='0' and t5.cod_sistema='".ConfigVO::getCodSistema()."' and t1.cod_sistema = ".ConfigVO::getCodSistema();

        $query = $this->banco->executaQuery($sql . " ORDER BY t3.num_acessos $limit");
        $array = array();
        while ($row = $this->banco->fetchArray($query)) {
            if ($row['cod_formato'] == 2) {
                //$sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='$row[cod_conteudo]' ORDER BY t1.cod_imagem_capa ASC";
                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) AND (t2.cod_imagem=t1.cod_imagem_capa) WHERE t1.cod_conteudo='".$row['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
                $query1 = $this->banco->executaQuery($sql1);
                $row1 = $this->banco->fetchArray($query1);
            }
            $array[] = array(
                'cod_conteudo' => $row['cod_conteudo'],
                'cod_formato' => $row['cod_formato'],
                'titulo' => $row['titulo'],
                'imagem' => $row['imagem'],
                'formato' => $row['formato'],
                'num_recomendacoes' => $row['num_recomendacoes'],
                'num_acessos' => $row['num_acessos'],
                'url' => $row['url'],
                'imagem_album' => $row1['imagem']
            );
        }
        return $array;
    }

	public function getConteudoRelacionadoTags($codconteudo, $inicial=0, $mostrar=4) {
		$lista_tags = array();
		$sql = "select cod_tag from Conteudo_Tags where cod_conteudo = '".$codconteudo."';";
		//echo $sql;
		$query = $this->banco->executaQuery($sql);
        while ($row = $this->banco->fetchArray($query))
			$lista_tags[] = $row['cod_tag'];

        //$sql = "SELECT distinct(t1.cod_conteudo), t1.cod_formato, t1.titulo, t1.imagem, t2.formato, t3.num_recomendacoes, t3.num_acessos, t5.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_Formato AS t2 ON (t1.cod_formato=t2.cod_formato) LEFT JOIN Conteudo_Estatisticas AS t3 ON (t1.cod_conteudo=t3.cod_conteudo) INNER JOIN Conteudo_Tags AS t4 ON (t1.cod_conteudo=t4.cod_conteudo) LEFT JOIN Urls AS t5 ON (t1.cod_conteudo=t5.cod_item) WHERE t4.cod_tag in ('".implode("','", $lista_tags)."') AND t1.cod_conteudo != '".$codconteudo."' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t5.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema();
		$sql = "SELECT distinct(t1.cod_conteudo), t1.cod_formato, t1.titulo, t1.imagem, t2.formato, t3.num_recomendacoes, t3.num_acessos, t5.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_Formato AS t2 ON (t1.cod_formato=t2.cod_formato) LEFT JOIN Conteudo_Estatisticas AS t3 ON (t1.cod_conteudo=t3.cod_conteudo) INNER JOIN Conteudo_Tags AS t4 ON (t1.cod_conteudo=t4.cod_conteudo) LEFT JOIN Urls AS t5 ON (t1.cod_conteudo=t5.cod_item) WHERE t4.cod_tag in ('".implode("','", $lista_tags)."') AND t1.cod_conteudo != '".$codconteudo."' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t1.cod_formato IN (1,2,3,4) AND t5.tipo='4' and t1.cod_sistema = ".ConfigVO::getCodSistema();
        $query = $this->banco->executaQuery($sql . " ORDER BY t3.num_acessos LIMIT $inicial, $mostrar");
        $array = array();
        while ($row = $this->banco->fetchArray($query)) {
            if ($row['cod_formato'] == 2) {
                //$sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='$row[cod_conteudo]' ORDER BY t1.cod_imagem_capa ASC";
                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) AND (t2.cod_imagem=t1.cod_imagem_capa) WHERE t1.cod_conteudo='".$row['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
                $query1 = $this->banco->executaQuery($sql1);
                $row1 = $this->banco->fetchArray($query1);
            }

            $imagem_1 = $imagem_2 = '';

            if ($row['imagem'])
				$imagem_1 = $row['imagem'];

			if ($row1['imagem'])
				$imagem_2 = $row1['imagem'];

            $array[] = array(
                'cod_conteudo' => $row['cod_conteudo'],
                'cod_formato' => $row['cod_formato'],
                'titulo' => $row['titulo'],
                'imagem' => $imagem_1,
                'formato' => $row['formato'],
                'num_recomendacoes' => $row['num_recomendacoes'],
                'num_acessos' => $row['num_acessos'],
                'url' => $row['url'],
                'imagem_album' => $imagem_2
            );
        }
        return $array;
    }

	public function getMaisConteudoColaborador($codconteudo, $codformato, $codcolaborador, $inicial=0, $mostrar=4) {
        $sql = "SELECT t1.cod_conteudo, t1.cod_formato, t1.titulo, t1.imagem, t2.formato, t3.num_recomendacoes, t3.num_acessos, t4.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_Formato AS t2 ON (t1.cod_formato=t2.cod_formato) LEFT JOIN Conteudo_Estatisticas AS t3 ON (t1.cod_conteudo=t3.cod_conteudo) LEFT JOIN Urls AS t4 ON (t1.cod_conteudo=t4.cod_item) WHERE t1.cod_colaborador='$codcolaborador' AND t1.cod_formato='$codformato' AND t1.cod_conteudo!='$codconteudo' AND t1.excluido='0' and t1.cod_sistema = ".ConfigVO::getCodSistema();
        $query = $this->banco->executaQuery($sql . " ORDER BY t3.num_acessos LIMIT $inicial, $mostrar");
        $array = array();
        while ($row = $this->banco->fetchArray($query)) {
            if ($row['cod_formato'] == 2) {
                //$sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_conteudo='$row[cod_conteudo]' ORDER BY t1.cod_imagem_capa ASC";
                $sql1 = "SELECT t2.imagem FROM Albuns AS t1 INNER JOIN Imagens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) AND (t2.cod_imagem=t1.cod_imagem_capa) WHERE t1.cod_conteudo='".$row['cod_conteudo']."' ORDER BY t1.cod_imagem_capa ASC";
                $query1 = $this->banco->executaQuery($sql1);
                $row1 = $this->banco->fetchArray($query1);
            }
            $array[] = array(
                'cod_conteudo' => $row['cod_conteudo'],
                'cod_formato' => $row['cod_formato'],
                'titulo' => $row['titulo'],
                'imagem' => $row['imagem'],
                'formato' => $row['formato'],
                'num_recomendacoes' => $row['num_recomendacoes'],
                'num_acessos' => $row['num_acessos'],
                'url' => $row['url'],
                'imagem_album' => $row1['imagem']
            );
        }
        return $array;
    }

    public function addAcesso($codconteudo) {
    	// new
		$this->banco->executaQuery("DELETE FROM Conteudo_Ips WHERE tempo_saida < '".time()."' AND tipo='2'");

		session_start();
		$acessos = (array)$_SESSION['acessos'];

		// new
		$sql = "SELECT cod_conteudo FROM Conteudo_Ips WHERE cod_conteudo='".$codconteudo."' AND ip='".$_SERVER['REMOTE_ADDR']."' AND tipo='2'";
		$query = $this->banco->executaQuery($sql);

		if (!in_array($codconteudo, $acessos) && !$this->banco->numRows($query)) {
			$sql = "SELECT cod_conteudo FROM Conteudo_Estatisticas WHERE cod_conteudo='".$codconteudo."'";
			$query = $this->banco->executaQuery($sql);
			if ($this->banco->numRows($query)) {
				$this->banco->executaQuery("UPDATE Conteudo_Estatisticas SET num_acessos = num_acessos+1 WHERE cod_conteudo='".$codconteudo."'");
			} else {
				$this->banco->executaQuery("INSERT INTO Conteudo_Estatisticas VALUES ('".$codconteudo."', 0, 1)");
			}

			// new
			$time = time()+24*3600;
			$this->banco->executaQuery("INSERT INTO Conteudo_Ips VALUES ('".$codconteudo."', '2', '".$_SERVER['REMOTE_ADDR']."', '".$time."')");
			$_SESSION['acessos'][$codconteudo] = $codconteudo;

		}
	}

	public function addRecomendacao($codconteudo, $tipo) {
		// tipo 1 - positivo, 2 - negativo
		$this->banco->executaQuery("DELETE FROM Conteudo_Ips WHERE tempo_saida < '".time()."' AND tipo='1'");

		session_start();
		$recomendacoes = (array)$_SESSION['recomendacoes'];

		// pos
		$sql = "SELECT * FROM Conteudo_Estatisticas WHERE cod_conteudo='".$codconteudo."'";
		$query = $this->banco->executaQuery($sql);
		// neg
		$sql = "SELECT * FROM Conteudo_Estatisticas_VotosNegativos WHERE cod_conteudo='".$codconteudo."'";
		$query2 = $this->banco->executaQuery($sql);

		if (!$this->banco->numRows($query))
			$this->banco->executaQuery("INSERT INTO Conteudo_Estatisticas VALUES ('".$codconteudo."', 0, 0)");
		if (!$this->banco->numRows($query2))
			$this->banco->executaQuery("INSERT INTO Conteudo_Estatisticas_VotosNegativos VALUES ('".$codconteudo."', 0)");

		// new
		$sql = "SELECT cod_conteudo FROM Conteudo_Ips WHERE cod_conteudo='".$codconteudo."' AND ip='".$_SERVER['REMOTE_ADDR']."' AND tipo='1'";
		$query = $this->banco->executaQuery($sql);

		if (!in_array($codconteudo, $recomendacoes) && !$this->banco->numRows($query)) {
			if ($tipo == 1)
				$this->banco->executaQuery("UPDATE Conteudo_Estatisticas SET num_recomendacoes = num_recomendacoes+1 WHERE cod_conteudo='".$codconteudo."'");
			else
				$this->banco->executaQuery("UPDATE Conteudo_Estatisticas_VotosNegativos SET num_negativos = num_negativos+1 WHERE cod_conteudo='".$codconteudo."'");
			// new
			$time = time()+24*3600;
			$this->banco->executaQuery("INSERT INTO Conteudo_Ips VALUES ('".$codconteudo."', '1', '".$_SERVER['REMOTE_ADDR']."', '".$time."')");
			$_SESSION['recomendacoes'][$codconteudo] = $codconteudo;
		}

		$sql = "SELECT * FROM Conteudo_Estatisticas WHERE cod_conteudo='".$codconteudo."'";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);

		$sql = "SELECT * FROM Conteudo_Estatisticas_VotosNegativos WHERE cod_conteudo='".$codconteudo."'";
		$query = $this->banco->executaQuery($sql);
		$row2 = $this->banco->fetchArray($query);

		return array(1 => (int)$row['num_recomendacoes'], 2 => (int)$row2['num_negativos']);
	}

}
