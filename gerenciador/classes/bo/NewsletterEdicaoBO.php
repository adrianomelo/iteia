<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/NewsletterVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/Newsletter_ListaDAO.php");

class NewsletterEdicaoBO {

	private $newsdao = null;
	private $listadao = null;
	private $newsvo = null;
	private $dadosform = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->listadao = new Newsletter_ListaDAO();
		$this->newsdao = new NewsletterDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codnewsletter"] = (int)$this->dadosform["codnewsletter"];
		$this->dadosform['titulo'] = strip_tags($this->dadosform["titulo"]);
		$this->dadosform["data_inicio"] = strip_tags($this->dadosform["data_inicio"]);
		$this->dadosform["hora_inicio"] = strip_tags($this->dadosform["hora_inicio"]);
		$this->dadosform['envio_para'] = trim($dadosform['envio_para']);
		$this->dadosform["acao"] = (int)$this->dadosform["acao"];
	}
	
	protected function validaDados() {
		if (!$this->dadosform["data_inicio"]) $this->erro_campos[] = "data_inicio";
		if (!$this->dadosform["hora_inicio"]) $this->erro_campos[] = "hora_inicio";
		if (!$this->dadosform['titulo'])	  $this->erro_campos[] = 'titulo';
		if (!$this->dadosform['envio_para'])  $this->erro_campos[] = 'envio_para';
		if (!count($_SESSION['sessao_newsletter_itens'])) $this->erro_campos[] = "lista_itens";
		
		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function setDadosVO() {
		$this->newsvo = new NewsletterVO;
		$this->newsvo->setCodNewsletter($this->dadosform["codnewsletter"]);
		$this->newsvo->setTitulo($this->dadosform['titulo']);
		$this->newsvo->setEnvioPara($this->dadosform['envio_para']);
		$this->newsvo->setDataInicio(substr($this->dadosform["data_inicio"], 6, 4)."-".substr($this->dadosform["data_inicio"], 3, 2)."-".substr($this->dadosform["data_inicio"], 0, 2));
		$this->newsvo->setHoraInicio($this->dadosform['hora_inicio']);
		$this->newsvo->setDataEnvio($this->converteCampoDataHora($this->dadosform['data_inicio'], $this->dadosform['hora_inicio']));
		$this->newsvo->setListaItens($_SESSION['sessao_newsletter_itens']);
	}
	
	protected function editarDados() {
		if (!$this->newsvo->getCodNewsletter()) {
			$cod_newsletter = $this->newsdao->cadastrarNewsletter($this->newsvo);
			
			$this->newsdao->adicionaProgramacao($cod_newsletter, $this->newsvo->getDataEnvio(), $this->newsvo->getHoraInicio());
		} else {
			$this->newsdao->atualizarNewsletter($this->newsvo);
			$cod_newsletter = $this->newsvo->getCodNewsletter();
			
			if ($this->newsvo->getDataEnvio() . ' ' . $this->newsvo->getDataEnvio() >= date('Y-m-d H:i:s'))
				$this->newsdao->adicionaProgramacao($cod_newsletter, $this->newsvo->getDataEnvio(), $this->newsvo->getHoraInicio());
		}
		
		unset($_SESSION["sessao_newsletter_itens"]);
		return $cod_newsletter;
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
		$this->newsdao->limpaItens();
	}

	public function setDadosCamposEdicao($codnewsletter) {
		$newsvo = $this->newsdao->getNewsletterVO($codnewsletter);
		$this->dadosform["codnewsletter"] = $newsvo->getCodNewsletter();
		$this->dadosform['titulo'] = $newsvo->getTitulo();
		$this->dadosform['envio_para'] = $newsvo->getEnvioPara();
		$this->dadosform['data_inicio'] = date('d/m/Y', strtotime($newsvo->getDataInicio()));
		$this->dadosform['hora_inicio'] = $newsvo->getHoraInicio();
		
		foreach ($newsvo->getListaItens() as $key => $value)
			$_SESSION["sessao_newsletter_itens"][$key] = $key;
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
	
	public function getListasEnvio() {
		return $this->listadao->getListas();
	}
	
	protected function converteCampoDataHora($data, $hora) {
		$partes_data = explode('/', $data);
		$partes_hora = explode(':', $hora);
		return date('Y-m-d H:i:s', strtotime(((int)$partes_data[2]).'-'.((int)$partes_data[1]).'-'.((int)$partes_data[0]).' '.((int)$partes_hora[0]).':'.((int)$partes_hora[1]).':00'));
	}
	
}