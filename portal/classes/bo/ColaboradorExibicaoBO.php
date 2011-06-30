<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/ColaboradorVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/ColaboradorDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/NoticiaDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/ConteudoDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/ConteudoExibicaoDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/HomeDAO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/PlayerUtil.php');

class ColaboradorExibicaoBO {

    private $cod_colaborador = 0;
    private $colabdao = null;
    private $notdao = null;
    private $contdao = null;
    private $homedao = null;

    public function __construct($cod_colaborador) {
        $this->cod_colaborador = $cod_colaborador;
        $this->colabdao = new ColaboradorDAO;
        $this->notdao = new NoticiaDAO;
        $this->contdao = new ConteudoDAO;
        $this->contexbdao = new ConteudoExibicaoDAO;
        $this->homedao = new HomeDAO;
    }

    public function getNumeroConteudo($cod_colaborador, $formato) {
        return $this->colabdao->getNumeroConteudoUsuario($cod_colaborador, $formato, 1);
    }

    public function exibirConteudo() {
        $colaborador['colaborador'] = $this->colabdao->getUsuarioDados($this->cod_colaborador);
        
        if (!$colaborador['colaborador']['disponivel'] || $colaborador['colaborador']['situacao'] == 2) {
        	Header('Location: /index.php');
        	die;
		}
        
        $colaborador['colaborador']['num_audios'] 	= $this->getNumeroConteudo($this->cod_colaborador, 3);
        $colaborador['colaborador']['num_videos'] 	= $this->getNumeroConteudo($this->cod_colaborador, 4);
        $colaborador['colaborador']['num_textos'] 	= $this->getNumeroConteudo($this->cod_colaborador, 1);
        $colaborador['colaborador']['num_imagens'] 	= $this->getNumeroConteudo($this->cod_colaborador, 2);
        $colaborador['colaborador']['num_noticias'] = $this->getNumeroConteudo($this->cod_colaborador, 5);
        
        (int)$colaborador['colaborador']['num_arquivos_total'] = ($colaborador['colaborador']['num_audios'] + $colaborador['colaborador']['num_videos'] + $colaborador['colaborador']['num_textos'] + $colaborador['colaborador']['num_imagens'] + $colaborador['colaborador']['num_noticias']);
        
		$colaborador['colaborador']['links'] 			= $this->colabdao->getSitesUsuario($this->cod_colaborador);
		$colaborador['colaborador']['autores'] 			= $this->colabdao->getAutoresRelacionadosColaborador($this->cod_colaborador, 6);
		$colaborador['colaborador']['noticias'] 		= $this->notdao->getNoticiaConteudo($this->cod_colaborador);
		$colaborador['colaborador']['comunicadores'] 	= $this->colabdao->getComunicadoresUsuario($this->cod_colaborador);

		foreach((array)$this->contdao->getMaisAcessados('acessos', $this->cod_colaborador, 0, 0, 0, 6) as $key => $value) {
        	$colaborador['colaborador']['mais_acessados'][] = array(
				'cod_conteudo' 		=> $value['cod_conteudo'],
				'autores' 			=> Util::getHtmlListaAutores($value['cod_conteudo']),
				'canal'				=> Util::getHtmlCanal($value['cod_segmento']),
				'cod_formato' 		=> $value['cod_formato'],
				'titulo' 			=> $value['titulo'],
				'data_cadastro' 	=> $value['data_cadastro'],
				'datahora' 			=> $value['datahora'],
				'descricao' 		=> $value['descricao'],
				'imagem' 			=> $value['imagem'],
				'cod_colaborador' 	=> $value['cod_colaborador'],
				'num_acessos' 		=> $value['num_acessos'],
				'num_recomendacoes' => $value['num_recomendacoes'],
				'imagem_album' 		=> $value['imagem_album'],
				'colaborador' 		=> $value['colaborador'],
				'url_colaborador' 	=> $value['url_colaborador'],
				'url' 				=> $value['url'],
				'video' 			=> $this->getConteudoVideo($value['cod_conteudo'])
			);
        }
        
        include('includes/include_visualizacao_colaborador.php');
    }
	
	private function getConteudoVideo($codconteudo) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/VideoDAO.php');
		$contdao = new VideoDAO;
		return $contdao->getArquivoVideo($codconteudo);
	}
    
}