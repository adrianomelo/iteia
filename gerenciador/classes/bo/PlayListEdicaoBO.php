<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/PlayListVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PlayListDAO.php");

class PlayListEdicaoBO {

	private $playdao = null;
	private $playvo = null;
	private $dadosform = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->playdao = new PlayListDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codplay"] = (int)$this->dadosform["codplay"];
		$this->dadosform["cod_usuario"] = (int)$this->dadosform["cod_usuario"];
		$this->dadosform["data_inicio"] = strip_tags($this->dadosform["data_inicio"]);
		$this->dadosform["hora_inicio"] = strip_tags($this->dadosform["hora_inicio"]);
	}
	
	protected function setDadosVO() {
		$this->playvo = new PlayListVO;
		$this->playvo->setCodPlayList($this->dadosform["codplay"]);
		
		if ($this->dadosform["cod_usuario"] == '-2100')
			$this->dadosform["cod_usuario"] = 0;
			
		$this->playvo->setCodUsuario($this->dadosform["cod_usuario"]);
		
		$this->playvo->setDataInicio(substr($this->dadosform["data_inicio"], 6, 4)."-".substr($this->dadosform["data_inicio"], 3, 2)."-".substr($this->dadosform["data_inicio"], 0, 2));
		$this->playvo->setHoraInicio($this->dadosform['hora_inicio']);
		$this->playvo->setListaItens($_SESSION['sessao_playlist_itens']);
	}
	
	protected function validaDados() {
		if (!$this->dadosform["data_inicio"]) $this->erro_campos[] = "data_inicio";
		if (!$this->dadosform["hora_inicio"]) $this->erro_campos[] = "hora_inicio";
		if (!count($_SESSION['sessao_playlist_itens'])) $this->erro_campos[] = "lista_itens";
		if (!$this->dadosform["cod_usuario"]) $this->erro_campos[] = "cod_usuario";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function editarDados() {
		if (!$this->playvo->getCodPlayList()) {
			$cod_playlist = $this->playdao->cadastrarPlayList($this->playvo);
		} else {
			$this->playdao->atualizarPlayList($this->playvo);
			$cod_playlist = $this->playvo->getCodPlayList();
		}
	
		unset($_SESSION["sessao_playlist_itens"]);
		return $cod_playlist;
	}
	
	public function editar(&$dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (Exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		return $this->editarDados();
	}
	
	public function limpaItens() {
		$this->playdao->limpaItens();
	}

	public function setDadosCamposEdicao($codplay) {
		$playvo = $this->playdao->getPlayListVO($codplay);
		$this->dadosform["codplay"] = $playvo->getCodPlayList();
		
		$this->dadosform["cod_usuario"] = $playvo->getCodUsuario();
		
		if ($this->dadosform["cod_usuario"] == 0)
			$this->dadosform["cod_usuario"] = '-2100';
			
		$this->dadosform['data_inicio'] = date('d/m/Y', strtotime($playvo->getDataInicio()));
		$this->dadosform['hora_inicio'] = $playvo->getHoraInicio();
		
		foreach ($playvo->getListaItens() as $key => $value)
			$_SESSION["sessao_playlist_itens"][$key] = $key;
	}
	
	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}
	
	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " style=\"border:1px solid #FF0000; background:#FFDFDF;\"";
		else
			return "";
	}
	
	public function getDadosUsuario($cod) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usudao = new UsuarioDAO;
		return $usudao->getUsuarioDados($cod);
	}
	
}