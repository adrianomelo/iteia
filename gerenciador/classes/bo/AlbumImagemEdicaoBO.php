<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AlbumImagemVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ImagemDAO.php");

class AlbumImagemEdicaoBO extends ConteudoBO {

	private $albumvo = null;
	private $imgdao = null;

	public function __construct() {
		$this->imgdao = new ImagemDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codalbum"] = (int)$this->dadosform["codalbum"];
		$this->dadosform["capa"] = (int)$this->dadosform["capa"];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		//$this->dadosform["descricao"] = substr(trim(Util::removeTags($this->dadosform["descricao"])), 0, 1200);
		$this->dadosform["descricao"] = substr(trim($this->dadosform["descricao"]), 0, 2000);
		$this->dadosform["codclassificacao"] = (int)$this->dadosform["codclassificacao"];
		$this->dadosform["codsegmento"] = (int)$this->dadosform["codsegmento"];
		$this->dadosform["codcanal"] = (int)$this->dadosform["codcanal"];
		$this->dadosform["tags"] = $this->dadosform["tags"];
		$this->dadosform["permitir_comentarios"] = (int)$this->dadosform["permitir_comentarios"];
		
		$this->dadosform["pertence_voce"] = (int)$this->dadosform["pertence_voce"];
		$this->dadosform["codcolaborador"] = (int)$this->dadosform["codcolaborador"];
		
		$this->dadosform["pedir_autorizacao"] = (int)$this->dadosform["pedir_autorizacao"];
		$this->dadosform["colaboradores_lista"] = strip_tags(trim($this->dadosform["colaboradores_lista"]));
		
		$this->dadosform["sessao_id"] = trim($this->dadosform["sessao_id"]);
		
		$this->dadosform["contsegmento"] = (int)$this->dadosform["contsegmento"];
		$this->dadosform["contsubarea"] = (int)$this->dadosform["contsubarea"];
		$this->dadosform["codsubarea"] = (int)$this->dadosform["codsubarea"];
	}

	protected function validaDados() {
		if (!$this->dadosform["titulo"]) {
			$this->erro_campos[] = "titulo";
		}

		if (!$this->dadosform["descricao"]) {
			$this->erro_campos[] = "descricao";
		}

		if (!count($_SESSION["sess_conteudo_imagens_album"][$this->dadosform["sessao_id"]])) {
			$this->erro_mensagens[] = "Adicione ao menos uma imagem";
			$this->erro_campos[] = "imagem";
		}
		
		if ($this->dadosform["contsegmento"] && !$this->dadosform["codsegmento"]) $this->erro_campos[] = "codsegmento";
		//if ($this->dadosform["contsubarea"] && !$this->dadosform["codsubarea"]) $this->erro_campos[] = "codsubarea";
		
		//if ($this->dadosform["pertence_voce"] == 1) {
		//	if (!count($_SESSION['sess_conteudo_autores_ficha'])) {
		//		$this->erro_mensagens[] = "Selecione ao menos um autor na Ficha técnica";
		//		$this->erro_campos[] = "ficha";
		//	}
		//}
		
		//if ($_SESSION['logado_dados']['nivel'] >= 6 && !$this->dadosform["codalbum"] && !$this->dadosform["codcolaborador"]) {
		//	$this->erro_mensagens[] = "Selecione um colaborador para vincular a este conteúdo";
		//	$this->erro_campos[] = "colaborador";
		//}
		
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			if (!$this->dadosform["pedir_autorizacao"]) {
				$this->erro_mensagens[] = "Selecione um tipo de Autorização";
				$this->erro_campos[] = "autorizacao";
			}
			if ($this->dadosform["pedir_autorizacao"] == 2) {
				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
				$usrdao = new UsuarioDAO;
			
				if (!count($usrdao->getCheckColabadoresAprovacao($this->dadosform["colaboradores_lista"]))) {
					$this->erro_mensagens[] = "Selecione ao menos um colaborador para solicitar aprovação";
					$this->erro_campos[] = "colaboradores_lista";
				}
			}
		}
		
		/*
		if ($_SESSION['logado_dados']['nivel'] == 2) {
			if (!$this->dadosform["pedir_autorizacao"]) {
				$this->erro_mensagens[] = "Selecione um tipo de Autorização";
				$this->erro_campos[] = "autorizacao";
			}
			if ($this->dadosform["pedir_autorizacao"] == 2 && !$this->dadosform["colaboradores_lista"]) {
				$this->erro_mensagens[] = "Selecione ao menos um colaborador para solicitar aprovação";
				$this->erro_campos[] = "colaboradores_lista";
			}
		}
		*/

		foreach ($this->direitosbo->validaDados($this->dadosform) as $errodir)
			$this->erro_mensagens[] = $errodir;

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->albumvo = new AlbumImagemVO;
		$this->albumvo->setCodConteudo((int)$this->dadosform["codalbum"]);

		/*if ($_SESSION['logado_como'] == 1) // se for enviada por um autor
			$this->albumvo->setCodAutor($_SESSION['logado_cod']);
		else
			$this->albumvo->setCodColaborador($_SESSION['logado_cod']);*/
		$this->albumvo->setCodAutor($_SESSION['logado_cod']);
		
		//if ($_SESSION['logado_dados']['nivel'] >= 6)
		//	$this->albumvo->setCodColaborador($this->dadosform["codcolaborador"]);
		//else
			$this->albumvo->setCodColaborador($_SESSION['logado_dados']['cod_colaborador']);

		$this->albumvo->setCodClassificacao($this->dadosform['codclassificacao']);
		$this->albumvo->setCodSegmento($this->dadosform['codsegmento']);
		$this->albumvo->setCodSubArea($this->dadosform['codsubarea']);
		$this->albumvo->setCodCanal($this->dadosform['codcanal']);
		$this->albumvo->setTags(Util::geraTags($this->dadosform['tags']));
		$this->albumvo->setCodLicenca($this->direitosbo->getCodLicenca($this->dadosform["direitos"], $this->dadosform["cc_usocomercial"], $this->dadosform["cc_obraderivada"]));

		if (!(int)$this->dadosform["codalbum"])
			$this->albumvo->setRandomico(Util::geraRandomico());

		$this->albumvo->setCodImagemCapa($this->dadosform["capa"]);
		$this->albumvo->setListaImagens($_SESSION["sess_conteudo_imagens_album"][$this->dadosform["sessao_id"]]);

		$this->albumvo->setTitulo($this->dadosform["titulo"]);
		$this->albumvo->setDescricao($this->dadosform["descricao"]);

		if (!(int)$this->dadosform["codalbum"]) {
			$this->albumvo->setDataHora(date("Y-m-d H:i:s"));

			//if ($_SESSION['logado_como'] == 1)
			if ($_SESSION['logado_dados']['nivel'] == 2)
				$this->albumvo->setSituacao(0);
			else
				$this->albumvo->setSituacao(1);

			//$this->albumvo->setSituacao(0); // é cadastrado como inativo
			//$this->albumvo->setPublicado(0); // é cadastrado como pendente
			
			if ($_SESSION['logado_dados']['nivel'] >= 5)
				$this->albumvo->setPublicado(1); // é cadastrado como publicado
			else
				$this->albumvo->setPublicado(0); // é cadastrado como pendente
			
		}
		
		$this->albumvo->setPedirAutorizacao($this->dadosform["pedir_autorizacao"]);
			
		// buscar os cod_usuarios dos colaboradores selecionados pra jogar na vo
			
		$colaboradores = explode(';', $this->dadosform["colaboradores_lista"]);
		$arrayColab = array();
			
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
			
		foreach ($colaboradores as $nome) {
			if ($nome) {
				$usuario = $usrdao->getBuscaDadosUsuarioNome($nome, 1);
				$arrayColab[$usuario['cod_usuario']] = array('cod_usuario' => $usuario['cod_usuario']);
			}
		}
			
		$this->albumvo->setListaColaboradoresRevisao(array_unique($arrayColab));

		//if ($_SESSION['logado_como'] == 1) // se for enviada por um autor
			//$this->albumvo->setPublicado(0); // é cadastrado como pendente
			// se for por um autor, fica pendente ate ir pra lista publica ou pra aprovação
			// se for colaborador, fica pendente ate ele definir autores
			// se for administrador, fica pendente ate ele definir um colaborador ou definir autores
		//else
		//	$this->textovo->setPublicado(1); // é cadastrado como aprovado
		
		$this->albumvo->setListaAutores($_SESSION["sess_conteudo_autores_ficha"][$this->dadosform["sessao_id"]]);
		$this->albumvo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->albumvo->setPermitirComentarios($this->dadosform['permitir_comentarios']);
	}

	protected function editarDados() {
		if (!$this->albumvo->getCodConteudo()) {
			$codalbum = $this->imgdao->cadastrarAlbum($this->albumvo);
		} else {
			$this->imgdao->atualizarAlbum($this->albumvo);
			$codalbum = $this->albumvo->getCodConteudo();
		}
		unset($_SESSION["sess_conteudo_imagens_album"][$this->dadosform["sessao_id"]]);
		unset($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]]);
		$this->dadosform = array();
		$this->arquivos = array();
		return $codalbum;
	}

	public function setDadosCamposEdicao($codalbum) {
		$albumvo = $this->imgdao->getAlbumVO($codalbum);

		$this->dadosform["codalbum"] = $this->dadosform["codconteudo"] = $albumvo->getCodConteudo();
		$this->dadosform["codautor"] = $albumvo->getCodAutor();
		$this->dadosform["codcolaborador"] = $albumvo->getCodColaborador();
		$this->dadosform["titulo"] = $albumvo->getTitulo();
		$this->dadosform["descricao"] = $albumvo->getDescricao();
		$this->dadosform["datahora"] = $albumvo->getDataHora();
		$this->dadosform["capa"] = $albumvo->getCodImagemCapa();
		$_SESSION["sess_conteudo_imagens_album"][$this->dadosform["sessao_id"]] = $albumvo->getListaImagens();

		$dados_direito = $this->direitosbo->setDadosCamposEdicao($albumvo->getCodLicenca());
		$this->dadosform = array_merge($this->dadosform, $dados_direito);

		$this->dadosform["codlicenca"] = $albumvo->getCodLicenca();

		$this->dadosform["codclassificacao"] = $albumvo->getCodClassificacao();
		$this->dadosform["codsegmento"] = $albumvo->getCodSegmento();
		$this->dadosform["codsubarea"] = $albumvo->getCodSubArea();
		$this->dadosform["codcanal"] = $albumvo->getCodCanal();
		$this->dadosform["tags"] = $albumvo->getTags();

		$this->dadosform["url"] = $albumvo->getUrl();
		$this->dadosform["situacao"] = $albumvo->getSituacao();
		$this->dadosform["publicado"] = $albumvo->getPublicado();
		$this->dadosform["permitir_comentarios"] = $albumvo->getPermitirComentarios();

		$this->setSessionAutoresFicha($codalbum, $this->imgdao, $this->dadosform['sessao_id']);
		
		foreach ((array)$_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]] as $key => $value) {
			if ($this->dadosform["codautor"] == $value['codautor']) {
				$this->dadosform["pertence_voce"] = 1;
				break;
			}
		}
	}

	public function getImagensAlbum($codalbum) {
		return $this->imgdao->getImagensAlbum($codalbum);
	}

	public function getImagemCapaAlbum($codalbum) {
		return $this->imgdao->getImagemCapaAlbum($codalbum);
	}
	
	public function getColaboradorConteudoAprovado($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario['nome'];
	}


	// metodos comuns a todo os formartos

	public function getAutoresConteudo($codalbum) {
		return $this->imgdao->getAutoresConteudo($codalbum);
	}
	
	public function getPostadorConteudo($codalbum) {
		return $this->imgdao->getPostadorConteudo($codalbum);
	}
	
	public function getAutoresFichaConteudo($codalbum) {
		return $this->imgdao->getAutoresFichaTecnicaCompletaConteudo($codalbum);
	}

	public function getColaboradorConteudo($codalbum) {
		return $this->imgdao->getColaboradorConteudo($codalbum);
	}

	public function getSegmentoConteudo($codalbum) {
		return $this->imgdao->getSegmentoConteudo($codalbum);
	}
	
	public function getSubAreaConteudo($codtexto) {
		return $this->imgdao->getSubAreaConteudo($codtexto);
	}

	public function getCategoriaConteudo($codalbum) {
		return $this->imgdao->getCategoriaConteudo($codalbum);
	}

	public function getConteudoRelacionado($codalbum) {
		return $this->imgdao->getConteudoRelacionadoConteudo($codalbum);
	}

	public function getGrupoRelacionado($codalbum) {
		return $this->imgdao->getGrupoRelacionadoConteudo($codalbum);
	}

	public function getLicenca($codalbum) {
		return $this->imgdao->getLicenca($codalbum);
	}
	
	public function getListaColaboradoresAprovacao($codalbum) {
		return $this->imgdao->getListaColaboradoresAprovacao($codalbum);
	}

}