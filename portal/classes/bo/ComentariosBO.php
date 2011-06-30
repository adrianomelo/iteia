<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ComentariosVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ComentariosDAO.php");

class ComentariosBO {
	
    private $dadosform = array();
    private $comentdao = null;
	private $erros_campos = array();

    public function __construct() {
        $this->comentdao = new ComentariosDAO;
    }
	
    public function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
        $this->dadosform['cod_conteudo'] = (int)$this->dadosform['cod_conteudo'];
        $this->dadosform['nome'] = strip_tags(trim($this->dadosform['nome']));
        $this->dadosform['email'] = strip_tags(trim($this->dadosform['email']));
        $this->dadosform['site'] = strip_tags(trim($this->dadosform['site']));
        $this->dadosform['comentario'] = substr(strip_tags(trim($this->dadosform['comentario'])), 0, 2000);
    }

    private function validaDados() {
		if (!$this->dadosform['comentario']) $this->erros_campos[] = '<li><a href="#comentario">Escreva seu <strong>comentário</strong></a></li>';
		if (!$this->dadosform['nome']) $this->erros_campos[] = '<li><a href="#seu-nome">Escreva seu <strong>nome</strong></a></li>';
		if (!$this->dadosform['email']) $this->erros_campos[] = '<li><a href="#seu-email">Adicione um <strong>e-mail</strong> válido</a></li>';
		
		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email'])
			$this->erros_campos[] = 'Por favor, adicione um e-mail válido.';

        if (count($this->erros_campos))
			throw new Exception(' ');
    }

    public function inserirComentario(&$dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDados();
		} catch (exception $e) {
			throw $e;
			return 1;
		}
		$this->setDadosCadastraComentarios();
	}

    private function setDadosCadastraComentarios() {
        $comentvo = new ComentariosVO;
        $comentvo->setCodConteudo($this->dadosform['cod_conteudo']);
        $comentvo->setNome($this->dadosform['nome']);
        $comentvo->setEmail($this->dadosform['email']);
        $comentvo->setSite($this->dadosform['site']);
        $comentvo->setComentario($this->dadosform['comentario']);
        $this->comentdao->cadastrarComentario($comentvo);
        $this->dadosform = array();
    }
	
	public function getCamposErros() {
		return $this->erros_campos;	
	}
	
	public function getComentarios($codconteudo) {
        return $this->comentdao->getComentarios($codconteudo);
    }

}
