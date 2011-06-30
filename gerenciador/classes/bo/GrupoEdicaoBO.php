<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/ImagemUtil.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/GrupoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/GrupoDAO.php");

class GrupoEdicaoBO extends UsuarioBO {
	
	private $grupovo = null;
	private $grupodao = null;
	
	public function __construct() {
		$this->grupodao = new GrupoDAO;
		parent::__construct();
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform["codgrupo"] = (int)$dadosform['codgrupo'];
		$this->dadosform["nome"] = substr(trim($dadosform["nome"]), 0, 100);
		//$this->dadosform["descricao"] = substr(trim(strip_tags($dadosform["descricao"], '<b><i><ul><li><em><strong><p><br><ol><img><a>')), 0, 50000);
		$this->dadosform["descricao"] = substr(trim($dadosform["descricao"]), 0, 1400);
		$this->dadosform["tipo"] = (array)$dadosform['tipo'];
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
		$this->dadosform["site"] = str_replace("http://", "", substr(trim($dadosform["site"]), 0, 200));
		//$this->dadosform["finalendereco"] = substr(trim($dadosform["finalendereco"]), 0, 30);
		$this->dadosform["finalendereco"] = Util::geraTags(substr(trim($dadosform["finalendereco"]), 0, 30));

        $this->dadosform["contato"] = $_SESSION['contato_comunicadores'];
		$this->dadosform["sitesrelacionados"] = $_SESSION['sites_relacionados'];
		$this->dadosform["autoresintegrantes"] = $_SESSION['sessao_autores_integrantes'];
		
		$this->dadosform['imgtemp'] = trim($dadosform['imgtemp']);
		$this->dadosform['imagem_visualizacao'] = trim($dadosform['imagem_visualizacao']);
    }
	
	protected function validaDados() {
		if (!$this->dadosform["nome"]) $this->erro_campos[] = 'nome';
		
		if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";
		
		if (!$this->dadosform["codpais"]) $this->erro_campos[] = "pais";
		
		if ($this->dadosform["codpais"] == ConfigGerenciadorVO::getCodPaisBrasil()) {
			if (!$this->dadosform["codestado"])  $this->erro_campos[] = "estado"; 
			if (!$this->dadosform["codcidade"])  $this->erro_campos[] = "codcidade";
		} elseif (!$this->dadosform["cidade"])   $this->erro_campos[] = "cidade";
		
		if ($this->dadosform["email"]) {
			if (!Util::checkEmail($this->dadosform["email"])) {
				$this->erro_campos[] = "email";
			} elseif ($this->grupodao->existeEmail($this->dadosform["email"], 3, $this->dadosform["codgrupo"])) {
				$this->erro_campos[] = "email";
			}
		}

		if (!$this->dadosform["codgrupo"]) {
			if (!$this->dadosform["finalendereco"]) {
				$this->erro_campos[] = "finalendereco";
			} elseif (!eregi("^[a-zA-Z0-9]+$", $this->dadosform["finalendereco"])) {
				$this->erro_mensagens[] = "Final do endereço só pode conter letras e números";
				$this->erro_campos[] = "finalendereco";
			} elseif ($this->grupodao->existeFinalEndereco($this->dadosform["finalendereco"], $this->dadosform["codgrupo"])) {
				$this->erro_mensagens[] = "Final do endereço já existente";
				$this->erro_campos[] = "finalendereco";
			}
		} else {
			if ($this->dadosform["finalendereco"] && ($_SESSION['logado_dados']['nivel'] >= 7)) {
				if ($this->grupodao->existeFinalEndereco($this->dadosform["finalendereco"], $this->dadosform["codgrupo"])) {
					$this->erro_mensagens[] = "Final do endereço já existente";
					$this->erro_campos[] = "finalendereco";
				}
			}
		}

		if (count($this->erro_campos)) {
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
		}
    }
	
	protected function setDadosVO() {
		$this->grupovo = new GrupoVO;
		$this->grupovo->setCodUsuario($this->dadosform["codgrupo"]);
		$this->grupovo->setCodTipo(3); // grupo
		$this->grupovo->setNome($this->dadosform["nome"]);
		$this->grupovo->setDescricao($this->dadosform["descricao"]);
		$this->grupovo->setEndereco($this->dadosform["endereco"]);
		$this->grupovo->setComplemento($this->dadosform["complemento"]);
		$this->grupovo->setBairro($this->dadosform["bairro"]);
		$this->grupovo->setCep($this->dadosform["cep"]);
		$this->grupovo->setCodCidade((int)$this->dadosform["codcidade"]);
		$this->grupovo->setCidade($this->dadosform["cidade"]);
		$this->grupovo->setCodEstado((int)$this->dadosform["codestado"]);
		$this->grupovo->setCodPais((int)$this->dadosform["codpais"]);
		$this->grupovo->setTelefone($this->dadosform["telefone"]);
		$this->grupovo->setCelular($this->dadosform["celular"]);
		$this->grupovo->setEmail($this->dadosform["email"]);
		$this->grupovo->setSite($this->dadosform["site"]);
		$this->grupovo->setUrl($this->dadosform["finalendereco"]);
		
        $this->grupovo->setTipo($this->dadosform["tipo"]);

		//if ($_SESSION['logado_como'] == 1)
		//	$situacao = 1; // se um autor for cadastrar grupo ele fica pendente
		//else
			$situacao = 3; // se um grupo for cadastrado por um colaborador ele fica ativo
		if (!$this->dadosform["codgrupo"])
			$this->grupovo->setSituacao($situacao); // ativo

		$this->grupovo->setContatos($this->dadosform["contato"]);
		$this->grupovo->setSitesRelacionados($this->dadosform["sitesrelacionados"]);
		$this->grupovo->setListaIntegrantes($this->dadosform["autoresintegrantes"]);
	}
	
    protected function editarDados() {
		if (!$this->grupovo->getCodUsuario()) {
			$codgrupo = $this->grupodao->cadastrar($this->grupovo);
		} else {
			$this->grupodao->atualizar($this->grupovo);
			$codgrupo = $this->grupovo->getCodUsuario();
		}
		
		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imggrupo_".$codgrupo;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->grupodao->atualizarFoto($nomearquivo_final, $codgrupo);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		unset($_SESSION['contato_comunicadores'], $_SESSION['sites_relacionados'], $_SESSION['sessao_autores_integrantes']);
		return $codgrupo;
    }
    
	public function setDadosCamposEdicao($codgrupo) {
		$grupovo = $this->grupodao->getGrupoVO($codgrupo);
		
		$this->dadosform["codgrupo"] = $grupovo->getCodUsuario();
		$this->dadosform["nome"] = $grupovo->getNome();
		$this->dadosform["descricao"] = $grupovo->getDescricao();
		$this->dadosform["codpais"] = $grupovo->getCodPais();
		$this->dadosform["codestado"] = $grupovo->getCodEstado();
		$this->dadosform["cidade"] = $grupovo->getCidade();
		$this->dadosform["codcidade"] = $grupovo->getCodCidade();
		$this->dadosform["endereco"] = $grupovo->getEndereco();
		$this->dadosform["complemento"] = $grupovo->getComplemento();
		$this->dadosform["bairro"] = $grupovo->getBairro();
		$this->dadosform["imagem_visualizacao"] = $grupovo->getImagem();
		$this->dadosform["telefone"] = $grupovo->getTelefone();
		$this->dadosform["celular"] = $grupovo->getCelular();
		$this->dadosform["email"] = $grupovo->getEmail();
		$this->dadosform["site"] = $grupovo->getSite();
		$this->dadosform["finalendereco"] = $grupovo->getUrl();
		$this->dadosform["cep"] = $grupovo->getCep();
        
        $this->dadosform["tipo"] = explode(',', $grupovo->getTipo());
        
		$this->dadosform["contato"] = $grupovo->getContatos();
		$this->dadosform["sitesrelacionados"] = $grupovo->getSitesRelacionados();
		$this->dadosform["autoresintegrantes"] = $grupovo->getListaIntegrantes();
		
		foreach ($this->dadosform["contato"] as $key => $value)
			$_SESSION['contato_comunicadores'][] = array('tipo' => $value['cod_comunicador'], 'nome_usuario' => $value['nome_usuario']);
			
		foreach ($this->dadosform["sitesrelacionados"] as $key => $value)
			$_SESSION['sites_relacionados'][] = array('titulo' => utf8_encode($value['site']), 'endereco' => $value['url']);
			
		foreach ($this->dadosform["autoresintegrantes"] as $key => $value)
			$_SESSION['sessao_autores_integrantes'][$value['cod_usuario']] = array('cod_usuario' => $value['cod_usuario'], 'nome' => $value['nome'], 'imagem' => $value['imagem'], 'responsavel' => $value['responsavel']);
	}
	
	public function getListaTipos() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/GrupoTipoDAO.php");
		$tipodao = new GrupoTipoDAO;
		return $tipodao->getListaTipos(array());
	}
	
	public function getComunicadoresGrupo($codgrupo) {
		return $this->grupodao->getComunicadoresGrupo($codgrupo);
	}
	
	public function getSitesGrupo($codgrupo) {
		return $this->grupodao->getSitesGrupo($codgrupo);
	}
	
	public function getAutoresGrupo($codgrupo) {
		return $this->grupodao->getAutoresGrupo($codgrupo);
	}
	
}