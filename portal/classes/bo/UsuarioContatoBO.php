<?php
require('classes/vo/ConfigPortalVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/util/Util.php');

class UsuarioContatoBO {

	private $dadosform = array();
	private $erros_campos = array();
	private $usrdao = null;
	
	public function __construct() {
        $this->usrdao = new UsuarioDAO;
    }

	public function getDadosUsuario($codusuario) {
		return $this->usrdao->getUsuarioDados($codusuario);
	}

    public function enviaContato($dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (exception $e) {
			throw $e;
		}
		$this->sendContato();
	}

	private function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform['codusuario'] = strip_tags(trim($this->dadosform['codusuario']));
		$this->dadosform['nome'] = strip_tags(trim($this->dadosform['nome']));
		$this->dadosform['email'] = strip_tags(trim($this->dadosform['email']));
		$this->dadosform['mensagem'] = substr(strip_tags(trim(stripslashes($this->dadosform['mensagem']))), 0, 3000);
	}

	private function validaDados() {
		if (!$this->dadosform['mensagem']) $this->erros_campos[] = '<a href="#mensagem">Escreva sua <strong>mensagem</strong></a>';
		if (!$this->dadosform['nome']) $this->erros_campos[] = '<a href="#seu-nome">Escreva seu <strong>nome</strong></a>';
		if (!$this->dadosform['email']) $this->erros_campos[] = '<a href="#seu-email">Adicione um <strong>e-mail</strong> válido</a>';
		
		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email'])
			$this->erros_campos[] = '<a href="#seu-email">Adicione um <strong>e-mail</strong> válido</a>';

        if (count($this->erros_campos))
			throw new Exception(' ');
	}

	private function sendContato() {
		$texto_email = file_get_contents(ConfigVO::getDirSite().'portal/templates/template_email.html');
		$dados = $this->usrdao->getUsuarioDados($this->dadosform['codusuario']);
		
		$mensagem  = '';
		$mensagem .= "<p>Ol&aacute;!</p>";
		$mensagem .= "<p>Você recebeu um email de contato do portal iTEIA.</p><br />";

		$mensagem .= "<p>Dados do contato:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome'];
		$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email'];
		$mensagem .= "<p><strong>Mensagem:</strong> ".$this->dadosform['mensagem'].'<br />';

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::getUrlSite(), $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail("Contato - Portal iTEIA", 'iTEIA', ConfigVO::EMAIL, $texto_email, $dados['email']);
	}

	public function getCamposErros() {
		return $this->erros_campos;	
	}
	
}