<?php
require_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/AutorDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."vo/GrupoVO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/GrupoDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/HomeDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");

class GrupoExibicaoBO {

    private $cod_grupo = 0;
    private $autordao = null;
    private $contdao = null;
    private $colabdao = null;
    private $notdao = null;
    private $homedao = null;
    private $grupodao = null;

    public function __construct($cod_grupo) {
        $this->cod_grupo = $cod_grupo;
        $this->contdao = new ConteudoDAO;
        $this->autordao = new AutorDAO;
        $this->colabdao = new ColaboradorDAO;
        $this->notdao = new NoticiaDAO;
        $this->homedao = new HomeDAO;
        $this->grupodao = new GrupoDAO;
    }
    
    public function getNumeroConteudo($cod_grupo, $formato) {
        return $this->grupodao->getNumeroConteudoUsuario($cod_grupo, $formato, 3);
    }
    
    public function exibirConteudo() {
        $grupo['grupo'] = $this->grupodao->getUsuarioDados($this->cod_grupo);
        
        if (!$grupo['grupo']['disponivel'] || $grupo['grupo']['situacao'] == 2){
        	Header('Location: /index.php');
        	die;
		}
        
        $grupo['grupo']['num_arquivos_total'] = $this->getNumeroConteudo($this->cod_grupo, '');
		$grupo['grupo']['num_audios'] = $this->getNumeroConteudo($this->cod_grupo, 3);
        $grupo['grupo']['num_videos'] = $this->getNumeroConteudo($this->cod_grupo, 4);
        $grupo['grupo']['num_textos'] = $this->getNumeroConteudo($this->cod_grupo, 1);
        $grupo['grupo']['num_imagens'] = $this->getNumeroConteudo($this->cod_grupo, 2);
        $grupo['grupo']['noticias'] = $this->notdao->getUltimasNoticias(array(), 2);
        $grupo['grupo']['links'] = $this->grupodao->getSitesUsuario($this->cod_grupo);
        
        $grupo['grupo']['colaborador'] = $this->grupodao->getColaboradoresRelacionadoGrupo($this->cod_grupo);
        $grupo['grupo']['autores'] = $this->grupodao->getAutoresRelacionadoGrupo($this->cod_grupo);
        
        //$grupo['grupo']['destaques'] = $this->homedao->getListaConteudoHomeUsuario($this->cod_grupo, 1);
        
        $lista_destaques = $this->homedao->getListaHomeConteudo($this->cod_grupo);
		
		foreach ($lista_destaques as $i => $destaque) {
			
			$colaborador = $this->contdao->getDadosColaboradorConteudo($destaque['codconteudo']);
			
			$grupo['grupo']['destaques'][$i]['cod_playlist'] = $destaque['cod_playlist'];
			
			$grupo['grupo']['destaques'][$i]['url_colaborador'] = $colaborador['url'];
			$grupo['grupo']['destaques'][$i]['colaborador'] = $colaborador['nome'];
			
			$grupo['grupo']['destaques'][$i]['titulo'] = $destaque['titulo'];
			$grupo['grupo']['destaques'][$i]['descricao'] = $destaque['descricao'];
			$grupo['grupo']['destaques'][$i]['datahora'] = $destaque['datahora'];
			$grupo['grupo']['destaques'][$i]['cod_formato'] = $destaque['codformato'];

			$estatisticas = $this->contdao->getEstatisticas($destaque['codconteudo']);
			$grupo['grupo']['destaques'][$i]['num_acessos'] = $estatisticas['num_acessos'];
			$grupo['grupo']['destaques'][$i]['num_recomendacoes'] = $estatisticas['num_recomendacoes'];
			$grupo['grupo']['destaques'][$i]['url'] = $this->contdao->getUrl($destaque['codconteudo']);
			$imagem = $this->contdao->getImagemFormato($destaque['codconteudo']);
			
			if ($destaque["cod_formato"] == 2)
				$grupo['grupo']['destaques'][$i]['imagem_album'] = $destaque["imagem"];
			else
				$grupo['grupo']['destaques'][$i]['imagem'] = $destaque["imagem"];
				
		}
        
        
        
		$grupo['grupo']['mais_acessados'] = $this->contdao->getMaisAcessados('acessos', '', '', $this->cod_grupo);
        $grupo['grupo']['mais_recentes'] = $this->contdao->getMaisAcessados('recentes', '', '', $this->cod_grupo);
        $grupo['grupo']['mais_votados'] = $this->contdao->getMaisAcessados('votos', '', '', $this->cod_grupo);
        
		$grupo['grupo']['colaborador'] = $this->grupodao->getColaboradoresRelacionadoGrupo($this->cod_grupo);

        include('includes/include_visualizacao_grupo.php');
    }

    
}