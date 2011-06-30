<?php
include_once(dirname(__FILE__).'/BuscaCacheBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/PlayerUtil.php');

class BuscaiTeiaBO extends BuscaCacheBO {

	protected function gerarResultado() {
		$itensres = array();
		$param_extra = $this->buscavo->getParametrosExtra();
		
		if (isset($param_extra['ordenacao']) && (int)$param_extra['ordenacao'])
			$this->setOrdem($param_extra['ordenacao']);
		
		if (in_array(2, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 3 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['audios'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(3, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 4 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['videos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(4, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 1 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['textos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(5, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 2 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['imagens'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(6, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 5 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['noticias'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(7, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 6 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['eventos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}
		
		if (in_array(8, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CS.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['canais'] = $this->gerarResultadoItens('canais', $sql_complemento);
		}
		
		if (in_array(9, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'VA.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['autores'] = $this->gerarResultadoItens('autores', $sql_complemento);
		}
		
		if (in_array(10, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'VC.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['colaboradores'] = $this->gerarResultadoItens('colaboradores', $sql_complemento);
		}

		return $itensres;
	}
	
	public function getItensBusca() {
		$resultado_pagina = &$this->resultado_busca;
		$arrayItens = array();
		foreach ($resultado_pagina as $item)
			$arrayItens[] = $this->getResultadoItensDados($item['tipo'], $item['coditem']);
		return $arrayItens;
	}

	public function exibeResultadoHtml() {
		$html = '';
		$resultado_pagina = &$this->resultado_busca;
		
		// DAO's para acesso de outros dados
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/SegmentoDAO.php');
		$canaldao = new SegmentoDAO;
		
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/VideoDAO.php');
		$videodao = new VideoDAO;
		
		foreach ($resultado_pagina as $item) {
			$link_item = '';
			$tipo_item = '';
			$titulo_item = '';
			$foto_item = '';
			$complemento_item = '';
			$descricao_item = '';
			$dadositem = $this->getResultadoItensDados($item['tipo'], $item['coditem']);

			switch ($item['tipo']) {
				case 'audios':
					$icon_item = 'audio';
					$link_formato_item = '/audios';
					$formato_item = 'Áudios';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=38" alt="" />';
					$canal = Util::getHtmlCanal($dadositem['cod_segmento'], '');
					$complemento_item = (!empty($canal) ? $canal. ' |' : '');
					break;
				case 'videos':
					$icon_item = 'video';
					$link_formato_item = '/videos';
					$formato_item = 'Vídeos';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					$dadosvideo = $videodao->getArquivoVideo($item['coditem']);
					$foto_item = PlayerUtil::getImagemVideo($dadosvideo['arquivo'], $dadosvideo['link'], $dadositem['imagem'], 34, 100, 75);
					$canal = Util::getHtmlCanal($dadositem['cod_segmento'], '');
					$complemento_item = (!empty($canal) ? $canal. ' |' : '');
					break;
				case 'textos':
					$icon_item = 'texto';
					$link_formato_item = '/textos';
					$formato_item = 'Textos';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=34" alt="" />';
					$canal = Util::getHtmlCanal($dadositem['cod_segmento'], '');
					$complemento_item = (!empty($canal) ? $canal. ' |' : '');
					break;
				case 'imagens':
					$icon_item = 'imagem';
					$link_formato_item = '/imagens';
					$formato_item = 'Imagens';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=2&amp;s=34" alt="" />';
					$canal = Util::getHtmlCanal($dadositem['cod_segmento'], '');
					$complemento_item = (!empty($canal) ? $canal. ' |' : '');
					break;
				case 'noticias':
					$icon_item = 'noticia';
					$link_formato_item = '/jornal';
					$formato_item = 'Jornal';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=34" alt="" />';
					$canal = Util::getHtmlCanal($dadositem['cod_segmento'], '');
					$complemento_item = (!empty($canal) ? $canal. ' |' : '');
					break;
				case 'eventos':
					$icon_item = 'evento';
					$link_formato_item = '/eventos';
					$formato_item = 'Eventos';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['data_cadastro']));
					$link_item = '/evento.php?cod='.$item['coditem'];
					$titulo_item = $dadositem['titulo'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=34" alt="" />';
					break;
				case 'canais':
					$icon_item = 'canal';
					$link_formato_item = '/canais';
					$formato_item = 'Canal';
					$link_item = '/canal.php?cod='.$item['coditem'];
					$titulo_item = $dadositem['nome'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=34" alt="" />';
					$complemento_item = 'Conteúdos: ' . $canaldao->getTotalConteudoPorCodSegmento($item['coditem']);
					break;
				case 'autores':
					$icon_item = 'autor';
					$link_formato_item = '/autores';
					$formato_item = 'Autores';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['datacadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['nome'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=38" alt="" />';
					break;
				case 'colaboradores':
					$icon_item = 'colaborador';
					$link_formato_item = '/colaboradores';
					$formato_item = 'Colaboradores';
					$data_item = date('d.m.Y - H\\hi', strtotime($dadositem['datacadastro']));
					$link_item = $dadositem['url_titulo'];
					$titulo_item = $dadositem['nome'];
					$descricao_item = $dadositem['descricao'];
					if ($dadositem['imagem'])
						$foto_item = '<img src="/exibir_imagem.php?img='.$dadositem['imagem'].'&amp;tipo=4&amp;s=38" alt="" />';
					break;
			}

			$html .= "<tr>\n";
            $html .= "<td class=\"col-desc\">\n";
			$html .= "<p class=\"meta\"><strong class=\"".$icon_item."\"><a href=\"".$link_formato_item."\">".$formato_item."</a></strong> | ".$complemento_item." ".($data_item ? "<small>".$data_item."</small>" : "")."</p>\n";
            $html .= "<h1><a href=\"".$link_item."\" title=\"Ir para página deste conteúdo\"><span class=\"midia\">".Util::iif($this->getPalavraChave(), Util::marcarPalavra(Util::getTrecho(strip_tags($titulo_item), array($this->getPalavraChave()), 450), $this->getPalavraChave()), strip_tags($titulo_item))."</span></a></h1>\n";
			if ($foto_item)
				$html .= "<div class=\"capa\"><a href=\"".$link_item."\" title=\"Ir para página deste conteúdo\">".$foto_item."</a></div>\n";
				
			if ($descricao_item)
				$html .= "<p>".Util::cortaTexto(Util::iif($this->getPalavraChave(), Util::marcarPalavra(Util::getTrecho(strip_tags($descricao_item), array($this->getPalavraChave()), 450), $this->getPalavraChave()), strip_tags($descricao_item)))."</p>\n";
			$html .= "</td>\n";
            $html .= "</tr>\n";
		}

		return $html;
	}

}
