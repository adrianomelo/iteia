<?php
include_once(dirname(__FILE__)."/../vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");

class FeedsBO {

	public function __construct() {
	}
	
	private function getConteudoVO($codformato, $codconteudo) {
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
		}
		return $contvo;
	}
	
	public function getFeedsConteudo($codformato) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConteudoVO.php");
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$lista_ultimas = $contdao->getUltimasDoFormato($codformato, 10);
		
		switch($codformato) {
			case 1: $formato = 'Textos'; break;
			case 2: $formato = 'Imagens'; break;
			case 3: $formato = 'Áudios'; break;
			case 4: $formato = 'Vídeos'; break;
			case 5: $formato = 'Jornal'; break;
			case 6: $formato = 'Eventos'; break;
		}
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".$GLOBALS['iteiaurl']."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - '.$formato)."</title>\n";
		$rss .= "<link>".ConfigVO::URL_SITE."feeds.php?formato=".$codformato."</link>\n";
		$rss .= "<description>".utf8_encode($formato. " - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($lista_ultimas as $key => $codconteudo) {
			$contvo = $this->getConteudoVO($codformato, $codconteudo);
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($contvo->getTitulo()))."</title>\n";
			$rss .= "<link>".ConfigVO::URL_SITE.$contvo->getUrl()."</link>\n";
			$rss .= "<pubDate>".date("r", strtotime($contvo->getDataHora()))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	public function getFeedsCanal($codcanal) {
		include_once('BuscaiTeiaBO.php');
		$buscabo = new BuscaiTeiaBO;
		
		$dados = array();
		$dados['extras'] = array('codcanal' => $codcanal);
		
		$dados_audios = $dados;
		$dados_audios['formatos'] = array(2, 3, 4, 5);
		$memid2 = $buscabo->efetuaBusca($dados_audios);
		
		$buscabo2 = new BuscaiTeiaBO($memid2, 1);
		$itens = $buscabo2->getItensBusca();
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Canal')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=8&amp;canal=".$codcanal."</link>\n";
		$rss .= "<description>".utf8_encode("Últimos Conteúdos do Canal - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($itens as $item) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($item['titulo']))."</title>\n";
			$rss .= "<link>".ConfigVO::URL_SITE.$item['url_titulo']."</link>\n";
			$rss .= "<pubDate>".date("r", strtotime($item['datahora']))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}

	public function getFeedsNoticias() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
		$notdao = new NoticiaDAO;
		$ultimas = $notdao->getUltimasNoticias(array(), 10);
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Jornal')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=5</link>\n";
		$rss .= "<description>".utf8_encode("Últimas notícias - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $noticia) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($noticia["titulo"]))."</title>\n";
			$rss .= "<link>".ConfigVO::getUrlSite().$noticia["url"]."</link>\n";
			$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["subtitulo"]))."</description>\n";
			$rss .= "<pubDate>".date("r", strtotime($noticia["datahora"]))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	public function getFeedsAgenda() {
		include_once('classes/bo/AgendaBO.php');
		$agebo = new AgendaBO;
		$eventos = $agebo->getListaAgenda($_GET, 0, 10);
		
		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Eventos')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?formato=6</link>\n";
		$rss .= "<description>".utf8_encode("Eventos - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($eventos as $evento) {
			if ($evento['cod_conteudo']) {
			$rss .= "<item>\n";
				$rss .= "<title>".htmlspecialchars(utf8_encode($evento["titulo"]))."</title>\n";
				$rss .= "<link>".ConfigVO::getUrlSite()."evento.php?cod=".$evento['cod_conteudo']."</link>\n";
				$rss .= "<description>".htmlspecialchars(utf8_encode($evento["descricao"]))."</description>\n";
				$rss .= "<pubDate>".date("r", strtotime($evento['hora_inicial']))."</pubDate>\n";
				$rss .= "</item>\n";
			}
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
	
	public function getFeedsGeral() {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		$contdao = new ConteudoDAO;
		$ultimas = $contdao->getListaConteudo(array(), 0, 20);

		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - Geral')."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php</link>\n";
		$rss .= "<description>".utf8_encode("Últimas Obras - iTEIA")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $noticia) {
			if ((int)$noticia['cod']) {
				$url = $contdao->getUrl($noticia['cod']);
				
				$rss .= "<item>\n";
				$rss .= "<title>".utf8_encode(Util::unhtmlentities($noticia["titulo"]))."</title>\n";
				$rss .= "<link>".ConfigVO::getUrlSite().$url."</link>\n";
				$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["descricao"]))."</description>\n";
				$rss .= "<pubDate>".date("r", strtotime($noticia["datahora"]))."</pubDate>\n";
				$rss .= "</item>\n";
			}
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}

	public function getFeedsUsuario($codusuario) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$contdao = new ConteudoDAO;
		$usuariodao = new UsuarioDAO;

		$usuario = $usuariodao->getUsuarioDados($codusuario);
		if ($usuario['cod_tipo'] == 1)
			$ultimas = $contdao->getMaisAcessados('recentes', $usuario['cod_usuario'], '', '', '', 10);
		elseif ($usuario['cod_tipo'] == 2)
			$ultimas = $contdao->getMaisAcessados('recentes', '', $usuario['cod_usuario'], '', '', 10);
		if ($usuario['cod_tipo'] == 3)
			$ultimas = $contdao->getMaisAcessados('recentes', '', '', $usuario['cod_usuario'], '', 10);

		$rss = "";
		$rss = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n<rss version=\"2.0\" xml:base=\"".ConfigVO::getUrlSite()."\">\n";
		$rss .= "<channel>\n<title>".utf8_encode('RSS iTEIA - '.$usuario['nome'])."</title>\n";
		$rss .= "<link>".ConfigVO::getUrlSite()."feeds.php?cod=".$codusuario."</link>\n";
		$rss .= "<description>".utf8_encode($usuario['nome']." - Pernambuco Nação Cultural")."</description>\n";
		$rss .= "<language>pt</language>\n";
		$rss .= "<pubDate>".date("r")."</pubDate>\n";

		foreach ($ultimas as $conteudo) {
			$rss .= "<item>\n";
			$rss .= "<title>".htmlspecialchars(utf8_encode($conteudo["titulo"]))."</title>\n";
			$rss .= "<link>".ConfigVO::getUrlSite().$conteudo["url"]."</link>\n";
			//$rss .= "<description>".htmlspecialchars(utf8_encode($noticia["subtitulo"]))."</description>\n";
			$rss .= "<pubDate>".date("r", strtotime($conteudo["datahora"]))."</pubDate>\n";
			$rss .= "</item>\n";
		}
		$rss .= "</channel>\n</rss>\n";
		return $rss;
	}
}
