<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/ImagemUtil.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");

class AutorUnificarBO extends UsuarioBO {

	private $autorvo = null;
	private $autordao = null;
	
	private $dadosautor1 = array();
	private $dadosautor2 = array();

	public function __construct() {
		$this->autordao = new AutorDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		//print_r($dadosform);
		$this->dadosform["codautor1"] = (int)$dadosform['codautor1'];
		$this->dadosform["codautor2"] = (int)$dadosform['codautor2'];
		
		$this->dadosform["nomeartistico"] = (int)$dadosform['nomeartistico'];
		$this->dadosform["nomecompleto"] = (int)$dadosform['nomecompleto'];
		$this->dadosform["cpf"] = (int)$dadosform["cpf"];
		
		$this->dadosform["datanascimento"] = (int)$dadosform["datanascimento"];
		$this->dadosform["falecido"] = (int)$dadosform["falecido"];

		$this->dadosform["biografia"] = (int)$dadosform["biografia"];
		$this->dadosform['imagem'] = (int)$dadosform['imagem'];
		
		$this->dadosform["endereco"] = (int)$dadosform["endereco"];
		$this->dadosform["complemento"] = (int)$dadosform["complemento"];
		$this->dadosform["bairro"] = (int)$dadosform["bairro"];
		$this->dadosform["cep"] = (int)$dadosform["cep"];
		$this->dadosform["pais"] = (int)$dadosform["pais"];
		$this->dadosform["estado"] = (int)$dadosform["estado"];
		$this->dadosform["cidade"] = (int)$dadosform["cidade"];
		
		//$this->dadosform["cidade"] = substr(trim($dadosform["cidade"]), 0, 100);
		
		$this->dadosform["telefone"] = (int)$dadosform["telefone"];
		$this->dadosform["celular"] = (int)$dadosform["celular"];
		
		$this->dadosform["email"] = (int)$dadosform["email"];
		$this->dadosform["site"] = (int)$dadosform["site"];
		
		$this->dadosform["contato"] = (int)$dadosform["contato"];
		$this->dadosform["sitesrelacionados"] = (int)$dadosform["sitesrelacionados"];
		
		$this->dadosform["finalendereco"] = (int)$dadosform["finalendereco"];
		$this->dadosform["tipo_autor"] = (int)$dadosform["tipo_autor"];	
    }

	protected function validaDados() {
		if (!$this->dadosform["nomeartistico"]) $this->erro_campos[] = "nomeartistico";
		if (!$this->dadosform["nomecompleto"]) 	$this->erro_campos[] = "nomecompleto";
		//if (!$this->dadosform["cpf"]) 			$this->erro_campos[] = "cpf";
		if (!$this->dadosform["biografia"]) 	$this->erro_campos[] = "biografia";
		if (!$this->dadosform["pais"]) 			$this->erro_campos[] = "pais";
		if (!$this->dadosform["estado"])  		$this->erro_campos[] = "estado";
		if (!$this->dadosform["cidade"])  		$this->erro_campos[] = "cidade";
		if (!$this->dadosform["finalendereco"]) $this->erro_campos[] = "finalendereco";
		if (!$this->dadosform["tipo_autor"])  	$this->erro_campos[] = "tipo_autor";

		if (count($this->erro_campos)) {
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
		}
    }
    
    private function getDadosOpcaoSelecionada($opcao) {
    	// pego os dados dos dois autores
    	if ($opcao == 1)
    		return $this->autordao->getAutorVO($this->dadosform["codautor1"]);
    	elseif ($opcao == 2)
    		return $this->autordao->getAutorVO($this->dadosform["codautor2"]);
   		else
   			return $this->autordao->getAutorVO(0);
    }

	protected function setDadosVO() {
		
		// manter apenas selecionados de cada autor 
		// preciso atualizar apenas um deles e inativar o cadastro do outro
		// atualizar todo o conteudo do autor 2 para o autor 1
		
		// defino que o autor a ser mantido sempre será o autor 1
		$this->autorvo = new AutorVO;
		$this->autorvo->setCodUsuario($this->dadosform["codautor1"]);
		
		// nivel e tipo são os mesmos definidos no form, ou é 1 ou 2
		$this->autorvo->setCodTipo(2);
		$this->autorvo->setCodNivel($this->dadosform["tipo_autor"]);
		
		$this->autorvo->setNome($this->getDadosOpcaoSelecionada($this->dadosform["nomeartistico"])->getNome());
		$this->autorvo->setNomeCompleto($this->getDadosOpcaoSelecionada($this->dadosform["nomecompleto"])->getNomeCompleto());
		$this->autorvo->setCPF($this->getDadosOpcaoSelecionada($this->dadosform["cpf"])->getCPF());
		$this->autorvo->setDataNascimento($this->getDadosOpcaoSelecionada($this->dadosform["datanascimento"])->getDataNascimento());
		
		if ($this->dadosform["falecido"] == 2)
			$this->autorvo->setDataFalecimento($this->getDadosOpcaoSelecionada(1)->getDataFalecimento());
		else
			$this->autorvo->setDataFalecimento("0000-00-00");
			
		$this->autorvo->setDescricao($this->getDadosOpcaoSelecionada($this->dadosform["biografia"])->getDescricao());
		$this->autorvo->setImagem($this->getDadosOpcaoSelecionada($this->dadosform["imagem"])->getImagem());
		
		$this->autorvo->setEndereco($this->getDadosOpcaoSelecionada($this->dadosform["endereco"])->getEndereco());
		$this->autorvo->setComplemento($this->getDadosOpcaoSelecionada($this->dadosform["complemento"])->getComplemento());
		$this->autorvo->setBairro($this->getDadosOpcaoSelecionada($this->dadosform["bairro"])->getBairro());
		$this->autorvo->setCep($this->getDadosOpcaoSelecionada($this->dadosform["cep"])->getCep());
		$this->autorvo->setCodPais((int)$this->getDadosOpcaoSelecionada($this->dadosform["pais"])->getCodPais());
		$this->autorvo->setCodEstado((int)$this->getDadosOpcaoSelecionada($this->dadosform["estado"])->getCodEstado());
		$this->autorvo->setCodCidade((int)$this->getDadosOpcaoSelecionada($this->dadosform["cidade"])->getCodCidade());
		
		$this->autorvo->setTelefone($this->getDadosOpcaoSelecionada($this->dadosform["telefone"])->getTelefone());
		$this->autorvo->setCelular($this->getDadosOpcaoSelecionada($this->dadosform["celular"])->getCelular());
		
		$this->autorvo->setEmail($this->getDadosOpcaoSelecionada($this->dadosform["email"])->getEmail());
		$this->autorvo->setSite($this->getDadosOpcaoSelecionada($this->dadosform["site"])->getSite());
		
		$arrayContatos = array();
		foreach ($this->getDadosOpcaoSelecionada($this->dadosform["contato"])->getContatos() as $key => $value)
			$arrayContatos[] = array('tipo' => $value['cod_comunicador'], 'nome_usuario' => $value['nome_usuario']);
		
		$this->autorvo->setContatos($arrayContatos);
		
		$arraySites = array();
		foreach ($this->getDadosOpcaoSelecionada($this->dadosform["sitesrelacionados"])->getSitesRelacionados() as $key => $value)
			$arraySites[] = array('titulo' => $value['site'], 'endereco' => $value['url']);
		
		$this->autorvo->setSitesRelacionados($arraySites);
		
		$this->autorvo->setUrl($this->getDadosOpcaoSelecionada($this->dadosform["finalendereco"])->getUrl());
		
		$this->autorvo->setLogin($this->getDadosOpcaoSelecionada($this->dadosform["finalendereco"])->getUrl());
        $this->autorvo->setSenha(substr(md5(time()), 0, 6));
		
		//print_r($this->autorvo); die;
	}

    protected function editarDados() {
		// primeiro desativo e modifico a url do autor 2 (não gerar conflito com a url do autor 1)
		// agora a url do autor 2 é excluida
		$this->autordao->atualizarUrlUnificacao($this->dadosform['codautor2']);
		
		// atualizo dados do autor mantido (autor 1)
		$this->autordao->atualizar($this->autorvo);
		
		// atualiza foto
		$this->autordao->atualizarFoto($this->autorvo->getImagem(), $this->autorvo->getCodUsuario());
		
		// ===================================================================
		// manda email se o autor tiver email e não for do tipo wiki
		if ($this->autorvo->getEmail() && $this->autorvo->getCodTipo() == 2) {
			include(ConfigVO::getDirUtil().'/htmlMimeMail5/htmlMimeMail5.php');

			// atualiza senha, somente se tiver email e for autor
			// pois a nova senha é enviada por email
			$this->autordao->atualizaSenha($this->autorvo);

			$mail = new htmlMimeMail5();
	    	$texto_email = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_email.html');
	
			$conteudo .= "<p>Seu cadastro no iTEIA foi modificado.</p>\n";
			$conteudo .= "<p>O seu nome de usuário e senha de acesso ao gerenciador de conteúdo:</p>\n<p>Usuário: <strong>".$this->autorvo->getLogin()."</strong><br />\nSenha: <strong>".$this->autorvo->getSenha()."</strong><br />\n</p>\n";
	    	
			$texto_email = eregi_replace("<!--%URL_IMG%-->", ConfigVO::URL_SITE, $texto_email);
			$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $conteudo, $texto_email);
	
			$mail->setHtml($texto_email);
			$mail->setReturnPath($this->autorvo->getEmail());
			$mail->setFrom("\"Portal iTEIA\" <gerenciador@iteia.org.br>");
			$mail->setSubject("iTEIA - Atualização Cadastro de Autor");
	    	$mail->send(array($this->autorvo->getEmail()));		
		}
		
		// atualizo todo o conteudo do autor 2 para autor 1
		$this->autordao->atualizarConteudoUnificacao($this->dadosform['codautor1'], $this->dadosform['codautor2']);

		$this->dadosform = $this->dadosautor1 = $this->dadosautor2 = $this->arquivos = array();
		return $this->autorvo->getCodUsuario();
    }

	public function getDadosAutorUm($codautor) {
		$autorvo = $this->autordao->getAutorVO($codautor);
		
		$this->dadosform['codautor1'] =  $this->dadosautor1["codautor"] = $autorvo->getCodUsuario();
		
		$this->dadosautor1["nomeartistico"] = $autorvo->getNome();
		$this->dadosautor1["nomecompleto"] = $autorvo->getNomeCompleto();
		$this->dadosautor1["datanascimento"] = Util::iif($autorvo->getDataNascimento() != "0000-00-00" && $autorvo->getDataNascimento() != "", date('d/m/Y', strtotime($autorvo->getDataNascimento())), '');

		if ($autorvo->getDataFalecimento() != "0000-00-00" && $autorvo->getDataFalecimento() != '') {
			$this->dadosautor1["falecido"] = 1;
			$this->dadosautor1["datafalecimento"] = date('d/m/Y', strtotime($autorvo->getDataFalecimento()));
		} else {
			$this->dadosautor1["falecido"] = 0;
        	$this->dadosautor1["datafalecimento"] = '';
		}
		
		$this->dadosautor1["biografia"] = $autorvo->getDescricao();
		$this->dadosautor1["pais"] = $this->getPais($autorvo->getCodPais());
		$this->dadosautor1["estado"] = $this->getEstado($autorvo->getCodEstado());
		$this->dadosautor1["cidade"] = $this->getCidade($autorvo->getCodCidade());
		$this->dadosautor1["endereco"] = $autorvo->getEndereco();
		$this->dadosautor1["complemento"] = $autorvo->getComplemento();
		$this->dadosautor1["bairro"] = $autorvo->getBairro();
		$this->dadosautor1["imagem_visualizacao"] = $autorvo->getImagem();
		$this->dadosautor1["telefone"] = $autorvo->getTelefone();
		$this->dadosautor1["celular"] = $autorvo->getCelular();
		$this->dadosautor1["email"] = $autorvo->getEmail();
		$this->dadosautor1["site"] = $autorvo->getSite();
		$this->dadosautor1["finalendereco"] = $autorvo->getUrl();
		$this->dadosautor1["cep"] = $autorvo->getCep();
        $this->dadosautor1["login"] = $autorvo->getLogin();
		$this->dadosautor1["contato"] = $autorvo->getContatos();
		$this->dadosautor1["sitesrelacionados"] = $autorvo->getSitesRelacionados();
		$this->dadosautor1["cpf"] = $autorvo->getCPF();
		$this->dadosautor1["tipo_autor"] = $autorvo->getTipoAutor();
		$this->dadosautor1["data_cadastro"] = date('d/m/Y', strtotime($autorvo->getDataCadastro()));
	}
	
	public function getDadosAutorDois($codautor) {
		$autorvo = $this->autordao->getAutorVO($codautor);

		$this->dadosform['codautor2'] = $this->dadosautor2["codautor"] = $autorvo->getCodUsuario();
		
		$this->dadosautor2["nomeartistico"] = $autorvo->getNome();
		$this->dadosautor2["nomecompleto"] = $autorvo->getNomeCompleto();
		$this->dadosautor2["datanascimento"] = Util::iif($autorvo->getDataNascimento() != "0000-00-00" && $autorvo->getDataNascimento() != "", date('d/m/Y', strtotime($autorvo->getDataNascimento())), '');

		if ($autorvo->getDataFalecimento() != "0000-00-00" && $autorvo->getDataFalecimento() != '') {
			$this->dadosautor2["falecido"] = 1;
			$this->dadosautor2["datafalecimento"] = date('d/m/Y', strtotime($autorvo->getDataFalecimento()));
		} else {
			$this->dadosautor2["falecido"] = 0;
        	$this->dadosautor2["datafalecimento"] = '';
		}
		
		$this->dadosautor2["biografia"] = $autorvo->getDescricao();
		$this->dadosautor2["pais"] = $this->getPais($autorvo->getCodPais());
		$this->dadosautor2["estado"] = $this->getEstado($autorvo->getCodEstado());
		$this->dadosautor2["cidade"] = $this->getCidade($autorvo->getCodCidade());
		$this->dadosautor2["endereco"] = $autorvo->getEndereco();
		$this->dadosautor2["complemento"] = $autorvo->getComplemento();
		$this->dadosautor2["bairro"] = $autorvo->getBairro();
		$this->dadosautor2["imagem_visualizacao"] = $autorvo->getImagem();
		$this->dadosautor2["telefone"] = $autorvo->getTelefone();
		$this->dadosautor2["celular"] = $autorvo->getCelular();
		$this->dadosautor2["email"] = $autorvo->getEmail();
		$this->dadosautor2["site"] = $autorvo->getSite();
		$this->dadosautor2["finalendereco"] = $autorvo->getUrl();
		$this->dadosautor2["cep"] = $autorvo->getCep();
        $this->dadosautor2["login"] = $autorvo->getLogin();
		$this->dadosautor2["contato"] = $autorvo->getContatos();
		$this->dadosautor2["sitesrelacionados"] = $autorvo->getSitesRelacionados();
		$this->dadosautor2["cpf"] = $autorvo->getCPF();
		$this->dadosautor2["tipo_autor"] = $autorvo->getTipoAutor();
		$this->dadosautor2["data_cadastro"] = date('d/m/Y', strtotime($autorvo->getDataCadastro()));
	}
	
	public function setValorCampo($nomecampo, $valor) {
		$this->dadosform[$nomecampo] = $valor;
	}
	
	public function getValorCampoAutorUm($campo) {
		return $this->dadosautor1[$campo];	
	}
	
	public function getValorCampoAutorDois($campo) {
		return $this->dadosautor2[$campo];	
	}

	public function getComunicadoresAutor($codautor) {
		return $this->autordao->getComunicadoresAutor($codautor);
	}

	public function getSitesAutor($codautor) {
		return $this->autordao->getSitesAutor($codautor);
	}

}