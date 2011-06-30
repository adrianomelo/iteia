<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");

include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AutorVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AutorDAO.php");

class CadastroCulturalBO {

	private $dadosform = array();
	private $erro_campos = array();
	private $erro_mensagens = array();

	private $autorvo = null;
	private $autordao = null;

	public function __construct() {
		$this->autordao = new AutorDAO;
	}

	private function setDadosForm(&$dadosform) {
		$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
		$this->dadosform['nomeartistico'] = strip_tags(trim($dadosform['nomeartistico']));
		$this->dadosform['cpf'] = strip_tags(trim($dadosform['cpf']));
		$this->dadosform['rg'] = strip_tags(trim($dadosform['rg']));
		$this->dadosform['registro'] = substr(strip_tags(trim($dadosform['registro'])), 0, 1400);
		//$this->dadosform['atuacao'] = strip_tags(trim($dadosform['atuacao']));
		$this->dadosform['endereco'] = strip_tags(trim($dadosform['endereco']));
		$this->dadosform['complemento'] = strip_tags(trim($dadosform['complemento']));
		$this->dadosform['datanascimento'] = strip_tags(trim($dadosform['datanascimento']));
		$this->dadosform['codcidade'] = (int)$dadosform['codcidade'];
		$this->dadosform['codestado'] = (int)$dadosform['codestado'];
		$this->dadosform['codpais'] = (int)$dadosform['codpais'];
		$this->dadosform['cidade'] = strip_tags(trim($dadosform['cidade']));
		//$this->dadosform['ano'] = (int)$dadosform['ano'];
		$this->dadosform['bairro'] = strip_tags(trim($dadosform['bairro']));
		
		$this->dadosform['codtipo'] = (int)$dadosform['codtipo'];
		$this->dadosform['numero'] = strip_tags(trim($dadosform['numero']));
		$this->dadosform['orgao'] = strip_tags(trim($dadosform['orgao']));
		
		$this->dadosform['cep'] = strip_tags(trim($dadosform['cep']));
		$this->dadosform['email'] = strip_tags(trim($dadosform['email']));
		$this->dadosform['email2'] = strip_tags(trim($dadosform['email2']));
		$this->dadosform['site'] = str_replace('http://', '', substr(trim($dadosform['site']), 0, 200));
		$this->dadosform['fone'] = strip_tags(trim($dadosform['fone']));
		$this->dadosform['celular'] = strip_tags(trim($dadosform['celular']));
		$this->dadosform['historico'] = strip_tags(trim(stripslashes($dadosform['historico'])));
		//$this->dadosform['finalendereco'] = strip_tags(trim(stripslashes(substr($dadosform['finalendereco'], 0, 30))));
		$this->dadosform['finalendereco'] = Util::geraTags(substr(trim($dadosform["finalendereco"]), 0, 30));
		
		$this->dadosform['senha'] = strip_tags(trim(stripslashes($dadosform['senha'])));
		$this->dadosform['senha2'] = strip_tags(trim(stripslashes($dadosform['senha2'])));
		//$this->dadosform['entidade'] = strip_tags(trim($dadosform['entidade']));
		//$this->dadosform['cnpj'] = strip_tags(trim($dadosform['cnpj']));
		//$this->dadosform['endereco_empresa'] = strip_tags(trim($dadosform['endereco_empresa']));
		//$this->dadosform['cidade_empresa'] = strip_tags(trim($dadosform['cidade_empresa']));
		//$this->dadosform['cep_empresa'] = strip_tags(trim($dadosform['cep_empresa']));
		//$this->dadosform['email_empresa'] = strip_tags(trim($dadosform['email_empresa']));
		//$this->dadosform['site_empresa'] = str_replace('http://', '', substr(trim($dadosform['site_empresa']), 0, 200));
		//$this->dadosform['fone_empresa'] = strip_tags(trim($dadosform['fone_empresa']));
		$this->dadosform['deacordo'] = intval($dadosform['deacordo']);
	}

	private function validaDadosForm() {
		if (!$this->dadosform['nome']) {
			$this->erro_campos[] = 'nome';
			$this->erro_mensagens[] = '<a href="#seu-nome">Escreva seu <strong>nome</strong></a>';
		}

		//if (!$this->dadosform['nomeartistico']) {
		//	$this->erro_campos[] = 'nomeartistico';
		//}
		
		if (!$this->dadosform['historico']) {
			$this->erro_campos[] = 'historico';
			$this->erro_mensagens[] = '<a href="#historico">Escreva sua <strong>biografia</strong></a>';
		}

		if (!$this->dadosform['email']) {
			$this->erro_campos[] = 'email';
			$this->erro_mensagens[] = '<a href="#email">Adicione um <strong>e-mail</strong></a>';
		}

		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email']) {
			$this->erro_campos[] = 'email';
			$this->erro_mensagens[] = '<a href="#email">Adicione um <strong>e-mail</strong> válido</a>';
		}

		if (!$this->dadosform['codcidade'])
			$this->erro_campos[] = 'cidade';

		if (!$this->dadosform['codestado'])
			$this->erro_campos[] = 'estado';

		if (!$this->dadosform['codpais'])
			$this->erro_campos[] = 'pais';

		if ($this->dadosform["codpais"] == 2) {
			if (!$this->dadosform["codestado"]) {
				$this->erro_campos[] = "estado";
				$this->erro_mensagens[] = '<a href="#estado">Selecione um <strong>estado</strong></a>';
			}
			if (!$this->dadosform["codcidade"]) {
				$this->erro_campos[] = "cidade";
				$this->erro_mensagens[] = '<a href="#selectcidade">Selecione uma <strong>cidade</strong></a>';
			}
		} elseif (!$this->dadosform["cidade"]) {
			$this->erro_campos[] = "cidade";
			$this->erro_mensagens[] = '<a href="#cidade">Escreva o nome da <strong>cidade</strong></a>';
		}

		//if ($this->dadosform["cpf"]) {
		//	if ($this->autordao->existeCpf($this->dadosform["cpf"], 0))
		//		$this->erro_campos[] = "cpf";
		//}

		//if (!$this->dadosform['endereco_empresa'])
		//	$this->erro_campos[] = 'endereco_empresa';

		//if (!$this->dadosform['cidade_empresa'])
		//	$this->erro_campos[] = 'cidade_empresa';
			
		//if (!Util::checkEmail($this->dadosform['email2']) && $this->dadosform['email2'])
		//	$this->erro_campos[] = 'email2';
			
		//if ($this->dadosform['email'] != $this->dadosform['email2']) {
		//	$this->erro_campos[] = 'email';
		//	$this->erro_campos[] = 'email2';
		//	$this->erro_mensagens[] = "E-mail não é igual a Repetir E-mail";
		//}

		//if (!Util::checkEmail($this->dadosform['email_empresa']) && $this->dadosform['email_empresa'])
		//	$this->erro_campos[] = 'email_empresa';

		if (!$this->dadosform["finalendereco"]) {
			$this->erro_campos[] = "finalendereco";
			$this->erro_mensagens[] = '<a href="#final_endereco">Informe o Nome de <strong>usuário</strong></a>';
		}
		elseif (!eregi("^[a-zA-Z0-9]+$", $this->dadosform["finalendereco"])) {
			$this->erro_campos[] = "finalendereco";
			$this->erro_mensagens[] = '<a href="#final_endereco">Nome de <strong>usuário</strong> inválido</a>';
		}
		elseif ($this->autordao->existeFinalEndereco($this->dadosform["finalendereco"], 0)) {
			$this->erro_campos[] = "finalendereco";
			$this->erro_mensagens[] = "Final do endereço já existente";
			$this->erro_mensagens[] = '<a href="#final_endereco">Nome de <strong>usuário</strong> já existe</a>';
		}

		if (!$this->dadosform["senha"]) {
			$this->erro_campos[] = "senha";
			$this->erro_mensagens[] = '<a href="#pass">Informe uma <strong>senha</strong></a>';
		}

		if ($this->dadosform["senha"] != $this->dadosform["senha2"]) {
			$this->erro_campos[] = "senha";
			$this->erro_mensagens[] = '<a href="#pass">As <strong>senhas</strong> não conhecidem</a>';
		}

        if ($this->dadosform["senha"] && strlen($this->dadosform["senha"]) < 6) {
			$this->erro_campos[] = "senha";
			$this->erro_mensagens[] = '<a href="#pass">A <strong>senha</strong> precisa ter mais de 6 caracteres</a>';
        }

		if (!$this->dadosform['deacordo']) {
			$this->erro_campos[] = 'deacordo';
			$this->erro_mensagens[] = '<a href="#acordo">Aceite os <strong>termos de uso</strong></a>';
		}

		if (count($this->erro_campos) || count($this->erro_mensagens))
			throw new Exception(' ');
	}

	public function cadastrar($dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDadosForm();
		} catch (exception $e) {
			throw $e;
		}
		$this->sendForm();
	}

	private function sendForm() {

		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");

		$mensagem  = "";
		$mensagem .= "<p>Ol&aacute;!</p>";
		$mensagem .= "<p><strong><em>".$this->dadosform['nome']."</em></strong> enviou seus dados para cadastro do iTEIA!.</p>";

		$mensagem .= "<p>Dados de cadastro:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome'];
		if ($this->dadosform['nomeartistico'])
			$mensagem .= "<p><strong>Nome Artístico:</strong> ".$this->dadosform['nomeartistico'];
		if ($this->dadosform['datanascimento'])
			$mensagem .= "<p><strong>Data de nascimento:</strong> ".$this->dadosform['datanascimento'];
		if ($this->dadosform['historico'])
			$mensagem .= "<p><strong>Biografia:</strong> ".nl2br($this->dadosform['historico']);
		if ($this->dadosform['endereco'])
			$mensagem .= "<p><strong>Endereço:</strong> ".$this->dadosform['endereco'];
		if ($this->dadosform['complemento'])
			$mensagem .= "<p><strong>Complemento:</strong> ".$this->dadosform['complemento'];
		if ($this->dadosform['bairro'])
			$mensagem .= "<p><strong>Bairro:</strong> ".$this->dadosform['bairro'];
		$mensagem .= "<p><strong>País:</strong> ".$this->getPais($this->dadosform['codpais']);

		if ($this->dadosform["codpais"] == 2) {
			$mensagem .= "<p><strong>Estado:</strong> ".$this->getEstado($this->dadosform['codestado']);
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->getCidade($this->dadosform['codcidade']);
		} else {
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->dadosform['cidade'];
		}

		if ($this->dadosform['fone'])
			$mensagem .= "<p><strong>Telefone fixo:</strong> ".$this->dadosform['fone'];
		$mensagem .= "<p><strong>E-mail:</strong> ".$this->dadosform['email'];
		$mensagem .= "<br />";

		$mensagem .= "<p><strong>Final do endereço:</strong> ".$this->dadosform['finalendereco'];
		$mensagem .= "<p><strong>Login:</strong> ".$this->dadosform['finalendereco'];
		$mensagem .= "<p><strong>Senha:</strong> ".$this->dadosform['senha'];

		$mensagem .= "<p><strong>Acordo com as regras para participar do iTEIA:</strong> ".Util::iif($this->dadosform['deacordo'] == 1, 'Sim', 'Não');

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail($this->dadosform['nome']." enviou seus dados para cadastro do iTEIA", $this->dadosform['email'], ConfigVO::getEmail(), $texto_email, ConfigVO::getEmail());

		$this->setDadosVO();
		$codusuario = $this->autordao->cadastrar($this->autorvo);
		$this->autordao->addNotificacaoNovoAutorAprovacao($codusuario);
		$this->autordao->inseriDadosEndereco($codusuario, $this->dadosform['codtipo'], $this->dadosform['numero'], $this->dadosform['orgao']);
		
		$mensagem = "";
		$mensagem .= "<p>Olá ".$this->dadosform['nome'].",</p>";
		$mensagem .= "<br/><p>A Equipe iTeia recebeu sua solicitação para fazer parte da nossa rede como Autor.</p>";
		$mensagem .= "<p>Mas... Espere só um pouquinho!</p>";
		$mensagem .= "<p>Seu cadastro está aguardando a aprovação de um <a href=\"".ConfigVO::URL_SITE."colaboradores.php\">Colaborador</a>.</p>";
		$mensagem .= "<br/>---";
		$mensagem .= "<br/>Equipe iTeia";
		$mensagem .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
		$mensagem .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";

		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		
		//Util::enviaemail('[iTEIA] - Seu cadastro aguarda uma aprovação. Agora falta pouco!', $this->dadosform['email'], $this->dadosform['email'], $texto_email, $this->dadosform['email']);
		//enviaemail($assunto, $de, $email, $conteudo, $emaildestino)
		
		Util::enviaemail('[iTEIA] - Seu cadastro aguarda uma aprovação. Agora falta pouco!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $this->dadosform['email']);
	}

	protected function setDadosVO() {
		$this->autorvo = new AutorVO;
		$this->autorvo->setCodUsuario((int)0);
		$this->autorvo->setCodTipo(2);
		
		if (!$this->dadosform["nomeartistico"]) $this->dadosform["nomeartistico"] = $this->dadosform["nome"];
		
		$this->autorvo->setNome($this->dadosform["nomeartistico"]);
		$this->autorvo->setNomeCompleto($this->dadosform["nome"]);
		$this->autorvo->setDataNascimento(substr($this->dadosform["datanascimento"], 6, 4).'-'.substr($this->dadosform["datanascimento"], 3, 2).'-'.substr($this->dadosform["datanascimento"], 0, 2));

		$this->autorvo->setDataFalecimento("0000-00-00");

		$this->autorvo->setDescricao($this->dadosform["historico"]);
		$this->autorvo->setEndereco($this->dadosform["endereco"]);
		$this->autorvo->setComplemento($this->dadosform["complemento"]);
		$this->autorvo->setBairro($this->dadosform["bairro"]);
		$this->autorvo->setCep($this->dadosform["cep"]);
		$this->autorvo->setCodCidade((int)$this->dadosform["codcidade"]);
		$this->autorvo->setCidade($this->dadosform["cidade"]);
		$this->autorvo->setCodEstado((int)$this->dadosform["codestado"]);
		$this->autorvo->setCodPais((int)$this->dadosform["codpais"]);
		$this->autorvo->setTelefone($this->dadosform["fone"]);
		$this->autorvo->setCelular($this->dadosform["celular"]);
		$this->autorvo->setEmail($this->dadosform["email"]);
		$this->autorvo->setSite($this->dadosform["site"]);
		$this->autorvo->setUrl($this->dadosform["finalendereco"]);

		$this->autorvo->setCPF($this->dadosform["cpf"]);

        $this->autorvo->setLogin($this->dadosform["finalendereco"]);
        $this->autorvo->setSenha($this->dadosform["senha"]);

        $this->autorvo->setCodNivel(2);

		//if ($_SESSION['logado_como'] == 1)
		//	$situacao = 1; // se um autor for cadastrar autor ele fica pendente
		//else
			$situacao = 1; // se um autor for cadastrado por um colaborador ele fica inatico
		$this->autorvo->setSituacao($situacao);

		//$this->autorvo->setContatos($this->dadosform["contato"]);
		//$this->autorvo->setSitesRelacionados($this->dadosform["sitesrelacionados"]);
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}
	
	public function setValorCampo($campo, $valor) {
		$this->dadosform[$campo] = $valor;
	}
	
	public function getErros() {
		return '<li>'.implode("</li><li>\n", $this->erro_mensagens).'</li>';
	}

	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return ' erro';
		else
			return '';
	}

	public function getListaEstados() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getListaEstados();
	}

	public function getListaPaises() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getListaPaises();
	}

	public function getListaCidades($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getListaCidades($codestado);
	}

	public function getEstado($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getNomeEstado($codestado);
	}

	public function getPais($codpais) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getNomePais($codpais);
	}

	public function getCidade($codcidade) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}

}
