<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/SegmentoDAO.php");

class CanalBO {

    private $segdao = null;
	
    public function __construct() {
        $this->segdao = new SegmentoDAO;
    }

	public function getCanaisAtivos($limit=15) {
		return $this->segdao->getSegmentosAtivosRandom($limit);
	}
	
	public function getCanaisCultura() {
		return $this->segdao->getSegmentosAtivosCultura($limit);
	}
	
	public function getCanalDados($codcanal) {
		return $this->segdao->getSegmentoDados($codcanal);
	}
	
	public function getConteudosRelacionados($codcanal) {
		$conteudos = $this->segdao->getCodConteudoPorCodSegmento($codcanal, 8);
		$temcount = 1;
		$colspan = 4;
		$cont = 1;
		foreach($conteudos as $key => $conteudo) {
			$temul = false;
			if ($temcount == 1) {
				$html .= '<div class="coluna'.($cont == 2 ? ' no-margin-r' : '').'">';
				$html .= '<ul>';
			}
			
			$contvo = $this->getConteudoVO($conteudo['cod_conteudo'], $conteudo['cod_formato']);
			$html .= '<li'.(!isset($conteudos[$key + 1]) ? ' class="no-border"' : '').'>';
			$html .= '<small>'.date('d.m.Y H\\hi', strtotime($contvo->getDataHora())).'</small>';
			$html .= '<h1><a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo">'.htmlentities($contvo->getTitulo()).'</a></h1>';
			if ($contvo->getImagem())
				$html .= '<div class="capa"><span class="'.Util::getIconeConteudo($contvo->getCodFormato()).'"><a href="'.Util::getFormatoConteudoBusca($contvo->getCodFormato()).'.php" title="Ir para página de imagens">Imagens</a></span> <a href="'.$contvo->getUrl().'" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img='.$contvo->getImagem().'&amp;tipo='.$conteudo['cod_formato'].'&amp;s=34" alt="" width="100" height="75" /></a></div>';
			$html .= '<p>'.Util::cortaTexto(nl2br($contvo->getDescricao()), 150).'</p>';
			$html .= '<div class="hr"><hr /></div>';
			$html .= '</li>';
			
			if ($temcount == $colspan) {
				$temcount -= $colspan;
				$html .= '</ul></div>';
				$temul = true;
				$cont++;
			}
			$temcount++;			
		}
		if (!$temul && $html) $html .= '</ul></div>';
		return $html;	
	}
	
	public function getTags($codcanal) {
		$conteudos = $this->segdao->getCodConteudoPorCodSegmento($codcanal, 8);
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
		$contdao = new ConteudoExibicaoDAO;
		
		foreach($conteudos as $conteudo) {
			$tags = $contdao->getTagsConteudoNovo($conteudo['cod_conteudo']);
			foreach($tags as $tag)
				$array_tags[$tag['tag']] = $tag['tag'];
			
		}
		
		foreach ($array_tags as $tag)
			$html .= (($html != '') ? ' ' : ' ').'<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;tag='.urlencode($tag).'" class="common0 size0">'.$tag.'</a>';
			
		return $html;
	}
	
	public function getAutores($codcanal) {
		$conteudos = $this->segdao->getCodConteudoPorCodSegmento($codcanal, 10);
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
		$contdao = new ConteudoExibicaoDAO;
		
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		
		foreach($conteudos as $conteudo) {
			$usuarios = $contdao->getAutoresConteudoNovo($conteudo['cod_conteudo']);
			foreach($usuarios as $key => $usuarioa)
				$array_usuarios[$usuarioa['cod_usuario']] = $usuario = $usrdao->getUsuarioDados($usuarioa['cod_usuario']);
		}
		
		foreach($array_usuarios as $usuario) {
			$html .= '<li>';
			$html .= '<div class="foto"><a href="/'.$usuario['url'].'" title="Ir para página deste autor"><img src="/exibir_imagem.php?img='.$usuario['imagem'].'&amp;tipo=1&amp;s=29" alt="Imagem do usuário: '.htmlentities($usuario['nome']).'" width="40" height="40" /></a></div>';
			$html .= '</li>';
		}
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

}