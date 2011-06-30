<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/LoginDAO.php");

class LoginBO {

	private $logindao = null;
	public $dados = array();
	private $checalogin = array();

	public function __construct() {
		$this->logindao = new LoginDAO;
	}

	private function setDadosLogin(&$dados) {
		$this->dados = $dados;
		$this->dados['login'] = trim($this->dados['login']);
		$this->dados['senha'] = trim($this->dados['senha']);
		$this->dados['redir'] = $this->dados['redir'];
	}

	private function validaDados() {
		$this->erro = array();
		if (!$this->dados['login']) {
			$this->erro['emptylogin'] = true;
		}
		if (!$this->dados['senha']) {
			$this->erro['emptysenha'] = true;
		}
		if ($this->dados['login'] && $this->dados['senha']) {
			$this->checalogin = $this->logindao->checaLogin($this->dados['login'], $this->dados['senha']);
			if ($this->checalogin['login'] != $this->dados['login']) {
				$this->erro['errologin'] = true;
			}
			if ($this->checalogin['senha'] != $this->dados['senha']) {
				$this->erro['errosenha'] = true;
			}
		}
	}

	public function logar(&$dados) {
		$this->setDadosLogin($dados);
		$this->validaDados();
		if (!count($this->erro)) {
			$this->executaLogin();
		}
		return $this->erro;
	}

	private function executaLogin() {
		$this->sessaoPENC();
		$_SESSION['logado'] = true;
		$_SESSION['logado_dados'] = $this->checalogin;
		$_SESSION['logado_cod'] = $this->checalogin['cod'];
		
		$_SESSION['logado_sessao_time'] = (time() + 3600);
		
		/*$_SESSION['logado_como'] = intval($this->checalogin['logado_como']);
		$_SESSION['nome'] = $this->checalogin['nome'];*/
		$redirecionar = 'index.php';
		
		if ($this->dados['redir'])
			$redirecionar = base64_decode($this->dados['redir']);
		
		Header("Location: ".$redirecionar);
		exit();
	}

	public function getLogin() {
		return $this->dados['login'];
	}
	
	public function getRedir() {
		return $this->dados['redir'];
	}

	public function verificaLogin() {
	    $this->sessaoPENC();
		
		if (isset($_SESSION['logado'])) {
			if ($_SESSION['logado_sessao_time'] < time())
				$this->sairLogin();	
		  	else
		  		$_SESSION['logado_sessao_time'] = (time() + 3600);		
		}
		
		if (!isset($_SESSION['logado'])) {
			
			// http://penc.intereditor.com.br/agenda.php
			
			if ($_SERVER['REQUEST_URI'])
				$redir = '?redir='.base64_encode($_SERVER['REQUEST_URI']);
			
			Header("Location: login.php".$redir);
			die;
		}
	}
	
	public function verificaLogado() {
	    $this->sessaoPENC();
		if (isset($_SESSION['logado'])) {
			Header("Location: index.php");
			die;
		}
	}

	public function verificaAdmin() {
		//if ($_SESSION['logado_como'] != 'admin') {
		if (($_SESSION['logado_dados']['nivel'] != 7) && ($_SESSION['logado_dados']['nivel'] != 8)) {
			Header("location: index.php");
			exit();
		}
	}

	private function sessaoPENC() {
		session_name('session_penc');
		session_start();
	}

	public function sairLogin() {
		$this->sessaoPENC();
		session_destroy();
		Header("Location: ".ConfigVO::URL_SITE);
		die;
	}

	public function lembrarAcesso($dados) {
		$this->lembrar = $this->logindao->lembrarAcesso($dados['buscarpor'], $dados['lembrar']);
		
		if ($this->lembrar)
			$this->enviaEmail($this->lembrar, $dados['lembrar'], $dados['buscarpor']);
		else
			$this->erro = true;
			
		return $this->erro;
	}

	private function enviaEmail($dados, $lembrar, $email) {
		include(ConfigVO::getDirUtil().'/htmlMimeMail5/htmlMimeMail5.php');

		$mail = new htmlMimeMail5();
    	$texto_email = file_get_contents(ConfigVO::DIR_SITE.'/portal/templates/template_email.html');


		$texto_email = eregi_replace("<!--%DATA_HORA%-->", date('d/m/Y, H:i:s'), $texto_email);
		$texto_email = eregi_replace("<!--%IP%-->", $_SERVER['REMOTE_ADDR'], $texto_email);

    	//if ($lembrar == 'senha') {
			$conteudo .= "<p>Esqueceu sua senha? A Equipe iTeia lembra pra você!</p>";
			$conteudo .= "<p>Seu login: ".$dados['login']."<br/>";
			$conteudo .= "Sua senha: ".$dados['senha']."</p>";
			//$conteudo .= "<p><strong>".$dados['nome']."</strong> abaixo segue sua senha de acesso ao <strong>Penc</strong>.</p>\n";
			//$conteudo .= "<p>Senha: <b>".$dados['senha']."</b></p><br />\n";
    	//}
		//if ($lembrar == 'login') {
			//$conteudo .= "<p><strong>".$dados['nome']."</strong> abaixo segue seu login de acesso ao <strong>Penc</strong>.</p>\n";
			//$conteudo .= "<p>Login: <b>".$dados['login']."</b></p><br />\n";
    	//}
    	
		$conteudo .= "<br/>Para sua segurança não revele sua senha a ninguém.<br/>";
		$conteudo .= "Esperamos seu post!";
		
		$conteudo .= "<br/>---";
		$conteudo .= "<br/>Equipe iTeia";
		$conteudo .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
		$conteudo .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";
		
		//$conteudo .= "<p>O seu nome de usuário e senha de acesso ao gerenciador de conteúdo:</p>\n<p>Usuário: <strong>".$dados['login']."</strong><br />\nSenha: <strong>".$dados['senha']."</strong><br />\n</p>\n";
    	
		$texto_email = eregi_replace("<!--%URL%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $conteudo, $texto_email);

		if ($lembrar == 'login') {
			$dados['email'] = $email;
		}

		$mail->setHtml($texto_email);
		$mail->setReturnPath($dados['email']);
		$mail->setFrom("\"Portal iTEIA\" <gerenciador@iteia.org.br>");
		$mail->setSubject("[iTEIA] - Esqueceu sua senha?");
    	$mail->send(array($dados['email']));
	}

	public function lembrarEmail($dados) {
		include(ConfigVO::getDirUtil().'/htmlMimeMail5/htmlMimeMail5.php');

    	$mail = new htmlMimeMail5();
    	$texto_email = file_get_contents(ConfigVO::DIR_SITE.'/portal/templates/template_email.html');

		$conteudo .= "Nome: ".strip_tags(trim($dados['nome']))."<br /><br />\n";
		$conteudo .= "Email: ".strip_tags(trim($dados['email']))."<br /><br />\n";

		$texto_email = eregi_replace("<!--%URL_IMG%-->", ConfigVO::getUrlImg(), $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $conteudo, $texto_email);

		$mail->setHtml($texto_email);
		$mail->setReturnPath(ConfigVO::EMAIL);
		$mail->setFrom("\"[iTEIA] - Gerenciador\" <".ConfigVO::EMAIL.">");
		$mail->setSubject("iTEIA] - Esqueceu seu email?");
    	$mail->send(array(ConfigVO::EMAIL));
	}
}