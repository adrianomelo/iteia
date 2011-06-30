<?php
include_once("UsuarioBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/UsuarioVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");

class SenhaBO extends UsuarioBO {
	
	private $senhavo = null;
	private $senhadao = null;
	
	public function __construct() {
		$this->senhadao = new UsuarioDAO;
	}
	
	protected function setDadosForm(&$dadosform) {
		$this->dadosform['codusuario'] = (int)$dadosform['codusuario'];
		$this->dadosform["senha"] = $dadosform["senha"];
		$this->dadosform["novasenha"] = $dadosform["novasenha"];
		$this->dadosform["novasenha2"] = $dadosform["novasenha2"];
	}
	
	protected function validaDados() {
		if (!$this->dadosform["senha"]) $this->erro_campos[] = "senha";
		if (!$this->dadosform["novasenha"]) $this->erro_campos[] = "novasenha";
		if (!$this->dadosform["novasenha2"]) $this->erro_campos[] = "novasenha2";
		
		if ($this->dadosform["senha"] && !$this->senhadao->verificarSenha($this->dadosform['codusuario'])) {
			$this->erro_campos[] = "senha";
		}
		if ($this->dadosform["novasenha"] != $this->dadosform["novasenha2"]) {
			$this->erro_campos[] = "novasenha";
			$this->erro_campos[] = "novasenha2";
		}
        if ($this->dadosform["novasenha"] && strlen($this->dadosform["novasenha"]) < 6) {
			$this->erro_campos[] = "novasenha";
			$this->erro_campos[] = "novasenha2";
		}

		if (count($this->erro_campos)) {
			throw new Exception("<br />\n".implode("<br />\n", $this->erro_mensagens));
		}
	}
	
	protected function setDadosVO() {
		$this->senhavo = new UsuarioVO;
		$this->senhavo->setCodUsuario((int)$this->dadosform["codusuario"]);
		$this->senhavo->setSenha($this->dadosform["novasenha"]);
	}
	
	protected function editarDados() {
		$this->senhadao->atualizaSenha($this->senhavo);
		$this->dadosform = array();
		return true;
	}
	
}