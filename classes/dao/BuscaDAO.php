<?php
include_once("ConexaoDB.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/CidadeDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/EstadoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/SegmentoDAO.php");

class BuscaDAO {

	protected $banco = null;
	protected $cidades = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
		$this->estados = new EstadoDAO();
	}

   public function getListaTodosEstados($get){
        return $this->estados->getListaEstados($get);
   }

	private function getConteudoVideo($codconteudo, $codformato) {
		if ($codformato == 4) {
			include_once(ConfigPortalVO::getDirClassesRaiz()."dao/VideoDAO.php");
			$contdao = new VideoDAO;
			return $contdao->getArquivoVideo($codconteudo);
		}
	}

	public function getResultadoBusca($get, $inicial, $mostrar) {

		extract($get);
        $array = array();
      
	
        if ($assunto)
			$palavra = trim($assunto);

        $link  = '/busca_resultado.php?buscar=1';
        $link .= '&amp;palavra='.$palavra.'&amp;textos='.$textos.'&amp;imagens='.$imagens.'&amp;videos='.$videos.'&amp;audios='.$audios.'&amp;noticias='.$noticias.'&amp;colaboradores='.$colaboradores.'&amp;autores='.$autores.'&amp;grupos='.$grupos.'&amp;tag='.$tag.'&amp;relacionado='.$relacionado.'&amp;codvinculado='.$codvinculado.'&amp;todos_autores='.$todos_autores.'&amp;tipo_atividade='.$tipo_atividade.'&amp;agenda='.$agenda.'&amp;canais='.$canais.'&amp;canal='.$canal;
		if($autores){
		$link.='&only=1';
		}

        if ($cod)
        	$link .= '&amp;cod='.$cod;
        if ($autor)
        	$link .= '&amp;autor='.$autor;
        if ($colaborador)
        	$link .= '&amp;colaborador='.$colaborador;
        if ($grupo)
        	$link .= '&amp;grupo='.$grupo;
        if ($canal)
        	$link .= '&amp;canal='.$canal;
        if ($ordem)
        	$link .= '&amp;ordem='.$ordem;
        if ($maisautor)
        	$link .= '&amp;maisautor='.$maisautor;
        if ($local)
        	$link .= '&amp;local='.$local;
        if ($play)
        	$link .= '&amp;play='.$play;

        // data
        if ($de) {
    		$p1 = explode('/', $de);
    		$data1 = $p1[2].'-'.$p1[1].'-'.$p1[0];
    		if ($ate) {
    			$p2 = explode('/', $ate);
    			$data2 = $p2[2].'-'.$p2[1].'-'.$p2[0];
    		} else {
    			$data2 = date('Y').'-'.date('m').'-'.date('d');
    		}
    		$link .= '&amp;de='.$de.'&amp;ate='.$ate;
	    }

		$wherecont = " and t1.cod_sistema = ".ConfigVO::getCodSistema();

        // query conteudo
        if ($palavra)
            $wherecont .= " AND (t1.titulo like '%".$palavra."%' OR t1.descricao like '%".$palavra."%')";

        if ($data1 && $data2) {
			$wherecont .= " AND (t1.datahora >= '".$data1." 23:59:00' AND t1.datahora <= '".$data2." 00:00:00')";
		}
		
		//if()

		// dados tag relacionada
		if ($tag) {
			//$query = $this->banco->executaQuery("SELECT * FROM Tags WHERE tag='".trim($tag)."'");
			$query = $this->banco->executaQuery("SELECT * FROM Tags WHERE tag='".trim($tag)."' AND cod_sistema='".ConfigVO::getCodSistema()."'");
			$rowtag = $this->banco->fetchArray($query);

			$wherecont .= " AND t2.cod_tag='".$rowtag['cod_tag']."'";
			//print_r($rowtag);
		}
		
		// relacionado ao conteudo
		if ($relacionado) {
			$lista_tags = array();
			$query = $this->banco->executaQuery("select cod_tag from Conteudo_Tags where cod_conteudo = '".$relacionado."'");
	        while ($row = $this->banco->fetchArray($query))
				$lista_tags[] = $row['cod_tag'];
			
			$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Tags WHERE cod_tag IN ('".implode("','", $lista_tags)."') AND cod_conteudo != '".$relacionado."')";
		}
		
		// dos mesmos autores
		if ($maisautor) {
			$arrayAutores = array();
			$query = $this->banco->executaQuery("SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='".$maisautor."'");
        	while ($row = $this->banco->fetchArray($query))
    			$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];
    		
    		$wherecont .= " AND t1.cod_conteudo IN (SELECT t7.cod_conteudo FROM Conteudo_Autores_Ficha AS t7 INNER JOIN Conteudo AS t8 ON (t7.cod_conteudo=t8.cod_conteudo) INNER JOIN Urls AS t9 ON (t7.cod_conteudo=t9.cod_item) WHERE t7.cod_conteudo != '".$maisautor."' AND t7.cod_usuario IN (".implode(',', $arrayAutores).") AND t8.situacao='1' AND t8.publicado='1' AND t9.tipo = 4)";
		}
		
		
		if($cidade[0]){
	
		foreach($cidade as $cod_cidade) {
		//echo $cod_cidade;
		if ((int)$cod_cidade)
			$wherecont .=" AND u.cod_cidade=".(int)$cod_cidade;
		}}
		
		    if($estado[0]){
			foreach($estado as $codestado){
				if ((int)$codestado)
				$wherecont .= " AND u.cod_estado = ".(int)$codestado;
			}
			}
		
		// dados canal
		if ($canal)
			$wherecont .= " AND t1.cod_segmento='".$canal."'";
		
		// dados individuais
		if ($cod) {
			if ($autor)
				$wherecont .= " AND (t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_usuario='".$cod."') OR t1.cod_conteudo IN (SELECT ta.cod_conteudo FROM Conteudo_Autores AS ta INNER JOIN Conteudo AS tb ON (ta.cod_conteudo=tb.cod_conteudo) WHERE ta.cod_usuario='".$cod."' AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=ta.cod_conteudo) AND tb.excluido='0' AND tb.situacao='1' AND tb.publicado='1'))";
				//$wherecont .= " AND (t1.cod_conteudo IN (SELECT tf.cod_conteudo FROM Conteudo_Autores AS tf INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE tf.cod_usuario='".$cod."' AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=tf.cod_conteudo) AND t2.excluido='0' AND t2.situacao='1' AND t2.publicado='1'))";
				

			if ($colaborador)
				$wherecont .= " AND t1.cod_colaborador='".$cod."'";

			if ($grupo)
				$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Grupos WHERE cod_grupo='".$cod."')";
		}

        if ($textos)
			$arrayF[] = 1;

		if ($imagens)
			$arrayF[] = 2;

        if ($audios)
			$arrayF[] = 3;

		if ($videos)
			$arrayF[] = 4;

		if ($noticias)
			$arrayF[] = 5;
			
		if ($agenda)
			$arrayF[] = 6;
			
		if ($canais)
            $arrayF[] = 7;
			
		if (count($arrayF))
			$wherecont .= " AND t1.cod_formato IN "."('".implode("','", $arrayF)."')";
		else
			$wherecont .= " AND t1.cod_formato IN (1, 2, 3, 4, 5, 6)";
			
		// local de eventos (agenda)
		if ($local && $agenda) {
			$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Agenda WHERE cidade LIKE '".$local."%' AND data_inicial >= '".date('Y-m-d')."')";
			
		}
		
	

		// query conteudo
		if (!$ordem) {
        	$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Tags AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1'  AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario)  $wherecont)";
        } elseif ($ordem) {
			switch ($ordem) {
				case 'votados':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont )";
					$orderby = " t2.num_recomendacoes DESC";
				break;
				case 'acessados':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1'  AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont)";
					$orderby = " t2.num_acessos DESC";
				break;
				case 'recentes':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1'  AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont)";
					$orderby = " t1.cod_conteudo DESC";
				break;
				case 'destaques':
					if ($colaborador) $item = '3';
					if ($autor) $item = '2';
					if ($grupo) $item = '4';

					//$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.cod_item FROM Conteudo AS t1 LEFT JOIN Home_Itens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t2.pagina='".$item."' AND cod_usuario='".$cod."')";
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.cod_item FROM Usuarios u, Conteudo AS t1 LEFT JOIN Home_Itens_Apresentacao AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t2.cod_playlist='".$play."' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario)) ";
					$orderby = " t2.ordem DESC";
				break;
			}
		}
		
	
		  
		  
		if (!$orderby)
			$orderby = " data DESC";

		 // query usuario
		$whereusuario = "t1.disponivel = 1 and t1.situacao = 3 and t1.cod_sistema = ".ConfigVO::getCodSistema();
		if ($palavra)
        	$whereusuario .= " AND (t1.nome like '%".$palavra."%' OR t1.cod_usuario IN (SELECT cod_usuario FROM Autores WHERE nome_completo LIKE '%".$palavra."%'))";
		if ($data1 && $data2)
			$whereusuario .= " AND (t1.datacadastro >= '".$data1." 23:59:00' AND t1.datacadastro <= '".$data2." 00:00:00')";
			
		// todos autores de um conteudo
		if ($todos_autores && $tipo_atividade) {
			$arrayAutores = array();
			//$query = $this->banco->executaQuery("SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='".$todos_autores."' AND cod_atividade='".$tipo_atividade."'");
			$query = $this->banco->executaQuery("SELECT DISTINCT(cod_usuario) FROM Conteudo_Autores_Ficha WHERE cod_atividade='".$tipo_atividade."'");
        	while ($row = $this->banco->fetchArray($query))
    			$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];
    		
    		$whereusuario .= " AND t1.cod_usuario IN (".implode(',', $arrayAutores).")";
    		$orderby = " t1.imagem DESC";
		}
			
		// cidades / usuarios
		if ($cidade) {
			if (!$cidade) { $cidade = array(0); }
			foreach($cidade as $cod_cidade) {
				if (!intval($cod_cidade)) {
					$cidade = array(0);
					break;
				}
			}
			if ($cidade[0]) { 
			//echo "entrou";
			if ((int)$cidade)
				$whereusuario .= " AND t1.cod_cidade = ".implode(", ", $cidade)."";
			}
			foreach($cidade as $cod_cidade) {
				if ((int)$cod_cidade)
					$link .= "&amp;cidade[]=$cod_cidade";
			}
		}
		
		// estados / usuarios
		if ($estado) {
			if (!$estado) { $estado = array(0); }
			foreach($estado as $cod_estado) {
				if (!intval($cod_estado)) {
					$estado = array(0);
					break;
				}
			}
			if ($estado[0]) {
				if ((int)$estado)
					$whereusuario .= " AND t1.cod_estado = ".implode(", ", $estado)."";
			}
			foreach($estado as $cod_estado) {
				if ((int)$cod_estado)
					$link .= "&amp;estado[]=$cod_estado";
			}
		}
		
		if ($autores || $colaboradores || $grupos) {
			switch ($ordem) {
				case 'recentes': $orderby = 't1.cod_usuario DESC'; break;
				case 'ativos': $orderby = 't1.geral DESC'; break;
			}
		}
		
		if ($codvinculado) {
			$sql_1 = "SELECT DISTINCT(t2.cod_usuario) FROM Conteudo AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_colaborador='".$codvinculado."'";
        	$sql_result = $this->banco->executaQuery($sql_1);
			$arrayAutores = array();
        	
			while ($sql_row = $this->banco->fetchArray($sql_result))
		    	$arrayAutores[$sql_row['cod_usuario']] = $sql_row['cod_usuario'];
			
			$whereusuario .= " t1.cod_usuario IN (".implode(', ', $arrayAutores).")";
			$autores = true;
		}
			
		$compwhereusuario = "WHERE $whereusuario";
		
		
		// query colaborador
		$querycolab = "(SELECT t1.cod_usuario AS cod, 2 AS tipo, t1.datacadastro AS data FROM v_colaboradores AS t1 $compwhereusuario and t1.cod_tipo = 1)";
		//$querycolab = "(SELECT t1.cod_usuario AS cod, 2 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 1)";
		// query autor
		$queryautor = "(SELECT t1.cod_usuario AS cod, 3 AS tipo, t1.datacadastro AS data FROM v_autores AS t1 $compwhereusuario and t1.cod_tipo = 2)";
		//$queryautor = "(SELECT t1.cod_usuario AS cod, 3 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 2)";
		// query grupos
		//$querygrupo = "(SELECT t1.cod_usuario AS cod, 4 AS tipo, t1.datacadastro AS data FROM v_grupos AS t1 $compwhereusuario and t1.cod_tipo = 3)";
		$querygrupo = "(SELECT t1.cod_usuario AS cod, 4 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 3)";
		// query canais
		$querycanais = "(SELECT t1.cod_segmento AS cod, 5 AS tipo, t1.cod_segmento AS data FROM Conteudo_Segmento AS t1 where disponivel = 1 ".($palavra ? "AND nome like '".$palavra."%'" : "")." and cod_sistema=".ConfigVO::getCodSistema().")";
		
		//if ($colaboradores)
			//$sql .= " union ".$querycolab;
		//	$sql = $querycolab;
		//if ($autores)
			//$sql .= " union ".$queryautor;
		//	$sql = $queryautor;
		//if ($grupos)
			//$sql .= " union ".$querygrupo;
		//	$sql = $querygrupo;
		
		if ($colaboradores)
			$sql = $querycolab;

		if ($autores) {
			if ($colaboradores)
				$sql = "$sql UNION $queryautor";
			else
				$sql = $queryautor;
		}

		if ($grupos) {
			if ($autores || $colaboradores)
				$sql = "$sql UNION $querygrupo";
			else
				$sql = $querygrupo;
		}

		if ($canais) {
			if ($autores || $colaboradores || $grupos)
				$sql = "$sql UNION $querycanais";
			else
				$sql = $querycanais;
		}

		if (count($arrayF)) {
			if ($autores || $colaboradores || $grupos || $canais)
				$sql = "$sql UNION $querycont";
			else
				$sql = $querycont;
		}

		if ($cod)
			$sql = $querycont;

		if ($tag)
			$sql = $querycont;

		//if ($canais)
		//	$sql = $querycanais;
			
		


		if(!$sql)
			//$sql = $querycont;
			//$sql="$querycont UNION $queryautor" ;
			$sql = "$querycont UNION $queryautor UNION $querycolab UNION $querygrupo UNION $querycanais";
//echo $sql;
		$array['link'] = $link;
		
		$querycompleta = $this->banco->executaQuery($sql);
		$totalcompleta  = $this->banco->numRows($querycompleta);
		
		//$queryc = $this->banco->executaQuery("SELECT  s.cod_segmento FROM Conteudo_Segmento s WHERE disponivel=1 AND nome like '$palavra%'and cod_sistema=".ConfigVO::getCodSistema());
        //$totalcanais= mysql_num_rows($queryc);
		
		if((!$cidade)&&(!$estado))	{
		$totalcompleta=$totalcompleta+$totalcanais;
		}
		$array['total'] = $totalcompleta;
       
		$query = $this->banco->executaQuery("$sql ORDER BY $orderby LIMIT $inicial,$mostrar");
		
		$sql2 = "$sql ORDER BY $orderby LIMIT $inicial,$mostrar";
		
		//aqui1
		//echo $sql; die;
		//echo $sql2; die;


		// class
		$contdao = new ConteudoExibicaoDAO;
		$usuariodao = new UsuarioDAO;
		$canaldao = new SegmentoDAO;

		$array['totalizacao'] = array();
		$array['totalizacao']['total'] = $array['total'];
		
		// lateral
		if ($apenas)
			$link .= '&amp;apenas='.$apenas;

		/* descomentar depois
		while ($row2 = $this->banco->fetchArray($querycompleta)) {
			switch($row2['tipo']) {
				case 1:
					$dadosc = $contdao->getConteudoResumido($row2['cod']);
					switch($dadosc['cod_formato']) {
						case 1: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont1; break;
						case 2: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont2; break;
						case 3: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont3; break;
						case 4: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont4; break;
						case 5: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont5; break;
						case 6: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont6; break;
						case 7: $array['totalizacao'][$dadosc['cod_formato']] = ++$cont7; break;
					}
					break;
				case 2;
					$array['totalizacao']['colaborador'] = ++$contcolab;
					break;
				case 3:
					$array['totalizacao']['autor'] = ++$contautor;
					break;
			}
		}
		*/

		$busca = array();

		while ($row = $this->banco->fetchArray($query)) {
			$busca[] = array (
				'cod' => $row['cod'],
				'tipo' => $row['tipo']
			);
		}
		
		foreach($busca as $cod => $value) {
			switch($value['tipo']) {
				case 1:
					$dadosc = $contdao->getConteudoResumido($value['cod']);
					
					if ($dadosc['cod_formato'] == 6)
						$dadosc['url_titulo'] = '/evento.php?cod='.$value['cod'];
					
					$array[] = array(
						'cod' => $dadosc['cod_conteudo'],
						'titulo' => $dadosc['titulo'],
						//'titulo' => $this->marcarPalavra($dadosc['titulo'], $palavra),
						'imagem' => $dadosc['imagem'],
						'tformato' => ucfirst(Util::getFormatoConteudoBusca($dadosc['cod_formato'])),
						'url_secao' => Util::getFormatoConteudoBusca($dadosc['cod_formato']).'.php',
						'descricao' => $dadosc['descricao'],
						'canal' => ($dadosc['cod_segmento'] ? Util::getHtmlCanal($dadosc['cod_segmento'], ''). ' |' : ''),
						//'descricao' => $this->marcarPalavra($dadosc['descricao'], $palavra),
						'url_titulo' => $dadosc['url_titulo'],
						'data' => date('d.m.Y - H\\hi', strtotime($dadosc['data_cadastro'])),
						'tipo' => ($dadosc['tipo'] == 2 ? '2' : 'a'),
						'icon' => Util::getIconeConteudo($dadosc['cod_formato']),
						'imagem_padrao' => Util::getImagemPadraoConteudo($dadosc['cod_formato']),
						'video' => $this->getConteudoVideo($value['cod'], $dadosc['cod_formato']),
						'cod_formato' => $dadosc['cod_formato'],
					);
				break;
				case 2:
					$dadosc = $usuariodao->getUsuarioDados($value['cod']);
					$array[] = array(
						'cod' => $dadosc['cod_usuario'],
						'titulo' => $dadosc['nome'],
						'imagem' => $dadosc['imagem'],
						'descricao' => $dadosc['descricao'],
						'url_titulo' => $dadosc['url'],
						'data' => date('d.m.Y - H\\hi', strtotime($dadosc['datacadastro'])),
						'url_secao' => 'colaboradores.php',
						'tformato' => 'Colaboradores',
						'icon' => 'colaborador',
						'tipo' => 'a',
						'imagem_padrao' => Util::getImagemPadraoUsuario(1)
					);
				break;
				case 3:
					$dadosc = $usuariodao->getUsuarioDados($value['cod']);
					$array[] = array(
						'cod' => $dadosc['cod_usuario'],
						'titulo' => $dadosc['nome'],
						'imagem' => $dadosc['imagem'],
						'descricao' => $dadosc['descricao'],
						'data' => date('d.m.Y - H\\hi', strtotime($dadosc['datacadastro'])),
						'url_titulo' => $dadosc['url'],
						'url_secao' => 'autores.php',
						'tipo' => 'a',
						'tformato' => 'Autores',
						'icon' => 'autor',
						'imagem_padrao' => Util::getImagemPadraoUsuario(2)
					);
				break;
				case 4:
					$dadosc = $usuariodao->getUsuarioDados($value['cod']);
					$array[] = array(
						'cod' => $dadosc['cod_usuario'],
						'titulo' => $dadosc['nome'],
						'imagem' => $dadosc['imagem'],
						'descricao' => $dadosc['descricao'],
						'data' => date('d.m.Y - H\\hi', strtotime($dadosc['datacadastro'])),
						'url_titulo' => $dadosc['url'],
						'url_secao' => 'grupos.php',
						'tipo' => 'a',
						'tformato' => 'Grupos',
						'icon' => 'grupo',
						'imagem_padrao' => Util::getImagemPadraoUsuario(3)
					);
				break;
				case 5:
					$dadosc = $canaldao->getSegmentoDados($value['cod']);
					$array[] = array(
						'cod' => $dadosc['cod_segmento'],
						'titulo' => $dadosc['nome'],
						'imagem' => $dadosc['imagem'],
						'descricao' => $dadosc['descricao'],
						//'data' => date('d.m.Y - H\\hi', strtotime($dadosc['datacadastro'])),
						'url_titulo' => '/canal.php?cod='.$value['cod'],
						'url_secao' => 'canais.php',
						'tipo' => 'a',
						'tformato' => 'Canal',
						'icon' => 'canal',
						//'imagem_padrao' => Util::getImagemPadraoUsuario(3)
						'canal' => 'Conteúdos: ' . $canaldao->getTotalConteudoPorCodSegmento($value['cod']),
					);
				break;
  			}
  		}
		//echo $querycont;
  		return $array;
    }
	
	public function getResultadoBuscaFiltro($get) {
		extract($get);	
		$html = '';
		$link = '/busca_resultado.php?buscar=1&novabusca=1';
		
		// zerando os contadores
		$total_geral = $total_audios = $total_videos = $total_textos = $total_imagens = $total_noticias = $total_eventos = $total_canais = $total_autores = $total_colaboradores = 0;
		
		// quando precisar não "marcar" uma busca
		$marca_aud = $marca_vid = $marca_txt = $marca_img = false;
		
		// se tiver cod deve buscar em todos os tipos abaixos
		if ($cod)
			$audios = $videos = $textos = $imagens = $noticias = $agenda = true;
		
		// se tiver canal deve buscar em todos os tipos abaixos	
		if ($canal) {
			$audios = $videos = $textos = $imagens = true;
			$sem_complemento = true;
		}
		
		// wheres comum aos conteudos multimidia
		// conteudo de um unico canal
		$sqlwhere_conteudo_canal = " AND cod_segmento = " . $canal;
		
		// conteudo dos mesmos autores
		$arrayAutores = array();
		$query = $this->banco->executaQuery('SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='.$maisautor);
        while ($row = $this->banco->fetchArray($query))
    		$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];
    	$sqlwhere_conteudo_domesmo_autor .= " AND cod_conteudo IN (SELECT t1.cod_conteudo FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t2.cod_conteudo=t3.cod_item) WHERE t2.cod_conteudo != '".$maisautor."' AND t1.cod_usuario IN (".implode(',', $arrayAutores).") AND t2.situacao='1' AND t2.publicado='1' AND t3.tipo = 4)";
		
		// conteudo do mesmo autor ou colaborador
		if ($autor)
			$sqlwhere_conteudo_domesmo_autor_ou_colaborador = " AND (cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_usuario='".$cod."') OR cod_conteudo IN (SELECT ta.cod_conteudo FROM Conteudo_Autores AS ta INNER JOIN Conteudo AS tb ON (ta.cod_conteudo=tb.cod_conteudo) WHERE ta.cod_usuario='".$cod."' AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=ta.cod_conteudo) AND tb.excluido='0' AND tb.situacao='1' AND tb.publicado='1'))";

		if ($colaborador)
			$sqlwhere_conteudo_domesmo_autor_ou_colaborador = ' AND cod_colaborador='.$cod;
			
		// links complementares
		if ((int)$canal) $link .= '&amp;canal='.(int)$canal;
		
		// busca audios
		if ($audios) {
			$sqlaudios = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='3' AND cod_sistema=".ConfigVO::getCodSistema();
			
			if (!$sem_complemento)
				$url_audio = '&audios=1';

			if ((int)$canal) $sqlaudios .= $sqlwhere_conteudo_canal;
			if ((int)$maisautor) $sqlaudios .= $sqlwhere_conteudo_domesmo_autor;
			if ((int)$cod) $sqlaudios .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;

			$query = $this->banco->executaQuery($sqlaudios);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_audios = (int)$row['total'];
		}
		
		// busca videos
		if ($videos) {
			$sqlvideos = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='4' AND cod_sistema=".ConfigVO::getCodSistema();
			
			if ((int)$canal) $sqlvideos .= $sqlwhere_conteudo_canal;
			if ((int)$maisautor) $sqlvideos .= $sqlwhere_conteudo_domesmo_autor;
			if ((int)$cod) $sqlvideos .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;
			
			$query = $this->banco->executaQuery($sqlvideos);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_videos = (int)$row['total'];
		}
		
		// busca textos
		if ($textos) {
			$sqltextos = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='1' AND cod_sistema=".ConfigVO::getCodSistema();
			
			//$link .= '&amp;textos=1';

			if ((int)$canal) $sqltextos .= $sqlwhere_conteudo_canal;
			if ((int)$maisautor) $sqltextos .= $sqlwhere_conteudo_domesmo_autor;
			if ((int)$cod) $sqltextos .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;
			
			$query = $this->banco->executaQuery($sqltextos);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_textos = (int)$row['total'];
		}
		
		// busca imagens
		if ($imagens) {
			$sqlimagens = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='2' AND cod_sistema=".ConfigVO::getCodSistema();
			
			//$link .= '&amp;imagens=1';

			if ((int)$canal) $sqlimagens .= $sqlwhere_conteudo_canal;
			if ((int)$maisautor) $sqlimagens .= $sqlwhere_conteudo_domesmo_autor;
			if ((int)$cod) $sqlimagens .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;
			
			$query = $this->banco->executaQuery($sqlimagens);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_imagens = (int)$row['total'];
		}
		
		// busca noticias
		if ($noticias) {
			$sqlnoticias = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='5' AND cod_sistema=".ConfigVO::getCodSistema();

			if ((int)$cod) $sqlnoticias .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;
			
			$query = $this->banco->executaQuery($sqlnoticias);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_noticias = (int)$row['total'];
		}
		
		// busca eventos
		if ($agenda) {
			$sqleventos = "SELECT COUNT(cod_conteudo) AS total FROM Conteudo WHERE excluido='0' AND publicado='1' AND situacao='1' AND cod_formato='6' AND cod_sistema=".ConfigVO::getCodSistema();

			if ((int)$cod) $sqleventos .= $sqlwhere_conteudo_domesmo_autor_ou_colaborador;
			
			$query = $this->banco->executaQuery($sqleventos);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_eventos = (int)$row['total'];
		}
		
		// busca canais
		if ($canais) {
			$sqlcanais = "SELECT COUNT(1) AS total FROM Conteudo_Segmento WHERE disponivel = '1' AND cod_sistema=".ConfigVO::getCodSistema();
			
			$sqlcanais .= $sqlwhere;
			
			$query = $this->banco->executaQuery($sqlcanais);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_canais = (int)$row['total'];
		}
		
		// busca autores
		if ($autores) {
			$sqlautores = "SELECT COUNT(cod_usuario) AS total FROM v_autores WHERE cod_tipo = 2 AND disponivel = 1 AND situacao = 3 AND cod_sistema = ".ConfigVO::getCodSistema();

			$sqlautores .= $sqlwhere;
			
			$query = $this->banco->executaQuery($sqlautores);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_autores = (int)$row['total'];
		}
		
		// busca colaboradores
		if ($colaboradores) {
			$sqlcolaboradores = "SELECT COUNT(cod_usuario) AS total FROM v_colaboradores WHERE cod_tipo = 1 AND disponivel = 1 AND situacao = 3 AND cod_sistema = ".ConfigVO::getCodSistema();

			$sqlcolaboradores .= $sqlwhere;
			
			$query = $this->banco->executaQuery($sqlcolaboradores);
			$row = $this->banco->fetchArray($query);
			$total_geral += $total_colaboradores = (int)$row['total'];
		}
		
		// marcação individual e total
		// mostrar conteudo de um determinado canal
		/*if ($canal)
			$td_true = $unset_categorias = true;
		elseif ($maisautor)
			$td_true = $unset_categorias = true;
		elseif ($cod)
			$td_true = $unset_categorias = true;
		
		if ($cod && $audios)
			$unset_categorias = false;
		
		if ($td_true) $td = true;
		if ($unset_categorias) unset($audios, $videos, $textos, $imagens, $noticias, $agenda, $autores, $colaboradores);
		*/
		
		// html final
		//if ($td)
		if (!$marcado || $marcado == 1)
			$html .= '<li class="filtro">Todos ('.$total_geral.')</li>';
		//elseif ($tdlink)
		//	$html .= '<li><a href="'.$link.'">Todos </a>('.$total_geral.')</li>';
		else {
			$inicia_marcado = strrpos($link, 'marcado=');
			$linktodos = ($inicia_marcado ? substr_replace($link, '', $inicia_marcado, ($inicia_marcado + 9)) : $link);
			$html .= '<li><a href="'.$linktodos.'&amp;marcado=1">Todos</a> ('.$total_geral.')</li>';
		}
		
		//if ($aud || $audios)
		if ($marcado == 2)
			$html .= '<li class="filtro">Áudios ('.$total_audios.')</li>';
		else
			$html .= '<li><a href="'.$link.$url_audio.'&amp;marcado=2">Áudios</a> ('.$total_audios.')</li>';
		
		//if ($vid || $videos)
		if ($marcado == 3)
			$html .= '<li class="filtro">Vídeos ('.$total_videos.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=3">Vídeos</a> ('.$total_videos.')</li>';
		
		//if ($txt || $textos || ($marcado == 4))
		if ($marcado == 4)
			$html .= '<li class="filtro">Textos ('.$total_textos.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=4">Textos</a> ('.$total_textos.')</li>';

		//if ($img || $imagens)
		if ($marcado == 5)
			$html .= '<li class="filtro">Imagens ('.$total_imagens.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=5">Imagens</a> ('.$total_imagens.')</li>';

		//if ($not || $noticias)
		if ($marcado == 6)
			$html .= '<li class="filtro">Notícias ('.$total_noticias.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=6">Notícias</a> ('.$total_noticias.')</li>';

		//if ($agd || $agenda)
		if ($marcado == 7)
			$html .= '<li class="filtro">Eventos ('.$total_eventos.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=7">Eventos</a> ('.$total_eventos.')</li>';

		//if($can || $canais)
		if ($marcado == 8)
		    $html .= '<li class="filtro">Canais ('.$total_canais.')</li>';
		else
		    $html .= '<li><a href="'.$link.'&amp;marcado=8" class="canal">Canais</a> ('.$total_canais.')</li>';

		//if ($aut || $autores)
		if ($marcado == 9)
			$html .= '<li class="filtro">Autores ('.$total_autores.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=9">Autores</a> ('.$total_autores.')</li>';

		//if ($col || $colaboradores)
		if ($marcado == 10)
			$html .= '<li class="filtro">Colaboradores ('.$total_colaboradores.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;marcado=10">Colaboradores</a> ('.$total_colaboradores.')</li>';

		return $html;
	}
	
	/*
	public function getResultadoBuscaFiltro($get) {

		extract($get);
        $array = array();
       
        if ($assunto)
			$palavra = trim($assunto);

        $link  = '/busca_resultado.php?buscar=1';
        $link .= '&amp;palavra='.$palavra;

        if ($cod)
        	$link .= '&amp;cod='.$cod;
        if ($autor)
        	$link .= '&amp;autor='.$autor;
        if ($colaborador)
        	$link .= '&amp;colaborador='.$colaborador;
        if ($grupo)
        	$link .= '&amp;grupo='.$grupo;
        if ($canal)
        	$link .= '&amp;canal='.$canal;
        if ($ordem)
        	$link .= '&amp;ordem='.$ordem;
        if ($maisautor)
        	$link .= '&amp;maisautor='.$maisautor;
        if ($local)
        	$link .= '&amp;local='.$local;
        if ($play)
        	$link .= '&amp;play='.$play;
		if ($relacionado)
			$link .= '&amp;relacionado='.$relacionado;
		if ($codvinculado)
			$link .= '&amp;codvinculado='.$codvinculado;
		if ($todos_autores)
			$link .= '&amp;todos_autores='.$todos_autores;
		if ($tipo_atividade)
			$link .= '&amp;tipo_atividade='.$tipo_atividade;
		if ($tag)
			$link .= '&amp;tag='.$tag;
			

        // data
        if ($de) {
    		$p1 = explode('/', $de);
    		$data1 = $p1[2].'-'.$p1[1].'-'.$p1[0];
    		if ($ate) {
    			$p2 = explode('/', $ate);
    			$data2 = $p2[2].'-'.$p2[1].'-'.$p2[0];
    		} else {
    			$data2 = date('Y').'-'.date('m').'-'.date('d');
    		}
    		$link .= '&amp;de='.$de.'&amp;ate='.$ate;
	    }

		$wherecont = " and t1.cod_sistema = ".ConfigVO::getCodSistema();

        // query conteudo
        if ($palavra)
            $wherecont .= " AND (t1.titulo like '%".$palavra."%' OR t1.descricao like '%".$palavra."%')";

        if ($data1 && $data2) {
			$wherecont .= " AND (t1.datahora >= '".$data1." 23:59:00' AND t1.datahora <= '".$data2." 00:00:00')";
		}

		// dados tag relacionada
		if ($tag) {
			//$query = $this->banco->executaQuery("SELECT * FROM Tags WHERE tag='".trim($tag)."'");
			$query = $this->banco->executaQuery("SELECT * FROM Tags WHERE tag='".trim($tag)."' AND cod_sistema='".ConfigVO::getCodSistema()."'");
			$rowtag = $this->banco->fetchArray($query);

			$wherecont .= " AND t2.cod_tag='".$rowtag['cod_tag']."'";
			//print_r($rowtag);
		}
		
		// relacionado ao conteudo
		if ($relacionado) {
			$lista_tags = array();
			$query = $this->banco->executaQuery("select cod_tag from Conteudo_Tags where cod_conteudo = '".$relacionado."'");
	        while ($row = $this->banco->fetchArray($query))
				$lista_tags[] = $row['cod_tag'];
			
			$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Tags WHERE cod_tag IN ('".implode("','", $lista_tags)."') AND cod_conteudo != '".$relacionado."')";
		}
		
		// dos mesmos autores
		if ($maisautor) {
			$arrayAutores = array();
			$query = $this->banco->executaQuery("SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='".$maisautor."'");
        	while ($row = $this->banco->fetchArray($query))
    			$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];
    		
    		$wherecont .= " AND t1.cod_conteudo IN (SELECT t7.cod_conteudo FROM Conteudo_Autores_Ficha AS t7 INNER JOIN Conteudo AS t8 ON (t7.cod_conteudo=t8.cod_conteudo) INNER JOIN Urls AS t9 ON (t7.cod_conteudo=t9.cod_item) WHERE t7.cod_conteudo != '".$maisautor."' AND t7.cod_usuario IN (".implode(',', $arrayAutores).") AND t8.situacao='1' AND t8.publicado='1' AND t9.tipo = 4)";
		}
		
		// dados canal
		if ($canal)
			$wherecont .= " AND t1.cod_segmento='".$canal."'";

		// dados individuais
		if ($cod) {
			if ($autor)
				$wherecont .= " AND (t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_usuario='".$cod."') OR t1.cod_conteudo IN (SELECT ta.cod_conteudo FROM Conteudo_Autores AS ta INNER JOIN Conteudo AS tb ON (ta.cod_conteudo=tb.cod_conteudo) WHERE ta.cod_usuario='".$cod."' AND NOT EXISTS (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=ta.cod_conteudo) AND tb.excluido='0' AND tb.situacao='1' AND tb.publicado='1'))";

			if ($colaborador)
				$wherecont .= " AND t1.cod_colaborador='".$cod."'";

			if ($grupo)
				$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Grupos WHERE cod_grupo='".$cod."')";
		}

        if ($textos)
			$arrayF[] = 1;

		if ($imagens)
			$arrayF[] = 2;

        if ($audios)
			$arrayF[] = 3;

		if ($videos)
			$arrayF[] = 4;

		if ($noticias)
			$arrayF[] = 5;
			
		if ($agenda)
			$arrayF[] = 6;
			
		if($canal)
			$arrayF[] = 7;
		if (count($arrayF))
			$wherecont .= " AND t1.cod_formato IN "."('".implode("','", $arrayF)."')";
		else
			if (!count($arrayF))
				$wherecont .= " AND t1.cod_formato IN (1, 2, 3, 4, 5, 6)";
			
		
		$cont_estado = "";
				
			if($cidade[0]){
	    
		//$this->banco->executaQuery("SELECT COUNT()"
		foreach($cidade as $cod_cidade) {
		//echo $cod_cidade;
			if ((int)$cod_cidade)
				$wherecont .=" AND u.cod_cidade=".(int)$cod_cidade;
		}}
		
		    if($estado[0]){
			foreach($estado as $codestado){
				if ((int)$codestado)
					$wherecont .= " AND u.cod_estado = ".(int)$codestado;
			}
			}
		
		// dados canal
		if ($canal)
			$wherecont .= " AND t1.cod_segmento='".$canal."'";
		
			$wherecont .=$cont_estado;
		// local de eventos (agenda)
		if ($local && $agenda) {
			$wherecont .= " AND t1.cod_conteudo IN (SELECT cod_conteudo FROM Agenda WHERE cidade LIKE '".$local."%' AND data_inicial >= '".date('Y-m-d')."')";
			
		}
		
		//echo $wherecont;
		
		// query conteudo
		if (!$ordem) {
        	$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Tags AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont )";
        } elseif ($ordem) {
			switch ($ordem) {
				case 'votados':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont )";
					$orderby = " t2.num_recomendacoes DESC";
				break;
				case 'acessados':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u,  Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont )";
					$orderby = " t2.num_acessos DESC";
				break;
				case 'recentes':
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.num_recomendacoes FROM Usuarios u, Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND (t1.cod_colaborador=u.cod_usuario OR t1.cod_autor=u.cod_usuario) $wherecont ) ";
					$orderby = " t1.cod_conteudo DESC";
				break;
				case 'destaques':
					if ($colaborador) $item = '3';
					if ($autor) $item = '2';
					if ($grupo) $item = '4';

					//$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.cod_item FROM Conteudo AS t1 LEFT JOIN Home_Itens AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t2.pagina='".$item."' AND cod_usuario='".$cod."')";
					$querycont = "(SELECT DISTINCT(t1.cod_conteudo) AS cod, 1 AS tipo, t1.data_cadastro AS data, t2.cod_item FROM Conteudo AS t1 LEFT JOIN Home_Itens_Apresentacao AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t2.cod_playlist='".$play."')";
					$orderby = " t2.ordem DESC";
				break;
			}
		}

		if (!$orderby)
			$orderby = " data DESC";

		 // query usuario
		$whereusuario = "t1.disponivel = 1 and t1.situacao = 3 and t1.cod_sistema = ".ConfigVO::getCodSistema();
		if ($palavra)
        	$whereusuario .= " AND (t1.nome like '%".$palavra."%' OR t1.cod_usuario IN (SELECT cod_usuario FROM Autores WHERE nome_completo LIKE '%".$palavra."%'))";
		if ($data1 && $data2)
			$whereusuario .= " AND (t1.datacadastro >= '".$data1." 23:59:00' AND t1.datacadastro <= '".$data2." 00:00:00')";
			
		// todos autores de um conteudo
		if ($todos_autores && $tipo_atividade) {
			$arrayAutores = array();
			//$query = $this->banco->executaQuery("SELECT cod_usuario FROM Conteudo_Autores_Ficha WHERE cod_conteudo='".$todos_autores."' AND cod_atividade='".$tipo_atividade."'");
			$query = $this->banco->executaQuery("SELECT DISTINCT(cod_usuario) FROM Conteudo_Autores_Ficha WHERE cod_atividade='".$tipo_atividade."'");
        	while ($row = $this->banco->fetchArray($query))
    			$arrayAutores[$row['cod_usuario']] = $row['cod_usuario'];
    		
    		$whereusuario .= " AND t1.cod_usuario IN (".implode(',', $arrayAutores).")";
    		$orderby = " t1.imagem DESC";
		}
		
		// cidades / usuarios
		if ($cidade) {
			if (!$cidade) { $cidade = array(0); }
			foreach($cidade as $cod_cidade) {
				if (!intval($cod_cidade)) {
					$cidade = array(0);
					break;
				}
			}
			if ($cidade[0]) {
				if ((int)$cidade)
					$whereusuario .= " AND t1.cod_cidade = ".implode(", ", $cidade)."";
			}
			foreach($cidade as $cod_cidade) {
				if ((int)$cod_cidade)
					$link .= "&amp;cidade[]=".$cod_cidade;
			}
		}
		
		// estados / usuarios
		if ($estado) {
			if (!$estado) { $estado = array(0); }
			foreach($estado as $cod_estado) {
				if (!intval($cod_estado)) {
					$estado = array(0);
					break;
				}
			}
			if ($estado[0]) {
				if ((int)$estado)
					$whereusuario .= " AND t1.cod_estado IN (".implode(", ", $estado).")";
			}
			foreach($estado as $cod_estado) {
				if ((int)$cod_estado)
					$link .= "&amp;estado[]=".$cod_estado;
			}
		}
		
		if ($autores || $colaboradores || $grupos) {
			switch ($ordem) {
				case 'recentes': $orderby = 't1.cod_usuario DESC'; break;
				case 'ativos': $orderby = 't1.geral DESC'; break;
			}
		}
		
		if ($codvinculado) {
			$sql_1 = "SELECT DISTINCT(t2.cod_usuario) FROM Conteudo AS t1 INNER JOIN Conteudo_Autores AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t1.cod_colaborador='".$codvinculado."'";
        	$sql_result = $this->banco->executaQuery($sql_1);
			$arrayAutores = array();
        	
			while ($sql_row = $this->banco->fetchArray($sql_result))
		    	$arrayAutores[$sql_row['cod_usuario']] = $sql_row['cod_usuario'];
			
			$whereusuario .= " t1.cod_usuario IN (".implode(', ', $arrayAutores).")";
			$autores = true;
		}
			
		$compwhereusuario = "WHERE $whereusuario";
		
		$querycolab = "(SELECT t1.cod_usuario AS cod, 2 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 1)";
		$queryautor = "(SELECT t1.cod_usuario AS cod, 3 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 2)";
		$querygrupo = "(SELECT t1.cod_usuario AS cod, 4 AS tipo, t1.datacadastro AS data FROM Usuarios AS t1 $compwhereusuario and t1.cod_tipo = 3)";
		$querycanais = "(SELECT t1.cod_segmento AS cod, 5 AS tipo, t1.cod_segmento AS data FROM Conteudo_Segmento AS t1 where disponivel = 1 ".($palavra ? "AND nome like '".$palavra."%'" : "")." and cod_sistema=".ConfigVO::getCodSistema().")";
		
		//if ($colaboradores)
		if (!$canal) {
			if ($colaboradores)
				$sql = $querycolab;
			if ($autores)
				$sql = $queryautor;
		}
		
		//if ($autores) {
			//if ($colaboradores)
				//if (!$canal && $colaboradores)
				//	$sql = "$sql UNION $queryautor";
			//else
			//	$sql = $queryautor;
		//}

		if ($grupos) {
			if ($autores || $colaboradores)
				$sql = "$sql UNION $querygrupo";
			else
				$sql = $querygrupo;
		}

		$count_canal = false;

		//if (count($arrayF)) {
		//	if ($autores || $colaboradores || $grupos)
				if ($canal)
					$sql = $querycont;
				//elseif($autores) {
				//	$sql = "$sql UNION $queryautor";
				//}
				//elseif($colaboradores) {
				//	$sql = "$sql UNION $querycolab";
				//}
				else {
					if (!$autores && !$colaboradores) {
						if ($sql) {
							$sql = "$sql UNION $querycont";
							$count_canal = true;
						}
					}
				}
		//	else
		//		$sql = $querycont;
		//}

		if ($cod)
			$sql = $querycont;

		if ($tag)
			$sql = $querycont;

		if ($canais)
			$sql = $querycanais;

		//echo $querycont; die;

		if(!$sql)
			$sql = "$querycont UNION $queryautor UNION $querycolab UNION $querycanais";
		elseif($buscatopo_tipo == 'todos')
			$sql = "$querycont UNION $queryautor UNION $querycolab UNION $querycanais";
		if (in_array($buscatopo_tipo, array('videos', 'textos', 'imagens', 'noticias', 'eventos')))
			$sql = $querycont;
			
		//echo $sql;
        //aqui2
		
		//echo $sql;
		
		$querycompleta = $this->banco->executaQuery($sql);
		$total = $this->banco->numRows($querycompleta);
		$onlyautor_colaborador=$_GET['only'];

		//$query = $this->banco->executaQuery("$sql ORDER BY $orderby LIMIT $inicial,$mostrar");
		//$sql2 = "$sql ORDER BY $orderby LIMIT $inicial,$mostrar";
		
		//echo $sql; die;
		//echo $sql2; die;

		// class
		$contdao = new ConteudoExibicaoDAO;
		$usuariodao = new UsuarioDAO;

		$totalizacao = array();
		
		// lateral
		if ($apenas)
			$link .= '&amp;apenas='.$apenas;

		
		while ($row2 = $this->banco->fetchArray($querycompleta)) {
			
			//print_r($row2);
			
			switch($row2['tipo']) {
				case 1:
					$dadosc = $contdao->getConteudoResumido($row2['cod']);
					switch($dadosc['cod_formato']) {
						case 1: $totalizacao[$dadosc['cod_formato']] = ++$cont1; break;
						case 2: $totalizacao[$dadosc['cod_formato']] = ++$cont2; break;
						case 3: $totalizacao[$dadosc['cod_formato']] = ++$cont3; break;
						case 4: $totalizacao[$dadosc['cod_formato']] = ++$cont4; break;
						case 5: $totalizacao[$dadosc['cod_formato']] = ++$cont5; break;
						case 6: $totalizacao[$dadosc['cod_formato']] = ++$cont6; break;
						case 7: $totalizacao[$dadosc['cod_formato']] = ++$cont7; break;
					}
					break;
				case 2;
					$totalizacao['colaborador'] = ++$contcolab;
					break;
				case 3:
					$totalizacao['autor'] = ++$contautor;
					break;
				case 5:
					$totalizacao['canal'] = ++$contcanal;
					break;
			}
		}
		
		//print_r($totalizacao);
		
		$query = $this->banco->executaQuery("SELECT  s.cod_segmento FROM Conteudo_Segmento s
WHERE disponivel=1 AND nome like '$palavra%'and cod_sistema=".ConfigVO::getCodSistema());
   
        $totalcanais= mysql_num_rows($query);		  
		//$linktodos  = '/busca_resultado.php?buscar=1';
        //$linktodos .= '&amp;palavra='.$palavra.'&amp;textos='.$textos.'&amp;imagens='.$imagens.'&amp;videos='.$videos.'&amp;audios='.$audios.'&amp;noticias='.$noticias.'&amp;colaboradores='.$colaboradores.'&amp;autores='.$autores.'&amp;grupos='.$grupos.'&amp;tag='.$tag.'&amp;relacionado='.$relacionado.'&amp;codvinculado='.$codvinculado.'&amp;todos_autores='.$todos_autores.'&amp;tipo_atividade='.$tipo_atividade.'&amp;agenda='.$agenda;
		
		$html  = '';
		//echo $querycont;
		//$total = $total+$icanal;
		//Link todos<a href="'.$link.'&amp;audios=1&amp;videos=1&amp;textos=1&amp;imagens=1&amp;noticias=1&amp;agenda=1&amp;autores=1&amp;colaboradores=1">
		if((!$cidade)&&(!$estado)){
		$total=$total;
		}
		
		if ($count_canal && !$canais && !$tag && !$autores && !$colaboradores && !$cod)
			$total = $total + $totalcanais;
		
		if(!$onlyautor_colaborador){
		if (!$td)
		  
			$html .= '<li class="filtro">Todos ('.$total.')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;audios=1&amp;videos=1&amp;textos=1&amp;imagens=1&amp;noticias=1&amp;agenda=1&amp;autores=1&amp;colaboradores=1">Todos </a>('.$total.')</li>';
		
		if ($aud)
			$html .= '<li class="filtro">Áudios ('.(int)$totalizacao[3].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;audios=1&amp;aud=1&amp;td=1">Áudios</a> ('.(int)$totalizacao[3].')</li>';
		
		if ($vid)
			$html .= '<li class="filtro">Vídeos ('.(int)$totalizacao[4].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;videos=1&amp;vid=1&amp;td=1">Vídeos </a> ('.(int)$totalizacao[4].')</li>';
		
		if ($txt)
			$html .= '<li class="filtro">Textos ('.(int)$totalizacao[1].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;textos=1&amp;txt=1&amp;td=1">Textos</a> ('.(int)$totalizacao[1].')</li>';
			
		if ($img)
			$html .= '<li class="filtro">Imagens ('.(int)$totalizacao[2].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;imagens=1&amp;img=1&amp;td=1">Imagens</a> ('.(int)$totalizacao[2].')</li>';
			
		if ($not)
			$html .= '<li class="filtro">Notícias ('.(int)$totalizacao[5].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;noticias=1&amp;not=1&amp;td=1">Notícias</a> ('.(int)$totalizacao[5].')</li>';
			
		if ($agd)
			$html .= '<li class="filtro">Eventos ('.(int)$totalizacao[6].')</li>';
		else
			$html .= '<li><a href="'.$link.'&amp;agenda=1&amp;agd=1&amp;td=1">Eventos</a> ('.(int)$totalizacao[6].')</li>';
			
		if(!$canal && !$tag && !$autores && !$colaboradores && !$cod){
		  if((!$cidade)&&(!$estado)){
		if($can) 
		    $html .= '<li class="filtro">Canais ('.(int)$totalcanais.')</li>';
		else
		    $html .= '<li><a href="'.$link.'&amp;canais=1&amp;can=1&amp;td=1" class="canal">Canais</a> ('.(int)$totalcanais.')</li>';  
			}	
			}
			}
		if($onlyautor_colaborador){
		//if (!$td)
		    $total=((int)$totalizacao['autor']+(int)$totalizacao['colaborador']);
			$html .= '<li class="filtro">Todos ('.$total.')</li>';
			}
		if ($aut)
			$html .= '<li class="filtro">Autores ('.(int)$totalizacao['autor'].')</li>';
		else
		    if($onlyautor_colaborador){
			$html .= '<li><a href="'.$link.'&amp;autores=1&amp;aut=1&amp;td=1&only=1">Autores</a> ('.(int)$totalizacao['autor'].')</li>';
			} else {
			$html .= '<li><a href="'.$link.'&amp;autores=1&amp;aut=1&amp;td=1">Autores</a> ('.(int)$totalizacao['autor'].')</li>';
			}
		if ($col)
			$html .= '<li class="filtro">Colaboradores ('.(int)$totalizacao['colaborador'].')</li>';
		else
		  if($onlyautor_colaborador){
			$html .= '<li><a href="'.$link.'&amp;colaboradores=1&amp;col=1&amp;td=1&only=1">Colaboradores</a> ('.(int)$totalizacao['colaborador'].')</li>';
            } else {
			$html .= '<li><a href="'.$link.'&amp;colaboradores=1&amp;col=1&amp;td=1">Colaboradores</a> ('.(int)$totalizacao['colaborador'].')</li>';
			}
  		return $html;
    }
	*/

}