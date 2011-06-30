<?php
include_once('ConexaoDB.php');

class LateralParaFundarpeDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getEventos() {
		$array = array();
	    $where = "WHERE t1.cod_formato = 6 and t1.excluido='0' and t1.cod_sistema = '1' and t2.data_inicial >= '".date('Y-m-d')."'";

        $sql = "SELECT t1.titulo, t2.*, t3.titulo AS url_agenda FROM Conteudo AS t1 INNER JOIN Agenda AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) $where";

        $query = $this->banco->executaQuery("$sql ORDER BY t2.data_inicial, t2.hora_inicial LIMIT 2");
        while ($row = mysql_fetch_array($query))
            $array[] = $row;
        return $array;
    }

	public function getConteudo($formato) {
		//$sql = "SELECT t1.cod_conteudo, t1.titulo, t1.imagem, t3.titulo AS url FROM Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t3.tipo='4' AND t1.cod_formato='".$formato."' ORDER BY t2.num_acessos DESC LIMIT 1";
		$sql = "SELECT t1.cod_conteudo, t1.titulo, t1.imagem, (t2.num_acessos / TIMESTAMPDIFF(DAY, t1.data_cadastro, NOW())) AS ordenacao, t3.titulo AS url FROM Conteudo AS t1 LEFT JOIN Conteudo_Estatisticas AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) INNER JOIN Urls AS t3 ON (t1.cod_conteudo=t3.cod_item) WHERE t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t3.tipo='4' AND t1.cod_formato='".$formato."' AND t1.cod_sistema='1' ORDER BY ordenacao DESC LIMIT 1";

		$query = $this->banco->executaQuery($sql);
		$row = mysql_fetch_array($query);
		if ($formato == 2) {
			$sql2 = "SELECT t1.imagem FROM Imagens AS t1 INNER JOIN Albuns AS t2 ON (t1.cod_imagem=t2.cod_imagem_capa) WHERE t2.cod_conteudo='".$row['cod_conteudo']."'";
			$query2 = $this->banco->executaQuery($sql2);
			$row2 = mysql_fetch_array($query2);
			$row['imagem'] = $row2['imagem'];
		}
		return $row;
	}

}
