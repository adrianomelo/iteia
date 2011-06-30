<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ImagemVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ImagemDAO.php");

class ImagemBuscaBO {

	private $imgdao = null;
	private $parametros = "";
	private $dados_form = array();
	private $total = 0;

	public function __construct() {
		$this->imgdao = new ImagemDAO();
	}

	public function setTotal($total) {
		$this->total = $total;
	}

	public function getTotal() {
		return $this->total;
	}

	public function getListaImagens(&$dados_form, $pagina, $intervalo) {

		$dados_form_aux = $dados_form;
		if (isset($dados_form_aux["get"]))
			unset($dados_form_aux["get"]);
		if (isset($dados_form_aux["pagina"]))
			unset($dados_form_aux["pagina"]);
		if (isset($dados_form_aux["intervalo"]))
			unset($dados_form_aux["intervalo"]);
		if (isset($dados_form_aux["undefined"]))
			unset($dados_form_aux["undefined"]);
		if (isset($dados_form_aux["acao"]))
			unset($dados_form_aux["acao"]);
		$this->parametros = http_build_query($dados_form_aux);

		$this->dados_form = $dados_form;
		$this->dados_form["lista_imagens"] = $_SESSION["sess_conteudo_imagens_album"][$dados_form['sessao_id']];

		if (!$this->total)
			$this->total = $this->imgdao->buscarImagens($this->dados_form, $pagina, $intervalo, true);
		$lista_imagens = $this->imgdao->buscarImagens($this->dados_form, $pagina, $intervalo);
		return $lista_imagens;
	}

	public function getHtmlNavegacao() {
		return NavegacaoUtil::geraHtmlPaginas("irPaginaBuscaImagens", $this->dados_form, $this->total, true);
	}

	public function getParametros() {
		return $this->parametros;
	}
	
}
