<?php
include_once("ConexaoDB.php");

class ListaPublicaDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getListaPublica($inicial, $mostrar) {
		$array = array();
		
		$array['link'] = "index_lista_publica.php?buscar=1";
		$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";
		
		// pego todo conteudo da lista publica
		$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t1.titulo, t3.cod_usuario, t3.nome, t4.titulo AS url FROM Conteudo AS t1 INNER JOIN Conteudo_ListaPublica AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t3 ON (t1.cod_autor=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) $where AND t4.tipo='2'";

		//echo $sql;
		
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));
		
		$query = $this->banco->executaQuery("$sql ORDER BY t2.cod_conteudo LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchObject($query)) {
			
			switch($row->cod_formato) {
				case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
				case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
				case 3: $formato = '<span class="audio" title="Áudio">Áudio</span>'; break;
				case 4: $formato = '<span class="video" title="Vídeo">Vídeo</span>'; break;
			}
			
			$array[$row->cod_conteudo] = array(
				'cod_conteudo' => $row->cod_conteudo,
				'cod_formato' => $row->cod_formato,
				'nome' => $row->nome,
				'titulo' => $row->titulo,
				'formato' => $row->cod_formato,
				'imagem' => $formato,
				'url' => $row->url,
			);
		}
		return $array;
	}

	public function apagarListaPublica($codconteudo) {
		$this->banco->sql_delete('Conteudo_ListaPublica', "cod_conteudo='".$codconteudo."'");
	}

}
