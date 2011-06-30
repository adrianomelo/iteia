<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");

include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AutorVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AutorDAO.php");

include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");

class CadastroCulturalColaboradorBO {

	private $dadosform = array();
	private $erro_campos = array();
	private $erro_mensagens = array();

	private $autorvo = null;
	private $autordao = null;

	public function __construct() {
		$this->autordao = new AutorDAO;
		$this->colaboradordao = new ColaboradorDAO;
	}

	private function setDadosForm(&$dadosform) {
		// colaborador
		$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
		$this->dadosform['atuacao'] = strip_tags(trim($dadosform['atuacao']));
		$this->dadosform['endereco'] = strip_tags(trim($dadosform['endereco']));
		$this->dadosform['complemento'] = strip_tags(trim($dadosform['complemento']));
		$this->dadosform['bairro'] = strip_tags(trim($dadosform['bairro']));
		$this->dadosform['codpais'] = (int)$dadosform['codpais'];
		$this->dadosform['codestado'] = (int)$dadosform['codestado'];
		$this->dadosform['codcidade'] = (int)$dadosform['codcidade'];
		$this->dadosform['cidade'] = strip_tags(trim($dadosform['cidade']));
		$this->dadosform['telefone'] = strip_tags(trim($dadosform['telefone']));
		
		$this->dadosform['finalendereco'] = Util::geraTags(substr(trim($dadosform["finalendereco"]), 0, 30));
		
		// autor
		$this->dadosform['nome_autor'] = strip_tags(trim($dadosform['nome_autor']));
		$this->dadosform['nomeartistico_autor'] = strip_tags(trim($dadosform['nomeartistico_autor']));
		$this->dadosform['historico_autor'] = substr(strip_tags(trim($dadosform['historico_autor'])), 0, 1400);
		$this->dadosform['endereco_autor'] = strip_tags(trim($dadosform['endereco_autor']));
		$this->dadosform['complemento_autor'] = strip_tags(trim($dadosform['complemento_autor']));
		$this->dadosform['datanascimento_autor'] = strip_tags(trim($dadosform['datanascimento_autor']));
		$this->dadosform['codcidade_autor'] = (int)$dadosform['codcidade_autor'];
		$this->dadosform['codestado_autor'] = (int)$dadosform['codestado_autor'];
		$this->dadosform['codpais_autor'] = (int)$dadosform['codpais_autor'];
		$this->dadosform['cidade_autor'] = strip_tags(trim($dadosform['cidade_autor']));
		$this->dadosform['bairro_autor'] = strip_tags(trim($dadosform['bairro_autor']));
		$this->dadosform['codtipo_autor'] = (int)$dadosform['codtipo_autor'];
		$this->dadosform['numero_autor'] = strip_tags(trim($dadosform['numero_autor']));
		$this->dadosform['orgao_autor'] = strip_tags(trim($dadosform['orgao_autor']));
		$this->dadosform['email_autor'] = strip_tags(trim($dadosform['email_autor']));
		$this->dadosform['fone_autor'] = strip_tags(trim($dadosform['fone_autor']));
		$this->dadosform['finalendereco_autor'] = Util::geraTags(substr(trim($dadosform["finalendereco_autor"]), 0, 30));
		$this->dadosform['senha_autor'] = strip_tags(trim(stripslashes($dadosform['senha_autor'])));
		$this->dadosform['senha2_autor'] = strip_tags(trim(stripslashes($dadosform['senha2_autor'])));
		$this->dadosform['deacordo_autor'] = intval($dadosform['deacordo_autor']);
	}

	private function validaDadosForm() {
		// colaborador
		if (!$this->dadosform['nome']) {
			$this->erro_campos[] = 'nome';
			$this->erro_mensagens[] = '<a href="#name">Escreva o <strong>nome</strong> da instituição</a>';
		}

		if (!$this->dadosform['atuacao']) {
			$this->erro_campos[] = 'atuacao';
			$this->erro_mensagens[] = '<a href="#atuacao">Escreva a <strong>atuação</strong> da instituição</a>';
		}

		if (!$this->dadosform['endereco']) {
			$this->erro_campos[] = 'endereco';
			$this->erro_mensagens[] = '<a href="#endereco-colaborador">Escreva o <strong>endereço</strong></a>';
		}
			
		if (!$this->dadosform['bairro']) {
			$this->erro_campos[] = 'bairro';
			$this->erro_mensagens[] = '<a href="#bairro-colaborador">Escreva o <strong>bairro</strong></a>';
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
			$this->erro_mensagens[] = '<a href="#final_endereco">Nome de <strong>usuário</strong> já existe</a>';
		}

		// autor
		if (!$this->dadosform['nome_autor']) {
			$this->erro_campos[] = 'nome_autor';
			$this->erro_mensagens[] = '<a href="#nome_autor">Escreva seu <strong>nome</strong></a>';
		}
		
		if (!$this->dadosform['historico_autor']) {
			$this->erro_campos[] = 'historico_autor';
			$this->erro_mensagens[] = '<a href="#historico_autor">Escreva sua <strong>biografia</strong></a>';
		}

		if (!$this->dadosform['email_autor']) {
			$this->erro_campos[] = 'email_autor';
			$this->erro_mensagens[] = '<a href="#email_autor">Adicione um <strong>e-mail</strong></a>';
		}

		if (!Util::checkEmail($this->dadosform['email_autor']) && $this->dadosform['email_autor']) {
			$this->erro_campos[] = 'email_autor';
			$this->erro_mensagens[] = '<a href="#email_autor">Adicione um <strong>e-mail</strong> válido</a>';
		}

		if (!$this->dadosform['codcidade_autor'])
			$this->erro_campos[] = 'cidade_autor';

		if (!$this->dadosform['codestado_autor'])
			$this->erro_campos[] = 'estado_autor';

		if (!$this->dadosform['codpais_autor'])
			$this->erro_campos[] = 'pais_autor';

		if ($this->dadosform["codpais_autor"] == 2) {
			if (!$this->dadosform["codestado_autor"]) {
				$this->erro_campos[] = "estado_autor";
				$this->erro_mensagens[] = '<a href="#estado_autor">Selecione um <strong>estado</strong></a>';
			}
			if (!$this->dadosform["codcidade_autor"]) {
				$this->erro_campos[] = "cidade_autor";
				$this->erro_mensagens[] = '<a href="#selectcidade_autor">Selecione uma <strong>cidade</strong></a>';
			}
		} elseif (!$this->dadosform["cidade_autor"]) {
			$this->erro_campos[] = "cidade_autor";
			$this->erro_mensagens[] = '<a href="#cidade_autor">Escreva o nome da <strong>cidade</strong></a>';
		}

		if (!$this->dadosform["finalendereco_autor"]) {
			$this->erro_campos[] = "finalendereco_autor";
			$this->erro_mensagens[] = '<a href="#final_endereco_autor">Informe o Nome de <strong>usuário</strong></a>';
		}
		elseif (!eregi("^[a-zA-Z0-9]+$", $this->dadosform["finalendereco_autor"])) {
			$this->erro_campos[] = "finalendereco_autor";
			$this->erro_mensagens[] = '<a href="#final_endereco_autor">Nome de <strong>usuário</strong> inválido</a>';
		}
		elseif ($this->autordao->existeFinalEndereco($this->dadosform["finalendereco_autor"], 0)) {
			$this->erro_campos[] = "finalendereco_autor";
			$this->erro_mensagens[] = '<a href="#final_endereco_autor">Nome de <strong>usuário</strong> já existe</a>';
		}

		if (!$this->dadosform["senha_autor"]) {
			$this->erro_campos[] = "senha_autor";
			$this->erro_mensagens[] = '<a href="#pass">Informe uma <strong>senha</strong></a>';
		}

		if ($this->dadosform["senha_autor"] != $this->dadosform["senha2_autor"]) {
			$this->erro_campos[] = "senha_autor";
			$this->erro_mensagens[] = '<a href="#pass">As <strong>senhas</strong> não conhecidem</a>';
		}

        if ($this->dadosform["senha_autor"] && strlen($this->dadosform["senha_autor"]) < 6) {
			$this->erro_campos[] = "senha_autor";
			$this->erro_mensagens[] = '<a href="#pass">A <strong>senha</strong> precisa ter mais de 6 caracteres</a>';
        }

		if (!$this->dadosform['deacordo_autor']) {
			$this->erro_campos[] = 'deacordo_autor';
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
		// colaborador
		$mensagem .= "<p>Dados de cadastro - Colaborador:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome'];
		$mensagem .= "<p><strong>Descrição da atuação:</strong> ".$this->dadosform['atuacao'];
		$mensagem .= "<p><strong>Endereço:</strong> ".$this->dadosform['endereco'];
		$mensagem .= "<p><strong>Complemento:</strong> ".$this->dadosform['complemento'];
		$mensagem .= "<p><strong>Bairro:</strong> ".$this->dadosform['bairro'];
		$mensagem .= "<p><strong>País:</strong> ".$this->getPais($this->dadosform['codpais']);
		if ($this->dadosform["codpais"] == 2) {
			$mensagem .= "<p><strong>Estado:</strong> ".$this->getEstado($this->dadosform['codestado']);
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->getCidade($this->dadosform['codcidade']);
		} else {
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->dadosform['cidade'];
		}
		$mensagem .= "<p><strong>Telefone:</strong> ".$this->dadosform['telefone'];
		$mensagem .= "<p><strong>Final do endereço:</strong> ".$this->dadosform['finalendereco'];
		$mensagem .= "<br />";
		
		// autor
		$mensagem .= "<p><strong>Dados do responsável pela solicitação</strong>";
		$mensagem .= "<br />";
		
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome_autor'];
		if ($this->dadosform['nomeartistico_autor'])
			$mensagem .= "<p><strong>Nome Artístico:</strong> ".$this->dadosform['nomeartistico_autor'];
		if ($this->dadosform['datanascimento_autor'])
			$mensagem .= "<p><strong>Data de nascimento:</strong> ".$this->dadosform['datanascimento_autor'];
		if ($this->dadosform['historico_autor'])
			$mensagem .= "<p><strong>Biografia:</strong> ".nl2br($this->dadosform['historico_autor']);
		if ($this->dadosform['endereco_autor'])
			$mensagem .= "<p><strong>Endereço:</strong> ".$this->dadosform['endereco_autor'];
		if ($this->dadosform['complemento_autor'])
			$mensagem .= "<p><strong>Complemento:</strong> ".$this->dadosform['complemento_autor'];
		if ($this->dadosform['bairro_autor'])
			$mensagem .= "<p><strong>Bairro:</strong> ".$this->dadosform['bairro_autor'];
		$mensagem .= "<p><strong>País:</strong> ".$this->getPais($this->dadosform['codpais_autor']);

		if ($this->dadosform["codpais_autor"] == 2) {
			$mensagem .= "<p><strong>Estado:</strong> ".$this->getEstado($this->dadosform['codestado_autor']);
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->getCidade($this->dadosform['codcidade_autor']);
		} else {
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->dadosform['cidade_autor'];
		}

		if ($this->dadosform['fone_autor'])
			$mensagem .= "<p><strong>Telefone:</strong> ".$this->dadosform['fone_autor'];
		$mensagem .= "<p><strong>E-mail:</strong> ".$this->dadosform['email_autor'];
		$mensagem .= "<br />";

		$mensagem .= "<p><strong>Final do endereço:</strong> ".$this->dadosform['finalendereco_autor'];
		$mensagem .= "<p><strong>Login:</strong> ".$this->dadosform['finalendereco_autor'];
		$mensagem .= "<p><strong>Senha:</strong> ".$this->dadosform['senha_autor'];

		$mensagem .= "<p><strong>Acordo com as regras para participar do iTEIA:</strong> ".Util::iif($this->dadosform['deacordo_autor'] == 1, 'Sim', 'Não');

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail($this->dadosform['nome']." enviou seus dados para cadastro do iTEIA", $this->dadosform['email'], ConfigVO::getEmail(), $texto_email, ConfigVO::getEmail());
		
		$mensagem  = "";
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
		$mensagem .= "<p>Olá ".$this->dadosform['nome'].",";
		$mensagem .= "<br/><br/>A Equipe iTeia recebeu sua solicitação para fazer parte da nossa rede como Colaborador.";
		$mensagem .= "<br/>Mas... Espere só um pouquinho!";
		$mensagem .= "<br/>Seu cadastro está aguardando a aprovação.</p>";
		$mensagem .= "<br/>---";
		$mensagem .= "<br/>Equipe iTeia";
		$mensagem .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
		$mensagem .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";
		
		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		
		//Util::enviaemail('[iTEIA] - Seu cadastro aguarda uma aprovação. Agora falta pouco!', $this->dadosform['email'], ConfigVO::getEmail(), $texto_email, $this->dadosform['email_autor']);
		
		Util::enviaemail('[iTEIA] - Seu cadastro aguarda uma aprovação. Agora falta pouco!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $this->dadosform['email_autor']);
		
		$this->setDadosVO();
		$this->setDadosAutorVO();
		
		$codusuario = $this->colaboradordao->cadastrar($this->colaboradorvo);
		$this->colaboradordao->addNotificacaoNovoColaboradorAprovacao($codusuario);
		
		$codusuario_autor = $this->autordao->cadastrar($this->autorvo);
		$this->autordao->addNotificacaoNovoAutorAprovacao($codusuario_autor);
		$this->autordao->inseriDadosEndereco($codusuario_autor, $this->dadosform['codtipo_autor'], $this->dadosform['numero_autor'], $this->dadosform['orgao_autor']);
	}

	protected function setDadosVO() {
		$this->colaboradorvo = new ColaboradorVO;
		$this->colaboradorvo->setCodUsuario((int)0);
		$this->colaboradorvo->setCodTipo(1);
		
		$this->colaboradorvo->setNome($this->dadosform["nome"]);
		$this->colaboradorvo->setNomeCompleto($this->dadosform["nome"]);
		$this->colaboradorvo->setDataNascimento(substr($this->dadosform["datanascimento"], 6, 4).'-'.substr($this->dadosform["datanascimento"], 3, 2).'-'.substr($this->dadosform["datanascimento"], 0, 2));

		$this->colaboradorvo->setDescricao($this->dadosform["atuacao"]);
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
		$this->colaboradorvo->setSite($this->dadosform["site"]);
		$this->colaboradorvo->setUrl($this->dadosform["finalendereco"]);

		$this->colaboradorvo->setCPF($this->dadosform["cpf"]);

        $this->colaboradorvo->setLogin($this->dadosform["finalendereco"]);
        $this->colaboradorvo->setSenha($this->dadosform["senha"]);

        $this->colaboradorvo->setCodNivel(2);
		$situacao = 1; // se um autor for cadastrado por um colaborador ele fica inatico
		$this->colaboradorvo->setSituacao($situacao);
	}

	protected function setDadosAutorVO() {
		$this->autorvo = new AutorVO;
		$this->autorvo->setCodUsuario((int)0);
		$this->autorvo->setCodTipo(2);
		
		if (!$this->dadosform["nomeartistico_autor"]) $this->dadosform["nomeartistico_autor"] = $this->dadosform["nome_autor"];
		
		$this->autorvo->setNome($this->dadosform["nome_autor"]);
		$this->autorvo->setNomeCompleto($this->dadosform["nome_autor"]);
		$this->autorvo->setDataNascimento(substr($this->dadosform["datanascimento_autor"], 6, 4).'-'.substr($this->dadosform["datanascimento_autor"], 3, 2).'-'.substr($this->dadosform["datanascimento_autor"], 0, 2));

		$this->autorvo->setDataFalecimento("0000-00-00");

		$this->autorvo->setDescricao($this->dadosform["historico_autor"]);
		$this->autorvo->setEndereco($this->dadosform["endereco_autor"]);
		$this->autorvo->setComplemento($this->dadosform["complemento_autor"]);
		$this->autorvo->setBairro($this->dadosform["bairro_autor"]);
		$this->autorvo->setCep($this->dadosform["cep_autor"]);
		$this->autorvo->setCodCidade((int)$this->dadosform["codcidade_autor"]);
		$this->autorvo->setCidade($this->dadosform["cidade_autor"]);
		$this->autorvo->setCodEstado((int)$this->dadosform["codestado_autor"]);
		$this->autorvo->setCodPais((int)$this->dadosform["codpais_autor"]);
		$this->autorvo->setTelefone($this->dadosform["fone_autor"]);
		$this->autorvo->setCelular($this->dadosform["celular_autor"]);
		$this->autorvo->setEmail($this->dadosform["email_autor"]);
		$this->autorvo->setSite($this->dadosform["site_autor"]);
		$this->autorvo->setUrl($this->dadosform["finalendereco_autor"]);

		$this->autorvo->setCPF($this->dadosform["cpf_autor"]);

        $this->autorvo->setLogin($this->dadosform["finalendereco_autor"]);
        $this->autorvo->setSenha($this->dadosform["senha_autor"]);

        $this->autorvo->setCodNivel(2);
		$situacao = 1;
		$this->autorvo->setSituacao($situacao);
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
