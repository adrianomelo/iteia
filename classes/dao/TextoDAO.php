<?php
include_once("ConteudoDAO.php");

class TextoDAO extends ConteudoDAO {

	public function cadastrar(&$textovo) {
		$codconteudo = $this->cadastrarConteudo($textovo);

		if ($codconteudo) {
			$sql = "INSERT INTO Textos (cod_conteudo, foto_credito, foto_legenda) VALUES (".$codconteudo.", '".$textovo->getFotoCredito()."', '".$textovo->getFotoLegenda()."');";
			$sql_result = $this->banco->executaQuery($sql);
		}

		return $codconteudo;
	}

	public function atualizar(&$textovo) {
		$this->atualizarConteudo($textovo);
		
		$sql = "update Textos set foto_credito = '".$textovo->getFotoCredito()."', foto_legenda = '".$textovo->getFotoLegenda()."' where cod_conteudo = '".$textovo->getCodConteudo()."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getTextoVO(&$codconteudo) {
		$textovo = new TextoVO;
		$this->getConteudoVO($codconteudo, $textovo);

		$sql = "SELECT * FROM Textos WHERE cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();

		$textovo->setArquivo($sql_row->arquivo);
		$textovo->setTamanho($sql_row->tamanho);
		$textovo->setNomeArquivoOriginal($sql_row->nome_arquivo_original);
		
		$textovo->setFotoCredito($sql_row->foto_credito);
		$textovo->setFotoLegenda($sql_row->foto_legenda);

		return $textovo;
	}

	public function atualizarArquivo(&$textovo, $codtexto) {
		$sql = "UPDATE Textos SET arquivo = '".$textovo->getArquivo()."', nome_arquivo_original = '".$textovo->getNomeArquivoOriginal()."', tamanho = '".$textovo->getTamanho()."' WHERE cod_conteudo = '".$codtexto."'";
		$this->banco->executaQuery($sql);
	}
	
	public function getArquivoTexto($codtexto) {
        $sql = "SELECT * FROM Textos WHERE cod_conteudo='".$codtexto."';";
        $sql_result = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
    }
    
    public function excluirArquivo($codtexto) {
		$sql = "UPDATE Textos SET arquivo='', nome_arquivo_original='', tamanho='' WHERE cod_conteudo='".$codtexto."'";
        $this->banco->executaQuery($sql);
	}

}