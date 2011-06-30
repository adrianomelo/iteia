<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");

class DenunciaBO {

	private $dadosform = array();
	private $erros_campos = array();
	
	public function enviaDenuncia($dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (exception $e) {
			throw $e;
			return 1;
		}
		$this->sendForm();
	}

	private function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform['codconteudo'] = intval($this->dadosform['codconteudo']);
		$this->dadosform['mensagem'] = substr(strip_tags(trim(stripslashes($this->dadosform['mensagem']))), 0, 3000);
		$this->dadosform['tipo'] = intval($this->dadosform['tipo']);
		$this->dadosform['nome'] = strip_tags(trim($this->dadosform['nome']));
		$this->dadosform['email'] = strip_tags(trim($this->dadosform['email']));
	}

	private function validaDados() {
		if (!$this->dadosform['mensagem']) $this->erros_campos[] = '<a href="#denuncia">Descreva sua <strong>denúncia</strong></a>';
		if (!$this->dadosform['nome']) $this->erros_campos[] = '<a href="#seu-nome">Escreva seu <strong>nome</strong></a>';
		if (!$this->dadosform['email']) $this->erros_campos[] = '<a href="#seu-email" class="val-email">Adicione um <strong>e-mail</strong> válido</a>';
		
		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email'])
			$this->erros_campos[] = 'Por favor, adicione um e-mail válido.';

        if (count($this->erros_campos))
			throw new Exception(' ');
	}

	private function sendForm() {
		require(ConfigPortalVO::getDirClassesRaiz().'/dao/ConteudoExibicaoDAO.php');
		$conteudo = new ConteudoExibicaoDAO;

		$dadosconteudo = $conteudo->getDadosConteudo($this->dadosform['codconteudo']);
		$texto_email = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_email.html');

		switch ($this->dadosform['tipo']) {
			case 1: $tipo = 'Conte&uacute;do Impr&oacute;prio'; break;
			case 2: $tipo = 'Conte&uacute;do de Outro Autor'; break;
			case 3: $tipo = 'Conte&uacute;do Derivado de Obra Autoral'; break;
			case 4: $tipo = 'Cadastro de Autor indevido'; break;
			case 5: $tipo = 'Outros'; break;
		}

		$mensagem  = '';
		$mensagem .= "<p><strong>Denúncia:</strong> ".nl2br($this->dadosform['mensagem']);
		$mensagem .= "<p><strong>Tipo de denúncia:</strong> ".$tipo;
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome'];
		$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email'];

		$mensagem .= "<br /><br /><br />";

		$mensagem .= "<p><strong>Dados do Conteúdo:</strong>";
		$mensagem .= "<p><strong>Título:</strong> ".$dadosconteudo['titulo'];
		$mensagem .= "<p><strong>URL:</strong> ".ConfigVO::URL_SITE.$dadosconteudo['url'];

		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		Util::enviaemail('Conteúdo denunciado! - Portal iTEIA', $this->dadosform['email'], ConfigVO::EMAIL, $texto_email, array(ConfigVO::EMAIL));

	}

	public function getCamposErros() {
		return $this->erros_campos;	
	}

}