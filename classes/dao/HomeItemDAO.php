<?php
include_once("ConexaoDB.php");

class HomeItemDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function atualizar(&$homevo) {
		$sql = "update Home_Itens_Apresentacao set titulo = '".$homevo->getTitulo()."', descricao = '".$homevo->getResumo()."' where cod_item = '".$homevo->getCodItem()."';";
		$sql_result = $this->banco->executaQuery($sql);
		return $homevo->getCodItem();
	}
	
	public function atualizarImagem($nome, $coditem) {
		$sql = "update Home_Itens_Apresentacao set imagem = '".$nome."' where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function restaurarImagem($coditem) {
		$sql = "SELECT cod_conteudo FROM Home_Itens_Apresentacao WHERE cod_item='".$coditem."'";
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
		
		$sql = "update Home_Itens_Apresentacao set imagem = '".$sql_row['imagem']."' where cod_item = '".$coditem."';";
		$sql_result = $this->banco->executaQuery($sql);
		
		return array('f' => $sql_row["cod_formato"], 'i' => $coditem);
	}

	public function getHomeItemVO(&$coditem) {
		$homevo = new HomeItemVO;
		$sql = "select t1.*, t2.cod_formato from Home_Itens_Apresentacao AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) where t1.cod_item = ".$coditem.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$homevo->setCodItem($sql_row["cod_item"]);
		$homevo->setTitulo($sql_row["titulo"]);
		$homevo->setResumo($sql_row["descricao"]);
		$homevo->setImagem($sql_row["imagem"]);
		$homevo->setCodFormato($sql_row["cod_formato"]);
		return $homevo;
	}
	
	public function getDescricaoOriginal($coditem) {
		$sql = "select t2.descricao from Home_Itens_Apresentacao AS t1 INNER JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) where t1.cod_item = ".$coditem.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row['descricao'];
	}

}