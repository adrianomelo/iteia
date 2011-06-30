<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');
//include_once('inc_interjornal/classes/util/GlobalSiteUtil.kmf');

class BuscaNewsletterListaBO {

	private $listadao = null;
	protected $dadosform = array();

	public function __construct() {
		$this->listadao = new Newsletter_ListaDAO;
	}

	public function getNewsletterUsuariosListasBusca($dadosget, $inicial, $mostrar) {
		if ((int)$dadosget['acao'] == 1) {
			if (count($dadosget['lista'])) {
				foreach($dadosget['lista'] as $value)
					$this->listadao->apagarTodaLista($value);
			}
			if (count($dadosget['cod'])) {
				foreach($dadosget['cod'] as $value) {
					$dados = explode('_', $value);
					$this->listadao->apagarUsuarioLista($dados[0], $dados[1]);
				}
			}
			Header('location: home_newsletter_listas.php?mostrar='.$dadosget['mostrar'].'&pagina='.$dadosget['pagina'].'&titulo='.$dadosget['titulo'].'&codlista='.$dadosget['codlista']);
			die;
		}
		return $this->listadao->getNewsletterUsuariosListasBusca($dadosget, $inicial, $mostrar);
	}
	
	public function getListasEnvio() {
		return $this->listadao->getListas();
	}
	
}
