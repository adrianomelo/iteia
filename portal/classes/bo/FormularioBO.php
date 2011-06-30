<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/FormularioVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/dao/FormularioDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/util/Util.php');

class FormularioBO {

	private $campos_erro = array();
	private $areas_interesse = array(1 => 'artes plásticas/ gráficas', 2 => 'artesanato', 3 => 'cinema/vídeo', 4 => 'circo', 5 => 'cultura popular', 6 => 'dança', 7 => 'fotografia', 8 => 'literatura', 9 => 'moda', 10 => 'música', 11 => 'patrimônio material', 12 => 'pesquisa', 13 => 'produção cultural', 14 => 'ópera', 15 => 'teatro');
	
	private $formvo = null;
	private $formdao = null;
	
	public function __construct() {
		$this->formdao = new FormularioDAO;
	}

    public function actionContato($dadosform) {
		$this->setDadosFormContato($dadosform);
		try {
			$this->validaDadosFormContato();
		} catch (exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		$this->sendCadastro();
		$this->sendContato();
	}

	private function setDadosFormContato(&$dadosform) {
		$this->dadosform['tipo'] = strip_tags(trim($dadosform['tipo']));
		switch ($this->dadosform['tipo']) {
			case 'autor':
				$this->dadosform['interesse'] = $dadosform['interesse'];
				if (!is_array($dadosform['interesse']))
					$this->dadosform['interesse'] = array();
				$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
				$this->dadosform['telefone'] = strip_tags(trim($dadosform['telefone']));
				$this->dadosform['email'] = strip_tags(trim($dadosform['email']));
				$this->dadosform['emailCheck'] = strip_tags(trim($dadosform['emailCheck']));
				$this->dadosform['cpf'] = strip_tags(trim($dadosform['cpf']));
				break;
			case 'grupo':
				$this->dadosform['interesse2'] = $dadosform['interesse'];
				if (!is_array($dadosform['interesse']))
					$this->dadosform['interesse2'] = array();
				$this->dadosform['nome2'] = strip_tags(trim($dadosform['nome']));
				$this->dadosform['telefone2'] = strip_tags(trim($dadosform['telefone']));
				$this->dadosform['email2'] = strip_tags(trim($dadosform['email']));
				$this->dadosform['emailCheck2'] = strip_tags(trim($dadosform['emailCheck']));
				$this->dadosform['cpf2'] = strip_tags(trim($dadosform['cpf']));
				break;
			case 'colaborador':
				$this->dadosform['interesse3'] = $dadosform['interesse'];
				if (!is_array($dadosform['interesse']))
					$this->dadosform['interesse3'] = array();
				$this->dadosform['nome3'] = strip_tags(trim($dadosform['nome']));
				$this->dadosform['telefone3'] = strip_tags(trim($dadosform['telefone']));
				$this->dadosform['email3'] = strip_tags(trim($dadosform['email']));
				$this->dadosform['emailCheck3'] = strip_tags(trim($dadosform['emailCheck']));
				$this->dadosform['cpf3'] = strip_tags(trim($dadosform['cpf']));
				break;
		}
	}

	private function validaDadosFormContato() {
		if (!count($this->dadosform['interesse']) && !count($this->dadosform['interesse2']) && !count($this->dadosform['interesse3'])) {
			$erroform[] = 'Selecione ao menos uma área de interesse';
			$this->campos_erro[] = 'interesse';
		}
		if (!$this->dadosform['nome'] && !$this->dadosform['nome2'] && !$this->dadosform['nome3']) {
			$erroform[] = 'Nome';
			$this->campos_erro[] = 'nome';
		}
		if (!$this->dadosform['email'] && !$this->dadosform['email2'] && !$this->dadosform['email3']) {
			$erroform[] = 'Email';
			$this->campos_erro[] = 'email';
		}
		elseif (!Util::checkEmail($this->dadosform['email']) && !Util::checkEmail($this->dadosform['email2']) && !Util::checkEmail($this->dadosform['email3'])) {
			$erroform[] = 'O seu E-mail está em um formato incorreto';
			$this->campos_erro[] = 'email';
		}
		elseif (($this->dadosform['email'] != $this->dadosform['emailCheck']) && ($this->dadosform['email2'] != $this->dadosform['emailCheck2']) && ($this->dadosform['email3'] != $this->dadosform['emailCheck3'])) {
			$erroform[] = 'E-mail e sua repetição estão diferentes';
			$this->campos_erro[] = 'email';
		}
		if (count($erroform)) {
			throw new Exception ('<em>'.implode('</em>, <em>', $erroform).'</em>');
		}
	}
	
	private function setDadosVO() {
		$this->formvo = new FormularioVO;
		
		switch ($this->dadosform['tipo']) {
			case 'autor':
				$this->formvo->setNome($this->dadosform['nome']);
				$this->formvo->setTelefone($this->dadosform['telefone']);
				$this->formvo->setEmail($this->dadosform['email']);
				$this->formvo->setInteresses($this->dadosform['interesse']);
				$this->formvo->setCPF($this->dadosform['cpf']);
				$this->formvo->setUrl(Util::geraUrlTitulo($this->dadosform['nome']));
				$this->formvo->setCodTipo(2);
				break;
			case 'grupo':
				$this->formvo->setNome($this->dadosform['nome2']);
				$this->formvo->setTelefone($this->dadosform['telefone2']);
				$this->formvo->setEmail($this->dadosform['email2']);
				$this->formvo->setInteresses($this->dadosform['interesse2']);
				$this->formvo->setCPF($this->dadosform['cpf2']);
				$this->formvo->setUrl(Util::geraUrlTitulo($this->dadosform['nome2']));
				$this->formvo->setCodTipo(3);
				break;
			case 'colaborador':
				$this->formvo->setNome($this->dadosform['nome3']);
				$this->formvo->setTelefone($this->dadosform['telefone3']);
				$this->formvo->setEmail($this->dadosform['email3']);
				$this->formvo->setInteresses($this->dadosform['interesse3']);
				$this->formvo->setCPF($this->dadosform['cpf3']);
				$this->formvo->setUrl(Util::geraUrlTitulo($this->dadosform['nome3']));
				$this->formvo->setCodTipo(1);
				break;
		}
		$this->formvo->setSituacao(2); // inativo
	}
	
	private function sendCadastro() {
		$codusuario = $this->formdao->cadastrar($this->formvo);
		return $codusuario;
	}

	private function sendContato() {
		
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");

		$mensagem  = "";
		$mensagem .= "<p>Novo cadastro pelo formulário.</p><br />";

		switch ($this->dadosform['tipo']) {
			case 'autor':
				$mensagem .= "<p><strong>Tipo:</strong> Autor</p>";
				$mensagem .= "<p><strong>Áreas de interesse:</strong> ".$this->getAreasInteresse($this->dadosform['interesse'])."</p>\n";
				$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome']."</p>\n";
				$mensagem .= "<p><strong>Telefone:</strong> ".$this->dadosform['telefone']."</p>\n";
				$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email']."</p>\n";
				$mensagem .= "<p><strong>CPF:</strong> ".$this->dadosform['cpf']."</p>\n";
				break;
			case 'grupo':
				$mensagem .= "<p><strong>Tipo:</strong> Grupo</p>";
				$mensagem .= "<p><strong>Áreas de interesse:</strong> ".$this->getAreasInteresse($this->dadosform['interesse2'])."</p>\n";
				$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome2']."</p>\n";
				$mensagem .= "<p><strong>Telefone:</strong> ".$this->dadosform['telefone2']."</p>\n";
				$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email2']."</p>\n";
				$mensagem .= "<p><strong>CPF:</strong> ".$this->dadosform['cpf2']."</p>\n";
				break;
			case 'colaborador':
				$mensagem .= "<p><strong>Tipo:</strong> Colaborador</p>";
				$mensagem .= "<p><strong>Áreas de interesse:</strong> ".$this->getAreasInteresse($this->dadosform['interesse3'])."</p>\n";
				$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome3']."</p>\n";
				$mensagem .= "<p><strong>Telefone:</strong> ".$this->dadosform['telefone3']."</p>\n";
				$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email3']."</p>\n";
				$mensagem .= "<p><strong>CPF:</strong> ".$this->dadosform['cpf3']."</p>\n";
				break;
		}

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::getUrlSite(), $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail("Novo cadastro pelo formulário", 'Pernambuco Nação Cultural', 'contato@penc.pe.gov.br', $texto_email, 'penc@intercidadania.org.br');
		Util::enviaemail("Novo cadastro pelo formulário", 'Pernambuco Nação Cultural', 'contato@penc.pe.gov.br', $texto_email, 'fundarpe@gmail.com');
		Util::enviaemail("Novo cadastro pelo formulário", 'Pernambuco Nação Cultural', 'contato@penc.pe.gov.br', $texto_email, 'billy.blay@gmail.com');
		//Util::enviaemail("Novo cadastro pelo formulário", 'PENC', 'contato@penc.pe.gov.br', $texto_email, 'marcel@kmf.com.br');
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo])) {
			if (($nomecampo == 'interesse') || ($nomecampo == 'interesse2') || ($nomecampo == 'interesse3'))
				return $this->dadosform[$nomecampo];
			else
				return htmlentities(stripslashes($this->dadosform[$nomecampo]));
		}
		elseif (($nomecampo == 'interesse') || ($nomecampo == 'interesse2') || ($nomecampo == 'interesse3'))
			return array();
		return "";
	}

	public function verificaErro($campo) {
		return in_array($campo, $this->campos_erro);
	}

	private function getAreasInteresse($interesses) {
		$interesses_texto = array();
		foreach ($interesses as $cod)
			$interesses_texto[] = $this->areas_interesse[$cod];
		return implode(', ', $interesses_texto);
	}
}