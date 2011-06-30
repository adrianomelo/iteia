<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AgendaVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AgendaDAO.php");

class AgendaEdicaoBO extends ConteudoBO {

    private $agevo = null;
	private $agedao = null;

	public function __construct() {
		$this->agedao = new AgendaDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
	    $this->dadosform = $dadosform;
		$this->dadosform["codagenda"] = (int)$this->dadosform["codagenda"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform['codcolaborador'] = (int)$this->dadosform['codcolaborador'];
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 200);
		//$this->dadosform["descricao"] = substr(trim($this->dadosform["descricao"]), 0, 200);
		$this->dadosform["descricao"] = trim($this->dadosform["descricao"]);
		$this->dadosform["local"] = substr(trim($this->dadosform["local"]), 0, 200);
		$this->dadosform["endereco"] = substr(trim($this->dadosform["endereco"]), 0, 200);
		$this->dadosform["cidade"] = substr(trim($this->dadosform["cidade"]), 0, 200);
		$this->dadosform["telefone"] = substr(trim($this->dadosform["telefone"]), 0, 14);
		$this->dadosform["valor"] = trim($this->dadosform["valor"]);
        $this->dadosform["data_inicial"] = substr(trim($this->dadosform["data_inicial"]), 0, 10);
		$this->dadosform["data_final"] = substr(trim($this->dadosform["data_final"]), 0, 10);
        $this->dadosform["hora_inicial"] = substr(trim($this->dadosform["hora_inicial"]), 0, 5);
		$this->dadosform["hora_final"] = substr(trim($this->dadosform["hora_final"]), 0, 5);
		$this->dadosform["site"] = trim(str_replace("http://", "", $this->dadosform["site"]));
		$this->dadosform["tags"] = $this->dadosform["tags"];
	}

	protected function validaDados() {

		if (!$this->dadosform["titulo"])
			$this->erro_campos[] = "titulo";

        if (!$this->dadosform["descricao"])
			$this->erro_campos[] = "descricao";

        if (!$this->dadosform["local"])
			$this->erro_campos[] = "local";

        if (!$this->dadosform["endereco"])
			$this->erro_campos[] = "endereco";

        if (!$this->dadosform["cidade"])
			$this->erro_campos[] = "cidade";

		if (!$this->dadosform["data_inicial"])
			$this->erro_campos[] = "data_inicial";

		if (!$this->dadosform["hora_inicial"])
			$this->erro_campos[] = "hora_inicial";

		if ($this->dadosform["data_inicial"] && !checkdate(substr($this->dadosform["data_inicial"], 3, 2), substr($this->dadosform["data_inicial"], 0, 2), substr($this->dadosform["data_inicial"], 6, 2))) {
			$this->erro_campos[] = "data_inicial";
		}
		if ($this->dadosform["data_final"] && !checkdate(substr($this->dadosform["data_final"], 3, 2), substr($this->dadosform["data_final"], 0, 2), substr($this->dadosform["data_final"], 6, 2))) {
			$this->erro_campos[] = "data_final";
		}

        if ($this->dadosform["hora_inicial"]) {
			if ((substr($this->dadosform["hora_inicial"], 0, 2) > 23) || (substr($this->dadosform["hora_inicial"], 0, 2) < 0) || (substr($this->dadosform["hora_inicial"], 3, 2) > 59) || (substr($this->dadosform["hora_inicial"], 3, 2) < 0)) $this->erro_campos[] = "hora_inicial";
 		}

		if ($this->dadosform["hora_final"]) {
            if ((substr($this->dadosform["hora_final"], 0, 2) > 23) || (substr($this->dadosform["hora_final"], 0, 2) < 0) || (substr($this->dadosform["hora_final"], 3, 2) > 59) || (substr($this->dadosform["hora_final"], 3, 2) < 0))	$this->erro_campos[] = "hora_final";
		}

        if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception("<br />\n".implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->agevo = new AgendaVO;
		$this->agevo->setCodConteudo((int)$this->dadosform["codagenda"]);
		
		$this->agevo->setCodAutor($_SESSION['logado_cod']);
		if ($_SESSION['logado_dados']['nivel'] >= 5)
			$this->agevo->setCodColaborador($_SESSION['logado_dados']['cod_colaborador']);
		
		$this->agevo->setTitulo($this->dadosform["titulo"]);
		$this->agevo->setDescricao($this->dadosform["descricao"]);
		$this->agevo->setLocal($this->dadosform["local"]);
		$this->agevo->setEndereco($this->dadosform["endereco"]);
		$this->agevo->setCidade($this->dadosform["cidade"]);
		$this->agevo->setTelefone($this->dadosform["telefone"]);
		$this->agevo->setValor($this->dadosform["valor"]);
		$this->agevo->setSite($this->dadosform["site"]);
		$this->agevo->setDataInicial(substr($this->dadosform["data_inicial"], 6, 4)."-".substr($this->dadosform["data_inicial"], 3, 2)."-".substr($this->dadosform["data_inicial"], 0, 2));
		$this->agevo->setDataFinal(substr($this->dadosform["data_final"], 6, 4)."-".substr($this->dadosform["data_final"], 3, 2)."-".substr($this->dadosform["data_final"], 0, 2));
		$this->agevo->setHoraInicial(substr($this->dadosform["hora_inicial"], 0, 2).":".substr($this->dadosform["hora_inicial"], 3, 2).":00");
		$this->agevo->setHoraFinal(substr($this->dadosform["hora_final"], 0, 2).":".substr($this->dadosform["hora_final"], 3, 2).":59");

		if (!(int)$this->dadosform["codagenda"]) {
			//if ($_SESSION['logado_como'] == 1)
			if ($_SESSION['logado_dados']['nivel'] == 2)
				$this->agevo->setSituacao(0);
			else
				$this->agevo->setSituacao(1);
		}

		$this->agevo->setPublicado(1);
		$this->agevo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->agevo->setTags(Util::geraTags($this->dadosform['tags']));
	}

	protected function editarDados() {
		if (!$this->agevo->getCodConteudo()) {
			$codagenda = $this->agedao->cadastrar($this->agevo);
		} else {
			$this->agedao->atualizar($this->agevo);
			$codagenda = $this->agevo->getCodConteudo();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgagenda_".$codagenda;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->agedao->atualizarFoto($nomearquivo_final, $codagenda);
		}

		$this->dadosform = array();
		$this->arquivos = array();
		return $codagenda;
	}

	public function setDadosCamposEdicao($codagenda) {
		$agevo = $this->agedao->getAgendaVO($codagenda);
		$this->dadosform["codagenda"] = $agevo->getCodConteudo();
		$this->dadosform["titulo"] = $agevo->getTitulo();
		$this->dadosform["descricao"] = $agevo->getDescricao();
		$this->dadosform["local"] = $agevo->getLocal();
		$this->dadosform["endereco"] = $agevo->getEndereco();
		$this->dadosform["cidade"] = $agevo->getCidade();
		$this->dadosform["telefone"] = $agevo->getTelefone();
		$this->dadosform["valor"] = $agevo->getValor();
		$this->dadosform["site"] = $agevo->getSite();
		$this->dadosform["imagem_visualizacao"] = $agevo->getImagem();
		$this->dadosform["tags"] = $agevo->getTags();

		$this->dadosform["data_inicial"] = "";
		$this->dadosform["data_final"] = "";
		if ($agevo->getDataInicial() && ($agevo->getDataInicial() != "0000-00-00"))
			$this->dadosform["data_inicial"] = date("d/m/Y", strtotime($agevo->getDataInicial()));
		if ($agevo->getDataFinal() && ($agevo->getDataFinal() != "0000-00-00"))
			$this->dadosform["data_final"] = date("d/m/Y", strtotime($agevo->getDataFinal()));

        $this->dadosform["hora_inicial"] = "";
		$this->dadosform["hora_final"] = "";
		//if ($agevo->getHoraInicial() && ($agevo->getHoraInicial() != "00:00:00"))
			$this->dadosform["hora_inicial"] = date("H:i", strtotime($agevo->getHoraInicial()));
		//if ($agevo->getHoraFinal() && ($agevo->getHoraFinal() != "00:00:00"))
			$this->dadosform["hora_final"] = date("H:i", strtotime($agevo->getHoraFinal()));
	}

	public function excluirImagem($cod) {
		$this->agedao->excluirImagem($cod);
	}

	public function getColaboradorConteudo($codagenda) {
		return $this->agedao->getColaboradorConteudo($codagenda);
	}

	public function getConteudoRelacionado($codagenda) {
		return $this->agedao->getConteudoRelacionadoConteudo($codagenda);
	}

}