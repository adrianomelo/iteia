<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ConteudoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoSemelhanteDAO.php");

class ConteudoRelacionamentoBO extends ConteudoBO {

	private $contvo = null;
	private $contdao = null;

	public function __construct() {
		$this->contdao = new ConteudoSemelhanteDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codconteudo'] = (int)$dadosform['codconteudo'];
		$this->dadosform['cod_conteudo_relacionado'] = array_keys($_SESSION['sessao_conteudo_relacionamento']);
	}

	protected function validaDados() { }

	protected function setDadosVO() {
		$this->contvo = new ConteudoVO;
		$this->contvo->setCodConteudo($this->dadosform['codconteudo']);
		$this->contvo->setListaConteudoRelacionado($this->dadosform['cod_conteudo_relacionado']);
	}

	protected function editarDados() {
		if ($this->contvo->getCodConteudo()) {
			$this->contdao->atualizarConteudoRelacionado($this->contvo);
		}
		return $this->redirecionaVisualizacao();
	}

	public function getConteudoDados($codconteudo) {
		$dadosform = $this->contdao->getDadosConteudoVO($codconteudo);
		$this->dadosform['cod_conteudo'] = $dadosform->getCodConteudo();
		$this->dadosform['cod_formato'] = $dadosform->getCodFormato();
		$this->dadosform['titulo'] = $dadosform->getTitulo();
	}

	public function carregarConteudoRelacionamento($codconteudo) {
		$conteudorelacionado = $this->contdao->getConteudoRelacionadoConteudo($codconteudo);
		foreach ($conteudorelacionado as $key => $value) {
			if (!count($_SESSION['sessao_conteudo_relacionamento'][$value['cod_conteudo']])) {
				$_SESSION['sessao_conteudo_relacionamento'][$value['cod_conteudo']] = array('cod_formato' => $value['cod_formato'], 'titulo' => htmlentities($value['titulo']));
			}
		}
	}

	private function redirecionaVisualizacao() {
		$dadosform = $this->contdao->getDadosConteudoVO($this->contvo->getCodConteudo());
		switch ($dadosform->getCodFormato()) {
			case 1: $local = 'conteudo_publicado_texto'; break;
			case 2: $local = 'conteudo_publicado_imagem'; break;
			case 3: $local = 'conteudo_publicado_audio'; break;
			case 4: $local = 'conteudo_publicado_video'; break;
			case 5: $local = 'noticia_publicado'; break;
			case 6: $local = 'agenda_publicado'; break;
		}
		return $local.'.php?cod='.$this->contvo->getCodConteudo();
	}

}
