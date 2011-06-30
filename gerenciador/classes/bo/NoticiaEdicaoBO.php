<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/NoticiaVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NoticiaDAO.php");

class NoticiaEdicaoBO extends ConteudoBO {

	private $notvo = null;
	private $notdao = null;

	public function __construct() {
		$this->notdao = new NoticiaDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codnoticia"] = (int)$this->dadosform["codnoticia"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform['imgtemp2'] = trim($this->dadosform['imgtemp2']);
		$this->dadosform["codcolaborador"] = (int)$this->dadosform["codcolaborador"];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		$this->dadosform["subtitulo"] = substr(trim($this->dadosform["subtitulo"]), 0, 200);
		$this->dadosform["assinatura"] = substr(trim($this->dadosform["assinatura"]), 0, 100);
		//$this->dadosform["texto"] = substr(trim(Util::removeTags($this->dadosform["texto"])), 0, 20000);
		$this->dadosform["texto"] = substr(trim($this->dadosform["texto"]), 0, 20000);
		$this->dadosform["data"] = substr(trim($this->dadosform["data"]), 0, 10);
		$this->dadosform["hora"] = substr(trim($this->dadosform["hora"]), 0, 5);
		$this->dadosform["foto_credito"] = substr(trim($this->dadosform["foto_credito"]), 0, 100);
		$this->dadosform["foto_legenda"] = substr(trim($this->dadosform["foto_legenda"]), 0, 200);
		$this->dadosform["home"] = (int)$this->dadosform["home"];
		$this->dadosform["home_titulo"] = substr(trim($this->dadosform["home_titulo"]), 0, 100);
		$this->dadosform["home_resumo"] = substr(trim(Util::removeTags($this->dadosform["home_resumo"])), 0, 300);
		$this->dadosform["home_foto_credito"] = substr(trim($this->dadosform["home_foto_credito"]), 0, 100);
		$this->dadosform["home_foto_legenda"] = substr(trim($this->dadosform["home_foto_legenda"]), 0, 200);
		$this->dadosform["tags"] = $this->dadosform["tags"];
	}

	protected function validaDados() {

		if (!$this->dadosform["titulo"])
			$this->erro_campos[] = "titulo";
		if (!$this->dadosform["assinatura"])
			$this->erro_campos[] = "assinatura";
		if (!$this->dadosform["texto"])
			$this->erro_campos[] = "texto";

		if ($this->dadosform["home"]) {
			if (!$this->dadosform["home_titulo"])
				$this->erro_campos[] = "home_titulo";
			if (!$this->dadosform["home_resumo"])
				$this->erro_campos[] = "home_resumo";
		}

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception("<br />\n".implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->notvo = new NoticiaVO;
		$this->notvo->setCodConteudo((int)$this->dadosform["codnoticia"]);
		$this->notvo->setCodColaborador($_SESSION['logado_dados']['cod_colaborador']);
		$this->notvo->setCodAutor($_SESSION['logado_dados']['cod']);
		$this->notvo->setTitulo($this->dadosform["titulo"]);
		$this->notvo->setSubtitulo($this->dadosform["subtitulo"]);
		$this->notvo->setAssinatura($this->dadosform["assinatura"]);
		$this->notvo->setDescricao($this->dadosform["texto"]);
		if (!$this->dadosform["data"])
			$this->notvo->setDataHora(date("Y-m-d H:i:s"));
		else
			$this->notvo->setDataHora(substr($this->dadosform["data"], 6, 4)."-".substr($this->dadosform["data"], 3, 2)."-".substr($this->dadosform["data"], 0, 2)." ".substr($this->dadosform["hora"], 0, 2).":".substr($this->dadosform["hora"], 3, 2));
		$this->notvo->setFotoCredito($this->dadosform["foto_credito"]);
		$this->notvo->setFotoLegenda($this->dadosform["foto_legenda"]);
		$this->notvo->setHome($this->dadosform["home"]);
		/*if (!(int)$this->dadosform["codnoticia"])
			$this->notvo->setSituacao(0);*/
		$this->notvo->setSituacao(1);
		$this->notvo->setPublicado(1);
		$this->notvo->setHomeTitulo($this->dadosform["home_titulo"]);
		$this->notvo->setHomeResumo($this->dadosform["home_resumo"]);
		$this->notvo->setHomeFotoCredito($this->dadosform["home_foto_credito"]);
		$this->notvo->setHomeFotoLegenda($this->dadosform["home_foto_legenda"]);
		$this->notvo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->notvo->setTags(Util::geraTags($this->dadosform['tags']));
	}

	protected function editarDados() {
		if (!$this->notvo->getCodConteudo())
			$codnoticia = $this->notdao->cadastrar($this->notvo);
		else {
			$this->notdao->atualizar($this->notvo);
			$codnoticia = $this->notvo->getCodConteudo();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgnoticia_".$codnoticia;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->notdao->atualizarFoto($nomearquivo_final, $codnoticia);
		}
		if ($this->dadosform['imgtemp2']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgnoticiahome_".$codnoticia;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp2'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->notdao->atualizarFotoHome($nomearquivo_final, $codnoticia);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		return $codnoticia;
	}

	public function setDadosCamposEdicao($codnoticia) {
		$notvo = $this->notdao->getNoticiaVO($codnoticia);

		$this->dadosform["codnoticia"] = $notvo->getCodConteudo();
		$this->dadosform["codcolaborador"] = $notvo->getCodColaborador();
		$this->dadosform["titulo"] = $notvo->getTitulo();
		$this->dadosform["subtitulo"] = $notvo->getSubtitulo();
		$this->dadosform["assinatura"] = $notvo->getAssinatura();
		$this->dadosform["texto"] = $notvo->getDescricao();
		$this->dadosform["data"] = date("d/m/Y", strtotime($notvo->getDataHora()));
		$this->dadosform["hora"] = date("H:i", strtotime($notvo->getDataHora()));
		$this->dadosform["dataoriginal"] = $notvo->getDataHora();
		$this->dadosform["imagem_visualizacao"] = $notvo->getImagem();
		$this->dadosform["foto_credito"] = $notvo->getFotoCredito();
		$this->dadosform["foto_legenda"] = $notvo->getFotoLegenda();
		$this->dadosform["home"] = $notvo->getHome();
		$this->dadosform["home_titulo"] = $notvo->getHomeTitulo();
		$this->dadosform["home_resumo"] = $notvo->getHomeResumo();
		$this->dadosform["imagem_home"] = $notvo->getHomeFoto();
		$this->dadosform["home_foto_credito"] = $notvo->getHomeFotoCredito();
		$this->dadosform["home_foto_legenda"] = $notvo->getHomeFotoLegenda();
		$this->dadosform["conteudo_relacionado"] = $notvo->getListaConteudoRelacionado();
		$this->dadosform["tags"] = $notvo->getTags();

		$this->dadosform["url"] = $notvo->getUrl();
		$this->dadosform["situacao"] = $notvo->getSituacao();
		$this->dadosform["publicado"] = $notvo->getPublicado();
	}

	public function excluirImagem($cod) {
		$this->notdao->excluirImagem($cod);
	}

	public function excluirImagemHome($cod) {
		$this->notdao->excluirImagemHome($cod);
	}

	public function getColaboradorConteudo($codnoticia) {
		return $this->notdao->getColaboradorConteudo($codnoticia);
	}

	public function getConteudoRelacionado($codnoticia) {
		return $this->notdao->getConteudoRelacionadoConteudo($codnoticia);
	}
}