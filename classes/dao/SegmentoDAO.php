<?php
include_once("ConexaoDB.php");

class SegmentoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getSegmentosPai() {
		$array = array();
		$sql = "SELECT * FROM Conteudo_Segmento WHERE disponivel='1' AND cod_pai='0' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query)) {
    		$total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_segmento='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
    		$row['total'] = $total;
			$array[] = $row;
    	}
        return $array;
	}
	
	public function cadastrar(&$segvo) {
		$this->banco->sql_insert('Conteudo_Segmento', array('cod_pai' => $segvo->getCodPai(), 'nome' => $segvo->getNome(), 'descricao' => $segvo->getDescricao(), 'cod_sistema' => ConfigVO::getCodSistema(), 'verbete' => $segvo->getVerbete(), 'disponivel' => 1));
		return $this->banco->insertId();
	}
	
	public function atualizar(&$segvo) {
		$this->banco->sql_update('Conteudo_Segmento', array('cod_pai' => $segvo->getCodPai(), 'nome' => $segvo->getNome(), 'descricao' => $segvo->getDescricao(), 'verbete' => $segvo->getVerbete()), "cod_segmento='".$segvo->getCodSegmento()."'");
		return $segvo->getCodSegmento();
	}
	
	public function atualizarFoto($nomearquivo, $codsegmento) {
		$sql = "UPDATE Conteudo_Segmento SET imagem = '".$nomearquivo."' WHERE cod_segmento='".$codsegmento."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function excluirImagem($codsegmento) {
		$sql = "UPDATE Conteudo_Segmento SET imagem = '' WHERE cod_segmento='".$codsegmento."'";
		$this->banco->executaQuery($sql);
	}
	
	public function getSegmentoVO($codseg) {
        $segvo = new SegmentoVO;
        $query = $this->banco->executaQuery("SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codseg."' AND disponivel='1'");
        $row = $this->banco->fetchArray();
        $segvo->setCodSegmento($row['cod_segmento']);
        $segvo->setCodPai($row['cod_pai']);
        $segvo->setNome($row['nome']);
        $segvo->setDescricao($row['descricao']);
        $segvo->setImagem($row['imagem']);
        $segvo->setVerbete($row['verbete']);
        return $segvo;
    }
    
    public function executaAcao($codsegmento) {
		if (count($codsegmento))
			$this->banco->executaQuery("UPDATE Conteudo_Segmento SET disponivel='0' WHERE cod_segmento IN (".implode(',', $codsegmento).")");
	}
	
	public function getListaSegmentos() {
    	$array = $seg_array = array();
		$sql = "SELECT cod_segmento, nome FROM Conteudo_Segmento WHERE cod_pai='0' AND disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$seg_array[$row['cod_segmento']] = $row['cod_segmento'];
    	
    	$sql = "SELECT cod_segmento, nome, cod_pai, verbete FROM Conteudo_Segmento WHERE cod_pai IN ('".implode("','", $seg_array)."') AND disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query)) {
    		$total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_segmento='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
			$array[$row['cod_pai']][] = array(
				'cod' => $row['cod_segmento'],
				'nome' => $row['nome'],
				'verbete' => $row['verbete'],
				'total' => (int)$total
			);
		}
        return $array;    
    }
    
    public function getListaSegmentosCadastro() {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai='0' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }
    
    public function getListaSubAreasCadastro() {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai!='0' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }
	
	public function getListaSubAreasCadastroCodCanal($codcanal) {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai = '".$codcanal."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }
	
	public function getSegmentoDados($codsegmento) {
		$query = $this->banco->executaQuery("SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codsegmento."' AND disponivel='1' 
		");
		/*echo "SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codsegmento."' AND disponivel='1' 
		AND imagem is not null AND imagem != '' ";*/
        return $this->banco->fetchArray();
	}
	
	public function getHomeSegmentosRandom() {
		$array = array();
    	$sql = "SELECT cod_segmento, nome FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_segmento IN (SELECT t1.cod_segmento FROM Conteudo AS t1 WHERE t1.cod_segmento=cod_segmento AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t1.cod_sistema='".ConfigVO::getCodSistema()."') ORDER BY RAND() LIMIT 2";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
	}
	
	public function getCodConteudoPorCodSegmento($codsegmento, $limit=4) {
		$sql = "SELECT t1.cod_conteudo, t1.cod_formato FROM Conteudo AS t1 WHERE t1.cod_segmento='$codsegmento' AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t1.cod_sistema = ".ConfigVO::getCodSistema(). " ORDER BY RAND() LIMIT ".$limit;
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
	}
	
	public function getSegmentosAtivosRandom($limite=10) {
		$sql = "SELECT COUNT(c.cod_segmento) AS total, c.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND c.cod_segmento=cs.cod_segmento AND cs.disponivel=1 GROUP BY c.cod_segmento DESC LIMIT ".$limite;
		//echo $sql;
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query)) {
    		$array[$row['cod_segmento']] = $row;
			$array[$row['cod_segmento']]['dados'] = $this->getSegmentoDados($row['cod_segmento']);
		}
		shuffle($array);
        return $array;
	}
	
	public function getTotalConteudoPorCodSegmento($codsegmento) {
		$sql = "SELECT COUNT(c.cod_segmento) AS total FROM Conteudo c WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND c.cod_segmento='".$codsegmento."'";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
        return (int)$row['total'];
	}
	
	public function getSegmentosAtivosCultura() {
		$sql = "SELECT COUNT(c.cod_segmento) AS total, c.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0  AND c.cod_segmento=cs.cod_segmento AND cs.disponivel=1 /*AND cs.imagem IS NOT NULL AND cs.imagem != ''*/ GROUP BY c.cod_segmento DESC LIMIT 4 ";
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query)) {
    		$array[$row['cod_segmento']] = $row;
			$array[$row['cod_segmento']]['dados'] = $this->getSegmentoDados($row['cod_segmento']);
		}
		shuffle($array);
        return $array;
	}
	
}