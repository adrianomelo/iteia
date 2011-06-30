<?php
include_once("ConexaoDB.php");

class AguardandoAprovacaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function getListaAguardandoAprovacao($inicial, $mostrar) {
		$array = array();

		$array['link'] = "index_lista_aguardando.php?buscar=1";
		$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";

		// se eu for colaborador/admin irar exibir as minhas notificações para aprovação
		/*
		if ($_SESSION['logado_como'] > 1) {
			$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t1.titulo, t3.cod_usuario, t3.nome, t4.titulo AS url, t5.sigla FROM Conteudo AS t1 LEFT JOIN Conteudo_Revisao AS t2 ON (t1.cod_conteudo=t2.cod_conteudo) LEFT JOIN Usuarios AS t3 ON (t1.cod_autor=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) LEFT JOIN Estados AS t5 ON (t3.cod_estado=t5.cod_estado) $where AND t2.cod_colaborador='".$_SESSION['logado_cod']."' AND t4.tipo='2'";
		}
		*/

		$sql = "SELECT t1.cod_formato, t1.cod_conteudo, t1.imagem, t1.titulo, t1.publicado, t3.cod_usuario, t3.nome, t4.titulo AS url, t5.sigla FROM Conteudo AS t1 LEFT JOIN Usuarios AS t3 ON (t1.cod_autor=t3.cod_usuario) INNER JOIN Urls AS t4 ON (t3.cod_usuario=t4.cod_item) LEFT JOIN Estados AS t5 ON (t3.cod_estado=t5.cod_estado) $where AND t1.cod_autor='".$_SESSION['logado_cod']."' AND t1.publicado!=1 AND t1.cod_autor='".$_SESSION['logado_cod']."' AND t4.tipo='2'";

		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));

		$query = $this->banco->executaQuery("$sql ORDER BY t1.cod_conteudo DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchObject($query)) {

			switch($row->cod_formato) {
				case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
				case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
				case 3: $formato = '<span class="audio" title="Áudio">Áudio</span>'; break;
				case 4: $formato = '<span class="video" title="Vídeo">Vídeo</span>'; break;
			}

			switch($row->publicacao) {
				case 0: $publicacao = 'Pendente'; break;
				case 2: $publicacao = 'Rejeitado'; break;
			}

			$array[$row->cod_conteudo] = array(
				'cod_conteudo' => $row->cod_conteudo,
				'cod_formato' => $row->cod_formato,
				'titulo' => $row->titulo,
				'nome' => $row->nome,
				'formato' => $formato,
				'imagem' => $row->imagem,
				'publicacao' => $publicacao,
				'url' => $row->url,
				'url_arquivo' => 'index_exibir_aprovacao.php?cod='.$row->cod_conteudo,
				'sigla' => $row->sigla
			);
		}
		return $array;
	}

}
