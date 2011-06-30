<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/BuscaDadosVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/BuscaCacheDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');

abstract class BuscaCacheBO {

    protected $id = '';
	protected $buscadao = null;
	protected $buscavo = null;
	protected $expiracao = 10800;
	protected $resultado_busca = null;
	protected $mostrar = 10;
	protected $autoriza_busca = false;
	protected $obriga_palavra = false;
	protected $pagina = 0;
	protected $paginas = 0;
	protected $total = 0;
	protected $palavrachave = '';
	protected $data1_quandovazio = '2007-01-01';
	protected $limite_meses = 0;
	protected $ordem = 0;
	protected $tag_destaque_1 = '<strong>';
	protected $tag_destaque_2 = '</strong>';
	protected $label_anterior = '&lt;&lt; Anterior';
	protected $label_proxima = 'Pr&oacute;xima &gt;&gt;';
	protected $ordem_invertida = false;
	
    public function __construct($id = '', $pagina = 1) {
		$this->buscadao = new BuscaCacheDAO;
		$this->buscadao->deletaCache(time());
		
		if ($id) {
			$this->id = $id;
			$this->pagina = $pagina;
			if ($this->resultado_busca = $this->buscadao->getBuscaCache($this->id, $this->pagina)) {
				$this->total = $this->resultado_busca[0]['total'];
				$this->palavrachave = $this->resultado_busca[0]['palavra'];
			}
		}
	}

	public function efetuaBusca(&$dados) {
		if ($result = $this->getResultadoBusca($dados)) {
			$this->id = Util::gera_randomico('str', 20);
			$this->buscadao->setBuscaCache($this->id, $result['resultado'], $result['total'], $this->buscavo->getPalavraChave(), (time() + $this->expiracao));
			return $this->id;
		}
		return 0;
	}
	
	abstract protected function gerarResultado();
	abstract public function exibeResultadoHtml();

	protected function getResultadoBusca(&$dados) {
		$dados = $this->formataDados($dados);
		$this->buscavo = new BuscaDadosVO;
		$this->setBuscaDadosVO($dados);

		$result = array('dadosform' => $dados, 'dadosbusca' => null, 'resultado' => array(), 'total' => 0, 'ordem_invertida' => $this->ordem_invertida);
		if ($this->autoriza_busca) {
			$result['dadosbusca'] = $this->buscavo;
			$resultadobusca = $this->gerarResultado();
			$result['resultado'] = $this->ordenaResultado($resultadobusca);
			
			if (!is_array($result['resultado']))
				$result['resultado'] = array();
			
			$result['total'] = $this->total;
			$result['ordem_invertida'] = $this->ordem_invertida;
		}

		return $result;
	}
	
	protected function formataDados($dados) {
		$dados['palavra'] = substr(stripslashes(trim($dados['palavra'])), 0, 50);
		$dados['dia1'] = str_pad((int)$dados['dia1'], 2, '0', STR_PAD_LEFT);
		$dados['mes1'] = str_pad((int)$dados['mes1'], 2, '0', STR_PAD_LEFT);
		$dados['ano1'] = (int)$dados['ano1'];
		$dados['dia2'] = str_pad((int)$dados['dia2'], 2, '0', STR_PAD_LEFT);
		$dados['mes2'] = str_pad((int)$dados['mes2'], 2, '0', STR_PAD_LEFT);
		$dados['ano2'] = (int)$dados['ano2'];
		return $dados;
	}
	
	protected function setBuscaDadosVO(&$dados) {
		$this->autoriza_busca = false;
		if ($dados['palavra']) {
			$this->buscavo->setPalavraChave($dados['palavra']);
			$this->autoriza_busca = true;
		}
		if (!$dados['hoje']) {
			$data1 = $dados['ano1'].'-'.$dados['mes1'].'-'.$dados['dia1'];
			$data2 = $dados['ano2'].'-'.$dados['mes2'].'-'.$dados['dia2'];
			if (($data1 == '0000-00-00') || ($data1 == '0-00-00')) {
				if (!$this->data1_quandovazio)
					$this->data1_quandovazio = date('Y-m-d');
				$data1 = $this->data1_quandovazio;
			}
			if (($data2 == '0000-00-00') || ($data2 == '0-00-00'))
				$data2 = date('Y-m-d');
			if ($this->limite_meses) {
				$periodo1_max = date('Y-m-d', mktime(0, 0, 0, ($dados['mes2'] - $this->limite_meses), $dados['dia2'], $dados['ano2']));
				if ($data1 < $periodo1_max)
					$data1 = $periodo1_max;
			}
			if (checkdate(substr($data1, 5, 2), substr($data1, 8, 2), substr($data1, 0, 4)) && checkdate(substr($data2, 5, 2), substr($data2, 8, 2), substr($data2, 0, 4)) && $data1 <= $data2)
				$this->autoriza_busca = true;
			if (is_array($dados['codlicenca'])) {
				$this->buscavo->setCodLicenca($dados['codlicenca']);
				$this->autoriza_busca = true;
			}
			if ((int)$dados['codestado']) {
				$this->buscavo->setCodEstado((int)$dados['codestado']);
				$this->autoriza_busca = true;
			}
			if ((int)$dados['codcidade']) {
				$this->buscavo->setCodCidade((int)$dados['codcidade']);
				$this->autoriza_busca = true;
			}
			if ((int)$dados['formato']) {
				$this->buscavo->setCodFormato((int)$dados['formato']);
				$this->autoriza_busca = true;
			}
			if (is_array($dados['formatos'])) {
				$this->buscavo->setListaFormatos($dados['formatos']);
				$this->autoriza_busca = true;
			}
		}
		else {
			$data1 = date('Y-m-d');
			$data2 = $data1;
		}

		$this->buscavo->setDataInicial($data1);
		$this->buscavo->setDataFinal($data2);
		if (is_array($dados['extras']))
			$this->buscavo->setParametrosExtra($dados['extras']);
		if (!$this->obriga_palavra)
			$this->autoriza_busca = true;
		elseif (!count($bdadosvo->getPalavraChave()))
			$this->autoriza_busca = false;

		return $this->buscavo;
	}

	protected function gerarResultadoItens($tipo, &$sqlcompl) {
		$result = array();
		switch ($tipo) {
			case 'conteudo':
				$result = $this->buscadao->getBuscaConteudo($this->buscavo, $sqlcompl);
				break;
			case 'canais':
				$result = $this->buscadao->getBuscaCanais($this->buscavo, $sqlcompl);
				break;
			case 'autores':
				$result = $this->buscadao->getBuscaAutores($this->buscavo, $sqlcompl);
				break;
			case 'colaboradores':
				$result = $this->buscadao->getBuscaColaboradores($this->buscavo, $sqlcompl);
				break;
		}
		return $result;
	}
	
	protected function getResultadoItensDados($tipo, &$coditem) {
		$dados = array();
		switch ($tipo) {
			case 'audios':
			case 'videos':
			case 'textos':
			case 'imagens':
			case 'noticias':
			case 'eventos':
				$dados = $this->buscadao->getResultadoBuscaConteudo($coditem);
				break;
			case 'canais':
				$dados = $this->buscadao->getResultadoBuscaCanais($coditem);
				break;
			case 'autores':
			case 'colaboradores':
				$dados = $this->buscadao->getResultadoBuscaUsuario($coditem);
				break;
		}
		return $dados;
	}
	
	protected function ordenaResultado(&$resultadobusca) {
		$result = array();
		$result_temp = array();
		
		foreach ($resultadobusca as $tipo => $itensresult) {
			foreach ($itensresult as $itemdados) {
				if ($this->ordem == 1)
					$result_temp['a'.sprintf('%020s', $itemdados['recomendacoes']).'-'.$tipo.'-'.$itemdados['coditem']] = array('tipo' => $tipo, 'coditem' => $itemdados['coditem'], 'data' => $itemdados['data']);
				elseif ($this->ordem == 2)
					$result_temp['a'.sprintf('%020s', $itemdados['acessos']).'-'.$tipo.'-'.$itemdados['coditem']] = array('tipo' => $tipo, 'coditem' => $itemdados['coditem'], 'data' => $itemdados['data']);
				elseif ($this->ordem == 3)
					$result_temp['a'.sprintf('%020s', $itemdados['ativos']).'-'.$tipo.'-'.$itemdados['coditem']] = array('tipo' => $tipo, 'coditem' => $itemdados['coditem'], 'data' => $itemdados['data']);
				else
					$result_temp[$itemdados['data'].'-'.$tipo.'-'.$itemdados['coditem']] = array('tipo' => $tipo, 'coditem' => $itemdados['coditem'], 'data' => $itemdados['data']);
			}
		}

		if (!$this->ordem_invertida)
			krsort($result_temp);
		else
			ksort($result_temp);

		$cont = 0;
		$cont_paginas = 1;
		foreach ($result_temp as $item) {
			$result[$cont_paginas][] = $item;
			$cont++;
			if (!($cont % $this->mostrar))
				$cont_paginas++;
		}
		$this->total = $cont;
		$this->paginas = $cont_paginas;

		return $result;
	}
	
	public function getMostrar() {
		return $this->mostrar;
	}

	public function setMostrar($val) {
		$this->mostrar = $val;
	}
	
	public function setOrdem($val) {
		$this->ordem = $val;
	}

	public function setOrdemInvertida($val) {
		$this->ordem_invertida = $val;
	}

	public function getTotal() {
		return $this->total;
	}

	public function getPaginas() {
		return $this->paginas;
	}

	public function getPalavraChave() {
		return $this->palavrachave;
	}

}