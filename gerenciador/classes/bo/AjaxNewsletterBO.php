<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterItemDAO.php");

class AjaxNewsletterBO {

	private $newsdao = null;
	private $newsitemdao = null;
	private $dados_get = array();

	public function __construct($dados_get = array()) {
		$this->newsdao = new NewsletterDAO;
		$this->newsitemdao = new NewsletterItemDAO;
		$this->dados_get = $dados_get;
	}

	public function executaAcao() {
	    switch ($this->dados_get["get"]) {
			case "adicionar_conteudo_newsletter": $this->adicionarConteudoNewsletter(); break;
			case "listar_conteudo_newsletter": $this->listarConteudoNewsletter(); break;
			
			case "executa_acao": $this->executaAcaoSelecionados(); break;
			
			case "definir_destaque": $this->definirDestaque(); break;
			
			case "news_item_edicao_restaurar_titulo": $this->newsItemRestaurarTituloChamada(); break;
			case "news_item_edicao_restaurar_chamada": $this->newsItemRestaurarChamada(); break;
			case "news_item_edicao_restaurar_imagem": $this->newsItemRestaurarImagem(); break;
			case "news_item_edicao_editar": $this->newsItemEditarDestaque($_FILES); break;		
			
			case "executa_acao_salvarconteudo": $this->salvarItens(); break;
			case "executa_acao_apagarconteudoinicial": $this->apagarItensInicial(); break;
		}
	}
	
	private function definirDestaque() {
		include('NewsletterItemEdicaoBO.php');
		$itembo = new NewsletterItemEdicaoBO;
		$itembo->definirDestaque($this->dados_get['coditem']);
		$this->dados_get["destaque"] = $this->dados_get['coditem'];
		$this->listarConteudoNewsletter();
	}
	
	private function newsItemEditarDestaque(&$arquivos) {
		include('NewsletterItemEdicaoBO.php');
		$itembo = new NewsletterItemEdicaoBO;
		$itembo->editar($this->dados_get, $arquivos);
		$this->listarConteudoNewsletter();
	}
	
	private function newsItemRestaurarTituloChamada() {
		$dados_itens = $this->newsdao->getDadosItem($this->dados_get["coditem"]);
		echo strip_tags(utf8_encode($dados_itens['titulo']));
	}
	
	private function newsItemRestaurarChamada() {
		$dados_itens = $this->newsdao->getDadosItem($this->dados_get["coditem"]);
		echo strip_tags(utf8_encode($dados_itens['descricao']));
	}
	
	private function newsItemRestaurarImagem() {
		$dados_itens = $this->newsdao->getDadosItem($this->dados_get["coditem"]);
		
		$tipo = ($dados_itens['cod_formato'] == 2 ? '2' : 'a');
		
		if ($dados_itens['imagem'])
			echo "<img src=\"exibir_imagem.php?img=".$dados_itens['imagem']."&amp;tipo=".$tipo."&amp;s=21\" alt=\"\" id=\"imagem_exibicao\" />";
		else
			echo '<img src="img/imagens-padrao/texto.jpg" width="265" height="170" alt=""  />';
	}
	
	private function executaAcaoSelecionados() {
		$lista_selecionados = explode(",", $this->dados_get["itens"]);
		switch($this->dados_get["numacao"]) {
			case 1:
				$this->newsdao->removeConteudoSelecao($lista_selecionados);
				$this->newsdao->organizacaoFinal();
			break;
			case 2:
			case 3:
			if (count($lista_selecionados)) {
				foreach ($lista_selecionados as $coditem) {
					$this->newsdao->atualizaOrdenacao($coditem, $this->dados_get["numacao"]);
					$this->newsdao->organizacaoFinal();
				}
			}
			 break;
		}
		$this->listarConteudoNewsletter();
	}

	private function adicionarConteudoNewsletter() {
		if ((int)$this->dados_get["cod"]) {
			$cod_item = $this->newsdao->adicionarConteudoNewsletter($this->dados_get["cod"], $this->dados_get["codnewsletter"]);
			if ($cod_item)
				$_SESSION['sessao_newsletter_itens'][$cod_item] = $cod_item;
		}
		$this->listarConteudoNewsletter();
	}
	
	private function listarConteudoNewsletter() {
		$codnewsletter = $this->dados_get["codnewsletter"];
		include_once("includes/ajax_newsletter_conteudo_selecionados.php");
	}

	private function salvarItens() {
		$this->newsdao->salvarItens($this->dados_get["itens"]);
	}
	
	private function apagarItensInicial() {
		$this->newsdao->apagaItensInicial();
	}

}
