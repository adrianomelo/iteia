<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/HomeItemVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/HomeItemDAO.php");

class HomeItemEdicaoBO extends ConteudoBO {

	private $homevo = null;
	private $homedao = null;
	private $arquivo = null;

	public function __construct() {
		$this->homedao = new HomeItemDAO;
	}
	
	//public function editarItem(&$dadosform, &$arquivo) {
	//	$this->setDadosForm($dadosform, $arquivo);
	//	$this->setDadosVO();
	//	$this->editarDados();
	//}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["coditem"] = (int)$this->dadosform["coditem"];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		$this->dadosform["resumo"] = substr(trim(Util::removeTags($this->dadosform["resumo"])), 0, 200);
		$this->dadosform['imgtemp'] = (int)$this->dadosform['imgtemp'];
		$this->dadosform['individual'] = (int)$this->dadosform['individual'];
	}
	
	protected function validaDados() {}

	protected function setDadosVO() {
		$this->homevo = new HomeItemVO;
		$this->homevo->setCodItem((int)$this->dadosform["coditem"]);
		if (!$this->dadosform['individual']) {
			$this->homevo->setTitulo(utf8_decode($this->dadosform["titulo"]));
			$this->homevo->setResumo(utf8_decode($this->dadosform["resumo"]));
		} else {
			$this->homevo->setTitulo(($this->dadosform["titulo"]));
			$this->homevo->setResumo(($this->dadosform["resumo"]));
		}
	}

	protected function editarDados() {
		$this->homedao->atualizar($this->homevo);
		$coditem = $this->homevo->getCodItem();
		
		if (is_uploaded_file($this->arquivos['novaimagem']['tmp_name'])) {
			$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos['novaimagem']['name']));
			if (($extensao == 'jpg') || ($extensao == 'jpeg') || ($extensao == 'gif') || ($extensao == 'png')) {
				$nomearquivo = "imgdestaque_".$coditem.".".$extensao;
				$nomearquivo_parcial = "imgdestaque_".$coditem;
				copy($this->arquivos['novaimagem']['tmp_name'], ConfigVO::getDirFotos().$nomearquivo);
				$this->removerImagensCache($nomearquivo_parcial);
				$this->homedao->atualizarImagem($nomearquivo, $coditem);
			}
		} elseif ($this->dadosform['imgtemp']) {
			$imagem_atual = $this->homedao->restaurarImagem($coditem);
		}
		$this->dadosform = array();
		return $coditem;
	}

	public function setDadosCamposEdicao($coditem) {
		$homevo = $this->homedao->getHomeItemVO($coditem);
		
		$this->dadosform["coditem"] = $homevo->getCodItem();
		$this->dadosform["titulo"] = $homevo->getTitulo();
		$this->dadosform["resumo"] = $homevo->getResumo();
		$this->dadosform["imagem"] = $homevo->getImagem();
		$this->dadosform["codformato"] = $homevo->getCodFormato();
		
		$this->dadosform["original"] = $this->homedao->getDescricaoOriginal($coditem);
	}

}