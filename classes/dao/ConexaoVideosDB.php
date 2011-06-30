<?php
class ConexaoVideosDB {

	const SERVIDOR = "192.168.0.100";
	const USUARIO = "";
	const SENHA = "";
	const DATABASE = "iteia_geral";

	static private $instance;
	private $conexao;

	private function __construct() {
	}

	static public function singleton() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	protected function criaConexao() {
		$this->conexao = mysql_pconnect(self::SERVIDOR, self::USUARIO, self::SENHA);
		mysql_select_db(self::DATABASE, $this->conexao);
	}

	public function executaQuery($sql) {

		if (!$this->conexao)
			$this->criaConexao();
		if (!$this->conexao) return false;

		$sql_result = mysql_query($sql, $this->conexao);

		return $sql_result;
	}
}
