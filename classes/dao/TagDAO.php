<?php
include_once("ConexaoDB.php");

class TagDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function cadastrar(&$tagvo) {
		$this->banco->sql_insert('Tags', array('tag' => $tagvo->getTag(), 'cod_sistema' => ConfigVO::getCodSistema()));
		return $this->banco->insertId();
	}
	
	public function atualizar(&$tagvo) {
		// busco se ja existi a tag no banco
		$query = $this->banco->executaQuery("SELECT cod_tag FROM Tags WHERE tag='".$tagvo->getTag()."' AND cod_tag!='".$tagvo->getCodTag()."' AND cod_sistema='".ConfigVO::getCodSistema()."' LIMIT 1");
		$num_row = $this->banco->numRows($query);
		$row = $this->banco->fetchObject($query);
		
		// mudar todos p/ este tagid
		$id_novo = $row->cod_tag;
		
		if ($num_row && $id_novo) { // se eu axar um tag existente
			// excluo a tag a ser edita e passo seu conteudo p/ a tag existente
			if ($id_novo) {
				$query = $this->banco->executaQuery("SELECT * FROM Conteudo_Tags WHERE cod_tag='".$tagvo->getCodTag()."'");
				$this->banco->executaQuery("DELETE FROM Tags WHERE cod_tag = '".$tagvo->getCodTag()."'");
				while ($trow = $this->banco->fetchObject($query)) {
					$this->banco->executaQuery("DELETE FROM Conteudo_Tags WHERE cod_tag = '".$tagvo->getCodTag()."'");
					$this->banco->executaQuery("REPLACE Conteudo_Tags VALUES ('".$trow->cod_conteudo."', '".$id_novo."')");
				}
			}
		} else {
			// senão atualizo a atual
			$this->banco->sql_update('Tags', array('tag' => $tagvo->getTag()), "cod_tag='".$tagvo->getCodTag()."'");
		}
	}
	
	public function unificarTags($tags, $tag) {
		// cadastro a nova tag no banco
		$this->banco->sql_insert('Tags', array('tag' => $tag, 'cod_sistema' => ConfigVO::getCodSistema()));
		$cod_tag_novo = $this->banco->insertId();
		
		foreach ($tags as $cod_tag) {
			$query = $this->banco->executaQuery("SELECT * FROM Conteudo_Tags WHERE cod_tag='".$cod_tag."'");
			$this->banco->executaQuery("DELETE FROM Tags WHERE cod_tag = '".$cod_tag."'");
				while ($trow = $this->banco->fetchObject($query)) {
					$this->banco->executaQuery("DELETE FROM Conteudo_Tags WHERE cod_tag = '".$cod_tag."'");
					$this->banco->executaQuery("REPLACE Conteudo_Tags VALUES ('".$trow->cod_conteudo."', '".$cod_tag_novo."')");
				}
		}
		
	}
	
	public function apagar($codtag) {
		if (count($codtag)) {
			$this->banco->executaQuery("DELETE FROM Tags WHERE cod_tag IN (".implode(',', $codtag).")");
			$this->banco->executaQuery("DELETE FROM Conteudo_Tags WHERE cod_tag IN (".implode(',', $codtag).")");
		}
	}
	
	public function getTagVO(&$codtag) {
		$query = $this->banco->executaQuery("SELECT * FROM Tags WHERE cod_tag='".$codtag."'");
		$sql_row = $this->banco->fetchArray($query);
		$tagvo = new TagsVO;
		$tagvo->setCodTag($sql_row["cod_tag"]);
		$tagvo->setTag($sql_row["tag"]);
		return $tagvo;
	}
	
	public function getTag($tag) {
		$query = $this->banco->sql_select('cod_tag', 'Tags', "tag='".$tag."' AND cod_sistema='".ConfigVO::getCodSistema()."'");
		return (bool)$this->banco->numRows($query);                                                              
	}
	
	public function getListaTags($dados) {
		$array = array();
		
		if ($dados)
			$where = " AND tag LIKE '%".$dados."%'";
		
		//$query = $this->banco->executaQuery("SELECT t1.* FROM Tags AS t1 WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t1.cod_tag IN (SELECT cod_tag FROM Conteudo_Tags WHERE cod_tag=t1.cod_tag) ORDER BY t1.tag");
		$query = $this->banco->executaQuery("SELECT t1.* FROM Tags AS t1 WHERE t1.cod_sistema='".ConfigVO::getCodSistema()."' $where ORDER BY t1.tag");
		while ($row = $this->banco->fetchObject($query)) {
			
			$total = $this->banco->fetchObject($this->banco->executaQuery("SELECT COUNT(cod_tag) AS total FROM Conteudo_Tags WHERE cod_tag='".$row->cod_tag."'"));
			
			$array[] = array(
				'cod' => $row->cod_tag,
				'tag' => $row->tag,
				'total' => (int)$total->total
			);
		}
		return $array;
	}
	
}