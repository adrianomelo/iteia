<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AutorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AutorDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");

class PrincipalBO {

	private $dadosusuario = null;
	private $usuariodao = null;

	public function __construct() {
		//if ($_SESSION['logado_como'] == 1) {
			$this->usuariodao = new AutorDAO;
			$this->dadosusuario = $this->usuariodao->getAutorVO($_SESSION['logado_cod']);
		/*} else {
			$this->usuariodao = new ColaboradorDAO;
			$this->dadosusuario = $this->usuariodao->getColaboradorVO($_SESSION['logado_cod']);
		}*/
	}

	public function getUsuarioDados() {
		if ($this->dadosusuario->getImagem()) {
			$dadosusuario['imagem'] = 'exibir_imagem.php?img='.$this->dadosusuario->getImagem().'&amp;tipo=a&amp;s=6';
		} else {
			//if ($_SESSION['logado_como'] == 1) {
				$dadosusuario['imagem'] =  'img/imagens-padrao/autor.jpg';
			/*} else {
				$dadosusuario['imagem'] =  'img/imagens-padrao/colaborador.jpg';
			}*/
		}

		$dadosusuario['cod_usuario'] = $this->dadosusuario->getCodUsuario();
		$dadosusuario['nome'] = $this->dadosusuario->getNome();
		$dadosusuario['url'] = $this->dadosusuario->getUrl();
		$dadosusuario['cidade'] = $this->getCidade($this->dadosusuario->getCodCidade());
		$dadosusuario['estado'] = $this->getEstado($this->dadosusuario->getCodEstado());

		return $dadosusuario;
	}
	
	public function getListaColaboradoresEdicao() {
		return $this->usuariodao->getListaColaboradoresEdicao($_SESSION['logado_dados']['cod']);
	}

	public function getEstado($codestado) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/EstadoDAO.php");
		$estdao = new EstadoDAO;
		return $estdao->getSiglaEstado($codestado);
	}

	public function getCidade($codcidade) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}

	public function getEstatisticasUsuario($codtipo) {
		return $this->usuariodao->getEstatisticasUsuario($codtipo);
	}

}
