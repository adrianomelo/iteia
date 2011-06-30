<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ConteudoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoSemelhanteDAO.php");

class ConteudoColaboradoresRevisaoBO extends ConteudoBO {

	private $contvo = null;
	private $contdao = null;

	public function __construct() {
		$this->contdao = new ConteudoSemelhanteDAO;
		parent::__construct();
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codconteudo'] = (int)$dadosform['codconteudo'];
		$this->dadosform['cod_colaboradores_revisao'] = $_SESSION['sessao_colaboradores_revisao'];
	}
	
	protected function validaDados() { }
	
	protected function setDadosVO() {
		$this->contvo = new ConteudoVO;
		$this->contvo->setCodConteudo($this->dadosform['codconteudo']);
		$this->contvo->setListaColaboradoresRevisao($this->dadosform['cod_colaboradores_revisao']);
	}
	
	protected function editarDados() {
		if ($this->contvo->getCodConteudo()) {
			$this->contdao->atualizarConteudoColaboradoresRevisao($this->contvo);
		}
		return $this->redirecionaVisualizacao();
	}
	
	public function carregarColaboradoresRevisao($codconteudo) {
		$colabrevisao = $this->contdao->getColaboradoresConteudoRevisao($codconteudo);
		foreach ($colabrevisao as $key => $value) {
			if (!count($_SESSION['sessao_colaboradores_revisao'][$value['cod_usuario']])) {
				$_SESSION['sessao_colaboradores_revisao'][$value['cod_usuario']] = $value;
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