<?php
require('classes/vo/ConfigPortalVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/util/Util.php');

class IndiqueBO {

	private $campos_erro = array();
	private $lista_emails = array();

    public function indicar($dadosform) {
		$this->setDadosFormContato($dadosform);
		try {
			$this->validaDadosFormContato();
		} catch (exception $e) {
			throw $e;
		}
		$this->enviaIndicacao();
	}

	private function setDadosFormContato(&$dadosform) {
		$this->dadosform['email2'] = strip_tags(trim($dadosform['email2']));
		$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
		$this->dadosform['email'] = strip_tags(trim($dadosform['email']));
		$this->dadosform['comentario'] = substr(strip_tags(trim(stripslashes($dadosform['comentario']))), 0, 3000);
	}

	private function validaDadosFormContato() {
		if (!$this->dadosform['email2']) {
			$erroform[] = 'Indicar para';
			$this->campos_erro[] = 'email2';
		}
		else {
			$lista_emails_prov = explode(',', $this->dadosform['email2']);
			if (!count($lista_emails_prov))
				$this->campos_erro[] = 'email2';
			else {
				foreach ($lista_emails_prov as $email) {
					//if (Util::checkEmail($email))
						$this->lista_emails[] = $email;
				}
				if (!count($this->lista_emails))
					$this->campos_erro[] = 'email2';
			}
		}
		if (!$this->dadosform['nome']) {
			$erroform[] = 'Nome';
			$this->campos_erro[] = 'nome';
		}
		if (!$this->dadosform['email']) {
			$erroform[] = 'Email';
			$this->campos_erro[] = 'email';
		}
		elseif (!Util::checkEmail($this->dadosform['email'])) {
			$erroform[] = 'O seu E-mail está em um formato incorreto';
			$this->campos_erro[] = 'email';
		}
		if (!$this->dadosform['comentario']) {
			$erroform[] = 'Comentário';
			$this->campos_erro[] = 'comentario';
		}
		if (count($erroform)) {
			throw new Exception ('<em>'.implode('</em>, <em>', $erroform).'</em>');
		}
	}

	private function enviaIndicacao() {
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_indique_site.html");

		/*
		$mensagem  = "";
		$mensagem .= "<p>Ol&aacute;!</p>";
		$mensagem .= "<p>".$this->dadosform['nome']." indicou este site para você.</p><br />";

		$mensagem .= "<p>Dados do contato:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome']."</p>";
		$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email']."</p>";
		$mensagem .= "<p><strong>Comentário:</strong> ".$this->dadosform['comentario']."</p>";
		$mensagem .= "<h3>Portal PENC</h3><br />\n";
	    $mensagem .= "<p>Acesse: <a href=\"".ConfigVO::getUrlSite()."\">".ConfigVO::getUrlSiteSemHttp()."</p>\n";
	    */
	    
	    $texto_email = eregi_replace("<!--%AUTOR_NOTICIA%-->", $this->dadosform['nome'], $texto_email);
		
		$texto_email = eregi_replace("<!--%AUTOR_EMAIl%-->", $this->dadosform['email'], $texto_email);
		
		if ($this->dadosform['comentario'])
			$comentario = "<strong>Coment&aacute;rio do remetente:</strong> <span style=\"color:#d21301; font-style:italic\" >".stripslashes($this->dadosform['comentario'])."</span>\n";
		else
			$comentario = "<strong>O remetente n&atilde;o fez coment&aacute;rios.</strong>\n";
			
		$texto_email = eregi_replace("<!--%COMENTARIO%-->", $comentario, $texto_email);

		$texto_email = eregi_replace("<!--%URL_IMG%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		
		foreach ($this->lista_emails as $email)
			Util::enviaemail($this->dadosform['nome']." convidou você para conhecer o Portal Pernambuco Nação Cultural", 'Portal Pernambuco Nação Cultural', ConfigVO::getEmail(), $texto_email, $email);
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return htmlentities(stripslashes($this->dadosform[$nomecampo]));
		return "";
	}

	public function verificaErro($campo) {
		return in_array($campo, $this->campos_erro);
	}
}
