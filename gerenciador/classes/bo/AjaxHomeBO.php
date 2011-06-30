<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/HomeDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PlayListDAO.php");

class AjaxHomeBO {

	private $homedao = null;
	private $playdao = null;
	private $dados_get = array();
	private $tempo_padrao = 60;

	public function __construct($dados_get = array()) {
		$this->homedao = new HomeDAO;
		$this->playdao = new PlayListDAO;
		$this->dados_get = $dados_get;
	}

	public function executaAcao() {
	    switch ($this->dados_get["get"]) {
			case "adicionar_conteudo_playlist": $this->adicionarConteudoPlayList(); break;
			case "mudar_tempo_playlist": $this->mudarTempoItemPlayList(); break;
			case "listar_conteudo_playlist": $this->listarConteudoPlayList(); break;
			
			case "executa_acao": $this->executaAcaoSelecionados(); break;
			
			case "home_item_edicao_restaurar_titulo": $this->homeItemRestaurarTituloChamada(); break;
			case "home_item_edicao_restaurar_chamada": $this->homeItemRestaurarChamada(); break;
			case "home_item_edicao_restaurar_imagem": $this->homeItemRestaurarImagem(); break;
			case "home_item_edicao_editar": $this->homeItemEditarDestaque($_FILES); break;		
			
			//case "listar_conteudo_home": $this->listarConteudoHome(); break;
			//case "mudar_tempo": $this->mudarTempoItemHome(); break;
			//case "executa_acao": $this->executaAcaoSelecionados(); break;
			//case "ordena_data": $this->ordernarPorData(); break;
			case "buscar_usuario": $this->buscarUsuario(); break;
			case "exibir_dadoshome_usuario": $this->exibirDadosHomeUsuario(); break;

			case "adicionar_conteudo": $this->adicionarConteudoUsuario(); break;
			case "executa_acao_conteudo": $this->executaAcaoConteudoSelecionados(); break;
			case "mudar_tempo_usuario": $this->mudarTempoItemHomeUsuario(); break;
			case "ordena_data_usuario": $this->ordernarPorDataUsuario(); break;
			
			case "executa_acao_salvarconteudo": $this->salvarItens(); break;
			case "executa_acao_apagarconteudoinicial": $this->apagarItensInicial(); break;
		}
	}
	
	private function homeItemEditarDestaque(&$arquivos) {
		include('HomeItemEdicaoBO.php');
		$itembo = new HomeItemEdicaoBO;
		$itembo->editar($this->dados_get, $arquivos);
		$this->listarConteudoPlayList();
	}
	
	private function homeItemRestaurarTituloChamada() {
		$dados_itens = $this->playdao->getDadosItem($this->dados_get["coditem"]);
		echo strip_tags(utf8_encode($dados_itens['titulo']));
	}
	
	private function homeItemRestaurarChamada() {
		$dados_itens = $this->playdao->getDadosItem($this->dados_get["coditem"]);
		echo strip_tags(utf8_encode($dados_itens['descricao']));
	}
	
	private function homeItemRestaurarImagem() {
		$dados_itens = $this->playdao->getDadosItem($this->dados_get["coditem"]);
		
		$tipo = ($dados_itens['cod_formato'] == 2 ? '2' : 'a');
		
		if ($dados_itens['imagem'])
			echo "<img src=\"exibir_imagem.php?img=".$dados_itens['imagem']."&amp;tipo=".$tipo."&amp;s=6\" width=\"124\" height=\"124\" alt=\"\" id=\"imagem_exibicao\" /><a href=\"javascript:removerImagem(".$dados_itens['cod_item'].");\" title=\"Remover\" class=\"remover\">Remover imagem</a>";
		else
			echo '<img src="img/imagens-padrao/texto.jpg" width="124" height="124" alt=""  />';
	}
	
	private function executaAcaoSelecionados() {
		$lista_selecionados = explode(",", $this->dados_get["itens"]);
		switch($this->dados_get["numacao"]) {
			case 1:
				$this->playdao->removeConteudoSelecao($lista_selecionados);
				$this->playdao->organizacaoFinal();
			break;
			case 2:
			case 3:
			if (count($lista_selecionados)) {
				foreach ($lista_selecionados as $coditem) {
					$this->playdao->atualizaOrdenacao($coditem, $this->dados_get["numacao"]);
					$this->playdao->organizacaoFinal();
				}
			}
			 break;
		}
		$this->playdao->atualizaDatasExibicao();
		$this->listarConteudoPlayList();
	}

	private function adicionarConteudoPlayList() {
		if ((int)$this->dados_get["cod"]) {
			$cod_item = $this->playdao->adicionarConteudoPlayList($this->dados_get["cod"], $this->dados_get["codplaylist"], $this->tempo_padrao);
			if ($cod_item)
				$_SESSION['sessao_playlist_itens'][$cod_item] = $cod_item;
		}
		$this->listarConteudoPlayList();
	}
	
	private function listarConteudoPlayList() {
		$codplaylist = $this->dados_get["codplaylist"];
		include_once("includes/ajax_playlist_conteudo_selecionados.php");
	}
	
	private function mudarTempoItemPlayList() {
		if ((int)$this->dados_get["cod"])
			$this->playdao->mudarTempoItemPlayList($this->dados_get["cod"], $this->dados_get["tempo"]);
		$this->listarConteudoPlayList();
	}
	
	// ===================================================================
	// ===================================================================
	// ===================================================================

	private function adicionarConteudoUsuario() {
		if ((int)$this->dados_get["cod"]) {
			$this->homedao->adicionarConteudoListaHomeUsuario($this->dados_get["cod"], $this->dados_get["tipousuario"], $this->dados_get["codusuario"], $this->tempo_padrao);
		}
		$this->exibirDadosHomeUsuario();
	}
	
	private function executaAcaoConteudoSelecionados() {
		$lista_selecionadas = explode(",", $this->dados_get["itens"]);
		if (count($lista_selecionadas)) {
			$todos_conteudo = $this->homedao->getListaConteudoHomeUsuario($this->dados_get["codusuario"], $this->dados_get["tipousuario"], true);

			switch ($this->dados_get["numacao"]) {
				case 1:
					$this->homedao->removeConteudoSelecao($lista_selecionadas);
					$this->organizaOrdenacao($this->homedao->getListaConteudoHomeUsuario($this->dados_get["codusuario"], $this->dados_get["tipousuario"], true));
					break;
				case 2:
					$todos_conteudo_aux = $todos_conteudo;
					foreach ($lista_selecionadas as $coditem) {
						$posicao_atual = array_search($coditem, $todos_conteudo_aux);
						if ($posicao_atual) {
							$todos_conteudo_aux[$posicao_atual] = $todos_conteudo_aux[$posicao_atual - 1];
							$todos_conteudo_aux[$posicao_atual - 1] = $coditem;
						}
						$this->organizaOrdenacao($todos_conteudo_aux);
					}
					break;
				case 3:
					$todos_conteudo_aux = $todos_conteudo;
					$lista_selecionadas = array_reverse($lista_selecionadas);
					foreach ($lista_selecionadas as $coditem) {
						$posicao_atual = array_search($coditem, $todos_conteudo_aux);
						if ($posicao_atual < (count($todos_conteudo) - 1)) {
							$todos_conteudo_aux[$posicao_atual] = $todos_conteudo_aux[$posicao_atual + 1];
							$todos_conteudo_aux[$posicao_atual + 1] = $coditem;
						}
						$this->organizaOrdenacao($todos_conteudo_aux);
					}
					break;
			}
		}
		$this->exibirDadosHomeUsuario();
	}
	
	private function mudarTempoItemHomeUsuario() {
		if ((int)$this->dados_get["cod"])
			$this->homedao->mudarTempoItemHome($this->dados_get["cod"], $this->dados_get["tempo"]);
		$this->exibirDadosHomeUsuario();
	}
	
	private function exibirDadosHomeUsuario() {
		$nome_usuario = trim($this->dados_get["nome_usuario"]);
		$tipo_usuario = (int)$this->dados_get["tipousuario"];
		$codusuario = (int)$this->dados_get["codusuario"];
		include_once("includes/ajax_home_dados_usuario.php");
	}
	
	private function ordernarPorDataUsuario() {
		$todas_noticias = $this->homedao->getListaConteudoHomeUsuario($this->dados_get["codusuario"], $this->dados_get["tipousuario"], true);
		$this->organizaOrdenacao($todas_noticias);
		$this->exibirDadosHomeUsuario();
	}
	
	private function organizaOrdenacao(&$lista) {
		$lista_ordenada = array_values($lista);
		foreach ($lista_ordenada as $i => $coditem)
			$this->homedao->atualizaOrdenacao($coditem, $i + 1);
	}
	
	private function salvarItens() {
		$this->homedao->salvarItens($this->dados_get["itens"]);
	}
	
	private function apagarItensInicial() {
		$this->homedao->apagaItensInicial();
	}

}
