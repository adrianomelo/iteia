<?php
include_once("ConexaoDB.php");

class NewsletterItemDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function atualizar(&$homevo) {
		$sql = "update Home_Newsletter_Itens set titulo = '".$homevo->getTitulo()."', descricao = '".$homevo->getResumo()."', credito = '".$homevo->getCredito()."' where cod_item = '".$homevo->getCodItem()."';";
		$sql_result = $this->banco->executaQuery($sql);
		return $homevo->getCodItem();
	}
	
	public function atualizarImagem($nome, $coditem) {
		$sql = "update Home_Newsletter_Itens set imagem = '".$nome."' where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function atualizarDestaque($itens, $coditem) {
		$sql = "update Home_Newsletter_Itens set destaque = 0 where cod_item in (".implode(',', $itens).");";
		$sql_result = $this->banco->executaQuery($sql);
		
		$sql = "update Home_Newsletter_Itens set destaque = 1 where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function restaurarImagem($coditem) {
		$sql = "SELECT cod_conteudo FROM Home_Newsletter_Itens WHERE cod_item='".$coditem."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row2 = $this->banco->fetchArray($sql_result);
		
		$sql = "SELECT imagem, cod_formato FROM Conteudo WHERE cod_conteudo='".$sql_row2['cod_conteudo']."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
			
		if ($sql_row["cod_formato"] == 2) {
			$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row2['cod_conteudo']."' and A.cod_imagem_capa = I.cod_imagem";
			$sql_result3 = $this->banco->executaQuery($sql);
			$sql_row3 = $this->banco->fetchArray($sql_result3);
			$sql_row['imagem'] = $sql_row3['imagem'];
		}
		
		$sql = "update Home_Newsletter_Itens set imagem = '".$sql_row['imagem']."' where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		
		return array('f' => $sql_row["cod_formato"], 'i' => $coditem);
	}

	public function getNewsletterItemVO(&$coditem) {
		$newsvo = new NewsletterItemVO;
		$sql = "select t1.*, t2.cod_formato from Home_Newsletter_Itens AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) where t1.cod_item = ".$coditem.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$newsvo->setCodItem($sql_row["cod_item"]);
		$newsvo->setTitulo($sql_row["titulo"]);
		$newsvo->setResumo($sql_row["descricao"]);
		$newsvo->setCredito($sql_row["credito"]);
		$newsvo->setImagem($sql_row["imagem"]);
		$newsvo->setCodFormato($sql_row["cod_formato"]);
		return $newsvo;
	}
	
	public function getDescricaoOriginal($coditem) {
		$sql = "select t2.descricao from Home_Newsletter_Itens AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) where t1.cod_item = ".$coditem.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row['descricao'];
	}

}