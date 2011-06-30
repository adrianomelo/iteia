<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/VideoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/VideoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/VideoConversaoDAO.php");

class VideoEdicaoBO extends ConteudoBO {

	private $vidvo = null;
	private $viddao = null;

	public function __construct() {
		$this->viddao = new VideoDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codvideo"] = (int)$this->dadosform["codvideo"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		//$this->dadosform["descricao"] = substr(trim(Util::removeTags($this->dadosform["descricao"])), 0, 1200);
		$this->dadosform["descricao"] = substr(trim($this->dadosform["descricao"]), 0, 2000);
		$this->dadosform["codclassificacao"] = (int)$this->dadosform["codclassificacao"];
		$this->dadosform["codsegmento"] = (int)$this->dadosform["codsegmento"];
		$this->dadosform["codcanal"] = (int)$this->dadosform["codcanal"];
		$this->dadosform["tags"] = $this->dadosform["tags"];
		$this->dadosform["tipo"] = (int)$this->dadosform["tipo"];
		$this->dadosform["link_video"] = substr(trim($this->dadosform["link_video"]), 0, 200);
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
		if (!$this->dadosform["titulo"]) $this->erro_campos[] = "titulo";

		if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";

		if (!$this->dadosform["codvideo"] && ($this->dadosform["tipo"] == 1) && !is_uploaded_file($this->arquivos["arquivo_video"]["tmp_name"])) {
			$this->erro_mensagens[] = "Falta o arquivo do vídeo";
			$this->erro_campos[] = "arquivo_video";
		}

		//if (!$this->dadosform["codvideo"] && ($this->dadosform["tipo"] == 2) && !$this->dadosform["link_video"]) {
		if (($this->dadosform["tipo"] == 2) && !$this->dadosform["link_video"]) {
			$this->erro_mensagens[] = "Falta o link do Youtube";
			$this->erro_campos[] = "link_video";
		}

		if (is_uploaded_file($this->arquivos["arquivo_video"]["tmp_name"])) {
			if ($this->arquivos["arquivo_video"]["size"] > 209715200) {
				// 104857600
				$this->erro_mensagens[] = "Arquivo está com mais de 200MB";
				$this->erro_campos[] = "arquivo_video";
			}

			// tipo de arquivo
			// flv, avi, wmv, asf e mpeg

			$extensao_video = strtolower(Util::getExtensaoArquivo($this->arquivos["arquivo_video"]["name"]));
			if ($extensao_video != 'flv' && $extensao_video != 'avi' && $extensao_video != 'wmv' && $extensao_video != 'asf' && $extensao_video != 'mpg') {
				$this->erro_mensagens[] = "Formato de arquivo diferente do permitido";
				$this->erro_campos[] = "arquivo_video";
			}
		}

		if ($this->dadosform["contsegmento"] && !$this->dadosform["codsegmento"]) $this->erro_campos[] = "codsegmento";
		//if ($this->dadosform["contsubarea"] && !$this->dadosform["codsubarea"]) $this->erro_campos[] = "codsubarea";

		//if ($this->dadosform["pertence_voce"] == 1) {
		//	if (!count($_SESSION['sess_conteudo_autores_ficha'])) {
		//		$this->erro_mensagens[] = "Selecione ao menos um autor na Ficha técnica";
		//		$this->erro_campos[] = "ficha";
		//	}
		//}

		//if ($_SESSION['logado_dados']['nivel'] >= 6 && !$this->dadosform["codvideo"] && !$this->dadosform["codcolaborador"]) {
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

		/*
		if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"])) {
			if ($this->arquivos["imagem"]["size"] > 2200000) {
				$this->erro_mensagens[] = "Foto está com mais de 2MB";
				$this->erro_campos[] = "imagem";
			}
			$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos["imagem"]["name"]));
			if (($extensao != 'jpg') && ($extensao != 'jpeg') && ($extensao != 'gif') && ($extensao != 'png')) {
				$this->erro_mensagens[] = "Apenas enviar foto no formato jpg, gif ou png";
				$this->erro_campos[] = "imagem";
			}
		}
		*/

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->vidvo = new VideoVO;
		$this->vidvo->setCodConteudo((int)$this->dadosform["codvideo"]);

		/*if ($_SESSION['logado_como'] == 1) // se for enviada por um autor
			$this->vidvo->setCodAutor($_SESSION['logado_cod']);
		else
			$this->vidvo->setCodColaborador($_SESSION['logado_cod']);*/
		$this->vidvo->setCodAutor($_SESSION['logado_cod']);
		//if ($_SESSION['logado_dados']['nivel'] >= 6)
		//	$this->vidvo->setCodColaborador($this->dadosform["codcolaborador"]);
		//else
			$this->vidvo->setCodColaborador($_SESSION['logado_dados']['cod_colaborador']);

		$this->vidvo->setCodClassificacao($this->dadosform['codclassificacao']);
		$this->vidvo->setCodSegmento($this->dadosform['codsegmento']);
		$this->vidvo->setCodSubArea($this->dadosform['codsubarea']);
		$this->vidvo->setCodCanal($this->dadosform['codcanal']);
		$this->vidvo->setTags(Util::geraTags($this->dadosform['tags']));
		$this->vidvo->setCodLicenca($this->direitosbo->getCodLicenca($this->dadosform["direitos"], $this->dadosform["cc_usocomercial"], $this->dadosform["cc_obraderivada"]));

		if (!(int)$this->dadosform["codvideo"])
			$this->vidvo->setRandomico(Util::geraRandomico());
		else
			$this->vidvo->setRandomico($this->viddao->getRandomico($this->dadosform["codvideo"]));

		$this->vidvo->setTitulo($this->dadosform["titulo"]);
		$this->vidvo->setDescricao($this->dadosform["descricao"]);

		if ($this->dadosform["tipo"] == 2)
			$this->vidvo->setLinkVideo($this->dadosform["link_video"]);

		if (!(int)$this->dadosform["codvideo"]) {
			$this->vidvo->setDataHora(date("Y-m-d H:i:s"));

			//if ($_SESSION['logado_como'] == 1)
			if ($_SESSION['logado_dados']['nivel'] == 2)
				$this->vidvo->setSituacao(0);
			else
				$this->vidvo->setSituacao(1);

			//$this->vidvo->setSituacao(0); // é cadastrado como inativo
			//$this->vidvo->setPublicado(0); // é cadastrado como pendente

			if ($_SESSION['logado_dados']['nivel'] >= 5)
				$this->vidvo->setPublicado(1); // é cadastrado como publicado
			else
				$this->vidvo->setPublicado(0); // é cadastrado como pendente

		}

		$this->vidvo->setPedirAutorizacao($this->dadosform["pedir_autorizacao"]);

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

		$this->vidvo->setListaColaboradoresRevisao(array_unique($arrayColab));

		//if ($_SESSION['logado_como'] == 1) // se for enviada por um autor
			//$this->vidvo->setPublicado(0); // é cadastrado como pendente
			// se for por um autor, fica pendente ate ir pra lista publica ou pra aprovação
			// se for colaborador, fica pendente ate ele definir autores
			// se for administrador, fica pendente ate ele definir um colaborador ou definir autores
		//else
		//	$this->textovo->setPublicado(1); // é cadastrado como aprovado

		$this->vidvo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->vidvo->setPermitirComentarios($this->dadosform['permitir_comentarios']);

		$this->vidvo->setListaAutores($_SESSION["sess_conteudo_autores_ficha"][$this->dadosform["sessao_id"]]);
	}

	protected function editarDados() {
		if (!$this->vidvo->getCodConteudo()) {
			$codvideo = $this->viddao->cadastrar($this->vidvo);
		} else {
			$this->viddao->atualizar($this->vidvo);
			$codvideo = $this->vidvo->getCodConteudo();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgvideo_".$codvideo;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->viddao->atualizarFoto($nomearquivo_final, $codvideo);
		}

		if (is_uploaded_file($this->arquivos['arquivo_video']['tmp_name'])) {
			$extensao = Util::getExtensaoArquivo($this->arquivos['arquivo_video']['name']);

			$nomearquivo_original = 'video_orig_'.$this->vidvo->getRandomico().'.'.$extensao;
			$nomearquivo_original_2 = $this->arquivos['arquivo_video']['name'];

			$nomearquivo = 'video_'.$this->vidvo->getRandomico().'.flv';

			$this->vidvo->setArquivoOriginal($nomearquivo_original_2);
			$this->vidvo->setArquivo($nomearquivo);
			copy($this->arquivos['arquivo_video']['tmp_name'], ConfigVO::getDirVideo().'originais/'.$nomearquivo_original);
			$this->viddao->atualizarArquivo($this->vidvo, $codvideo);
			if ($extensao != 'flv') {
				$videoconvdao = new VideoConversaoDAO;
				$videoconvdao->cadastrar($nomearquivo_original, $nomearquivo);
			}
			else
				copy($this->arquivos['arquivo_video']['tmp_name'], ConfigVO::getDirVideo().'convertidos/'.$nomearquivo);
		}
		unset($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform['sessao_id']]);
		$this->dadosform = array();
		$this->arquivos = array();
		return $codvideo;
	}

	public function setDadosCamposEdicao($codvideo) {
		$vidvo = $this->viddao->getVideoVO($codvideo);

		$this->dadosform["codvideo"] = $this->dadosform["codconteudo"] = $vidvo->getCodConteudo();
		$this->dadosform["codcolaborador"] = $vidvo->getCodColaborador();
		$this->dadosform["codautor"] = $vidvo->getCodAutor();
		$this->dadosform["titulo"] = $vidvo->getTitulo();
		$this->dadosform["descricao"] = $vidvo->getDescricao();
		$this->dadosform["datahora"] = $vidvo->getDataHora();
		$this->dadosform["imagem_visualizacao"] = $vidvo->getImagem();

		if ($vidvo->getArquivo()) {
			$this->dadosform["tipo"] = 1;
		} elseif ($vidvo->getLinkVideo()) {
			$this->dadosform["tipo"] = 2;
		}

		$this->dadosform["arquivo_video"] = $vidvo->getArquivo();
		$this->dadosform["link_video"] = $vidvo->getLinkVideo();
		$this->dadosform["arquivo_original"] = $vidvo->getArquivoOriginal();

		$dados_direito = $this->direitosbo->setDadosCamposEdicao($vidvo->getCodLicenca());
		$this->dadosform = array_merge($this->dadosform, $dados_direito);

		$this->dadosform["codlicenca"] = $vidvo->getCodLicenca();

		$this->dadosform["codclassificacao"] = $vidvo->getCodClassificacao();
		$this->dadosform["codsegmento"] = $vidvo->getCodSegmento();
		$this->dadosform["codsubarea"] = $vidvo->getCodSubArea();
		$this->dadosform["codcanal"] = $vidvo->getCodCanal();
		$this->dadosform["tags"] = $vidvo->getTags();

		$this->dadosform["url"] = $vidvo->getUrl();
		$this->dadosform["situacao"] = $vidvo->getSituacao();
		$this->dadosform["publicado"] = $vidvo->getPublicado();
		$this->dadosform["permitir_comentarios"] = $vidvo->getPermitirComentarios();

		$this->setSessionAutoresFicha($codvideo, $this->viddao, $this->dadosform['sessao_id']);

		foreach ((array)$_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]] as $key => $value) {
			if ($this->dadosform["codautor"] == $value['codautor']) {
				$this->dadosform["pertence_voce"] = 1;
				break;
			}
		}
	}

	public function getColaboradorConteudoAprovado($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario['nome'];
	}

	// metodos comuns a todo os formartos

	public function excluirImagem($codvideo) {
		return $this->viddao->excluirImagem($codvideo);
	}

	public function excluirArquivo($codvideo) {
		return $this->viddao->excluirArquivo($codvideo);
	}

	public function getPostadorConteudo($codvideo) {
		return $this->viddao->getPostadorConteudo($codvideo);
	}

	public function getAutoresFichaConteudo($codvideo) {
		return $this->viddao->getAutoresFichaTecnicaCompletaConteudo($codvideo);
	}

	public function getAutoresConteudo($codvideo) {
		return $this->viddao->getAutoresConteudo($codvideo);
	}

	public function getColaboradorConteudo($codvideo) {
		return $this->viddao->getColaboradorConteudo($codvideo);
	}

	public function getSegmentoConteudo($codvideo) {
		return $this->viddao->getSegmentoConteudo($codvideo);
	}

	public function getSubAreaConteudo($codtexto) {
		return $this->viddao->getSubAreaConteudo($codtexto);
	}

	public function getCategoriaConteudo($codvideo) {
		return $this->viddao->getCategoriaConteudo($codvideo);
	}

	public function getConteudoRelacionado($codvideo) {
		return $this->viddao->getConteudoRelacionadoConteudo($codvideo);
	}

	public function getGrupoRelacionado($codvideo) {
		return $this->viddao->getGrupoRelacionadoConteudo($codvideo);
	}

	public function getLicenca($codvideo) {
		return $this->viddao->getLicenca($codvideo);
	}

	public function getListaColaboradoresAprovacao($codvideo) {
		return $this->viddao->getListaColaboradoresAprovacao($codvideo);
	}

}