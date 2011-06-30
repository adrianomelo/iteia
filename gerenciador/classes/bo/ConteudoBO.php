<?php
include_once(dirname(__FILE__)."/../vo/ConfigGerenciadorVO.php");
include_once("DireitosBO.php");

abstract class ConteudoBO {

	protected $dadosform = array();
	protected $arquivos = array();
	protected $erro_campos = array();
	protected $erro_mensagens = array();
	protected $direitosbo = null;

	public function __construct() {
		$this->direitosbo = new DireitosBO;
	}

	abstract protected function setDadosForm(&$dadosform);
	abstract protected function validaDados();
	abstract protected function setDadosVO();
	abstract protected function editarDados();

	protected function setArquivos(&$arquivos) {
		$this->arquivos = $arquivos;
	}

	public function editar(&$dadosform, &$arquivos) {
		$this->setDadosForm($dadosform);
		$this->setArquivos($arquivos);
		try {
			$this->validaDados();
		} catch (Exception $e) {
			throw $e;
		}
		$this->setDadosVO();
		return $this->editarDados();
	}

	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return " style=\"border:1px solid #FF0000; background:#FFDFDF;\"";
		else
			return "";
	}

	public function setValorCampo($nomecampo, $valor) {
		$this->dadosform[$nomecampo] = $valor;
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}

	public function getListaEstados() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getListaEstados();
	}

	public function getEstado($codestado) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getNomeEstado($codestado);
	}

	public function getListaPaises() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getListaPaises();
	}

	public function getPais($codpais) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/PaisDAO.php");
		$paisdao = new PaisDAO;
		return $paisdao->getNomePais($codpais);
	}

	public function getListaCidades($codestado) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getListaCidades($codestado);
	}

	public function getCidade($codcidade) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}

	public function removerImagensCache($nomearq) {
		foreach (glob(ConfigVO::getDirImgCache()."*".$nomearq."*") as $arquivo)
			unlink($arquivo);
	}

	public function getListaSegmentos() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
		$segdao = new SegmentoDAO;
		return $segdao->getListaSegmentosCadastro();
	}
	
	public function getListaSubAreas() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
		$segdao = new SegmentoDAO;
		return $segdao->getListaSubAreasCadastro();
	}
	
	public function getListaAtividades() {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");
		$atvdao = new AtividadeDAO;
		return $atvdao->getListaAtividades(array());
	}

	public function getListaClassificacao($codformato) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ClassificacaoDAO.php");
		$clasdao = new ClassificacaoDAO;
		return $clasdao->getListaClassificacoes($codformato);
	}

	protected function setSessionAutoresFicha($codconteudo, &$contdao, &$sessao_id) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AtividadeDAO.php");
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CadastroDAO.php");
		
		$autordao = new AutorDAO;
		$estadodao = new EstadoDAO;
		$ativdao = new AtividadeDAO;
		$caddao = new CadastroDAO;
		
		$lista_ficha = $contdao->getAutoresFicha($codconteudo);
		foreach ($lista_ficha as $ficha) {
			$autorvo = $autordao->getAutorVO($ficha['cod_usuario']);
			$_SESSION['sess_conteudo_autores_ficha'][$sessao_id][$ficha['cod_usuario']] = array(
				'codautor' => $ficha['cod_usuario'],
				'nome' => $autorvo->getNome(),
				'wiki' => $caddao->checaAutorWiki($ficha['cod_usuario']),
				'atividade' => $ficha['cod_atividade'],
				'nome_atividade' => $ativdao->getAtividade($ficha['cod_atividade']),
				'estado' => $estadodao->getSiglaEstado($autorvo->getCodEstado()),
				'descricao' => $autorvo->getDescricao(),
				'falecido' => Util::iif($autorvo->getDataFalecimento() != '0000-00-00', 1, 0),
			);
		}
	}
}
