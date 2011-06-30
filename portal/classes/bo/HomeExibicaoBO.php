<?php
include_once(dirname(__FILE__)."/../vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/HomeDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class HomeExibicaoBO {

	private $homedao = null;
	private $lista_noticias_exibidas = array();
	private $lista_conteudos_exibidos = array();
	private $contdao = null;
	private $noticia_destaque = array();

	public function __construct() {
		$this->homedao = new HomeDAO();
		$this->contdao = new ConteudoDAO;
	}

	public function getHtmlConteudoHome() {
		$lista_conteudo = $this->homedao->getListaHomeConteudoIteia();
		$total = count($lista_conteudo);
		$destaques = array();
		
		foreach ($lista_conteudo as $key => $value) {
			$codconteudo 	= $lista_conteudo[$key]['codconteudo'];
			$codformato 	= $lista_conteudo[$key]['codformato'];
			$titulo 		= $lista_conteudo[$key]['titulo'];
			$descricao 		= $lista_conteudo[$key]['descricao'];
			$imagem 		= $lista_conteudo[$key]['imagem'];
			
			if ($codformato == 5)
				$this->noticia_destaque = $lista_conteudo[$key];
			
			if ($codconteudo && in_array($codformato, array(1, 2, 3, 4))) {
				$contvo = $this->getConteudoVO($codconteudo, $codformato);
				$colabdao = new ColaboradorDAO;
				$colabvo = $colabdao->getColaboradorVO($contvo->getCodColaborador());
				
				if ($imagem) {
					$nome_imagem = substr($imagem, 0, 10);
					if ($nome_imagem == 'imggaleria') {
						$imagem_principal = $this->contdao->getImagemFormato($codconteudo);
						$destaques[$key]['imagem'] = $imagem_principal['imagem'];
					} else {
						$destaques[$key]['imagem'] = $imagem;
					}
				}

				// se for video
				$imagemvideo[$key] = '';
				if ($codformato == 4 && !$imagem)
					$imagemvideo[$key] = PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 22, 300, 225);

				$destaques[$key]['cod_formato'] = $codformato;
				$destaques[$key]['tipo'] = Util::iif($codformato == 2, 2, 4);
				$destaques[$key]['url'] = $contvo->getUrl();
				$destaques[$key]['titulo'] = htmlentities($titulo);
				$destaques[$key]['descricao'] = Util::cortaTexto($descricao, 200);
			}
		}
		
		$html  = '';
		$html .= '<ul class="ui-tabs-nav"><li class="ui-tabs-nav-item ui-tabs-selected" id="nav-fragment-1"> <a href="#fragment-1"><strong class="'.Util::getIconeConteudo($destaques[1]['cod_formato']).'">'.ucfirst(Util::getFormatoConteudo($destaques[1]['cod_formato'])).'</strong><br /><span>'.$destaques[1]['titulo'].'</span></a></li>';
        $html .= '<li class="ui-tabs-nav-item" id="nav-fragment-2"><a href="#fragment-2"><strong class="'.Util::getIconeConteudo($destaques[2]['cod_formato']).'">'.ucfirst(Util::getFormatoConteudo($destaques[2]['cod_formato'])).'</strong><br /><span>'.$destaques[2]['titulo'].'</span></a></li>';
        $html .= '<li class="ui-tabs-nav-item" id="nav-fragment-3"><a href="#fragment-3"><strong class="'.Util::getIconeConteudo($destaques[3]['cod_formato']).'">'.ucfirst(Util::getFormatoConteudo($destaques[3]['cod_formato'])).'</strong><br /><span>'.$destaques[3]['titulo'].'</span></a></li>';
        $html .= '</ul>';
		
		$html .= '<div id="fragment-1" class="ui-tabs-panel" style="">'.($imagemvideo[1] ? $imagemvideo[1] : '<img src="/exibir_imagem.php?img='.$destaques[1]['imagem'].'&amp;tipo='.$destaques[1]['tipo'].'&amp;s=22" alt="" width="300" height="225" />').'
            <div class="info" >
              <p><a href="'.$destaques[1]['url'].'" title="Ir para página deste conteúdo">'.$destaques[1]['descricao'].'</a></p>
            </div>
          </div>';
          
        $html .= '<div id="fragment-2" class="ui-tabs-panel ui-tabs-hide" style="">'.($imagemvideo[2] ? $imagemvideo[2] : '<img src="/exibir_imagem.php?img='.$destaques[2]['imagem'].'&amp;tipo='.$destaques[2]['tipo'].'&amp;s=22" alt="" width="300" height="225" />').'
            <div class="info" >
              <p><a href="'.$destaques[2]['url'].'" title="Ir para página deste conteúdo">'.$destaques[2]['descricao'].'</a></p>
            </div>
          </div>';
        
        $html .= '<div id="fragment-3" class="ui-tabs-panel ui-tabs-hide" style="">'.($imagemvideo[3] ? $imagemvideo[3] : '<img src="/exibir_imagem.php?img='.$destaques[3]['imagem'].'&amp;tipo='.$destaques[3]['tipo'].'&amp;s=22" alt="" width="300" height="225" />').'
            <div class="info" >
              <p><a href="'.$destaques[3]['url'].'" title="Ir para página deste conteúdo">'.$destaques[3]['descricao'].'</a></p>
            </div>
          </div>';
		
		return $html;
	}

	private function getConteudoVO($codconteudo, $codformato) {
		switch ($codformato) {
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
			case 5:
				include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
				include_once(ConfigPortalVO::getDirClassesRaiz()."vo/NoticiaVO.php");
				$notdao = new NoticiaDAO;
				$contvo = $notdao->getNoticiaVO($codconteudo);
				break;
		}
		return $contvo;
	}
	
	public function getNoticiaDestaque() {
		$lista_conteudo = $this->homedao->getListaHomeConteudoIteia();
		
		foreach ($lista_conteudo as $key => $value) {
			if ($lista_conteudo[$key]['codformato'] == 5)
				$this->noticia_destaque = $lista_conteudo[$key];
		}
		$contvo = $this->getConteudoVO($this->noticia_destaque['codconteudo'], $this->noticia_destaque['codformato']);

		$html = '';
		$html .= '<div class="destaque">';
		$html .= '<small>'.date('d.m.Y - H\\hi', strtotime($contvo->getDataHora())).'</small><br />';
        $html .= '<div class="capa"><a href="/'.$contvo->getUrl().'" title="Leia a matéria completa"><img src="exibir_imagem.php?img='.$this->noticia_destaque['imagem'].'&amp;tipo=1&amp;s=33"  /></a></div>';
        $html .= '<h1><a href="/'.$contvo->getUrl().'" title="Leia a matéria completa">'.htmlentities($this->noticia_destaque['titulo']).'</a></h1>';
        $html .= '<p>'.htmlentities($this->noticia_destaque['descricao']).'</p>';
        $html .= '<hr class="separador3px" />';
        $html .= '</div>';
		
		return $html;
	}

	public function getHtmlUltimasNoticias() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
		$notdao = new NoticiaDAO;
		//$ultimafoto = $notdao->getUltimaNoticiaComImagem();
		
		if ($this->noticia_destaque['codconteudo'] && $this->noticia_destaque['codformato']) {
			$contvo = $this->getConteudoVO($this->noticia_destaque['codconteudo'], $this->noticia_destaque['codformato']);
			
		//print_r($this->noticia_destaque);
			
			$html  = '';
			$html .= '<div class="coluna destaque">';
			$html .= "<small>".date('d.m.Y - H\\hi', strtotime($contvo->getDataHora()))."</small>";
			$html .= '<div class="capa"><a href="/'.$contvo->getUrl().'"><img src="/exibir_imagem.php?img='.$this->noticia_destaque['imagem'].'&amp;tipo=1&amp;s=33" /></a></div>';
			$html .= '<h1><a href="/'.$contvo->getUrl().'">'.htmlentities($this->noticia_destaque['titulo']).'</a></h1>';
			$html .= '</div>';
			
		//	$html .= '<div class="coluna destaque">';
		//	$html .= "<small>".date('d.m.Y - H\\hi', strtotime($ultimafoto["datahora"]))."</small>";
		//	$html .= '<div class="capa"><a href="/'.$ultimafoto['url'].'"><img src="/exibir_imagem.php?img='.$ultimafoto['imagem'].'&amp;tipo=1&amp;s=33" /></a></div>';
		//	$html .= '<h1><a href="/'.$ultimafoto['url'].'">'.htmlentities($ultimafoto['titulo']).'</a></h1>';
		//	$html .= '</div>';
			$this->lista_noticias_exibidas[] = $this->noticia_destaque['codconteudo'];
		}
		
		$ultimas = $notdao->getUltimasNoticias($this->lista_noticias_exibidas, 5);

		if (count($ultimas)) {
			$html .= '<div class="coluna no-margin-r">';
			$html .= '<ul>';
			foreach ($ultimas as $noticia) {
				$html .= "<li><p><small>".date('d.m.Y - H\\hi', strtotime($noticia["datahora"]))."</small><br/><a href=\"/".$noticia["url"]."\">".Util::cortaTexto(htmlentities($noticia["titulo"]), 100)."</a></p></li>\n";
				$this->lista_noticias_exibidas[] = $noticia["cod_conteudo"];
			}
			$html .= "</ul>\n";
			$html .= '</div>';
		}
		return $html;
	}
	
	public function getHtmlConteudoCanais() {
		require_once(ConfigPortalVO::getDirClassesRaiz()."dao/SegmentoDAO.php");
		$segdao = new SegmentoDAO;
		
		$html = '';
		$canais = $segdao->getHomeSegmentosRandom();
		foreach($canais as $key => $canal) {
			$html .= '<div class="coluna'.(!isset($canais[$key + 1]) ? ' no-margin-r' : '').'">';
			$html .= '<h3 class="mais"><span>Destaques do canal</span> '.htmlentities($canal['nome']).'</h3>';
			$html .= '<ul>';
			
			$conteudos = $segdao->getCodConteudoPorCodSegmento($canal['cod_segmento']);
			foreach($conteudos as $keya => $conteudo) {
				$contvo = $this->getConteudoVO($conteudo['cod_conteudo'], $conteudo['cod_formato']);
				
				$html .= '<li'.(!isset($conteudos[$keya + 1]) ? ' class="no-border"' : '').'>';
				$html .= '<small>'.date('d.m.Y H\\hi', strtotime($contvo->getDataHora())).'</small>';
				$html .= '<h1><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
				
				if ($conteudo['cod_formato'] == 4) {
					$imagem = PlayerUtil::getImagemVideo($contvo->getArquivo(), $contvo->getLinkVideo(), $contvo->getImagem(), 34, 100, 75);
				} else {
					if ($contvo->getImagem())
						$imagem = '<img src="/exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$conteudo['cod_formato'].'&amp;s=34" alt="" width="100" height="75" />';
				}
				
				$html .= '<div class="capa"><span class="'.Util::getIconeConteudo($contvo->getCodFormato()).(!$imagem ? ' no-image' : '').'"><a href="'.Util::getFormatoConteudoBusca($contvo->getCodFormato()).'.php" title="Ir para página de imagens">Imagens</a></span> <a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">';
				
				$html .= $imagem;
				
				$html .= '</a></div>';
				$html .= '<p>'.Util::cortaTexto(nl2br($contvo->getDescricao()), 150).'</p>';
				$html .= '<div class="hr"><hr /></div>';
				$html .= '</li>';
			}
			
			$html .= '</ul>';
			$html .= '<div class="todos"><a href="/busca_resultado.php?buscar=1&amp;canal='.$canal['cod_segmento'].'&amp;autor=1&amp;audios=1&amp;videos=1&amp;textos=1&amp;imagens=1" title="Ir para página deste canal"><strong>Ver todos</strong></a></div>';
			$html .= '</div>';
		}
		
		return $html;
	}
	
	public function getUsuarios() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		$usuarios = $usrdao->getUsuariosRandom();
		
		$html = '';
		$html .= '<ul>';
		foreach($usuarios as $key => $usuario) {
		$tp = 0;
		$tp=$usrdao->getTipoUsuarioHome($usuario['cod_usuario']);
		
		if($tp){ $title="Ir para página deste autor";  } else { $title="Ir para página deste colaborador"; }
			$html .= '<li'.(!isset($usuarios[$key + 1]) ? ' class="no-border no-margin-b"' : '').'>';
            $html .= '<div class="foto"><a href="/'.$usuario['url'].'" title="'.$title.'"><img src="/exibir_imagem.php?img='.$usuario['imagem'].'&amp;tipo=1&amp;s=29" alt="Imagem do usuário: '.htmlentities($usuario['nome']).'" width="40" height="40" /></a></div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	public function getHtmlAgenda() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AgendaDAO.php");
		$agendao = new AgendaDAO;
		$eventos = $agendao->getListaAgendaPortal(array(), 0, 7, true);
		$html = "";

		if (count($eventos) > 1) {
			$html .= "<ul>\n";
			foreach ($eventos as $i => $agenda) {
				if (isset($agenda["cod_conteudo"]))
					$html .= "<li><small>".date("d/m - H:i", strtotime($agenda["data_inicial"]." ".$agenda["hora_inicial"]))."</small><br/><a href=\"/evento.php?cod=".$agenda["cod_conteudo"]."\">".Util::cortaTexto(htmlentities($agenda["titulo"]), 65)."</a></li>\n";
			}
			$html .= "</ul>\n";
		} else {
			$html .= "<p>Nenhum evento cadastrado para esta semana.</p>\n";
		}

		return $html;
	}
	
}
