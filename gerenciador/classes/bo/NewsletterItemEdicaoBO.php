<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/NewsletterItemVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterItemDAO.php");

class NewsletterItemEdicaoBO extends ConteudoBO {

	private $newsvo = null;
	private $newsdao = null;
	private $arquivo = null;

	public function __construct() {
		$this->newsdao = new NewsletterItemDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["coditem"] = (int)$this->dadosform["coditem"];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		$this->dadosform["resumo"] = substr(trim(Util::removeTags($this->dadosform["resumo"])), 0, 200);
		$this->dadosform["credito"] = substr(trim(Util::removeTags($this->dadosform["credito"])), 0, 200);
		$this->dadosform['imgtemp'] = (int)$this->dadosform['imgtemp'];
		$this->dadosform['individual'] = (int)$this->dadosform['individual'];
	}
	
	protected function validaDados() {}

	protected function setDadosVO() {
		$this->newsvo = new NewsletterItemVO;
		$this->newsvo->setCodItem((int)$this->dadosform["coditem"]);
		//if (!$this->dadosform['individual']) {
		//	$this->newsvo->setTitulo(($this->dadosform["titulo"]));
		//	$this->newsvo->setResumo(($this->dadosform["resumo"]));
		//	$this->newsvo->setCredito(($this->dadosform["credito"]));
		//} else {
			$this->newsvo->setTitulo(($this->dadosform["titulo"]));
			$this->newsvo->setResumo(($this->dadosform["resumo"]));
			$this->newsvo->setCredito(($this->dadosform["credito"]));
		//}
	}

	protected function editarDados() {
		$this->newsdao->atualizar($this->newsvo);
		$coditem = $this->newsvo->getCodItem();
		
		if (is_uploaded_file($this->arquivos['novaimagem']['tmp_name'])) {
			$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos['novaimagem']['name']));
			if (($extensao == 'jpg') || ($extensao == 'jpeg') || ($extensao == 'gif') || ($extensao == 'png')) {
				$nomearquivo = "imgnewsletter_".$coditem.".".$extensao;
				$nomearquivo_parcial = "imgnewsletter_".$coditem;
				copy($this->arquivos['novaimagem']['tmp_name'], ConfigVO::getDirFotos().$nomearquivo);
				$this->removerImagensCache($nomearquivo_parcial);
				$this->newsdao->atualizarImagem($nomearquivo, $coditem);
			}
		} elseif ($this->dadosform['imgtemp']) {
			$imagem_atual = $this->newsdao->restaurarImagem($coditem);
		}
		$this->dadosform = array();
		return $coditem;
	}
	
	public function definirDestaque($coditem) {
		$this->newsdao->atualizarDestaque($_SESSION["sessao_newsletter_itens"], $coditem);
	}

	public function setDadosCamposEdicao($coditem) {
		$homevo = $this->newsdao->getNewsletterItemVO($coditem);
		
		$this->dadosform["coditem"] = $homevo->getCodItem();
		$this->dadosform["titulo"] = $homevo->getTitulo();
		$this->dadosform["resumo"] = $homevo->getResumo();
		$this->dadosform["credito"] = $homevo->getCredito();
		$this->dadosform["imagem"] = $homevo->getImagem();
		$this->dadosform["codformato"] = $homevo->getCodFormato();
		
		$this->dadosform["original"] = $this->newsdao->getDescricaoOriginal($coditem);
	}

}