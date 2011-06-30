<?php
include_once('ConteudoBO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/SegmentoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/SegmentoDAO.php");

class SegmentoEdicaoBO extends ConteudoBO {

	private $segvo = null;
	private $segdao = null;

	public function __construct() {
		$this->segdao = new SegmentoDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codsegmento"] = (int)$this->dadosform["codsegmento"];
		$this->dadosform["nome"] = substr(trim($this->dadosform["nome"]), 0, 100);
		$this->dadosform["descricao"] = substr(trim(Util::removeTags($this->dadosform["descricao"])), 0, 50000);
		$this->dadosform["codpai"] = (int)$this->dadosform["codpai"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform["verbete"] = (int)$this->dadosform["verbete"];
	}
	
	protected function validaDados() {
		if (!$this->dadosform["nome"]) $this->erro_campos[] = "nome";

		//if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}
	
	protected function setDadosVO() {
		$this->segvo = new SegmentoVO;
		$this->segvo->setCodSegmento((int)$this->dadosform["codsegmento"]);
		$this->segvo->setCodPai($this->dadosform['codpai']);
		$this->segvo->setNome($this->dadosform["nome"]);
		$this->segvo->setDescricao($this->dadosform["descricao"]);
		$this->segvo->setVerbete($this->dadosform["verbete"]);
	}
	
	protected function editarDados() {
		if (!$this->segvo->getCodSegmento()) {
			$codsegmento = $this->segdao->cadastrar($this->segvo);
		} else {
			$this->segdao->atualizar($this->segvo);
			$codsegmento = $this->segvo->getCodSegmento();
		}
		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgsegmento_".$codsegmento;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->segdao->atualizarFoto($nomearquivo_final, $codsegmento);
		}
		$this->dadosform = array();
		$this->arquivos = array();
		return $codsegmento;
	}
	
	public function setDadosCamposEdicao($codseg) {
		$segvo = $this->segdao->getSegmentoVO($codseg);
		$this->dadosform["codsegmento"] = $segvo->getCodSegmento();
		$this->dadosform["codpai"] = $segvo->getCodPai();
		$this->dadosform["nome"] = $segvo->getNome();
		$this->dadosform["descricao"] = $segvo->getDescricao();
		$this->dadosform["verbete"] = $segvo->getVerbete();
		$this->dadosform["imagem_visualizacao"] = $segvo->getImagem();
	}
	
	public function getListaSegmentos($get) {
		if ($get['acao']) {
			$this->segdao->executaAcao($get['codsegmento']);
			Header('Location: conteudo_segmentos.php');
			die;
		}
		
		return $this->segdao->getListaSegmentos();
	}
	
	public function getSegmentosPai() {
		return $this->segdao->getSegmentosPai();
	}
	
}