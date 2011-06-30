<?php
include_once("ConexaoVideosDB.php");

class VideoConversaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoVideosDB::singleton();
	}

	public function cadastrar($arquivo_original, $arquivo_convertido) {
		$sql = "insert into Videos_Conversao (arquivo_original, arquivo_convertido) values ('".$arquivo_original."', '".$arquivo_convertido."');";
		$sql_result = $this->banco->executaQuery($sql);
	}

}
