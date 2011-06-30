<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/util/Util.php');

class ConteudoEmailBO {

	private $email_destino = array();

    public function actionEnviar($dadosform) {
		$this->setDadosFormEnviar($dadosform);
		try {
			$this->validaDadosFormEnviar();
		} catch (exception $e) {
			throw $e;
		}
		return $this->sendEnviar();
	}

	private function setDadosFormEnviar(&$dadosform) {
		$this->dadosform['codconteudo'] = strip_tags(trim($dadosform['cod']));
		$this->dadosform['nome'] = strip_tags(trim($dadosform['nome']));
		$this->dadosform['email'] = strip_tags(trim($dadosform['email']));
		$this->dadosform['email2'] = strip_tags(trim($dadosform['email2']));
		$this->dadosform['comentario'] = substr(strip_tags(trim(stripslashes($dadosform['comentario']))), 0, 3000);
	}

	private function validaDadosFormEnviar() {
		if (!$this->dadosform['nome']) {
			$erroform[] = 'Nome';
		}
		if (!$this->dadosform['email']) {
			$erroform[] = 'Email';
		}
		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email']) {
			$erroform[] = 'O seu E-mail está em um formato incorreto';
		}
		$emails_destino1 = array();
		$emails_destino = array();
		if (!$this->dadosform['email2'])
			$erroform[] = 'Enviar para';
		else {
			$emails_destino1 = explode(";", $this->dadosform['email2']);
			for ($i = 0; $i < sizeof($emails_destino1); $i++) {
				//if (Util::checkEmail($emails_destino1[$i]))
					$this->email_destino[] = trim($emails_destino1[$i]);
			}
			if (!count($this->email_destino))
				$erroform[] = 'Enviar para';
		}
		if (count($erroform)) {
			throw new Exception ('<em>'.implode('</em>, <em>', $erroform).'</em>');
		}
	}

	private function sendEnviar() {
		require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");

		$contdao = new ConteudoExibicaoDAO;
		$conteudo = $contdao->getConteudoResumido($this->dadosform['codconteudo']);

		$texto_email = file_get_contents(ConfigVO::DIR_SITE."portal/templates/template_conteudo_email.html");
		
		$mensagem  = "";
		
		$texto_email = eregi_replace("<!--%AUTOR_NOTICIA%-->", $this->dadosform['nome'], $texto_email);
		
		$texto_email = eregi_replace("<!--%AUTOR_EMAIl%-->", $this->dadosform['email'], $texto_email);
		
		if ($this->dadosform['comentario'])
			$comentario = "<strong>Coment&aacute;rio do remetente:</strong> <span style=\"color:#d21301; font-style:italic\" >".stripslashes($this->dadosform['comentario'])."</span>\n";
		else
			$comentario = "<strong>O remetente n&atilde;o fez coment&aacute;rios sobre essa mat&eacute;ria.</strong>\n";
		
		//$autores = $contdao->getAutoresConteudo($this->dadosform['codconteudo']);
		$autores = $contdao->getAutoresFichaTecnicaConteudo($this->dadosform['codconteudo']);
		
		$texto_email = eregi_replace("<!--%AUTORES%-->", $autores, $texto_email);
		
		$texto_email = eregi_replace("<!--%COMENTARIO%-->", $comentario, $texto_email);

		$texto_email = eregi_replace("<!--%HORA%-->", date('d.m.Y - H:i', strtotime($conteudo['datahora'])), $texto_email);

		$texto_email = eregi_replace("<!--%TITULO%-->", $conteudo['titulo'], $texto_email);

		$texto_email = eregi_replace("<!--%ASSINATURA%-->", $conteudo['assinatura'], $texto_email);

		$texto_email = eregi_replace("<!--%TEXTO%-->", $conteudo['descricao'], $texto_email);
		
		$texto_email = eregi_replace("<!--%URL_CONTEUDO%-->", ConfigVO::URL_SITE.$conteudo['url_titulo'], $texto_email);
		
		$imagem_html = "";
		if ($conteudo['imagem']) {
			
			$caminho = ($conteudo['tipo'] == 2) ? ConfigVO::getDirGaleria() : ConfigVO::getDirFotos();
			
			$valores = getimagesize($caminho.$conteudo['imagem']);
			$width = min($valores[0], 124);
			$size = 6;
			if ($width < 124) $size = 0;
				
            $imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$conteudo['imagem']."&amp;tipo=".$conteudo['tipo']."&amp;s=".$size;
			
			$imagem_html .= "<div class=\"fotografia\" style=\"width: ".$width."px; padding:5px 10px; margin:0 15px 7px 0;	float: left; border: 1px solid #d6d6d6; font-size: 9px;	background:#f9f9f9;\">".$conteudo['foto_credito']."<br />\n";
          	$imagem_html .= "<img src=\"".$imagem."\" alt=\"Descrição para a fotografia.\" /><br />\n";
          	$imagem_html .= "<p>".$conteudo['foto_legenda']."</p>\n";
        	$imagem_html .= "</div>\n";
		}
		
		$texto_email = eregi_replace("<!--%IMAGEM_NOTICIA%-->", $imagem_html, $texto_email);

		$texto_email = eregi_replace("<!--%URL_IMG%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		
		switch ($conteudo['cod_formato']) {
			case 1: $complementa = 'texto'; break;
			case 2: $complementa = 'imagem'; break;
			case 3: $complementa = 'áudio'; break;
			case 4: $complementa = 'vídeo'; break;
		}
		
		Util::enviaemail($this->dadosform['nome']." enviou uma ".$complementa." para você", 'Portal Pernambuco Nação Cultural', 'contato@fundarpe.pe.gov.br', $texto_email, $this->email_destino);
		return $this->email_destino;
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}

}