<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/ImagemUtil.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");

class ColaboradorEdicaoBO extends UsuarioBO {

	private $colaboradorvo = null;
	private $colaboradordao = null;

	public function __construct() {
		$this->colaboradordao = new ColaboradorDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform["codcolaborador"] = (int)$dadosform['codcolaborador'];
		$this->dadosform["nomeinstituicao"] = substr(trim($dadosform["nomeinstituicao"]), 0, 100);
		$this->dadosform["entidade"] = substr(trim($dadosform["entidade"]), 0, 100);
		$this->dadosform["rede"] = (array)$dadosform["rede"];
		//$this->dadosform["descricao"] = substr(trim(strip_tags($dadosform["descricao"], '<b><i><ul><li><em><strong><p><br><ol><img><a>')), 0, 50000);
		$this->dadosform["descricao"] = substr(trim($dadosform["descricao"]), 0, 1400);
		$this->dadosform["endereco"] = substr(trim($dadosform["endereco"]), 0, 80);
		$this->dadosform["complemento"] = substr(trim($dadosform["complemento"]), 0, 100);
		$this->dadosform["bairro"] = substr(trim($dadosform["bairro"]), 0, 100);
		$this->dadosform["cep"] = substr(trim($dadosform["cep"]), 0, 100);
		$this->dadosform["codpais"] = (int)$dadosform["codpais"];
		$this->dadosform["codestado"] = (int)$dadosform["codestado"];
		$this->dadosform["codcidade"] = (int)$dadosform["codcidade"];
		$this->dadosform["cidade"] = substr(trim($dadosform["cidade"]), 0, 100);
		$this->dadosform["telefone"] = substr(trim($dadosform["telefone"]), 0, 50);
		$this->dadosform["celular"] = substr(trim($dadosform["celular"]), 0, 50);

		$this->dadosform["email"] = substr(trim($dadosform["email"]), 0, 100);
		$this->dadosform["mostrar_email"] = (int)$dadosform["mostrar_email"];

		$this->dadosform["site"] = str_replace("http://", "", substr(trim($dadosform["site"]), 0, 200));
		//$this->dadosform["finalendereco"] = substr(trim($dadosform["finalendereco"]), 0, 30);
		$this->dadosform["finalendereco"] = Util::geraTags(substr(trim($dadosform["finalendereco"]), 0, 30));
		//$this->dadosform["login"] = $dadosform["login"];
		//$this->dadosform["senha"] = $dadosform["senha"];
		//$this->dadosform["senha2"] = $dadosform["senha2"];

        $this->dadosform["contato"] = $_SESSION['contato_comunicadores'];
		$this->dadosform["sitesrelacionados"] = $_SESSION['sites_relacionados'];
		$this->dadosform["autoresintegrantes"] = $_SESSION['sessao_autores_integrantes'];

		$this->dadosform['imgtemp'] = trim($dadosform['imgtemp']);
		$this->dadosform['imagem_visualizacao'] = trim($dadosform['imagem_visualizacao']);
    }

	protected function validaDados() {
		if (!$this->dadosform["nomeinstituicao"]) $this->erro_campos[] = 'nomeinstituicao';

		//if (!$this->dadosform["entidade"]) $this->erro_campos[] = 'entidade';

		if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";

		if (!$this->dadosform["codpais"]) $this->erro_campos[] = "pais";

		if ($this->dadosform["codpais"] == ConfigGerenciadorVO::getCodPaisBrasil()) {
			if (!$this->dadosform["codestado"])  $this->erro_campos[] = "estado";
			if (!$this->dadosform["codcidade"])  $this->erro_campos[] = "codcidade";
		} elseif (!$this->dadosform["cidade"])   $this->erro_campos[] = "cidade";

		if ($this->dadosform["email"]) {
			if (!Util::checkEmail($this->dadosform["email"])) {
				$this->erro_campos[] = "email";
				$this->erro_mensagens[] = "E-mail está em formato incorreto";
			} elseif ($this->colaboradordao->existeEmail($this->dadosform["email"], 1, $this->dadosform["codcolaborador"])) {
				$this->erro_campos[] = "email";
				$this->erro_mensagens[] = "E-mail já existente";
			}
		}

		/*
		if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"])) {
			if ($this->arquivos["imagem"]["size"] > 2200000) {
				$this->erro_mensagens[] = "Imagem ilustrativa está com mais de 2MB";
				$this->erro_campos[] = "imagem";
			}
			$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos["imagem"]["name"]));
			if (($extensao != 'jpg') && ($extensao != 'jpeg') && ($extensao != 'gif') && ($extensao != 'png')) {
				$this->erro_mensagens[] = "Apenas enviar imagem no formato jpg, gif ou png";
				$this->erro_campos[] = "imagem";
			}
		}
		*/

		if (!$this->dadosform["codcolaborador"]) {
			if (!$this->dadosform["finalendereco"]) {
				$this->erro_campos[] = "finalendereco";
			} elseif (!eregi("^[a-zA-Z0-9]+$", $this->dadosform["finalendereco"])) {
				$this->erro_mensagens[] = "Final do endereço só pode conter letras e números";
				$this->erro_campos[] = "finalendereco";
			} elseif ($this->colaboradordao->existeFinalEndereco('colaboradores/'.$this->dadosform["finalendereco"], $this->dadosform["codcolaborador"])) {
				$this->erro_mensagens[] = "Final do endereço já existente";
				$this->erro_campos[] = "finalendereco";
			}
			//if (!$this->dadosform["senha"]) $this->erro_campos[] = "senha";
		} else {
			if ($this->dadosform["finalendereco"] && ($_SESSION['logado_dados']['nivel'] >= 7)) {
				if ($this->colaboradordao->existeFinalEndereco($this->dadosform["finalendereco"], $this->dadosform["codcolaborador"], 1)) {
					$this->erro_mensagens[] = "Final do endereço já existente";
					$this->erro_campos[] = "finalendereco";
				}
			}
		}

		/*
        if ($this->dadosform["senha"] != $this->dadosform["senha2"]) {
			$this->erro_mensagens[] = "A Senha está diferente da Repetição";
			$this->erro_campos[] = "senha";
		}
        if ($this->dadosform["senha"] && strlen($this->dadosform["senha"]) < 6) {
			$this->erro_mensagens[] = "A Senha precisa ter no mínimo 6 caracteres";
			$this->erro_campos[] = "senha";
		}
		*/

		if (count($this->erro_campos)) {
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
		}
    }

	protected function setDadosVO() {
		$this->colaboradorvo = new ColaboradorVO;
		$this->colaboradorvo->setCodUsuario((int)$this->dadosform["codcolaborador"]);
		$this->colaboradorvo->setCodTipo(1);
		$this->colaboradorvo->setNome($this->dadosform["nomeinstituicao"]);
		$this->colaboradorvo->setEntidade($this->dadosform["entidade"]);
		$this->colaboradorvo->setRede($this->dadosform["rede"]);

		$this->colaboradorvo->setDescricao($this->dadosform["descricao"]);
		$this->colaboradorvo->setEndereco($this->dadosform["endereco"]);
		$this->colaboradorvo->setComplemento($this->dadosform["complemento"]);
		$this->colaboradorvo->setBairro($this->dadosform["bairro"]);
		$this->colaboradorvo->setCep($this->dadosform["cep"]);
		$this->colaboradorvo->setCodCidade((int)$this->dadosform["codcidade"]);
		$this->colaboradorvo->setCidade($this->dadosform["cidade"]);
		$this->colaboradorvo->setCodEstado((int)$this->dadosform["codestado"]);
		$this->colaboradorvo->setCodPais((int)$this->dadosform["codpais"]);
		$this->colaboradorvo->setTelefone($this->dadosform["telefone"]);
		$this->colaboradorvo->setCelular($this->dadosform["celular"]);
		$this->colaboradorvo->setEmail($this->dadosform["email"]);
		$this->colaboradorvo->setMostrarEmail($this->dadosform["mostrar_email"]);
		$this->colaboradorvo->setSite($this->dadosform["site"]);
		$this->colaboradorvo->setUrl($this->dadosform["finalendereco"]);

        //$this->colaboradorvo->setLogin($this->dadosform["finalendereco"]);
        //$this->colaboradorvo->setSenha($this->dadosform["senha"]);

		$this->colaboradorvo->setAdministrador(0); // não é admin
		$this->colaboradorvo->setSituacao(3); // ativo

		$this->colaboradorvo->setContatos($this->dadosform["contato"]);
		$this->colaboradorvo->setSitesRelacionados($this->dadosform["sitesrelacionados"]);
		$this->colaboradorvo->setListaIntegrantes($this->dadosform["autoresintegrantes"]);
	}

    protected function editarDados() {
		if (!$this->colaboradorvo->getCodUsuario()) {
			$codcolaborador = $this->colaboradordao->cadastrar($this->colaboradorvo);

			// envia email
			$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
			$mensagem  = "";
			$mensagem .= "<p>Olá ".$this->dadosform["nomeinstituicao"].",</p>";
			$mensagem .= "<p>A Equipe iTeia tem o prazer em informar que o seu cadastro foi aprovado, e você já pode compartilhar seu conteúdo em nossa rede.</p>";
			$mensagem .= "<p>Seja bem vindo!</p>";

			//$mensagem .= "<br/><p>Seu login: ".$dados['login']."</p>";
			//$mensagem .= "<p>Sua senha: ".$dados['senha']."</p>";

			$mensagem .= "<br/><p>Veja a sua página aqui: <a href=\"".ConfigVO::URL_SITE.$this->dadosform["finalendereco"]."\">".ConfigVO::URL_SITE.$this->dadosform["finalendereco"]."</a></p>";

			//$mensagem .= "<br/><p>Faça login agora e veja como divulgar suas ações culturais.</p>";
			//$mensagem .= "<p>Esperamos pelo seu post!</p>";

			$mensagem .= "<br/>---";
			$mensagem .= "<br/>Equipe iTeia";
			$mensagem .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
			$mensagem .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";

			$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
			$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
			Util::enviaemail('[iTEIA] - Parabéns, você agora faz parte da rede iTEIA!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $this->dadosform["email"]);

		} else {
			$this->colaboradordao->atualizar($this->colaboradorvo);
			$codcolaborador = $this->colaboradorvo->getCodUsuario();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgcolab_".$codcolaborador;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->colaboradordao->atualizarFoto($nomearquivo_final, $codcolaborador);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados'], $_SESSION['sessao_autores_integrantes']);
		return $codcolaborador;
    }

	public function setDadosCamposEdicao($codcolaborador) {
		$colaboradorvo = $this->colaboradordao->getColaboradorVO($codcolaborador);

		$this->dadosform["codcolaborador"] = $colaboradorvo->getCodUsuario();
		$this->dadosform["nomeinstituicao"] = $colaboradorvo->getNome();
		$this->dadosform["entidade"] = $colaboradorvo->getEntidade();

		$this->dadosform["rede"] = explode(',', $colaboradorvo->getRede());

		$this->dadosform["descricao"] = $colaboradorvo->getDescricao();
		$this->dadosform["codpais"] = $colaboradorvo->getCodPais();
		$this->dadosform["codestado"] = $colaboradorvo->getCodEstado();
		$this->dadosform["cidade"] = $colaboradorvo->getCidade();
		$this->dadosform["codcidade"] = $colaboradorvo->getCodCidade();
		$this->dadosform["endereco"] = $colaboradorvo->getEndereco();
		$this->dadosform["complemento"] = $colaboradorvo->getComplemento();
		$this->dadosform["bairro"] = $colaboradorvo->getBairro();
		$this->dadosform["imagem_visualizacao"] = $colaboradorvo->getImagem();
		$this->dadosform["telefone"] = $colaboradorvo->getTelefone();
		$this->dadosform["celular"] = $colaboradorvo->getCelular();
		$this->dadosform["email"] = $colaboradorvo->getEmail();
		$this->dadosform["mostrar_email"] = $colaboradorvo->getMostrarEmail();
		$this->dadosform["site"] = $colaboradorvo->getSite();
		$this->dadosform["finalendereco"] = $colaboradorvo->getUrl();
		$finalend_partes = explode('/', $this->dadosform['finalendereco']);
		if ($finalend_partes[1])
			$this->dadosform['finalendereco'] = $finalend_partes[1];
		$this->dadosform["cep"] = $colaboradorvo->getCep();
        $this->dadosform["login"] = $colaboradorvo->getLogin();
		$this->dadosform["contato"] = $colaboradorvo->getContatos();
		$this->dadosform["sitesrelacionados"] = $colaboradorvo->getSitesRelacionados();
		$this->dadosform["autoresintegrantes"] = $colaboradorvo->getListaIntegrantes();

		foreach ($this->dadosform["contato"] as $key => $value)
			$_SESSION['contato_comunicadores'][] = array('tipo' => $value['cod_comunicador'], 'nome_usuario' => $value['nome_usuario']);

		foreach ($this->dadosform["sitesrelacionados"] as $key => $value)
			$_SESSION['sites_relacionados'][] = array('titulo' => utf8_encode($value['site']), 'endereco' => $value['url']);

		foreach ($this->dadosform["autoresintegrantes"] as $key => $value)
			$_SESSION['sessao_autores_integrantes'][$value['cod_usuario']] = array('cod_usuario' => $value['cod_usuario'], 'nome' => $value['nome'], 'imagem' => $value['imagem'], 'responsavel' => $value['responsavel']);
	}

	public function getComunicadoresColaborador($codcolaborador) {
		return $this->colaboradordao->getComunicadoresColaborador($codcolaborador);
	}

	public function getSitesColaborador($codcolaborador) {
		return $this->colaboradordao->getSitesColaborador($codcolaborador);
	}

	public function getAutoresParticipantes($codcolaborador) {
		return $this->colaboradordao->getAutoresParticipantes($codcolaborador);
	}

}