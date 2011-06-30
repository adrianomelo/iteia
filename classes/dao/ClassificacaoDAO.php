<?php
include_once("ConexaoDB.php");

class ClassificacaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaClassificacoes($codformato=0) {
    	$array = array();
    	if ($codformato)
    		$where = "AND cod_formato='".$codformato."'";
    	$sql = "SELECT cod_classificacao, nome FROM Conteudo_Classificacao WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ".$where." ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }

	public function cadastrar(&$classvo) {
		$this->banco->sql_insert('Conteudo_Classificacao', array('cod_formato' => $classvo->getCodFormato(), 'nome' => $classvo->getNome(), 'cod_sistema' => ConfigVO::getCodSistema(), 'descricao' => $classvo->getDescricao(), 'disponivel' => 1));
		return $this->banco->insertId();
	}

	public function atualizar(&$classvo) {
		$this->banco->sql_update('Conteudo_Classificacao', array('cod_formato' => $classvo->getCodFormato(), 'nome' => $classvo->getNome(), 'descricao' => $classvo->getDescricao()), "cod_classificacao='".$classvo->getCodClassificacao()."'");
		return $classvo->getCodClassificacao();
	}

	public function getListaClassificacao() {
        $array = array();
        $query = $this->banco->executaQuery("SELECT * FROM Conteudo_Classificacao WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome");
        while ($row = $this->banco->fetchArray($query)) {
            $array[$row['cod_formato']][] = array (
                'cod' => $row['cod_classificacao'],
                'quantidade' => $this->banco->numRows($this->banco->executaQuery("SELECT cod_classificacao FROM Conteudo WHERE cod_classificacao='".$row['cod_classificacao']."' AND excluido='0' AND publicado='1'")),
                'nome' => $row['nome'],
            );
        }
        return $array;
    }

	public function getClassificacaoVO($codclass) {
        $classvo = new ClassificacaoVO;
        $query = $this->banco->executaQuery("SELECT * FROM Conteudo_Classificacao WHERE cod_classificacao='$codclass' AND disponivel='1'");
        $row = $this->banco->fetchArray();
        $classvo->setCodClassificacao($row['cod_classificacao']);
        $classvo->setCodFormato($row['cod_formato']);
        $classvo->setNome($row['nome']);
        $classvo->setDescricao($row['descricao']);
        return $classvo;
    }

    public function executaAcao($codclassificacao) {
		if (count($codclassificacao))
			$this->banco->executaQuery("UPDATE Conteudo_Classificacao SET disponivel='0' WHERE cod_classificacao IN (".implode(',', $codclassificacao).")");
	}


}