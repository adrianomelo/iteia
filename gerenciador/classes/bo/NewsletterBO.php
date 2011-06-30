<?php
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/NewsletterDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

class NewsletterBO {

	private $newsdao = null;
	private $dados_form = array();
	private $meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	
	public function __construct() {
		$this->newsdao = new NewsletterDAO();
	}
	
	public function getEnviadas($inicial, $mostrar) {
		return $this->newsdao->getNewsletterEnviadasRecentes($inicial, $mostrar);
	}
	
	public function getAgendadas() {
		return $this->newsdao->getNewsletterAgendadasRecentes();
	}
	
	public function removeProgramacao($codnewsletter) {
		$this->newsdao->removeProgramacao($codnewsletter);
		header('location: home_newsletter.php');
	}

	public function getListaConteudoSelecionados($codplay) {
		return $this->newsdao->getListaConteudoSelecionados($codplay);
	}
	
	public function getPrevisaoInterna($codnewsletter, $sessao=true) {
		
		$template = file_get_contents(ConfigVO::DIR_SITE.'portal/templates/template_newsletter.html');
		
		$template = eregi_replace("<!--%IMG_URL%-->", ConfigVO::URL_SITE."newsletter/", $template);
		
		$dados_newsletter = $this->newsdao->getDadosNewsletter($codnewsletter);
		$timestamp = strtotime($dados_newsletter['data_cadastro']);
		
		$template = eregi_replace("<!--%DATA%-->", Util::iif($codnewsletter, date("d", $timestamp)." de ".$this->meses[date("m", $timestamp) - 1]." de ".date("Y", $timestamp), date("d")." de ".$this->meses[date("m") - 1]." de ".date("Y")), $template);
		
		$template = eregi_replace("<!--%EMAIL%-->", ConfigVO::EMAIL, $template);
		
		// destaque principal 23 de Setembro de 2008
		$destaque = $this->newsdao->getConteudoNewsletter($codnewsletter, $sessao, true, false);
		
		if ($destaque[0]['imagem']) {
			$html_foto_destaque = "<div style=\"background:#fff; border:1px solid #cecece; padding:4px; margin:18px 15px 10px 0; width:265px; float:left;\">".Util::iif($destaque[0]['credito'], "<span style=\"font-size:10px;\">".$destaque[0]['credito']."</span><br />")."\n<img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$destaque[0]['imagem']."&amp;tipo=".Util::iif($destaque[0]['galeria'], '2', '1')."&amp;s=21\" width=\"265\" height=\"170\" /></div>\n";
		}
		
		$template = eregi_replace("<!--%IMG_DESTAQUE%-->", $html_foto_destaque, $template);
		
		$template = eregi_replace("<!--%TITULO_DESTAQUE%-->", "<a href=\"".ConfigVO::URL_SITE.$destaque[0]['url']."\" style=\"color: #215eae; text-decoration: none;\">".$destaque[0]['titulo']."</a>", $template);
		
		$template = eregi_replace("<!--%DESCRICAO_DESTAQUE%-->", $destaque[0]['descricao'], $template);
		
		// demais noticias
		// extraindo a ultima do array
		$lista_noticias = $this->newsdao->getConteudoNewsletter(0, $sessao, false, true);
		$ultima_noticia = $lista_noticias[(count($lista_noticias) - 1)];
		
		array_pop($lista_noticias);
		
		foreach ($lista_noticias as $noticia) {
			$html_noticias .= "<tr>\n<td width=\"100%\" style=\"border-bottom:1px dotted #cecece; padding:0 0 6px 17px; *padding:10px 0 10px 17px; background:url(".ConfigVO::URL_SITE."newsletter/bullet.gif) left 16px no-repeat;\"><h2 style=\"color:#215eae; font-size:14px; margin-bottom:5px;\"><a href=\"".ConfigVO::URL_SITE.$noticia['url']."\" style=\"color: #215eae; text-decoration: none;\">".$noticia['titulo']."</a></h2>".$noticia['descricao']."</td>\n</tr>\n";	
		}
		
		$template = eregi_replace("<!--%LISTA_NOTICIAS%-->", $html_noticias, $template);
		
		// ultimo elemento
		if ($ultima_noticia['imagem']) {
			$html_foto_ultima = "<div align=\"center\"><img src=\"".ConfigVO::URL_SITE."exibir_imagem.php?img=".$ultima_noticia['imagem']."&amp;tipo=".Util::iif($ultima_noticia['galeria'], '2', '1')."&amp;s=6\" width=\"124\" height=\"124\" style=\"background:#fff; padding:4px; border:1px solid #cecece; margin-right:15px;\" /></div>\n";
		}
		
		$contdao = new ConteudoExibicaoDAO;
		$dados_autor = $contdao->getAutoresConteudo($ultima_noticia['cod_conteudo'], ' style="text-decoration:none; color:#215eae; font-size:10px;"');
		
		$template = eregi_replace("<!--%TITULO_ULTIMA%-->", "<a href=\"".ConfigVO::URL_SITE.$ultima_noticia['url']."\" style=\"text-decoration:none; color:#215eae; font-size:18px;\">".$ultima_noticia['titulo']."</a>", $template);
		
		$template = eregi_replace("<!--%DESCRICAO_ULTIMA%-->", $ultima_noticia['descricao'], $template);
		
		$template = eregi_replace("<!--%AUTORES_ULTIMA%-->", $dados_autor, $template);
		
		$template = eregi_replace("<!--%IMG_ULTIMA%-->", $html_foto_ultima, $template);
		
		return $template;
	}
	
	/*
	public function setDadosBusca($dados) {
		$this->dadosform['buscar'] = (int)$dados['buscar'];
		$this->dadosform['palavrachave'] = trim($dados['palavrachave']);
		$this->dadosform['buscarpor'] = trim($dados['buscarpor']);
		$this->dadosform['de'] = trim($dados['de']);
		$this->dadosform['ate'] = trim($dados['ate']);

		$this->dadosform['acao'] = (int)$dados['acao'];
		$this->dadosform['codplaylist'] = (array)$dados['codplaylist'];
	}
	
	public function getListaPlayList($get, $inicial, $mostrar, $tempo) {
		$this->setDadosBusca($get);

		if ($this->dadosform['acao']) {
			$this->playdao->executaAcoes($this->dadosform['acao'], $this->dadosform['codplaylist']);
			Header('Location: home.php');
			die;
		}
		
		return $this->playdao->getListaPlayList($get, $inicial, $mostrar, $tempo);
	}
	
	public function getValorCampo($campo) {
		return $this->dadosform[$campo];
	}
	*/

}