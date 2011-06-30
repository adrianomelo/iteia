<?php
include_once("ConteudoDAO.php");

class AudioDAO extends ConteudoDAO {

	public function cadastrarAlbum(&$audiovo) {
		$codconteudo = $this->cadastrarConteudo($audiovo);

		if ($codconteudo) {
			$lista_audios = $audiovo->getListaAudios();

			$sql = "update Audios set cod_conteudo = '".$codconteudo."' where cod_audio in ('".implode("','", $lista_audios)."');";
			$sql_result = $this->banco->executaQuery($sql);
		}

		return $codconteudo;
	}

	public function atualizarAlbum(&$audiovo) {
		$this->atualizarConteudo($audiovo);

		$lista_audios = $audiovo->getListaAudios();

		$sql = "update Audios set cod_conteudo = '".$audiovo->getCodConteudo()."' where cod_audio in ('".implode("','", $lista_audios)."');";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function cadastrarAudio(&$audvo, $sessao_id) {
		$sql = "select ordem from Audios where cod_audio in (".implode(',', (array)$_SESSION["sess_conteudo_audios_album"][$sessao_id]).") order by ordem desc limit 1;";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		$ordem = ((int)$sql_row[0] + 10);
		
		//$sql = "insert into Audios (cod_conteudo, randomico, titulo, tempo, ordem, datahora) values ('".$audvo->getCodConteudo()."', '".$audvo->getRandomico()."', '".$audvo->getFaixa()."', '".$audvo->getTempo()."', '".$ordem."', NOW());";
		
		$sql = "insert into Audios (cod_conteudo, randomico, titulo, ordem, datahora) values ('".$audvo->getCodConteudo()."', '".$audvo->getRandomico()."', '".$audvo->getFaixa()."', '".$ordem."', NOW());";
		$sql_result = $this->banco->executaQuery($sql);
		$codaudio = (int)$this->banco->insertId();
		return $codaudio;
	}

	public function atualizarAudio(&$audvo) {
		$sql = "update Audios set titulo = '".$audvo->getFaixa()."', tempo = '".$audvo->getTempo()."' where cod_audio = '".$audvo->getCodAudio()."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function excluirAudio($codaudio) {
		$sql = "update Audios set excluido = 1 where cod_audio = '".$codaudio."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

    public function atualizarArquivo($arquivo, $nomeoriginal, $tamanho, $codaudio) {
		$sql = "update Audios set audio = '".$arquivo."', arquivo_original = '".$nomeoriginal."', tamanho = '".$tamanho."' where cod_audio = '".$codaudio."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function getAudioVO(&$codconteudo) {
		$albumvo = new AlbumAudioVO;
		$this->getConteudoVO($codconteudo, $albumvo);

		$lista_audios = array();
		$sql = "select cod_audio, titulo, tempo, audio, arquivo_original from Audios where cod_conteudo = '".$codconteudo."' and excluido = 0 order by ordem asc;";
		
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista_audios[] = $sql_row["cod_audio"];
		}
		$albumvo->setListaAudios($lista_audios);

		return $albumvo;
	}
	
	public function getAudiosAlbum($codconteudo) {
        $sql = "SELECT * FROM Audios WHERE cod_conteudo='".$codconteudo."' AND excluido='0' order by ordem asc";
        $this->banco->executaQuery($sql);
        $array = array();
        while ($row = $this->banco->fetchArray())
            $array[] = $row;
        return $array;
    }
    
	public function getQtsFaixasAlbum($codconteudo) {
        $sql = "SELECT COUNT(1) FROM Audios WHERE cod_conteudo='".$codconteudo."' AND excluido='0'";
        $row = $this->banco->fetchArray($this->banco->executaQuery($sql));
        return (int)$row[0];
    }
	
    public function atualizaOrdenacao($cod, $move) {
		$move = Util::iif($move == 1, '-15', '15');
		$sql = "update Audios set ordem = ordem + $move where cod_audio = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function organizacaoFinal($sessao_id) {
		$sql = "SELECT ordem, cod_audio FROM Audios WHERE cod_audio IN (".implode(',', (array)$_SESSION["sess_conteudo_audios_album"][$sessao_id]).") ORDER BY ordem ASC";
		$resultado = $this->banco->executaQuery($sql);
			
		$i = 10;
		while ($row = $this->banco->fetchArray($resultado)) {
			$sql = "UPDATE Audios SET ordem = '$i' WHERE cod_audio = '".$row['cod_audio']."'";
			$this->banco->executaQuery($sql);
			$i += 10;
		}
	}
    
    public function buscarAudios(&$dados_form) {
		$sql_from = "Audios I";
		$sql_where = "I.excluido = 0";
		$ok = false;

		if (is_array($dados_form["lista_audios"]) && count($dados_form["lista_audios"])) {
			$sql_where .= " and I.cod_audio in ('".implode("','", $dados_form["lista_audios"])."')";
			$ok = true;
		}
		if ($dados_form["codconteudo"]) {
			$sql_where .= " and I.cod_conteudo = '".$dados_form["codconteudo"]."'";
			$ok = true;
		}
		
		$lista_audios = array();
		if ($ok) {
			$sql = "select I.cod_audio, I.randomico, I.audio, I.arquivo_original, I.titulo, I.ordem, I.tempo from ".$sql_from." where ".$sql_where." order by I.ordem asc";
			$sql_result = $this->banco->executaQuery($sql);
			while ($sql_row = $this->banco->fetchArray($sql_result))
					$lista_audios[] = $sql_row;
		}
		return $lista_audios;
	}
	
	public function getArquivoAudio($codaudio) {
        $sql = "SELECT * FROM Audios WHERE cod_audio='".$codaudio."';";
        $sql_result = $this->banco->executaQuery($sql);
        return $this->banco->fetchArray($sql_result);
    }
	
}