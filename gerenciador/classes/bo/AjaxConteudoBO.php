<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

class AjaxConteudoBO {

	private $dados_get = array();

	public function __construct($dados_get = array()) {
		$this->dados_get = $dados_get;
	}

	public function executaAcao() {
		switch ($this->dados_get["get"]) {
			case "listar_imagens_album": $this->listarImagensAlbum(); break;
			case "imagem_upload": $this->enviaImagemUpload($_FILES); break;
			case "imagem_conteudo_upload": $this->enviaImagemConteudoUpload($_FILES); break;
			case "imagem_noticia_upload": $this->enviaImagemNoticiaUpload($_POST, $_FILES); break;
			case "imagem_texto_upload": $this->enviaImagemTextoUpload($_POST, $_FILES); break;
			case "remover_imagem_album": $this->removerImagemAlbum(); break;
			case "salvar_imagem_legendas": $this->salvarImagemLegendas(); break;
			case "definir_capa": $this->definirCapaAlbum(); break;
			case "executa_acao_imagem": $this->executaAcaoImagemSelecionadas(); break;

			case "conteudo_relacionado": $this->buscaConteudoRelacionado(); break;
			case "adicionar_conteudo_relacionado": $this->adicionarConteudoRelacionamento(); break;
			case "remover_conteudo_relacionado": $this->removerConteudoRelacionamento(); break;
			case "carregar_conteudo_relacionado": $this->carregarConteudoRelacionamento(); break;

			case "buscar_autor": $this->buscaAutorRelacionamento(); break;
			case "adicionar_autor": $this->adicionarAutorRelacionamento(); break;
			case "remover_autor": $this->removerAutorRelacionamento(); break;
			case "carregar_autores": $this->carregarAutoresRelacionamento(); break;

			case "buscar_colaborador": $this->buscaColaboradorRelacionamento(); break;
			case "vincular_colaborador": $this->vincularColaboradorConteudo(); break;

			case "enviar_conteudo_listapublica": $this->enviarConteudoListaPublica(); break;

			case "adicionar_colaborador_revisao": $this->adicionarColaboradorRevisao(); break;
			case "remover_colaborador_revisao": $this->removerColaboradorRevisao(); break;
			case "carregar_colaboradores_revisao": $this->carregarColaboradoresRevisao(); break;

			case "aprovar_conteudo": $this->aprovarConteudo(); break;
			case "reprovar_conteudo": $this->reprovarConteudo(); break;
			case "redirecionar_conteudo": $this->redirecionaVisualizacao(); break;

			case "apagar_imagem_visualizacao": $this->apagarImagemVisualizacao(); break;

			case "associar_grupos": $this->associarGruposConteudo(); break;

			case "buscar_comentarios": $this->buscarComentarios(); break;

			case "listar_audios_album": $this->listarAudiosAlbum(); break;
			case "audio_upload": $this->enviaAudioUpload($_FILES); break;
			case "executa_acao_audio": $this->executaAcaoAudiosSelecionados(); break;
			case "salvar_audio_titulos": $this->salvarAudiosTitulos(); break;
			case "remover_audio_album": $this->removerAudioAlbum(); break;

			case "buscar_tag": $this->buscaTag(); break;

			case "buscar_integrantes": $this->buscaAutorIntegrantes(); break;
			case "adicionar_colaborador_integrantes": $this->adicionarAutorIntegrantes(); break;
			case "remover_colaborador_integrantes": $this->removerAutorIntegrantes(); break;
			case "definir_colaborador_responsavel_integrantes": $this->definirAutorResponsavelIntegrantes(); break;
			case "carregar_colaborador_integrantes": $this->carregarAutoresIntegrantes(); break;

			case "mostrar_comentarios_index": $this->carregarComentariosIndex(); break;

			case "imagem_usuario_upload": $this->enviaImagemUsuario($_FILES); break;

			case "adicionar_autor_wiki": $this->adicionarAutorWiki(); break;
			case "editar_autor_wiki": $this->editarAutorWiki(); break;
			case "autor_dados_ficha": $this->carregaDadosAutorFicha(); break;
			case "listar_autores_ficha": $this->listarAutoresFicha(); break;
			case "adicionar_autorficha_nalista": $this->adicionarAutorFicha(); break;
			case "remover_autor_listaficha": $this->removerAutorFicha(); break;
			
			case "unificar_tags": $this->unificarTags(); break;
			
			case "aprovar_autor": $this->aprovarAutor(); break;
			case "reprovar_autor": $this->reprovarAutor(); break;
			case "aprovar_colaborador": $this->aprovarColaborador(); break;
			case "reprovar_colaborador": $this->reprovarColaborador(); break;
			
			case "subcanais": $this->listarSubCanais(); break;
		}
	}
	
	private function listarSubCanais() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
		$segdao = new SegmentoDAO;
		foreach($segdao->getListaSubAreasCadastroCodCanal((int)$this->dados_get['codcanal']) as $key => $value)
			echo '<option value="'.$value['cod_segmento'].'" '.($this->dados_get['codsubcanal'] == $value['cod_segmento'] ? ' selected="selected"' : '').'>'.utf8_encode($value['nome']).'</option>';
	}
	
	private function unificarTags() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/TagDAO.php");
		$tagdao = new TagDAO;
		
		$tags = explode(',', $this->dados_get['codtag']);
		$tag = Util::geraTags($this->dados_get['tag']);
		
		if (count($tags))
			$tagdao->unificarTags($tags, $tag);
	}

	private function enviaImagemUsuario(&$arquivos) {
		if (is_uploaded_file($arquivos['imagem']['tmp_name'])) {
			$extensao = strtolower(Util::getExtensaoArquivo($arquivos['imagem']['name']));
			if (($extensao == 'jpg') || ($extensao == 'jpeg') || ($extensao == 'gif') || ($extensao == 'png')) {

				$nomearquivo = "imgusuario_".$_SESSION['logado_cod'].".".$extensao;

				copy($arquivos['imagem']['tmp_name'], ConfigVO::getDirFotos().$nomearquivo);
				Util::removerImagensCache($nomearquivo);

				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
				$usuariodao = new UsuarioDAO;

				$usuariodao->atualizarFoto($nomearquivo, $_SESSION['logado_cod']);
				echo $nomearquivo.'&rand='.md5(time());
			}
		}
	}

	private function carregarComentariosIndex() {
		include_once("classes/bo/ComentarioBO.php");
		$commentbo = new ComentarioBO;

		if ($this->dados_get['acao']) {
        	$codcomentario = explode(',', $this->dados_get['codcomentario']);
			$commentbo->executaAcoes($this->dados_get['acao'], $codcomentario);
		}

		$total_comentarios = $commentbo->getTotalComentariosAprovacao();
		$lista_comentarios = $commentbo->getListaComentariosIndex();
		include('includes/ajax_comentarios_index.php');
	}

	private function definirAutorResponsavelIntegrantes() {
		if ($this->dados_get['cod_usuario'])
			$_SESSION['sessao_autores_integrantes'][(int)$this->dados_get['cod_usuario']]['responsavel'] = 1;
		$this->carregarAutoresIntegrantes();
	}

	private function buscaAutorIntegrantes() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		$usuariodao = new CadastroDAO;

		$this->dados_get['palavrachave'] = $this->dados_get['q'];
		$this->dados_get['integrantes'] = Util::iif(!$this->dados_get['integrantes'], true, false);
		$this->dados_get['situacao'] = 3;
		$limite_resultado = (int)$this->dados_get['limiteresultado'];

		if ($this->dados_get['conteudo_simples'] == 1)
			$lista_autores = $usuariodao->getListaCadastrosFicha($this->dados_get, 30);
		else
			$lista_autores = $usuariodao->getListaCadastros($this->dados_get, 0, $limite_resultado);

		switch ($this->dados_get['buscar_tipo']) {
			case 2: include('includes/ajax_conteudo_colaborador_integrantes_selecao.php'); break;
			case 3: include('includes/ajax_conteudo_selecao_autores_wiki.php'); break;
			default: include('includes/ajax_cadastro_colaborador_integrantes_selecao.php'); break;
		}
	}

	private function adicionarAutorIntegrantes() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		$userdao = new UsuarioDAO;
		if ($this->dados_get['nome_integrante']) {
			$dados_usuario = $userdao->getBuscaDadosUsuarioNome($this->dados_get['nome_integrante']);
			if ($dados_usuario['cod_usuario']) {
				if (!in_array($dados_usuario['cod_usuario'], (array)$_SESSION['sessao_autores_integrantes'][$dados_usuario['cod_usuario']])) {
					$_SESSION['sessao_autores_integrantes'][(int)$dados_usuario['cod_usuario']] = $dados_usuario;
				}
			}
		}
		$this->carregarAutoresIntegrantes();
	}

	private function removerAutorIntegrantes() {
		if ($this->dados_get['cod_usuario'])
			unset($_SESSION["sessao_autores_integrantes"][(int)$this->dados_get['cod_usuario']]);
		$this->carregarAutoresIntegrantes();
	}

	public function carregarAutoresIntegrantes() {
		include('includes/ajax_cadastro_colaborador_integrantes_selecionados.php');
	}

	public function buscaTag() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$lista_tags = $contdao->getBuscaTag($this->dados_get['q']);
		include("includes/ajax_conteudo_busca_tag.php");
	}

	private function listarAudiosAlbum() {
		include("includes/ajax_conteudo_audiosalbum.php");
	}

	private function removerAudioAlbum() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AudioDAO.php");
		$auddao = new AudioDAO;
		
		$lista_selecionados = explode(",", $this->dados_get["codaudio"]);
		
		if (count($lista_selecionados)) {
			foreach ($lista_selecionados as $codaudio) {
				if ($codaudio) {
					$auddao->excluirAudio($codaudio);
					unset($_SESSION["sess_conteudo_audios_album"][$this->dados_get['sessao_id']][array_search($codaudio, $_SESSION["sess_conteudo_audios_album"][$this->dados_get['sessao_id']])]);
				}
			}
		}
		
		$this->listarAudiosAlbum();
	}

	private function enviaAudioUpload(&$arquivos) {
		include_once("AudioEdicaoBO.php");
		$audbo = new AudioEdicaoBO;
		$erro_mensagem = "";
		try {
			$codaudio = $audbo->editar($this->dados_get, $arquivos);
		} catch (Exception $e) {
			$erro_mensagem = $e->getMessage();
		}
		include("includes/ajax_conteudo_audiosalbum.php");
	}

	private function executaAcaoAudiosSelecionados() {
		include_once("AudioEdicaoBO.php");
		$audbo = new AudioEdicaoBO;

		$lista_selecionados = explode(",", $this->dados_get["itens"]);

		if (count($lista_selecionados)) {
			foreach ($lista_selecionados as $codaudio) {
				$audbo->organizaOrdenacaoAudio($codaudio, $this->dados_get["numacao"]);
				$audbo->organizacaoFinal($this->dados_get['sessao_id']);
			}
		}
		$this->listarAudiosAlbum();
	}

	private function salvarAudiosTitulos() {
		include_once("AudioEdicaoBO.php");
		$audbo = new AudioEdicaoBO;
		$audbo->salvaTitulos($this->dados_get['titulofaixaaud'], $this->dados_get['tempoaud']);
	}

	private function executaAcaoImagemSelecionadas() {
		include_once("ImagemEdicaoBO.php");
		$imgbo = new ImagemEdicaoBO;

		$lista_selecionadas = explode(",", $this->dados_get["itens"]);

		if (count($lista_selecionadas)) {
			foreach ($lista_selecionadas as $codimg) {
				$imgbo->organizaOrdenacaoImagem($codimg, $this->dados_get["numacao"]);
				$imgbo->organizacaoFinal($this->dados_get['sessao_id']);
			}
		}
		$this->listarImagensAlbum();
	}

	private function associarGruposConteudo() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		if (isset($this->dados_get['grupos'])) {
			$cod_grupos = explode(',', $this->dados_get['grupos']);
			$contdao->associarGruposConteudo($cod_grupos, $this->dados_get['cod_conteudo']);
		}
	}

	private function enviaImagemUpload(&$arquivos) {
		include_once("ImagemEdicaoBO.php");
		$imgbo = new ImagemEdicaoBO;
		$erro_mensagem = "";
		try {
			$codimagem = $imgbo->editar($this->dados_get, $arquivos);
		} catch (Exception $e) {
			$erro_mensagem = $e->getMessage();
		}
		//print_r($this->dados_get);
		//print_r($_SESSION['sess_conteudo_imagens_album']);
		include("includes/ajax_conteudo_imggaleria.php");
	}

	private function definirCapaAlbum() {
		include_once("ImagemEdicaoBO.php");
		$imgbo = new ImagemEdicaoBO;
		$imgbo->definirCapaAlbum($this->dados_get['codimg']);
		$this->dados_get["capa"] = $this->dados_get['codimg'];
		$this->listarImagensAlbum();
	}

	private function enviaImagemConteudoUpload(&$arquivos) {
		include_once('ImagemTemporariaBO.php');
		foreach ($arquivos as $nome => $arquivo) {
			$img = ImagemTemporariaBO::criar($arquivo);
			echo $img;
		}
	}

	private function enviaImagemNoticiaUpload(&$dados, &$arquivos) {
		include_once('ImagemTemporariaBO.php');
		$img = '';
		foreach ($arquivos as $nome => $arquivo)
			$img = ImagemTemporariaBO::criar($arquivo);
		$img .= '[!]'.$dados['foto_credito'];
		$img .= '[!]'.$dados['foto_legenda'];
		echo $img;
	}
	
	private function enviaImagemTextoUpload(&$dados, &$arquivos) {
		include_once('ImagemTemporariaBO.php');
		$img = '';
		foreach ($arquivos as $nome => $arquivo)
			$img = ImagemTemporariaBO::criar($arquivo);
		$img .= '[!]'.$dados['foto_credito'];
		$img .= '[!]'.$dados['foto_legenda'];
		echo $img;
	}

	private function removerImagemAlbum() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ImagemDAO.php");
		$imgdao = new ImagemDAO;
		if ($this->dados_get["codimg"]) {
			$imgdao->excluirImagem($this->dados_get["codimg"]);
			unset($_SESSION["sess_conteudo_imagens_album"][$this->dados_get["sessao_id"]][array_search($this->dados_get["codimg"], $_SESSION["sess_conteudo_imagens_album"][$this->dados_get["sessao_id"]])]);
		}
		$this->listarImagensAlbum();
	}

	private function listarImagensAlbum() {
		include("includes/ajax_conteudo_imggaleria.php");
	}

	private function salvarImagemLegendas() {
		include_once("ImagemEdicaoBO.php");
		$imgbo = new ImagemEdicaoBO;
		$imgbo->salvaLegendas($this->dados_get['legendaimg'], $this->dados_get['creditoimg']);
	}

	private function buscaConteudoRelacionado() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

	    $pagina  = Util::iif($this->dados_get['pagina'], $this->dados_get['pagina'], 1);
        $mostrar = Util::iif($this->dados_get['mostrar'], $this->dados_get['mostrar'], 10);
        $inicial = ($pagina - 1) * $mostrar;

        $this->dados_get['ajax'] = 1;

        $contdao = new ConteudoDAO;

        if ($this->dados_get['acao']) {
        	$codconteudo = explode(',', $this->dados_get['codconteudo']);
			$contdao->executaAcoes($this->dados_get['acao'], $codconteudo);
		}

        $resultado = $contdao->getListaConteudo($this->dados_get, $inicial, $mostrar);
		$paginacao = Util::paginacao($pagina, $mostrar, $resultado['total'], $resultado['link'], $resultado['link'], 1, 'navegaConteudo', '#mostra_resultados_relacionamento');
		switch ($this->dados_get['navegacao']) {
			case 0: include('includes/ajax_conteudo_busca_relacionado.php'); break;
			case 1: include('includes/ajax_conteudo_busca_navegacao.php'); break;
			case 2: include('includes/ajax_conteudo_busca_noticia.php'); break;
			case 3: include('includes/ajax_conteudo_busca_agenda.php'); break;
			case 4: include('includes/ajax_home_busca_conteudo.php'); break;
			case 5: include('includes/ajax_home_busca_conteudo_usuario.php'); break;
		}
	}

	private function adicionarConteudoRelacionamento() {
		include_once("ConteudoRelacionamentoBO.php");

		$contrelbo = new ConteudoRelacionamentoBO;
		$codconteudo = (int)$this->dados_get['cod_conteudo'];

		if ($codconteudo) {
			if (!count($_SESSION['sessao_conteudo_relacionamento'][$codconteudo])) {
				$conteudo = $contrelbo->getConteudoDados($codconteudo);
				$_SESSION['sessao_conteudo_relacionamento'][$contrelbo->getValorCampo('cod_conteudo')] = array('cod_formato' => $contrelbo->getValorCampo('cod_formato'), 'titulo' => htmlentities($contrelbo->getValorCampo('titulo')));
			}
		}
		$this->carregarConteudoRelacionamento();
	}

	private function removerConteudoRelacionamento() {
		$codconteudo = $this->dados_get['cod_conteudo'];
		if ($codconteudo) {
			$codconteudo = explode(',', $codconteudo);
			foreach ($codconteudo as $key => $value)
				unset($_SESSION['sessao_conteudo_relacionamento'][$value]);
		}
		$this->carregarConteudoRelacionamento();
	}

	public function carregarConteudoRelacionamento() {
		include('includes/ajax_conteudo_lista_relacionado.php');
	}

	private function buscaAutorRelacionamento() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		$usuariodao = new CadastroDAO;
		$lista_autores = $usuariodao->getListaCadastros($this->dados_get, 0, 0);
		include('includes/ajax_autores_selecao_relacionamento.php');
	}

	private function adicionarAutorRelacionamento() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		$autordao = new AutorDAO;
		if (isset($this->dados_get['autores'])) {

			$cod_autores = explode(',', $this->dados_get['autores']);
			$dados_autores = $autordao->getListaDadosAutores($cod_autores);

			foreach ($dados_autores as $autor)
				$_SESSION['sessao_autores_relacionados'][(int)$autor['cod_usuario']] = $autor;
		}
		$this->carregarAutoresRelacionamento();
	}

	private function removerAutorRelacionamento() {
		if ($this->dados_get['cod_autor'])
			unset($_SESSION["sessao_autores_relacionados"][(int)$this->dados_get['cod_autor']]);
		$this->carregarAutoresRelacionamento();
	}

	public function carregarAutoresRelacionamento() {
		include('includes/ajax_autores_selecionados.php');
	}

	private function buscaColaboradorRelacionamento() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		$usuariodao = new CadastroDAO;
		$lista_colaboradores = $usuariodao->getListaCadastros($this->dados_get, 0, 0);

		$template_include = 'ajax_colaborador_selecao_relacionamento.php';

		if ($this->dados_get['revisao'])
			$template_include = 'ajax_colaboradores_selecao_revisao.php';

		include('includes/'.$template_include);
	}

	private function vincularColaboradorConteudo() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$contdao->vincularColaborador($this->dados_get['cod_colaborador'], $this->dados_get['cod_conteudo']);
	}

	private function enviarConteudoListaPublica() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$contdao->enviarConteudoListaPublica($this->dados_get['cod_conteudo']);
	}

	private function adicionarColaboradorRevisao() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
		$colabdao = new ColaboradorDAO;
		if (isset($this->dados_get['colaboradores'])) {

			$cod_colaboradores = explode(',', $this->dados_get['colaboradores']);
			$dados_colaboradores = $colabdao->getListaDadosColaboradores($cod_colaboradores);

			foreach ($dados_colaboradores as $colaborador)
				$_SESSION['sessao_colaboradores_revisao'][(int)$colaborador['cod_usuario']] = $colaborador;
		}
		$this->carregarColaboradoresRevisao();
	}

	private function removerColaboradorRevisao() {
		if ($this->dados_get['cod_colaborador'])
			unset($_SESSION["sessao_colaboradores_revisao"][(int)$this->dados_get['cod_colaborador']]);
		$this->carregarColaboradoresRevisao();
	}

	public function carregarColaboradoresRevisao() {
		include('includes/ajax_colaboradores_selecionados.php');
	}

	private function aprovarConteudo() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$contdao->aprovarConteudo($this->dados_get['cod_conteudo']);
	}

	private function reprovarConteudo() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$contdao->reprovarConteudo($this->dados_get['cod_conteudo'], $this->dados_get['comentario']);
	}
	
	private function aprovarAutor() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		$autordao = new AutorDAO;
		$autordao->aprovarAutor($this->dados_get['codautor']);
		
		// envia email de aprovação
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		$dados = $usrdao->getUsuarioDados($this->dados_get['codautor']);
		$dadoscolab = $usrdao->getUsuarioDados($_SESSION['logado_dados']['cod_colaborador']);
		
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
		$mensagem  = "";
		$mensagem .= "<p>Olá ".$dados['nome'].",</p>";
		$mensagem .= "<p>A Equipe iTeia tem o prazer em informar que o colaborador ".$dadoscolab['nome']." aprovou o seu cadastro, e você já pode compartilhar seu conteúdo em nossa rede.</p>";
		$mensagem .= "<p>Seja bem vindo!</p>";
		
		$mensagem .= "<br/><p>Seu login: ".$dados['login']."</p>";
		$mensagem .= "<p>Sua senha: ".$dados['senha']."</p>";

		$mensagem .= "<br/><p>Veja a sua página aqui: ".ConfigVO::URL_SITE.$dados['url']."</p>";
		$mensagem .= "<p>Página do colaborador: ".ConfigVO::URL_SITE.$dadoscolab['url']."</p>";

		$mensagem .= "<br/><p>Faça login agora e veja como divulgar suas ações culturais.</p>";
		$mensagem .= "<p>Esperamos pelo seu post!</p>";
		
		$mensagem .= "<br/><p>---</p>";
		$mensagem .= "<p>Equipe Iteia</p>";
		$mensagem .= "<p>http://www.iteia.org.br</p>";
		$mensagem .= "<p>http://www.twitter.com/iteia</p>";

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail('[iTEIA] - Parabéns, você agora faz parte da rede iTEIA!', $dados['email'], ConfigVO::getEmail(), $texto_email, $dados['email']);
	}

	private function reprovarAutor() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		$autordao = new AutorDAO;
		$autordao->reprovarAutor($this->dados_get['codautor'], $this->dados_get['comentario']);
	}
	
	
		private function aprovarColaborador() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
		$autordao = new ColaboradorDAO;
		$autordao->aprovarColaborador($this->dados_get['codautor']);
		
		// envia email de aprovação
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
		$usrdao = new UsuarioDAO;
		$dados = $usrdao->getUsuarioDados($this->dados_get['codautor']);
		$dadoscolab = $usrdao->getUsuarioDados($_SESSION['logado_dados']['cod_colaborador']);
		
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
		$mensagem  = "";
		$mensagem .= "<p>Olá ".$dados['nome'].",</p>";
		$mensagem .= "<p>A Equipe iTeia tem o prazer em informar que o colaborador ".$dadoscolab['nome']." aprovou o seu cadastro, e você já pode compartilhar seu conteúdo em nossa rede.</p>";
		$mensagem .= "<p>Seja bem vindo!</p>";
		
		$mensagem .= "<br/><p>Seu login: ".$dados['login']."</p>";
		$mensagem .= "<p>Sua senha: ".$dados['senha']."</p>";

		$mensagem .= "<br/><p>Veja a sua página aqui: ".ConfigVO::URL_SITE.$dados['url']."</p>";
		$mensagem .= "<p>Página do colaborador: ".ConfigVO::URL_SITE.$dadoscolab['url']."</p>";

		$mensagem .= "<br/><p>Faça login agora e veja como divulgar suas ações culturais.</p>";
		$mensagem .= "<p>Esperamos pelo seu post!</p>";
		
		$mensagem .= "<br/><p>---</p>";
		$mensagem .= "<p>Equipe Iteia</p>";
		$mensagem .= "<p>http://www.iteia.org.br</p>";
		$mensagem .= "<p>http://www.twitter.com/iteia</p>";

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail('[iTEIA] - Parabéns, você agora faz parte da rede iTEIA!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $dados['email']);
	}

	private function reprovarColaborador() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
		$autordao = new ColaboradorDAO;
		$autordao->reprovarColaborador($this->dados_get['codautor'], $this->dados_get['comentario']);
	}
	
	

	private function redirecionaVisualizacao() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$dadosform = $contdao->getFormatoConteudo($this->dados_get['cod_conteudo']);
		switch ($dadosform) {
			case 1: $local = 'texto'; break;
			case 2: $local = 'imagem'; break;
			case 3: $local = 'audio'; break;
			case 4: $local = 'video'; break;
		}
		Header('Location:conteudo_publicado_'.$local.'.php?cod='.$this->dados_get['cod_conteudo']);
		die;
	}

	private function apagarImagemVisualizacao() {
		switch($this->dados_get['tipo']) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
				$condao = new ConteudoDAO;
				$condao->excluirImagem($this->dados_get['cod']);
				switch ($this->dados_get['tipo']) {
					case 1: $imagem = 'texto.jpg'; break;
					//case 2: $imagem = 'imagem.jpg'; break;
					case 3: $imagem = 'audio.jpg'; break;
					case 4: $imagem = 'video.jpg'; break;
					case 5: $imagem = 'texto.jpg'; break;
				}
			break;
			// usuarios
			case 6:
			case 7:
				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
				$userdao = new UsuarioDAO;
				$userdao->excluirImagem($this->dados_get['cod']);
				switch ($this->dados_get['tipo']) {
					case 6: $imagem = 'colaborador.jpg'; break;
					case 7: $imagem = 'autor.jpg'; break;
				}
			break;
			// segmentos
			case 8:
				include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
				$segdao = new SegmentoDAO;
				$segdao->excluirImagem($this->dados_get['cod']);
				$imagem = 'colaborador.jpg';
			break;
		}
		echo '<img src="img/imagens-padrao/'.$imagem.'" />';
	}

	private function buscarComentarios() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ComentariosDAO.php");

	    $pagina  = Util::iif($this->dados_get['pagina'], $this->dados_get['pagina'], 1);
        $mostrar = Util::iif($this->dados_get['mostrar'], $this->dados_get['mostrar'], 10);
        $inicial = ($pagina - 1) * $mostrar;

		$this->dados_get['pagina'] = $pagina;

        $comentdao = new ComentariosDAO;

        if ($this->dados_get['acao']) {
        	$codcomentario = explode(',', $this->dados_get['codcomentario']);
			$comentdao->executaAcoes($this->dados_get['acao'], $codcomentario);
		}

        $resultado = $comentdao->getListaComentarios($this->dados_get, $inicial, $mostrar);
		$paginacao = Util::paginacao($pagina, $mostrar, $resultado['total'], $resultado['link'], $resultado['link'], 1, 'navegaConteudo', '#mostra_resultados_comentarios');
		include('includes/ajax_comentarios_navegacao.php');
	}

	private function carregaDadosAutorFicha() {
		$codautor = $this->dados_get['cod'];
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
		$autordao = new AutorDAO;
		$autorvo = $autordao->getAutorVO($codautor);
		
		//$indice = array_search($codautor, (array)$_SESSION['sess_conteudo_autores_ficha']);
		
		$dados_autor = array(
			'nome_autor' => utf8_encode($autorvo->getNome()),
			'nome' => utf8_encode($autorvo->getNomeCompleto()),
			'pais' => $autorvo->getCodPais(),
			'estado' => $autorvo->getCodEstado(),
			'atividade' => $_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']][$codautor]['atividade'],
			'codcidade' => $autorvo->getCodCidade(),
			'cidade' => $autorvo->getCidade(),
			'email' => $autorvo->getEmail(),
			'telefone' => $autorvo->getTelefone(),
			'falecido' => Util::iif($autorvo->getDataFalecimento() != '0000-00-00', 1, 0),
			'descricao' => Util::clearText(utf8_encode($autorvo->getDescricao())),
		);
		echo json_encode($dados_autor);
	}

	private function adicionarAutorWiki() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");

		$autorvo = new AutorVO;
		$autorvo->setCodTipo(2);
		$autorvo->setCodNivel(1);
		$autorvo->setSituacao(3);
		$autorvo->setNome(($this->dados_get['nome']));
		$autorvo->setNomeCompleto(($this->dados_get['nome_completo']));
		$autorvo->setCodPais($this->dados_get['codpais']);
		
		if ($this->dados_get['cidade'] || $this->dados_get['codpais'] != ConfigGerenciadorVO::getCodPaisBrasil()) {
			$autorvo->setCodEstado(0);
			$autorvo->setCodCidade(0);
		} else {
			$autorvo->setCodEstado($this->dados_get['codestado']);
			$autorvo->setCodCidade($this->dados_get['codcidade']);
		}
		
		$autorvo->setCidade($this->dados_get['cidade']);
		$autorvo->setEmail($this->dados_get['email']);
		$autorvo->setTelefone($this->dados_get['telefone']);
		
		$autorvo->setDataFalecimento(Util::iif((int)$this->dados_get['falecido'] == 1, date('Y-m-d'), '0000-00-00'));
		
		$autorvo->setDescricao(urldecode(utf8_decode($this->dados_get['descricao'])));
		$autorvo->setTelefone($this->dados_get['telefone']);
		
		$autordao = new AutorDAO;
		$estadodao = new EstadoDAO;
		$ativdao = new AtividadeDAO;
		$caddao = new CadastroDAO;
		$codautor = $autordao->cadastrar($autorvo);
		$autordao->cadastrarUrl($codautor, $this->dados_get['nome']);
		
		$_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']][$codautor] = array(
			'codautor' => $codautor,
			'nome' => ($this->dados_get['nome']),
			'wiki' => $caddao->checaAutorWiki($codautor),
			'atividade' => $this->dados_get['atividade'],
			'nome_atividade' => $ativdao->getAtividade($this->dados_get['atividade']),
			'estado' => $estadodao->getSiglaEstado($this->dados_get['codestado']),
			'descricao' => $this->dados_get['descricao'],
			'falecido' => (int)$this->dados_get['falecido'],
		);
		
		$this->listarAutoresFicha();
	}
	
	private function editarAutorWiki() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");

		$autorvo = new AutorVO;
		$autorvo->setCodUsuario($this->dados_get['codautor']);
		$autorvo->setNome(($this->dados_get['nome']));
		$autorvo->setNomeCompleto(($this->dados_get['nome_completo']));
		$autorvo->setCodPais($this->dados_get['codpais']);
		
		if ($this->dados_get['cidade'] || $this->dados_get['codpais'] != ConfigGerenciadorVO::getCodPaisBrasil()) {
			$autorvo->setCodEstado(0);
			$autorvo->setCodCidade(0);
		} else {
			$autorvo->setCodEstado($this->dados_get['codestado']);
			$autorvo->setCodCidade($this->dados_get['codcidade']);
		}
		
		$autorvo->setCidade($this->dados_get['cidade']);
		$autorvo->setEmail($this->dados_get['email']);
		$autorvo->setTelefone($this->dados_get['telefone']);
		
		$autorvo->setDataFalecimento(Util::iif((int)$this->dados_get['falecido'] == 1, date('Y-m-d'), '0000-00-00'));
		$autorvo->setDescricao(urldecode(utf8_decode($this->dados_get['descricao'])));
		
		$autordao = new AutorDAO;
		$estadodao = new EstadoDAO;
		$ativdao = new AtividadeDAO;
		$caddao = new CadastroDAO;
		$codautor = $autordao->atualizar($autorvo);
		$autordao->editarUrl($codautor, $this->dados_get['nome']);
		
		//$indice = array_search($codautor, (array)$_SESSION['sess_conteudo_autores_ficha']);
		//unset($_SESSION['sess_conteudo_autores_ficha'][$codautor]);
		
		$_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']][$codautor] = array(
			'codautor' => $codautor,
			'nome' => ($this->dados_get['nome']),
			'wiki' => $caddao->checaAutorWiki($codautor),
			'atividade' => $this->dados_get['atividade'],
			'nome_atividade' => $ativdao->getAtividade($this->dados_get['atividade']),
			'estado' => $estadodao->getSiglaEstado($this->dados_get['codestado']),
			'descricao' => $this->dados_get['descricao'],
			'falecido' => (int)$this->dados_get['falecido'],
		);
		
		$this->listarAutoresFicha();
	}

	private function listarAutoresFicha() {
		include('includes/ajax_lista_autores_ficha.php');
	}

	private function adicionarAutorFicha() {
		$codautor = $this->dados_get['cod'];

		$ja_tem_na_lista = false;
		foreach ((array)$_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']] as $autor) {
			if ($autor['codautor'] == $codautor) {
				$ja_tem_na_lista = true;
				break;
			}
		}

		if (!$ja_tem_na_lista) {
			include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
			include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
			include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
			include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");
			include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
			$autordao = new AutorDAO;
			$estadodao = new EstadoDAO;
			$ativdao = new AtividadeDAO;
			$caddao = new CadastroDAO;
			$autorvo = $autordao->getAutorVO($codautor);
			$_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']][$codautor] = array(
				'codautor' => $codautor,
				'nome' => ($autorvo->getNome()),
				'atividade' => $this->dados_get['atividade'],
				'wiki' => $caddao->checaAutorWiki($codautor),
				'nome_atividade' => $ativdao->getAtividade($this->dados_get['atividade']),
				'estado' => $estadodao->getSiglaEstado($autorvo->getCodEstado()),
				'descricao' => $this->dados_get['descricao'],
				'falecido' => (int)$this->dados_get['falecido'],
			);
		}
		
		$this->listarAutoresFicha();
	}

	private function removerAutorFicha() {
		$codautor = $this->dados_get['cod'];
		$index_remover = false;
		$index_cod = 0;
		foreach ($_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']] as $index => $autor) {
			if ($autor['codautor'] == $codautor) {
				$index_cod = $index;
				$index_remover = true;
				break;
			}
		}
		if ($index_remover)
			unset($_SESSION['sess_conteudo_autores_ficha'][$this->dados_get['sessao_id']][$codautor]);
	}
	
}
