<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConteudoVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class ConteudoHomeBO {

	private $contdao = null;
	private $codformato = 0;
	private $conteudo = array();
	private $lista_ultimas = array();

	public function __construct($codformato, $qtd = 5) {
		$this->contdao = new ConteudoDAO;
		$this->codformato = (int)$codformato;
		$this->lista_ultimas = $this->contdao->getUltimasDoFormato($this->codformato, $qtd);
	}

	private function getConteudoVO($codconteudo) {
		switch ($this->codformato) {
			case 1:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/TextoDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/TextoVO.php");
				$contdao = new TextoDAO;
				$contvo = $contdao->getTextoVO($codconteudo);
				break;
			case 2:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ImagemDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AlbumImagemVO.php");
				$contdao = new ImagemDAO;
				$contvo = $contdao->getAlbumVO($codconteudo);
				break;
			case 3:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AudioDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/AlbumAudioVO.php");
				$contdao = new AudioDAO;
				$contvo = $contdao->getAudioVO($codconteudo);
				break;
			case 4:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/VideoDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/VideoVO.php");
				$contdao = new VideoDAO;
				$contvo = $contdao->getVideoVO($codconteudo);
				break;
		}
		return $contvo;
	}

	public function getHtmlUltima() {
		$codconteudo = &$this->lista_ultimas[0];
		$contvo = $this->getConteudoVO($codconteudo);
		switch ($this->codformato) {
			case 1: return $this->getHtmlTexto($contvo); break;
			case 2: return $this->getHtmlImagem($contvo); break;
			case 3: return $this->getHtmlAudio($contvo); break;
			case 4: return $this->getHtmlVideo($contvo); break;
		}
	}

	private function getHtmlTexto(&$contvo) {
		$html  = '';
		$html .= Util::getHtmlCanal($contvo->getCodSegmento());
		$html .= '<h1 class="midia"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
        $html .= '<p><strong class="autoria">Autores:</strong> '.Util::getHtmlListaAutores($contvo->getCodConteudo(), '').'</p>';
		if ($contvo->getImagem())
			$html .= '<div class="capa"><a href="'.$contvo->getUrl().'"><img src="exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=9" width="160" height="120" /></a></div>';
        $html .= '<p>'.Util::cortaTexto(nl2br($contvo->getDescricao()), 200).'</p>';
        $html .= '<a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo"><strong>Leia o texto completo</strong></a>';
		return $html;
	}

	private function getHtmlImagem(&$contvo) {
		$html  = '';
		$html .= Util::getHtmlCanal($contvo->getCodSegmento());
		$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo"><img src="exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=25" width="400" height="300" /></a></div>';
        $html .= '<h1 class="midia"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
		$html .= '<p>'.Util::cortaTexto(nl2br($contvo->getDescricao()), 200).'</p>';
		$html .= count($contvo->getListaImagens()).' fotos | <strong class="autoria">Autores:</strong> '.Util::getHtmlListaAutores($contvo->getCodConteudo(), '');
		return $html;
	}

	private function getHtmlAudio()  {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/AudioDAO.php');
		$audiodao = new AudioDAO;
		
		$temcount = 1;
		$colspan = 5;
		foreach ($this->lista_ultimas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$temul = false;
			if ($temcount == 1)
				$html .= '<ul>';
			
			$html .= '<li>';
			$html .= Util::getHtmlCanal($contvo->getCodSegmento());
			$html .= '<div class="capa cd">';
			if ($contvo->getImagem())
				$html .= '<a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=23" alt="Capa do álbum: '.htmlentities($contvo->getTitulo()).'" width="120" height="120" /></a>';
			$html .= '</div>';
            $html .= '<h1><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
            $html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p> '.$audiodao->getQtsFaixasAlbum($codconteudo).' faixas</li>';
 
			if (($temcount == $colspan) && (isset($this->lista_ultimas[$key+1]))):
    			$temcount -= $colspan;
				$html .= '</ul><div class="hr separador3px"><hr /></div>';
				$temul = true;
			endif;
			$temcount++;
		}
		if (!$temul) $html .= '</ul>';
		return $html;
	}

	private function getHtmlVideo(&$contvo) {
		$html .= Util::getHtmlCanal($contvo->getCodSegmento());
		//if ($contvo->getImagem())
			//$html  .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=25" width="400" height="300" /></a></div>';
		$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 25, 400, 300).'</a></div>';
        $html .= '<h1 class="midia"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
		$html .= '<p>'.htmlentities($contvo->getDescricao()).'</p>';
		$html .= '<strong class="autoria">Autores:</strong> '.Util::getHtmlListaAutores($contvo->getCodConteudo(), '');
		return $html;
	}

	public function getHtmlVejaMais($total = 6) {
		switch ($this->codformato) {
			case 1: return $this->getHtmlVejaMaisTexto($total); break;
			case 2: return $this->getHtmlVejaMaisImagem($total); break;
			case 3: return $this->getHtmlVejaMaisAudio($total); break;
			case 4: return $this->getHtmlVejaMaisVideo($total); break;
		}
	}

	public function getHtmlMaisRecomendados() {
		$recomendadas = $this->contdao->getMaisRecomendadasDoFormato($this->codformato, 4);
		switch ($this->codformato) {
			case 1: return $this->getHtmlMaisRecomendadosTexto($recomendadas); break;
			case 2: return $this->getHtmlMaisRecomendadosImagem($recomendadas); break;
			case 3: return $this->getHtmlMaisRecomendadosAudio($recomendadas); break;
			case 4: return $this->getHtmlMaisRecomendadosVideo($recomendadas); break;
		}
	}

	public function getHtmlMaisAcessadas() {
		$acessadas = $this->contdao->getMaisAcessadasDoFormato($this->codformato, 0, 4);
		$total = $this->contdao->getTotalAcessadasDoFormato($this->codformato);
		
		switch ($this->codformato) {
			case 1: return $this->getHtmlMaisAcessadasTexto($acessadas); break;
			case 2: return $this->getHtmlMaisAcessadasImagem($acessadas); break;
			case 3: return $this->getHtmlMaisAcessadasAudio($acessadas); break;
			case 4: return $this->getHtmlMaisAcessadasVideo($acessadas); break;
		}
	}
	
	private function getHtmlMaisRecomendadosAudio($recomendadas) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/AudioDAO.php');
		$audiodao = new AudioDAO;
		$objconteudo = new ConteudoDAO();
		
		foreach ($recomendadas[0] as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon = $objconteudo->getEstatisticas($codconteudo);
			
			$html .= "<li".(!isset($recomendadas[0][$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			$html .= "<div class=\"capa cd\">";
			if ($contvo->getImagem())
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=24\" width=\"87\" height=\"87\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			else
				$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			$html .= "</div>";
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			//$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p> '.$audiodao->getQtsFaixasAlbum($codconteudo).' faixas';
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
            $html .= '<div class="views">Recomendações: '.number_format($conteudon['num_recomendacoes'], '0', '.', '.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlMaisAcessadasAudio($acessadas) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/AudioDAO.php');
		$audiodao = new AudioDAO;
		
		foreach ($acessadas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$objconteudo = new ConteudoDAO();
			$conteudon=$objconteudo->getEstatisticas($codconteudo);
			$html .= "<li".(!isset($acessadas[$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			$html .= "<div class=\"capa cd\">";
			if ($contvo->getImagem())
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=24\" width=\"87\" height=\"87\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			else
				$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			$html .= "</div>";
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p> ';//.$audiodao->getQtsFaixasAlbum($codconteudo).' faixas';
            $html .= '<div class="views">Visualizações: '.number_format($conteudon['num_acessos'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlVejaMaisVideo($total) {
		$temcount = 1;
		$colspan = 3;
		array_shift($this->lista_ultimas);
		foreach ($this->lista_ultimas as $indice => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$temul = false;
			if ($temcount == 1)
				$html .= '<ul>';
				
			$html .= '<li'.($temcount == 1 ? ' class="no-padding-l"':'').'>';
			$html .= Util::getHtmlCanal($contvo->getCodSegmento());
			//if ($contvo->getImagem())
			//	$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" ><img title="Ir para página deste conteúdo" src="exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=26" alt="Capa do álbum: '.htmlentities($contvo->getTitulo()).'" width="120" height="90" /></a></div>';
			$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 26, 120, 90).'</a></div>';
			$html .= '<h1><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.$contvo->getTitulo().'</a></h1>';
            $html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
			$html .= '</li>';

			if ($temcount == $colspan):
    			$temcount -= $colspan;
				$html .= '</ul>';
				if (isset($this->lista_ultimas[$indice + 1]))
					$html .= '<div class="hr separador3px"><hr /></div>';
				$temul = true;
			endif;
			$temcount++;
		}
		if (!$temul) $html .= '</ul>';
		return $html;
	}
	
	private function getHtmlMaisRecomendadosVideo($recomendadas) {
		$objconteudo = new ConteudoDAO();
		
		foreach ($recomendadas[0] as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon = $objconteudo->getEstatisticas($codconteudo);
			
			$html .= "<li".(!isset($recomendadas[0][$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			//$html .= "<div class=\"capa\">";
			//if ($contvo->getImagem())
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			//else
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 26, 120, 90).'</a></div>';
			//$html .= "</div>";
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
            $html .= '<div class="views">Recomendações: '.number_format($conteudon['num_recomendacoes'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlMaisAcessadasVideo($acessadas) {
		$objconteudo = new ConteudoDAO();
		
		foreach ($acessadas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon=$objconteudo->getEstatisticas($codconteudo);
			$html .= "<li".(!isset($acessadas[$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			//$html .= "<div class=\"capa\">";
			//if ($contvo->getImagem())
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			//else
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			//$html .= "</div>";
			$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 26, 120, 90).'</a></div>';
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
            $html .= '<div class="views">Visualizações: '.number_format($conteudon['num_acessos'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlVejaMaisTexto($total) {
		array_shift($this->lista_ultimas);
		foreach ($this->lista_ultimas as $indice => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$html .= '<li'.(!isset($this->lista_ultimas[$indice + 1]) ? ' class="no-border"':'').'>';
			if ($contvo->getImagem())
				$html .= '<div class="capa"><a href="'.$contvo->getUrl().'" ><img title="Ir para página deste conteúdo" src="exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=27" alt="Capa do álbum: '.htmlentities($contvo->getTitulo()).'" width="60" height="45" /></a></div>';
			$html .= Util::getHtmlCanal($contvo->getCodSegmento()).'';
			$html .= '<strong><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.$contvo->getTitulo().'</a></strong><br />';
            $html .= Util::getHtmlListaAutores($codconteudo);
			$html .= '<div class="hr"><hr /></div>';
			$html .= '</li>';
		}
		return $html;
	}
	
	private function getHtmlMaisRecomendadosTexto($recomendadas) {
		$objconteudo = new ConteudoDAO();
		
		foreach ($recomendadas[0] as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon = $objconteudo->getEstatisticas($codconteudo);
			
			$html .= "<li".(!isset($recomendadas[0][$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			if ($contvo->getImagem()) {
				$html .= "<div class=\"capa\">";
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			//else
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
				$html .= "</div>";
			}
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
            $html .= '<div class="views">Recomendações: '.number_format($conteudon['num_recomendacoes'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlMaisAcessadasTexto($acessadas) {
		$objconteudo = new ConteudoDAO();
		
		foreach ($acessadas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon = $objconteudo->getEstatisticas($codconteudo);
			
			$html .= "<li".(!isset($acessadas[$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			if ($contvo->getImagem()) {
				$html .= "<div class=\"capa\">";
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			//else
			//	$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
				$html .= "</div>";
			}
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
            $html .= '<div class="views">Visualizações: '.number_format($conteudon['num_acessos'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlVejaMaisImagem($total) {
		$temcount = 1;
		$colspan = 3;
		array_shift($this->lista_ultimas);
		foreach ($this->lista_ultimas as $indice => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$temul = false;
			if ($temcount == 1)
				$html .= '<ul>';
				
			$html .= '<li'.($temcount == 1 ? ' class="no-padding-l"':'').'>';
			$html .= Util::getHtmlCanal($contvo->getCodSegmento());
			if ($contvo->getImagem())
				$html .= '<div class="capa"><a href="'.$contvo->getUrl().'"><img title="Ir para página deste conteúdo" src="exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$this->codformato.'&amp;s=26" alt="Capa do álbum: '.htmlentities($contvo->getTitulo()).'" width="120" height="90" /></a></div>';
			
			$html .= '<h1><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.$contvo->getTitulo().'</a></h1>';
            $html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
			$html .= count($contvo->getListaImagens()).' fotos';
			$html .= '</li>';

			if ($temcount == $colspan):
    			$temcount -= $colspan;
				$html .= '</ul>';
				if (isset($this->lista_ultimas[$indice + 1]))
					$html .= '<div class="hr separador3px"><hr /></div>';
				$temul = true;
			endif;
			$temcount++;
		}
		if (!$temul) $html .= '</ul>';
		return $html;
	}
	
	private function getHtmlMaisRecomendadosImagem($recomendadas) {
		$objconteudo = new ConteudoDAO();
		foreach ($recomendadas[0] as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$conteudon = $objconteudo->getEstatisticas($codconteudo);
			
			$html .= "<li".(!isset($recomendadas[0][$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			$html .= "<div class=\"capa\">";
			if ($contvo->getImagem())
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			else
				$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			$html .= "</div>";
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
			//$html .= count($contvo->getListaImagens()).' fotos';
            $html .= '<div class="views">Recomendações: '.number_format($conteudon['num_recomendacoes'], '0', '.', '.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
	private function getHtmlMaisAcessadasImagem($acessadas) {
		foreach ($acessadas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codconteudo);
			$objconteudo = new ConteudoDAO();
			$conteudon=$objconteudo->getEstatisticas($codconteudo);
			$html .= "<li".(!isset($acessadas[$key + 1]) ? ' class="no-border no-margin-b"' : '').">\n";
			$html .= Util::getHtmlCanal($contvo->getCodSegmento(), '');
			$html .= "<div class=\"capa\">";
			if ($contvo->getImagem())
				$html .= "<a href=\"".$contvo->getUrl()."\"><img title=\"Ir para página deste conteúdo\" src=\"/exibir_imagem.php?img=".$contvo->getImagem()."&amp;tipo=".$this->codformato."&amp;s=26\" width=\"120\" height=\"90\" alt=\"Capa do álbum: ".htmlentities($contvo->getTitulo())."\" /></a>\n";
			else
				$html .= "<a href=\"".$contvo->getUrl()."\"><img src=\"/img/padrao/audio_padrao.gif\" alt=\"".htmlentities($contvo->getTitulo())."\" /></a>\n";
			$html .= "</div>";
			$html .= "<p><strong><a href=\"".$contvo->getUrl()."\" title=\"Ir para página deste conteúdo\">".$contvo->getTitulo()."</a></strong></p>\n";
			$html .= '<p>'.Util::getHtmlListaAutores($codconteudo).'</p>';
			//$html .= count($contvo->getListaImagens()).' fotos';
            $html .= '<div class="views">Visualizações: '.number_format($conteudon['num_acessos'],'0','.','.').'</div><div class="hr"><hr /></div></li>';
		}

		return $html;
	}
	
}
