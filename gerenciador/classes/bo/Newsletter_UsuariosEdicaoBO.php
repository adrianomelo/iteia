<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_UsuariosDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'vo/Newsletter_UsuariosVO.php');

class Newsletter_UsuariosEdicaoBO {
	
	private $newsvo = null;
	private $newsdao = null;
	private $listadao = null;
	protected $erro_campos = array();
	protected $erro_campos_complemento = array();
	protected $dadosform = array();
	public $total_excluidas = 0;
	
	public function __construct() {
		$this->listadao = new Newsletter_ListaDAO();
		$this->newsdao = new Newsletter_UsuariosDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codusuario'] = (int)$dadosform['codusuario'];
		$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
		$this->dadosform['email'] = strip_tags(trim($dadosform['email']));		
	}
	
	protected function validaDados() {
		if (!$this->dadosform['nome']) {
			$this->erro_campos[] = 'nome';
			$this->erro_campos_complemento[] = 'Preencha o Nome';
		}
		
		if (!$this->dadosform['email']) {
			$this->erro_campos[] = 'email';
			$this->erro_campos_complemento[] = 'Preencha o E-mail';
		}
		
		if ($this->dadosform['email']) {
			if (!Util::checkEmail($this->dadosform['email'])) {
				$this->erro_campos[] = 'email';
				$this->erro_campos_complemento[] = 'O E-mail está em um formato incorreto';
			}
			if ($this->newsdao->getCountEmailUsuario($this->dadosform['email'],  $this->dadosform['codusuario'])) {
				$this->erro_campos[] = 'email';
				$this->erro_campos_complemento[] = 'E-mail já cadastrado';
			}
		}
		
		if (!count($_SESSION['sessao_newsletter_usuarios_lista']))
			$this->erro_campos_complemento[] = 'Selecione ao menos uma lista';

		if (count($this->erro_campos_complemento) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_campos_complemento));
	}
	
	protected function setDadosVO() {
		$this->newsvo = new Newsletter_UsuariosVO;
		$this->newsvo->setCodUsuario($this->dadosform['codusuario']);
		$this->newsvo->setNome($this->dadosform['nome']);
		$this->newsvo->setEmail($this->dadosform['email']);
		$this->newsvo->setLista($_SESSION['sessao_newsletter_usuarios_lista']);
	}
	
	protected function editarDados() {
		if ($this->newsvo->getCodUsuario()) {
			$cod_usuario = $this->newsdao->editar($this->newsvo);
		} else {
			$cod_usuario = $this->newsdao->cadastrar($this->newsvo);
		}
		$this->dadosform = array();
		unset($_SESSION['sessao_newsletter_usuarios_lista']);
		return $cod_usuario;
	}
	
	public function setDadosCamposEdicao(&$codusuario) {
		$newsvo = $this->newsdao->getDadosEdicaoVO($codusuario);
		$this->dadosform['codusuario'] = $newsvo->getCodUsuario();
		$this->dadosform['nome'] = $newsvo->getNome();
		$this->dadosform['email'] = $newsvo->getEmail();
		
		foreach($newsvo->getLista() as $key => $value)
			$_SESSION['sessao_newsletter_usuarios_lista'][$key] = $value;
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
	
	public function getDadosUsuario($cod_usuario) {
		return $this->newsdao->getDadosUsuario($cod_usuario);
	}
	
	public function getListaHistoricoUsuarios($inicial, $mostrar) {
		return $this->newsdao->getListaHistoricoUsuarios($_SESSION['sess_cliente_cod'], $inicial, $mostrar);	
	}
	
	public function getListasEnvio() {
		return $this->listadao->getListas($_SESSION['sess_cliente_cod']);
	}
	
}