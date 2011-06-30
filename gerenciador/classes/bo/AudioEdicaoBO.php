<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AudioVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AudioDAO.php");

class AudioEdicaoBO extends ConteudoBO {

	private $audvo = null;
	private $auddao = null;

	public function __construct() {
		$this->auddao = new AudioDAO;
	}

	protected function setDadosForm(&$dadosform) {
	    $this->dadosform = $dadosform;
		$this->dadosform["codaudio"] = (int)$this->dadosform["codaudio"];
		$this->dadosform["titulofaixa"] = substr(trim($this->dadosform["titulofaixa"]), 0, 100);
		$this->dadosform["titulofaixaaud"] = (array)$this->dadosform["titulofaixaaud"];
		$this->dadosform["tempo"] = substr(trim($this->dadosform["tempo"]), 0, 100);
		$this->dadosform["tempoaud"] = (array)$this->dadosform["tempoaud"];
		$this->dadosform["sessao_id"] = trim($this->dadosform["sessao_id"]);
	}

	protected function validaDados() {
		
		$this->salvaTitulos($this->dadosform["titulofaixaaud"], $this->dadosform["tempoaud"]);
		
		if (!$this->dadosform["codaudio"] && !count($this->arquivos["audio"]["tmp_name"]))
			$this->erro_mensagens[] = "Selecione ao menos um arquivo de &aacute;udio";
			
		if (count($this->arquivos["audio"]["tmp_name"])) {
			foreach ($this->arquivos["audio"]["tmp_name"] as $key => $value) {
				if (is_uploaded_file($this->arquivos["audio"]["tmp_name"][$key])) {
					if ($this->arquivos["audio"]["size"][$key] > 20971520) {
						$this->erro_mensagens[] = "Um dos &Aacute;udios est&aacute; com mais de 20MB";
						break;
					}
				}
			}
			
			// formato audio - mp3
			
			foreach ($this->arquivos["audio"]["tmp_name"] as $key => $value) {
				if (is_uploaded_file($this->arquivos["audio"]["tmp_name"][$key])) {
					
					$extensao_audio = strtolower(Util::getExtensaoArquivo($this->arquivos["audio"]["name"][$key]));
					
					if ($extensao_audio != 'mp3') {
						$this->erro_mensagens[] = "Formato de arquivo diferente do permitido";
						$this->erro_campos[] = "arquivo_audio";
						break;	
					}
				}
			}
		}
		
		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
    }

	protected function setDadosVO() {
		$this->audvo = new AudioVO;
		$this->audvo->setCodAudio((int)$this->dadosform["codaudio"]);
		$this->audvo->setCodConteudo((int)$this->dadosform["codconteudo"]);
		
		//if (!(int)$this->dadosform["codaudio"])
		//	$this->audvo->setRandomico(Util::geraRandomico());
		
		//$this->audvo->setFaixa($this->dadosform["titulofaixa"]);
		//$this->audvo->setTempo($this->dadosform["tempo"]);
		
		//$this->audvo->setDadosArquivo($this->arquivos["audio"]);
		
		if (!(int)$this->dadosform["codaudio"])
			$this->audvo->setDataHora(date("Y-m-d H:i:s"));
	}

    protected function editarDados() {
    	/*
    	if (!$this->audvo->getCodAudio()) {
			$codaudio = $this->auddao->cadastrarAudio($this->audvo);
			$_SESSION["sess_conteudo_audios_album"][] = $codaudio;
		}
		else {
			$this->auddao->atualizarAudio($this->audvo);
			$codaudio = $this->audvovo->getCodAudio();
		}
		*/
		
		//print_r($this->arquivos["audio"]); die;
		
		// cadastrar varios arquivos de audios ao mesmo tempo
		foreach ($this->arquivos["audio"]['tmp_name'] as $key => $audio) {
			
			if (is_uploaded_file($this->arquivos["audio"]["tmp_name"][$key])) {
			
				$this->audvo->setRandomico(Util::geraRandomico());
				$this->audvo->setFaixa($this->arquivos["audio"]['name'][$key]);
			
				$codaudio = $this->auddao->cadastrarAudio($this->audvo, $this->dadosform['sessao_id']);
				$_SESSION["sess_conteudo_audios_album"][$this->dadosform['sessao_id']][] = $codaudio;
		
				// envia os arquivos
				$extensao = Util::getExtensaoArquivo($this->arquivos["audio"]["name"][$key]);
				$nomearquivo = "audio_".$this->audvo->getRandomico().".".$extensao;
				
				copy($this->arquivos["audio"]["tmp_name"][$key], ConfigVO::getDirAudio().$nomearquivo);
				$this->auddao->atualizarArquivo($nomearquivo, $this->arquivos['audio']['name'][$key], $this->arquivos['audio']['size'][$key], $codaudio);
				
				sleep(2);
			}
			
		}
		
		/*
		if (is_uploaded_file($this->arquivos["audio"]["tmp_name"])) {
			$extensao = Util::getExtensaoArquivo($this->arquivos["audio"]["name"]);
			$nomearquivo = "audio_".$this->audvo->getRandomico().".".$extensao;
			copy($this->arquivos["audio"]["tmp_name"], ConfigVO::getDirAudio().$nomearquivo);
			$this->auddao->atualizarArquivo($nomearquivo, $this->arquivos['audio']['name'], $this->arquivos['audio']['size'], $codaudio);
		}
		*/
		
		$this->dadosform = array();
		$this->arquivos = array();
		return true;
	}
	
	public function salvaTitulos($titulosaud, $temposaud) {
		$this->audvo = new AudioVO;
		foreach ($titulosaud as $codaudio => $faixa) {
			if ((int)$codaudio) {
				$this->audvo->setCodAudio((int)$codaudio);
				$this->audvo->setFaixa(html_entity_decode($faixa));
				$this->audvo->setTempo($temposaud[$codaudio]);
				$this->auddao->atualizarAudio($this->audvo);
			}
		}
	}

	public function setDadosCamposEdicao($codaudio) {
		$audvo = $this->auddao->getAudioVO($codaudio);

		$this->dadosform["codaudio"] = $audvo->getCodImagem();
		$this->dadosform["titulofaixa"] = $audvo->getFaixa();
		$this->dadosform["tempo"] = $audvo->getTempo();
	}

    public function getListaAudiosSelecionados() {
		$dados_busca["lista_audios"] = $_SESSION["sess_conteudo_audios_album"][$this->dadosform['sessao_id']];
		return $this->auddao->getTotalItensAlbum($dados_busca);
	}
	
	public function organizaOrdenacaoAudio($coditem, $i) {
		$this->auddao->atualizaOrdenacao($coditem, $i);
	}
	
	public function organizacaoFinal($sessao_id) {
		$this->auddao->organizacaoFinal($sessao_id);
	}

}