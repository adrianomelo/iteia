<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ComentariosDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");
include_once("ComentariosBO.php");

class ConteudoExibicaoBO {

    private $contdao = null;
    private $codfact = null;
    private $codconteudo = 0;
    private $codformato = 0;
    private $conteudo = array();
	public $comentbo = null;
	public $comentdao = null;
	public $usuariodao = null;

    private $nocomment = array(161);

    public function __construct($codconteudo, $codformato) {
        $this->contdao = new ConteudoExibicaoDAO;
		$this->comentbo = new ComentariosBO;
		$this->comentdao = new ComentariosDAO;
		$this->usuariodao = new UsuarioDAO;
        $this->codconteudo = (int)$codconteudo;
        $this->codformato = (int)$codformato;
    }

    private function dadosGlobalConteudo() {
    	$this->contdao->addAcesso($this->codconteudo);
    	$this->conteudo['no_comment'] = $this->nocomment;
        $this->conteudo['conteudo'] = $this->contdao->getDadosConteudo($this->codconteudo);

		$autordados = $this->usuariodao->getUsuarioDados($this->conteudo['conteudo']['cod_autor']);

        // redireciona pra index se o conteudo não está acessivel
		// ou se o autor não estiver excluido ou inativo
        if ($this->conteudo['conteudo']['cod_formato'] != 5) {
			if ($autordados['disponivel'] != 1 || $autordados['situacao'] != 3) {
				Header('Location: /404.php');
				die;
			}
		}

		if (!$this->conteudo['conteudo']['cod_conteudo']) {
        	Header('Location: /404.php');
			die;
        }

		$this->conteudo['canal'] = Util::getHtmlCanal($this->conteudo['conteudo']['cod_segmento']);
        $this->conteudo['tags'] = $this->getTagsConteudo();
        //$this->conteudo['canal'] = $this->contdao->getCanalConteudo($this->codconteudo);
        $this->conteudo['autores'] = $this->getAutoresConteudo();
		
		$this->conteudo['relacionado'] = $this->contdao->getConteudoRelacionado($this->codconteudo, 0, 0);
		$this->conteudo['relacionado_tags'] = $this->contdao->getConteudoRelacionadoTags($this->codconteudo);
        $this->conteudo['maisconteudo'] = $this->contdao->getMaisConteudoColaborador($this->codconteudo, $this->codformato, $this->conteudo['conteudo']['cod_colaborador']);
		$this->conteudo['compartilhar'] = ConfigVO::URL_SITE.$this->conteudo['conteudo']['url'];
		$this->conteudo['comentarios'] = $this->comentdao->getQtsComentarios($this->codconteudo);

        $this->conteudo['maisconteudo_autores'] = $this->contdao->getConteudoAutoresFichaTecnica($this->codconteudo);
        $this->conteudo['autores_ficha_tecnica'] = $this->getAutoresFichaTecnicaConteudo();
		$cod_autores_ficha = $this->contdao->getCodAutoresFichaTecnicaConteudo($this->codconteudo);

		$this->conteudo['exibir_unico'] = false;
		if (in_array($this->conteudo['conteudo']['cod_autor'], $cod_autores_ficha) && count($cod_autores_ficha) == 1)
			$this->conteudo['exibir_unico'] = true;
			
		$this->conteudo['permitir_comentarios'] = $this->contdao->getPermissaoComentario($this->codconteudo);
		$licencas = $this->contdao->getLicenca($this->conteudo['conteudo']['cod_licenca']);

		foreach ($licencas as $key => $licenca) {
            $arraylicenca .= '<img src="/img/icones/cc/'.$licenca['imagem'].'" alt="'.$licenca['titulo'].'" width="17" height="17" />';
		}
		$tipo_licenca=$this->conteudo['conteudo']['cod_licenca'];
		//tipo
		
		$abrea='&nbsp;';
		$fechaa='';
		$url_licenca="#";
		
		if(($tipo_licenca!=7)&&($tipo_licenca!=8)){
		$url_licenca="http://creativecommons.org/licenses/by-nc-nd/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
	
		if($tipo_licenca==1){
		$url_licenca="http://creativecommons.org/licenses/by/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		
		if($tipo_licenca==2){
		$url_licenca="http://creativecommons.org/licenses/by-sa/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		if($tipo_licenca==3){
		$url_licenca="http://creativecommons.org/licenses/by-nd/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		if($tipo_licenca==4){
		$url_licenca="http://creativecommons.org/licenses/by-nc/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		if($tipo_licenca==5){
		$url_licenca="http://creativecommons.org/licenses/by-nc-sa/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		if($tipo_licenca==6){
		$url_licenca="http://creativecommons.org/licenses/by-nc-nd/2.5/br/";
		$abrea='&nbsp;<a href="'.$url_licenca.'">';
		$fechaa='</a>';
		} 
		
		
		$arraylicenca .= $abrea.Util::getTituloLicenca($this->conteudo['conteudo']['cod_licenca']).$fechaa;
		$this->conteudo['licenca'] = $arraylicenca;
    }

	private function getTagsConteudo() {
		$html  = '';
		foreach($this->contdao->getTagsConteudoNovo($this->codconteudo) as $tag)
			$html .= (($html != '') ? ' ' : ' ').'<a href="/busca_action.php?buscar=&amp;formatos=2,3,4,5&amp;tag='.urlencode($tag['tag']).'" class="common0 size0">'.$tag['tag'].'</a>';
		return $html;
	}

	private function getAutoresConteudo() {
		$autores = array();
		$html  = '';
		$html .= '<ul>';
		$usuariosativos = $this->contdao->getAutoresConteudoNovo($this->codconteudo);
		foreach($usuariosativos as $key => $usuarios) {
			$value = $this->usuariodao->getUsuarioDados($usuarios['cod_usuario']);

			$html .= '<li'.(!isset($usuariosativos[$key + 1]) ? ' class="no-margin-b no-border"' : '').'>';
			if ($value['imagem'])
				$html .= '<div class="foto"><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor"><img src="/exibir_imagem.php?img='.$value['imagem'].'&amp;tipo=a&amp;s=29" alt="Imagem do autor: '.$value['nome'].'" width="40" height="40" /></a></div>';
			$html .= '<strong><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor">'.$value['nome'].'</a></strong><br />';
			if ($value['cod_estado'])
				$html .= '<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades='.$value['cod_cidade'].'" title="Listar autores por cidade">'.$value['cidade'].'</a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados='.$value['cod_estado'].'" title="Listar autores por estado">'.$value['sigla'].'</a><br />';
			$html .= '<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor='.$usuarios['cod_usuario'].'" title="Listar os conteúdos deste autor" class="info">'.$value['autor_num_conteudo'].' conteúdos</a>';
			$html .= '<div class="hr"><hr /></div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	
	private function getAutoresFichaTecnicaConteudo() {
		$autores = array();
		$html  = '';
		$html .= '<ul>';
		$usuariosativos = $this->contdao->getAutoresFichaTecnicaConteudoNovo($this->codconteudo);
		$maisusuaios=$this->contdao->getAutoresFichaTecnicaConteudoMaisautores($this->codconteudo);
		foreach($usuariosativos as $key => $usuarios) {
			$value = $this->usuariodao->getUsuarioDados($usuarios['cod_usuario']);
			
			$html .= '<li'.(!isset($usuariosativos[$key + 1]) ? ' class="no-margin-b no-border"' : '').'>';
			if ($value['imagem'])
				$html .= '<div class="foto"><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor"><img src="/exibir_imagem.php?img='.$value['imagem'].'&amp;tipo=a&amp;s=29" alt="Imagem do autor: '.$value['nome'].'" width="40" height="40" /></a></div>';
			$html .= '<strong><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor">'.$value['nome'].'</a></strong><br />';
			if ($value['cod_estado'])
				$html .= '<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades='.$value['cod_cidade'].'" title="Listar autores por cidade">'.$value['cidade'].'</a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados='.$value['cod_estado'].'" title="Listar autores por estado">'.$value['sigla'].'</a><br />';
			$html .= '<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor='.$usuarios['cod_usuario'].'" title="Listar os conteúdos deste autor" class="info">'.$value['autor_num_conteudo'].' conteúdos</a>';
			$html .= '<div class="hr"><hr /></div>';
			$html .= '</li>';
		}
		$html .= '</ul>';
		
		
		
		//  
		    $i = 0;
			foreach($maisusuaios as $key => $usuarios) {
			$i++;
			$value = $this->usuariodao->getUsuarioDados($usuarios['cod_usuario']);
			
			//$html2 .= '<li'.(!isset($usuariosativos[$key + 1]) ? ' class="no-margin-b no-border"' : '').'>';
			if ($value['imagem'])
				$html2 .= '<div class="foto"><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor"><img src="/exibir_imagem.php?img='.$value['imagem'].'&amp;tipo=a&amp;s=29" alt="Imagem do autor: '.$value['nome'].'" width="40" height="40" /></a></div>';
			$html2 .= '<strong><a href="'.ConfigVO::URL_SITE.$value['url'].'" title="Ir para página deste autor">'.$value['nome'].'</a></strong><br />';
			if ($value['cod_estado'])
				$html2 .= '<a href="/busca_action.php?buscar=1&amp;formatos=9&amp;cidades='.$value['cod_cidade'].'" title="Listar autores por cidade">'.$value['cidade'].'</a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;estados='.$value['cod_estado'].'" title="Listar autores por estado">'.$value['sigla'].'</a><br />';
			$html2 .= '<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;autor='.$usuarios['cod_usuario'].'" title="Listar os conteúdos deste autor" class="info">'.$value['autor_num_conteudo'].' conteúdos</a>';
			$html2 .= '<div class="hr"><hr /></div>';
			//$html2 .= '</li>';
		}
		$html2 .= '</ul>';


	//modificado
		$retorno['1']=$html;
		$retorno['2']=$html2;
		$retorno[3]=$i;
		//return $html;
		
		return $retorno;
	}

    public function exibirConteudo() {
        include_once("ConteudoExibicaoFactory.php");
        $this->codfact = ConteudoExibicaoFactory::getFactory($this->codformato);
        $this->dadosGlobalConteudo();
        return $this->codfact->exibirConteudo($this->codconteudo, $this->conteudo, $this->comentbo);
    }

    public function DownloadArquivo($opcional = 0) {
        include_once("ConteudoExibicaoFactory.php");
        $this->codfact = ConteudoExibicaoFactory::getFactory($this->codformato);
        $this->dadosGlobalConteudo();
		if (!$opcional)
			return $this->codfact->DownloadArquivo($this->codconteudo, $this->conteudo);
		else
			return $this->codfact->DownloadArquivo($this->codconteudo, $opcional);
    }

    public function RecomendarConteudo($tipo) {
		$votos = $this->contdao->addRecomendacao($this->codconteudo, $tipo);
		return $votos[$tipo];
	}

	public function getConteudo() {
		$this->dadosGlobalConteudo();
		return $this->conteudo;
	}

}