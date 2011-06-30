<?php
include_once("ConexaoDB.php");

class ConteudoRecenteDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getListaConteudoRecente($inicial, $mostrar) {
		$array = array();
		
		$array['link'] = "index_lista_recentes.php?buscar=1";
		$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t1.cod_formato!=5 AND t1.cod_formato!=6";
		
		// lista conteudo recente de quem está logado
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			//$from = ", Conteudo_Autores AS t2";
			//$where .= " AND t1.cod_conteudo = t2.cod_conteudo AND t2.cod_usuario='".$_SESSION['logado_cod']."'";
			$where .= " AND t1.cod_autor='".$_SESSION['logado_cod']."'";
		} elseif ($_SESSION['logado_dados']['nivel'] >= 5) {
			$where .= " AND t1.cod_colaborador='".$_SESSION['logado_dados']['cod_colaborador']."'";
		}

		$sql = "SELECT t1.cod_conteudo, t1.cod_formato, t1.titulo, t1.data_cadastro, t1.situacao, t1.publicado FROM Conteudo AS t1 $from $where";
		
		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));
		
		$query = $this->banco->executaQuery("$sql ORDER BY t1.cod_conteudo DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchObject($query)) {
			
			switch($row->cod_formato) {
				case 1: $formato = '<span class="texto" title="Texto">Texto</span>'; break;
				case 2: $formato = '<span class="imagem" title="Imagem">Imagem</span>'; break;
				case 3: $formato = '<span class="audio" title="Áudio">Áudio</span>'; break;
				case 4: $formato = '<span class="video" title="Vídeo">Vídeo</span>'; break;
			}
			
			switch($row->situacao) {
                case 0:	$situacao = '<span class="inativo" title="Inativo">Inativo</span>'; break;
                case 1:	$situacao = '<span class="ativo" title="Ativo">Ativo</span>'; break;
			}
			
			if (!$row->publicado)
				$situacao = '<span class="pendente" title="Pendente">Pendente</span>';
			
			if ($row->publicado == 2)
				$situacao = '<span class="rejeitado" title="Rejeitado">Rejeitado</span>';
				
			/*	
			if (!$row->publicado)
				$url_arquivo = 'index_exibir_aguardando.php?cod=';
				
			if ($row->publicado == 2)
				$url_arquivo = 'index_exibir_reprovado.php?cod=';
				
			if ($row->publicado == 1)
				$url_arquivo = 'index_exibir_recente.php?cod=';
			*/
			
			switch($row->cod_formato) {
				case 1: $url_arquivo = "conteudo_edicao_texto.php?cod="; break;
                case 2: $url_arquivo = "conteudo_edicao_imagem.php?cod="; break;
                case 3: $url_arquivo = "conteudo_edicao_audio.php?cod="; break;
                case 4: $url_arquivo = "conteudo_edicao_video.php?cod="; break;
                case 5: $url_arquivo = "noticia_edicao.php?cod="; break;
				case 6: $url_arquivo = "agenda_edicao.php?cod="; break;
			}
			
			$array[$row->cod_conteudo] = array(
				'cod_conteudo' => $row->cod_conteudo,
				'cod_formato' => $row->cod_formato,
				'titulo' => $row->titulo,
				'formato' => $formato,
				'situacao' => $situacao,
				'url' => $row->url,
				'data' => date('d/m/Y, H:i', strtotime($row->data_cadastro)),
				//'url_arquivo' => 'index_exibir_recente.php?cod='.$row->cod_conteudo
				'url_arquivo' => $url_arquivo.$row->cod_conteudo
			);
		}
		return $array;
	}

}
