<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/BuscaDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class BuscaBO {

    private $buscadao = null;

    public function __construct() {
        $this->buscadao = new BuscaDAO;
    }

    public function getResultadoBusca($get, $inicial, $mostrar) {
        return $this->buscadao->getResultadoBusca($get, $inicial, $mostrar);
    }
	
	public function getResultadoBuscaFiltro($get) {
        return $this->buscadao->getResultadoBuscaFiltro($get,$icanal);
    }
	
	public function getListaEstados(){
	 return $this->buscadao->getListaTodosEstados($get);
	}
	
	public function getListaCidades(){
	
	}
	
    public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return ' erro';
		else
			return '';
	}
	
		public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}

}