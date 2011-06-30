<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AudioVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AudioDAO.php");

class AudioBuscaBO {

	private $auddao = null;

	public function __construct() {
		$this->auddao = new AudioDAO();
	}

	public function getListaAudios(&$dados_form) {
		$this->dados_form["lista_audios"] = $_SESSION["sess_conteudo_audios_album"][$dados_form['sessao_id']];
		return $this->auddao->buscarAudios($this->dados_form);
	}

}
