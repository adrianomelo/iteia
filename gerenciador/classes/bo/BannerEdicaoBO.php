<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/BannerVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/BannerDAO.php");

class BannerEdicaoBO {

	private $banvo = null;
	private $bandao = null;
	protected $erro_campos = array();
	protected $erro_mensagens = array();

	public function __construct() {
		$this->bandao = new BannerDAO;
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codbanner"] = (int)$this->dadosform["codbanner"];
		$this->dadosform["codcolaborador"] = (int)$this->dadosform['codcolaborador'];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 200);
		$this->dadosform["url"] = str_replace('http://', '', substr(trim($this->dadosform["url"]), 0, 200));
		$this->dadosform["prioridade"] = (int)$this->dadosform["prioridade"];
		$this->dadosform["home"] = (int)$this->dadosform["home"];
		$this->dadosform["data_inicial"] = substr(trim($this->dadosform["data_inicial"]), 0, 10);
		$this->dadosform["data_final"] = substr(trim($this->dadosform["data_final"]), 0, 10);
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
	}

	protected function validaDados() {
		if (!$this->dadosform["titulo"]) $this->erro_campos[] = "titulo";		

		if (!$this->dadosform["codbanner"] && !$this->dadosform["imgtemp"]) {
			$this->erro_campos[] = "imagem";
			$this->erro_mensagens[] = "Falta a Imagem do banner";
		}

		if ($this->dadosform["data_inicial"] && !checkdate(substr($this->dadosform["data_inicial"], 3, 2), substr($this->dadosform["data_inicial"], 0, 2), substr($this->dadosform["data_inicial"], 6, 2))) {
			$this->erro_campos[] = "data_inicial";
		}
		
		if ($this->dadosform["data_final"] && !checkdate(substr($this->dadosform["data_final"], 3, 2), substr($this->dadosform["data_final"], 0, 2), substr($this->dadosform["data_final"], 6, 2))) {
			$this->erro_campos[] = "data_final";
		}

        if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->banvo = new BannerVO;
		$this->banvo->setCodBanner((int)$this->dadosform["codbanner"]);
		$this->banvo->setCodColaborador($_SESSION['logado_cod']);
		$this->banvo->setTitulo($this->dadosform["titulo"]);
		$this->banvo->setUrl($this->dadosform["url"]);
		$this->banvo->setPrioridade($this->dadosform["prioridade"]);
		$this->banvo->setDataInicial(substr($this->dadosform["data_inicial"], 6, 4)."-".substr($this->dadosform["data_inicial"], 3, 2)."-".substr($this->dadosform["data_inicial"], 0, 2));
		$this->banvo->setDataFinal(substr($this->dadosform["data_final"], 6, 4)."-".substr($this->dadosform["data_final"], 3, 2)."-".substr($this->dadosform["data_final"], 0, 2));
		$this->banvo->setHome($this->dadosform["home"]);
	}

	protected function editarDados() {
		if (!$this->banvo->getCodBanner()) {
			$codbanner = $this->bandao->cadastrar($this->banvo);
		} else {
			$this->bandao->atualizar($this->banvo);
			$codbanner = $this->banvo->getCodBanner();
		}
		
		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgbanner_".$codbanner;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->bandao->atualizarFoto($nomearquivo_final, $codbanner);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		return $codbanner;
	}

	public function setDadosCamposEdicao($codbanner) {
		$banvo = $this->bandao->getBannerVO($codbanner);
		$this->dadosform["codbanner"] = $banvo->getCodBanner();
		$this->dadosform["codcolaborador"] = $banvo->getCodColaborador();
		$this->dadosform["colaborador"] = $banvo->getColaborador();
		$this->dadosform["titulo"] = $banvo->getTitulo();
		$this->dadosform["url"] = $banvo->getUrl();
		$this->dadosform["prioridade"] = $banvo->getPrioridade();
		$this->dadosform["data_inicial"] = "";
		$this->dadosform["data_final"] = "";
		if ($banvo->getDataInicial() && ($banvo->getDataInicial() != "0000-00-00"))
			$this->dadosform["data_inicial"] = date("d/m/Y", strtotime($banvo->getDataInicial()));
		if ($banvo->getDataFinal() && ($banvo->getDataFinal() != "0000-00-00"))
			$this->dadosform["data_final"] = date("d/m/Y", strtotime($banvo->getDataFinal()));
		$this->dadosform["imagem_visualizacao"] = $banvo->getImagem();
		$this->dadosform["home"] = $banvo->getHome();
	}

    public function excluirImagem($codbanner) {
		$this->bandao->excluirImagem($codbanner);
	}
	
	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}
	
	protected function setArquivos(&$arquivos) {
		$this->arquivos = $arquivos;
	}

	public function editar(&$dadosform, &$arquivos) {
		$this->setDadosForm($dadosform);
		$this->setArquivos($arquivos);
		try {
			$this->validaDados();
		} catch (Exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		return $this->editarDados();
	}
	
	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " style=\"border:1px solid #FF0000; background:#FFDFDF;\"";
		else
			return "";
	}
	
	public function removerImagensCache($nomearq) {
		foreach (glob(ConfigVO::getDirImgCache()."*".$nomearq."*") as $arquivo)
			unlink($arquivo);
	}

    public function executaAcao($cod, $acao) {
		$this->bandao->executaAcao($cod, $acao);
	}
	
}
