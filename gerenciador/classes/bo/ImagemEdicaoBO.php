<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ImagemVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ImagemDAO.php");

class ImagemEdicaoBO extends ConteudoBO {

	private $imgvo = null;
	private $imgdao = null;

	public function __construct() {
		$this->imgdao = new ImagemDAO;
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codimagem"] = (int)$this->dadosform["codimagem"];
		$this->dadosform["legenda"] = substr(trim($this->dadosform["legenda"]), 0, 200);
		$this->dadosform["legendaimg"] = (array)$this->dadosform["legendaimg"];
		$this->dadosform["credito"] = substr(trim($this->dadosform["credito"]), 0, 200);
		$this->dadosform["creditoimg"] = (array)$this->dadosform["creditoimg"];
		$this->dadosform["capa"] = (int)$this->dadosform["capa"];
		$this->dadosform["sessao_id"] = trim($this->dadosform["sessao_id"]);
	}

	protected function validaDados() {
		
		$this->salvaLegendas($this->dadosform["legendaimg"], $this->dadosform["creditoimg"]);
		
		if (!$this->dadosform["codimagem"] && !count($this->arquivos["imagem"]["tmp_name"]))
			$this->erro_mensagens[] = "Selecione ao menos um arquivo de imagem";
			
		if (count($this->arquivos["imagem"]["tmp_name"])) {
			foreach ($this->arquivos["imagem"]["tmp_name"] as $key => $value) {
				if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"][$key])) {
					if ($this->arquivos["imagem"]["size"][$key] > 5242880) {
						$this->erro_mensagens[] = "Uma das Imagens está com mais de 5MB";
						break;
					}
					$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos["imagem"]["name"][$key]));
					if (($extensao != 'jpg') && ($extensao != 'jpeg') && ($extensao != 'gif') && ($extensao != 'png')) {
						$this->erro_mensagens[] = "Apenas enviar imagem no formato jpg, gif ou png";
						break;
					}
				}
			}
		}

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->imgvo = new ImagemVO;
		$this->imgvo->setCodImagem((int)$this->dadosform["codimagem"]);
		$this->imgvo->setCodConteudo((int)$this->dadosform["codconteudo"]);
		
		//if (!(int)$this->dadosform["codimagem"])
		//	$this->imgvo->setRandomico(Util::geraRandomico());
		
		//$this->imgvo->setLegenda($this->dadosform["legenda"]);
		//$this->imgvo->setCredito($this->dadosform["credito"]);
		
		if (!(int)$this->dadosform["codimagem"])
			$this->imgvo->setDataHora(date("Y-m-d H:i:s"));
	}

	protected function editarDados() {
		
		// cadastrar varias imagens
		foreach ($this->arquivos["imagem"]['tmp_name'] as $key => $audio) {
			
			if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"][$key])) {
			
				$this->imgvo->setRandomico(Util::geraRandomico());
				//$this->audvo->setFaixa($this->arquivos["audio"]['name'][$key]);
			
				$codimagem = $this->imgdao->cadastrarImagem($this->imgvo, $this->dadosform['sessao_id']);
				$_SESSION["sess_conteudo_imagens_album"][$this->dadosform['sessao_id']][] = $codimagem;
		
				// envia os arquivos
				$extensao = Util::getExtensaoArquivo($this->arquivos["imagem"]["name"][$key]);
				$nomearquivo = "imggaleria_".$codimagem.".".$extensao;
				
				copy($this->arquivos["imagem"]["tmp_name"][$key], ConfigVO::getDirGaleria().$nomearquivo);
				$this->imgdao->atualizarArquivo($nomearquivo, $codimagem);
				
				sleep(2);
			}
			
		}
		
		/*
		if (!$this->imgvo->getCodImagem()) {
			$codimagem = $this->imgdao->cadastrarImagem($this->imgvo);
			$_SESSION["sess_conteudo_imagens_album"][] = $codimagem;
		}
		else {
			$this->imgdao->atualizarImagem($this->imgvo);
			$codimagem = $this->imgvo->getCodImagem();
		}
		if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"])) {
			$extensao = Util::getExtensaoArquivo($this->arquivos["imagem"]["name"]);
			$nomearquivo = "imggaleria_".$codimagem.".".$extensao;
			copy($this->arquivos["imagem"]["tmp_name"], ConfigVO::getDirGaleria().$nomearquivo);
			$this->imgdao->atualizarArquivo($nomearquivo, $codimagem);
		}
		*/
		$this->dadosform = array();
		$this->arquivos = array();
		return true;
	}
	
	public function salvaLegendas($legendasimg, $creditosimg) {
		$this->imgvo = new ImagemVO;
		foreach ($legendasimg as $codimagem => $legenda) {
			if ((int)$codimagem) {
				$this->imgvo->setCodImagem((int)$codimagem);
				$this->imgvo->setLegenda($legenda);
				$this->imgvo->setCredito($creditosimg[$codimagem]);
				$this->imgdao->atualizarImagem($this->imgvo);
			}
		}
	}
	
	public function definirCapaAlbum($codimagem) {
		$this->imgdao->atualizarCapa($_SESSION["sess_conteudo_imagens_album"][$this->dadosform['sessao_id']], $codimagem);
	}

	public function setDadosCamposEdicao($codimagem) {
		$imgvo = $this->imgdao->getImagemVO($codimagem);

		$this->dadosform["codimagem"] = $imgvo->getCodImagem();
		$this->dadosform["legenda"] = $imgvo->getLegenda();
		$this->dadosform["credito"] = $imgvo->getCredito();
	}
	
	public function getListaImagensSelecionados() {
		$dados_busca["lista_imagens"] = $_SESSION["sess_conteudo_imagens_album"][$this->dadosform['sessao_id']];
		return $this->imgdao->getTotalItensAlbum($dados_busca);
	}
	
	public function organizaOrdenacaoImagem($coditem, $i) {
		$this->imgdao->atualizaOrdenacao($coditem, $i);
	}
	
	public function organizacaoFinal($sessao_id) {
		$this->imgdao->organizacaoFinal($sessao_id);
	}

}