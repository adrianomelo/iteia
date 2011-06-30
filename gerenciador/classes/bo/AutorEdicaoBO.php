<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/ImagemUtil.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");

class AutorEdicaoBO extends UsuarioBO {

	private $autorvo = null;
	private $autordao = null;

	public function __construct() {
		$this->autordao = new AutorDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform["codautor"] = (int)$dadosform['codautor'];
		$this->dadosform["nomeartistico"] = substr(trim($dadosform["nomeartistico"]), 0, 100);
		$this->dadosform["nomecompleto"] = substr(trim($dadosform["nomecompleto"]), 0, 100);
		$this->dadosform["datanascimento"] = $dadosform["datanascimento"];
		$this->dadosform["falecido"] = (int)$dadosform["falecido"];
		$this->dadosform["datafalecimento"] = $dadosform["datafalecimento"];
		//$this->dadosform["biografia"] = substr(trim(strip_tags($dadosform["biografia"], '<b><i><ul><li><em><strong><p><br><ol><img><a>')), 0, 50000);
		$this->dadosform["biografia"] = substr(trim($dadosform["biografia"]), 0, 1400);
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

		$this->dadosform["tipo_autor"] = (int)$dadosform["tipo_autor"];

		$this->dadosform["site"] = str_replace("http://", "", substr(trim($dadosform["site"]), 0, 200));

		$this->dadosform["finalendereco"] = Util::geraTags(substr(trim($dadosform["finalendereco"]), 0, 30));

		$this->dadosform["login"] = $dadosform["login"];
		$this->dadosform["senha"] = $dadosform["senha"];
		$this->dadosform["senha2"] = $dadosform["senha2"];

		$this->dadosform["cpf"] = $dadosform["cpf"];

        $this->dadosform["contato"] = $_SESSION['contato_comunicadores'];
		$this->dadosform["sitesrelacionados"] = $_SESSION['sites_relacionados'];

		$this->dadosform['imgtemp'] = trim($dadosform['imgtemp']);
		$this->dadosform['imagem_visualizacao'] = trim($dadosform['imagem_visualizacao']);
    }

	protected function validaDados() {
		//if (!$this->dadosform["nomeartistico"]) $this->erro_campos[] = 'nomeartistico';

		if (!$this->dadosform["nomecompleto"]) $this->erro_campos[] = 'nomecompleto';

        if ($this->dadosform["datanascimento"] && !checkdate(substr($this->dadosform["datanascimento"], 3, 2), substr($this->dadosform["datanascimento"], 0, 2), substr($this->dadosform["datanascimento"], 6, 4))) $this->erro_campos[] = "datanascimento";

		if ($this->dadosform["falecido"] && !checkdate(substr($this->dadosform["datafalecimento"], 3, 2), substr($this->dadosform["datafalecimento"], 0, 2), substr($this->dadosform["datafalecimento"], 6, 4))) $this->erro_campos[] = "falecimento";

		//if (!$this->dadosform["biografia"]) $this->erro_campos[] = "biografia";

		if (!$this->dadosform["email"]) $this->erro_campos[] = "email";

		if (!$this->dadosform["codpais"]) $this->erro_campos[] = "pais";

		if ($this->dadosform["codpais"] == ConfigGerenciadorVO::getCodPaisBrasil()) {
			if (!$this->dadosform["codestado"])  $this->erro_campos[] = "estado";
			if (!$this->dadosform["codcidade"])  $this->erro_campos[] = "codcidade";
		} elseif (!$this->dadosform["cidade"])   $this->erro_campos[] = "cidade";

		if ($this->dadosform["email"]) {
			if (!Util::checkEmail($this->dadosform["email"])) {
				$this->erro_campos[] = "email";
				$this->erro_mensagens[] = "E-mail está em formato incorreto";
			} elseif ($this->autordao->existeEmail($this->dadosform["email"], 2, $this->dadosform["codautor"])) {
				$this->erro_campos[] = "email";
				$this->erro_mensagens[] = "E-mail já existente";
			}
		}

		if ($this->dadosform["cpf"]) {
			if ($this->autordao->existeCpf($this->dadosform["cpf"], $this->dadosform["codautor"])) {
				$this->erro_campos[] = "cpf";
				$this->erro_mensagens[] = "CPF já existente";
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

		if (!$this->dadosform["codautor"]) {
			if (!$this->dadosform["tipo_autor"]) {
				$this->erro_campos[] = "tipo_autor";
			}
			if (!$this->dadosform["finalendereco"]) {
				$this->erro_campos[] = "finalendereco";
			} elseif (!eregi("^[a-zA-Z0-9]+$", $this->dadosform["finalendereco"])) {
				$this->erro_mensagens[] = "Final do endereço só pode conter letras e números";
				$this->erro_campos[] = "finalendereco";
			} elseif ($this->autordao->existeFinalEndereco('autores/'.$this->dadosform["finalendereco"], $this->dadosform["codautor"])) {
				$this->erro_mensagens[] = "Final do endereço já existente";
				$this->erro_campos[] = "finalendereco";
			}
			if (!$this->dadosform["senha"] && !$this->dadosform["falecido"] && $this->dadosform["tipo_autor"] == 2) {
				$this->erro_campos[] = "senha";
			}
		} else {
			if ($this->dadosform["finalendereco"] && ($_SESSION['logado_dados']['nivel'] >= 7)) {
				if ($this->autordao->existeFinalEndereco($this->dadosform["finalendereco"], $this->dadosform["codautor"], 2)) {
					$this->erro_mensagens[] = "Final do endereço já existente";
					$this->erro_campos[] = "finalendereco";
				}
			}
		}

		if ($this->dadosform["tipo_autor"] == 2 && !$this->dadosform["codautor"]) {
	        if (!$this->dadosform["falecido"] && $this->dadosform["senha"] != $this->dadosform["senha2"]) {
				$this->erro_mensagens[] = "A Senha está diferente da Repetição";
				$this->erro_campos[] = "senha";
			}
	        if ($this->dadosform["senha"] && strlen($this->dadosform["senha"]) < 6 && !$this->dadosform["falecido"]) {
				$this->erro_mensagens[] = "A Senha precisa ter no mínimo 6 caracteres";
				$this->erro_campos[] = "senha";
			}
		}

		if (count($this->erro_campos)) {
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
		}
    }

	protected function setDadosVO() {
		$this->autorvo = new AutorVO;
		$this->autorvo->setCodUsuario((int)$this->dadosform["codautor"]);
		$this->autorvo->setCodTipo(2);
		$this->autorvo->setNome($this->dadosform["nomeartistico"]);
		$this->autorvo->setNomeCompleto($this->dadosform["nomecompleto"]);
		$this->autorvo->setDataNascimento(substr($this->dadosform["datanascimento"], 6, 4).'-'.substr($this->dadosform["datanascimento"], 3, 2).'-'.substr($this->dadosform["datanascimento"], 0, 2));
		
		if ($this->dadosform["falecido"]) {
			$this->autorvo->setDataFalecimento(substr($this->dadosform["datafalecimento"], 6, 4).'-'.substr($this->dadosform["datafalecimento"], 3, 2).'-'.substr($this->dadosform["datafalecimento"], 0, 2));
		} elseif ($this->dadosform["datafalecimento"] == '') {
			$this->autorvo->setDataFalecimento("0000-00-00");
        }
		
        $this->autorvo->setDescricao($this->dadosform["biografia"]);
		$this->autorvo->setEndereco($this->dadosform["endereco"]);
		$this->autorvo->setComplemento($this->dadosform["complemento"]);
		$this->autorvo->setBairro($this->dadosform["bairro"]);
		$this->autorvo->setCep($this->dadosform["cep"]);
		$this->autorvo->setCodCidade((int)$this->dadosform["codcidade"]);
		$this->autorvo->setCidade($this->dadosform["cidade"]);
		$this->autorvo->setCodEstado((int)$this->dadosform["codestado"]);
		$this->autorvo->setCodPais((int)$this->dadosform["codpais"]);
		$this->autorvo->setTelefone($this->dadosform["telefone"]);
		$this->autorvo->setCelular($this->dadosform["celular"]);
		$this->autorvo->setEmail($this->dadosform["email"]);
		$this->autorvo->setMostrarEmail($this->dadosform["mostrar_email"]);
		$this->autorvo->setSite($this->dadosform["site"]);
		$this->autorvo->setUrl($this->dadosform["finalendereco"]);

		$this->autorvo->setCPF($this->dadosform["cpf"]);

        $this->autorvo->setLogin($this->dadosform["finalendereco"]);
        $this->autorvo->setSenha($this->dadosform["senha"]);

		//if ($_SESSION['logado_como'] == 1)
		//	$situacao = 1; // se um autor for cadastrar autor ele fica pendente
		//else
			$situacao = 3; // se um autor for cadastrado por um colaborador ele fica inatico
		$this->autorvo->setSituacao($situacao);

		//if (!$this->dadosform["codautor"])
			$this->autorvo->setCodNivel($this->dadosform["tipo_autor"]);

		$this->autorvo->setContatos($this->dadosform["contato"]);
		$this->autorvo->setSitesRelacionados($this->dadosform["sitesrelacionados"]);
	}

    protected function editarDados() {
		if (!$this->autorvo->getCodUsuario()) {
			$codautor = $this->autordao->cadastrar($this->autorvo);

			// envia email
			$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
			$mensagem  = "";
			$mensagem .= "<p>Olá ".$this->dadosform["nomeartistico"].",</p>";
			$mensagem .= "<p>A Equipe iTeia tem o prazer em informar que o seu cadastro foi aprovado, e você já pode compartilhar seu conteúdo em nossa rede.</p>";
			$mensagem .= "<p>Seja bem vindo!</p>";

			$mensagem .= "<br/><p>Seu login: ".$this->dadosform['finalendereco']."</p>";
			$mensagem .= "<p>Sua senha: ".$this->dadosform['senha']."</p>";

			$mensagem .= "<br/><p>Veja a sua página aqui: <a href=\"".ConfigVO::URL_SITE.$this->dadosform["finalendereco"]."\">".ConfigVO::URL_SITE.$this->dadosform["finalendereco"]."</a></p>";

			$mensagem .= "<br/><p>Faça login agora e veja como divulgar suas ações culturais.</p>";
			$mensagem .= "<p>Esperamos pelo seu post!</p>";

			$mensagem .= "<br/>---";
			$mensagem .= "<br/>Equipe iTeia";
			$mensagem .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
			$mensagem .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";

			$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
			$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
			Util::enviaemail('[iTEIA] - Parabéns, você agora faz parte da rede iTEIA!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $this->dadosform["email"]);

		} else {
			$this->autordao->atualizar($this->autorvo);
			$codautor = $this->autorvo->getCodUsuario();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgautor_".$codautor;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->autordao->atualizarFoto($nomearquivo_final, $codautor);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados']);
		return $codautor;
    }

	public function setDadosCamposEdicao($codautor) {
		$autorvo = $this->autordao->getAutorVO($codautor);

		$this->dadosform["codautor"] = $autorvo->getCodUsuario();
		$this->dadosform["nomeartistico"] = $autorvo->getNome();
		$this->dadosform["nomecompleto"] = $autorvo->getNomeCompleto();
		$this->dadosform["datanascimento"] = Util::iif($autorvo->getDataNascimento() != "0000-00-00" && $autorvo->getDataNascimento() != "", date('d/m/Y', strtotime($autorvo->getDataNascimento())), '');

		if ($autorvo->getDataFalecimento() != "0000-00-00" && $autorvo->getDataFalecimento() != '') {
			$this->dadosform["falecido"] = 1;
			$this->dadosform["datafalecimento"] = date('d/m/Y', strtotime($autorvo->getDataFalecimento()));
		} else {
			$this->dadosform["falecido"] = 0;
        	$this->dadosform["datafalecimento"] = '';
		}

		$this->dadosform["biografia"] = $autorvo->getDescricao();
		$this->dadosform["codpais"] = $autorvo->getCodPais();
		$this->dadosform["codestado"] = $autorvo->getCodEstado();
		$this->dadosform["cidade"] = $autorvo->getCidade();
		$this->dadosform["codcidade"] = $autorvo->getCodCidade();
		$this->dadosform["endereco"] = $autorvo->getEndereco();
		$this->dadosform["complemento"] = $autorvo->getComplemento();
		$this->dadosform["bairro"] = $autorvo->getBairro();
		$this->dadosform["imagem_visualizacao"] = $autorvo->getImagem();
		$this->dadosform["telefone"] = $autorvo->getTelefone();
		$this->dadosform["celular"] = $autorvo->getCelular();
		$this->dadosform["email"] = $autorvo->getEmail();
		$this->dadosform["mostrar_email"] = $autorvo->getMostrarEmail();
		$this->dadosform["site"] = $autorvo->getSite();
		$this->dadosform["finalendereco"] = $autorvo->getUrl();
		$finalend_partes = explode('/', $this->dadosform['finalendereco']);
		if ($finalend_partes[1])
			$this->dadosform['finalendereco'] = $finalend_partes[1];
		$this->dadosform["cep"] = $autorvo->getCep();
        $this->dadosform["login"] = $autorvo->getLogin();
		$this->dadosform["contato"] = $autorvo->getContatos();
		$this->dadosform["sitesrelacionados"] = $autorvo->getSitesRelacionados();

		$this->dadosform["cpf"] = $autorvo->getCPF();

		$this->dadosform["tipo_autor"] = $autorvo->getTipoAutor();

		foreach ($this->dadosform["contato"] as $key => $value)
			$_SESSION['contato_comunicadores'][] = array('tipo' => $value['cod_comunicador'], 'nome_usuario' => $value['nome_usuario']);

		foreach ($this->dadosform["sitesrelacionados"] as $key => $value)
			$_SESSION['sites_relacionados'][] = array('titulo' => utf8_encode($value['site']), 'endereco' => $value['url']);
	}

	public function getComunicadoresAutor($codautor) {
		return $this->autordao->getComunicadoresAutor($codautor);
	}

	public function getSitesAutor($codautor) {
		return $this->autordao->getSitesAutor($codautor);
	}

	public function getAutorWiki($codautor) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		$caddao = new CadastroDAO;
		return $caddao->checaAutorWiki($codautor);
	}

	public function getColaboradoresRepresentantes($codautor) {
		return $this->autordao->getColaboradoresRepresentantes($codautor);
	}

}