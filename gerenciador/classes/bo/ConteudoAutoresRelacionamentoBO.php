<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ConteudoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoSemelhanteDAO.php");

class ConteudoAutoresRelacionamentoBO extends ConteudoBO {

	private $contvo = null;
	private $contdao = null;

	public function __construct() {
		$this->contdao = new ConteudoSemelhanteDAO;
		parent::__construct();
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codconteudo'] = (int)$dadosform['codconteudo'];
		$this->dadosform['cod_autores_relacionado'] = $_SESSION['sessao_autores_relacionados'];
	}
	
	protected function validaDados() { }
	
	protected function setDadosVO() {
		$this->contvo = new ConteudoVO;
		$this->contvo->setCodConteudo($this->dadosform['codconteudo']);
		$this->contvo->setListaAutores($this->dadosform['cod_autores_relacionado']);
	}
	
	protected function editarDados() {
		if ($this->contvo->getCodConteudo()) {
			$this->contdao->atualizarConteudoAutores($this->contvo);
		}
		return $this->redirecionaVisualizacao();
	}
	
	public function carregarAutoresRelacionamento($codconteudo) {
		$autorelacionado = $this->contdao->getAutoresConteudo($codconteudo);
		foreach ($autorelacionado as $key => $value) {
			if (!count($_SESSION['sessao_autores_relacionados'][$value['cod_usuario']])) {
				$_SESSION['sessao_autores_relacionados'][$value['cod_usuario']] = $value;
			}
		}
	}
	
	private function redirecionaVisualizacao() {
		$dadosform = $this->contdao->getDadosConteudoVO($this->contvo->getCodConteudo());
		switch ($dadosform->getCodFormato()) {
			case 1: $local = 'texto'; break;
			case 2: $local = 'imagem'; break;
			case 3: $local = 'audio'; break;
			case 4: $local = 'video'; break;
		}
		return 'conteudo_publicado_'.$local.'.php?cod='.$this->contvo->getCodConteudo();
	}
	
	
}