<?php
require_once('ConexaoDB.php');

class BuscaCacheDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function setBuscaCache($id, $resultado, $total, $palavra, $expiracao) {
		if (count($resultado)) {
			foreach($resultado as $pagina => $dados) {
				foreach($dados as $item) {
					$this->banco->sql_insert('Busca_Cache', array(
						'id' 		=> $id,
						'pagina'	=> $pagina,
						'total'		=> $total,
						'palavra'	=> $palavra,
						'tipo' 		=> $item['tipo'],
						'coditem' 	=> $item['coditem'],
						'data' 		=> $item['data'],
						'expiracao'	=> $expiracao
					));
				}
			}
			return $id;
		}
		return 0;
	}

	public function getBuscaCache($id, $pagina) {
		$resultado = array();
		if ($id) {
			$query = $this->banco->sql_select('*', 'Busca_Cache', "id='".$id."' AND pagina='".$pagina."'", '', 'ordem ASC');
			while ($row = $this->banco->fetchArray($query))
				$resultado[] = $row;
		}
		return $resultado;
	}
	
	public function deletaCache($dataexpiracao) {
		$this->banco->sql_delete('Busca_Cache', "expiracao <= '".$dataexpiracao."'");
	}

	// conteudo
	public function getBuscaConteudo(&$bdadosvo, &$sql_complemento) {
		$lista = array();
		$sql_from = ' FROM Conteudo CO';
		$sql_where = ' WHERE CO.excluido = 0 AND CO.publicado = 1 AND CO.situacao = 1 AND '.$sql_complemento;
		$param_extra = &$bdadosvo->getParametrosExtra();
		
		if ($bdadosvo->getDataInicial() && $bdadosvo->getDataFinal())
			$sql_where .= " AND CO.data_cadastro >= '".$bdadosvo->getDataInicial()." 00:00:00' AND CO.data_cadastro <= '".$bdadosvo->getDataFinal()." 23:59:59'";
		
		if ($bdadosvo->getPalavraChave())
			$sql_where .= " AND (CO.titulo LIKE '".$bdadosvo->getPalavraChave()."%' OR CO.descricao LIKE '".$bdadosvo->getPalavraChave()."%')";
		
		if (isset($param_extra['codcanal']) && $param_extra['codcanal'])
			$sql_where .= " AND CO.cod_segmento = '".$param_extra['codcanal']."'";
			
		if (isset($param_extra['colaborador']) && $param_extra['colaborador'])
			$sql_where .= " AND CO.cod_colaborador='".$param_extra['colaborador']."'";
			
		if (isset($param_extra['autor']) && $param_extra['autor'])
			$sql_where .= " AND CO.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Autores_Ficha WHERE cod_conteudo=CO.cod_conteudo AND cod_usuario = '".$param_extra['autor']."')";

		if (isset($param_extra['ordenacao']) && $param_extra['ordenacao']) {
			switch($param_extra['ordenacao']) {
				case 1: $sql_where .= " AND CO.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Estatisticas WHERE cod_conteudo=CO.cod_conteudo AND num_recomendacoes > 0)"; break;
				case 2: $sql_where .= " AND CO.cod_conteudo IN (SELECT cod_conteudo FROM Conteudo_Estatisticas WHERE cod_conteudo=CO.cod_conteudo AND num_acessos > 0)"; break;
			}
		}
		
		if (isset($param_extra['direito']) && $param_extra['direito']) {
			switch($param_extra['direito']) {
				case 1: $sql_where .= " AND CO.cod_licenca = 8"; break;
				case 2: $sql_where .= " AND CO.cod_licenca = 6"; break;
			}
		}
		
		if (isset($param_extra['cidades']) && $param_extra['cidades'])
			$sql_where .= " AND (CO.cod_colaborador IN (SELECT cod_usuario FROM Usuarios WHERE cod_usuario = CO.cod_colaborador AND cod_cidade IN (".implode(', ', $param_extra['cidades']).")) OR CO.cod_autor IN (SELECT cod_usuario FROM Usuarios WHERE cod_usuario = CO.cod_autor AND cod_cidade IN (".implode(', ', $param_extra['cidades']).")))";

		if (isset($param_extra['estados']) && $param_extra['estados'])
			$sql_where .= " AND (CO.cod_colaborador IN (SELECT cod_usuario FROM Usuarios WHERE cod_usuario = CO.cod_colaborador AND cod_estado IN (".implode(', ', $param_extra['estados']).")) OR CO.cod_autor IN (SELECT cod_usuario FROM Usuarios WHERE cod_usuario = CO.cod_autor AND cod_estado IN (".implode(', ', $param_extra['estados']).")))";
			
		if (isset($param_extra['relacionado']) && $param_extra['relacionado'])
			$sql_where .= " AND CO.cod_conteudo IN (SELECT cod_conteudo2 FROM Conteudo_Relacionados WHERE cod_conteudo2=CO.cod_conteudo AND cod_conteudo1 = '".$param_extra['relacionado']."')";
			
		if (isset($param_extra['conteudo']) && $param_extra['conteudo'])
			$sql_where .= " AND CO.cod_conteudo IN (SELECT CAF.cod_conteudo FROM Conteudo_Autores_Ficha AS CAF INNER JOIN Conteudo AS C ON (CAF.cod_conteudo=C.cod_conteudo) WHERE CAF.cod_conteudo != '".$param_extra['conteudo']."' AND CAF.cod_usuario IN (SELECT CAF2.cod_usuario FROM Conteudo_Autores_Ficha CAF2 WHERE CAF2.cod_conteudo='".$param_extra['conteudo']."') AND C.situacao='1' AND C.publicado='1')";
			
		if (isset($param_extra['tag']) && $param_extra['tag'])
			$sql_where .= " AND CO.cod_conteudo IN (SELECT CT.cod_conteudo FROM Conteudo_Tags CT INNER JOIN Tags AS T ON (CT.cod_tag=T.cod_tag) WHERE CT.cod_conteudo=CO.cod_conteudo AND T.tag = '".$param_extra['tag']."')";
		
		$sql = 'SELECT /*busca_conteudo*/ CO.cod_conteudo, CO.data_cadastro '.$sql_from.$sql_where;
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$dados = $this->getEstatisticaConteudo($sql_row['cod_conteudo']);
			$lista[] = array('data' => $sql_row['data_cadastro'], 'coditem' => $sql_row['cod_conteudo'], 'acessos' => $dados['acessos'], 'recomendacoes' => $dados['recomendacoes']);
		}

		return $lista;
	}
	
	private function getEstatisticaConteudo(&$codconteudo) {
		$sql = "SELECT num_recomendacoes AS recomendacoes, num_acessos AS acessos FROM Conteudo_Estatisticas WHERE cod_conteudo = '".$codconteudo."'";
		$query = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($query);
	}
	
	public function getResultadoBuscaConteudo(&$codconteudo) {
    	$sql = "SELECT CO.*, U.titulo AS url_titulo FROM Conteudo AS CO LEFT JOIN Urls AS U ON (CO.cod_conteudo=U.cod_item) WHERE CO.cod_conteudo='".$codconteudo."' AND CO.excluido='0' AND CO.publicado='1' AND U.tipo='4'";
		$query = $this->banco->executaQuery($sql);
        $row = $this->banco->fetchArray($query);

        $row['tipo'] = 'a';
        if ($row['cod_formato'] == 2) {
			$sqlimg = "SELECT I.imagem FROM Imagens AS I LEFT JOIN Albuns AS A ON (I.cod_imagem=A.cod_imagem_capa) WHERE A.cod_conteudo='".$codconteudo."'";
			$queryimg = $this->banco->executaQuery($sqlimg);
        	$rowimg = $this->banco->fetchArray($queryimg);
        	$row['imagem'] = $rowimg['imagem'];
        	$row['tipo'] = 2;
		}
		return $row;
	}
	
	// autor/colaborador
	public function getBuscaAutores(&$bdadosvo, &$sql_complemento) {
		$lista = array();
		$sql_from = ' FROM v_autores VA';
		$sql_where = ' WHERE VA.disponivel = 1 AND VA.situacao = 3 AND VA.cod_tipo = 2 AND '.$sql_complemento;
		$param_extra = &$bdadosvo->getParametrosExtra();
		
		if ($bdadosvo->getPalavraChave())
			$sql_where .= " AND (VA.nome LIKE '%".$bdadosvo->getPalavraChave()."%' OR VA.descricao LIKE '%".$bdadosvo->getPalavraChave()."%' OR VA.cod_usuario IN (SELECT cod_usuario FROM Autores WHERE nome_completo LIKE '%".$bdadosvo->getPalavraChave()."%'))";

		if ($bdadosvo->getDataInicial() && $bdadosvo->getDataFinal())
			$sql_where .= " AND (VA.datacadastro >= '".$bdadosvo->getDataInicial()." 00:00:00' AND VA.datacadastro <= '".$bdadosvo->getDataFinal()." 23:59:59')";
			
		if (isset($param_extra['cidades']) && $param_extra['cidades'])
			$sql_where .= " AND VA.cod_cidade IN (".implode(', ', $param_extra['cidades']).")";
			
		if (isset($param_extra['estados']) && $param_extra['estados'])
			$sql_where .= " AND VA.cod_estado IN (".implode(', ', $param_extra['estados']).")";
			
		if (isset($param_extra['colaborador']) && $param_extra['colaborador'])
			$sql_where .= " AND VA.cod_usuario IN (SELECT DISTINCT(CA.cod_usuario) FROM Conteudo AS C INNER JOIN Conteudo_Autores AS CA WHERE C.cod_conteudo=CA.cod_conteudo AND C.cod_colaborador='".$param_extra['colaborador']."')";

		//if (isset($param_extra['ordenacao']) && $param_extra['ordenacao'])
		//	$sql_where .= " ORDER BY VC.geral DESC";
		
		$sql = 'SELECT /*busca_autores*/ VA.cod_usuario, VA.datacadastro '.$sql_from.$sql_where;
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			
			if (isset($param_extra['ordenacao']) && $param_extra['ordenacao']) {
				$sql2 = "SELECT COUNT(t1.cod_conteudo) AS total FROM Conteudo_Autores_Ficha AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) WHERE t2.excluido='0' AND t2.publicado='1' AND t2.situacao='1' AND t2.cod_formato IN (1,2,3,4) AND t2.cod_sistema='".ConfigVO::getCodSistema()."' AND t1.cod_usuario='".$sql_row['cod_usuario']."' GROUP BY t1.cod_usuario";
				$sql_result2 = $this->banco->executaQuery($sql2);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
			}
			
			$lista[] = array('data' => $sql_row['datacadastro'], 'coditem' => $sql_row['cod_usuario'], 'ativos' => $sql_row2[0]);
		}

		return $lista;
	}
	
	public function getBuscaColaboradores(&$bdadosvo, &$sql_complemento) {
		$lista = array();
		$sql_from = ' FROM v_colaboradores VC';
		$sql_where = ' WHERE VC.disponivel = 1 AND VC.situacao = 3 AND VC.cod_tipo = 1 AND '.$sql_complemento;
		$param_extra = &$bdadosvo->getParametrosExtra();
		
		if ($bdadosvo->getPalavraChave())
			$sql_where .= " AND (VC.nome LIKE '%".$bdadosvo->getPalavraChave()."%' OR VC.descricao LIKE '%".$bdadosvo->getPalavraChave()."%')";

		if ($bdadosvo->getDataInicial() && $bdadosvo->getDataFinal())
			$sql_where .= " AND (VC.datacadastro >= '".$bdadosvo->getDataInicial()." 00:00:00' AND VC.datacadastro <= '".$bdadosvo->getDataFinal()." 23:59:59')";
		
		if (isset($param_extra['cidades']) && $param_extra['cidades'])
			$sql_where .= " AND VC.cod_cidade IN (".implode(', ', $param_extra['cidades']).")";
			
		if (isset($param_extra['estados']) && $param_extra['estados'])
			$sql_where .= " AND VC.cod_estado IN (".implode(', ', $param_extra['estados']).")";
			
		if (isset($param_extra['ordenacao']) && $param_extra['ordenacao'])
			$sql_where .= " ORDER BY VC.geral DESC";
			
		$sql = 'SELECT /*busca_colaboradores*/ VC.cod_usuario, VC.datacadastro, VC.geral '.$sql_from.$sql_where;
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista[] = array('data' => $sql_row['datacadastro'], 'coditem' => $sql_row['cod_usuario'], 'ativos' => $sql_row['geral']);

		return $lista;
	}
	
	public function getResultadoBuscaUsuario(&$codusuario) {
		$sql = "SELECT t1.*, t3.sigla, t3.cod_estado, t4.cod_cidade, t4.cidade, t5.titulo AS url_titulo FROM Usuarios AS t1 LEFT JOIN Estados AS t3 ON (t1.cod_estado=t3.cod_estado) LEFT JOIN Cidades AS t4 ON (t1.cod_cidade=t4.cod_cidade) LEFT JOIN Urls AS t5 ON (t1.cod_usuario=t5.cod_item) WHERE t1.cod_usuario='".$codusuario."' AND t5.tipo=t1.cod_tipo";
		$query = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray($query);
	}
	
	// canais
	public function getBuscaCanais(&$bdadosvo, &$sql_complemento) {
		$lista = array();
		$sql_from = ' FROM Conteudo_Segmento CS';
		$sql_where = ' WHERE CS.disponivel = 1 AND '.$sql_complemento;
		
		if (count($bdadosvo->getPalavraChave()))
			$sql_where .= " AND CS.nome LIKE '".$bdadosvo->getPalavraChave()."%'";
		
		$sql = 'SELECT /*busca_canais*/ CS.cod_segmento '.$sql_from.$sql_where;
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$lista[] = array('data' => date('Y-m-d H:i:s'), 'coditem' => $sql_row['cod_segmento']);

		return $lista;
	}

	public function getResultadoBuscaCanais(&$codsegmento) {
		return $this->banco->fetchArray($this->banco->executaQuery("SELECT CS.* FROM Conteudo_Segmento AS CS WHERE CS.cod_segmento='".$codsegmento."' AND CS.disponivel='1'"));
	}

}