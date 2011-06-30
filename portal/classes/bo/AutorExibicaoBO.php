<?php
require_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."vo/ColaboradorVO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/AutorDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/ColaboradorDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/HomeDAO.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
require_once(ConfigPortalVO::getDirClassesRaiz()."util/PlayerUtil.php");

class AutorExibicaoBO {

    private $cod_autor = 0;
    private $autordao = null;
    private $contdao = null;
    private $colabdao = null;
    private $notdao = null;
    private $homedao = null;

    public function __construct($cod_autor) {
        $this->cod_autor = $cod_autor;
        $this->contdao = new ConteudoDAO;
        $this->autordao = new AutorDAO;
        $this->colabdao = new ColaboradorDAO;
		$this->contexbdao = new ConteudoExibicaoDAO;
        $this->notdao = new NoticiaDAO;
        $this->homedao = new HomeDAO;
    }

    public function getNumeroConteudo($cod_autor, $formato) {
        return $this->autordao->getNumeroConteudoUsuario($cod_autor, $formato, 2);
    }

    public function getNumeroConteudoProprio($cod_autor, $formato) {
        return $this->autordao->getNumeroConteudoAutorSemFicha($cod_autor, $formato);
    }

    public function exibirConteudo() {
        $autor['autor'] = $this->autordao->getUsuarioDados($this->cod_autor);

        if (!$autor['autor']['disponivel'] || ($autor['autor']['situacao'] == 1) || ($autor['autor']['situacao'] == 2)){
        	Header('Location: /');
        	die;
		}

		$autor['autor']['cidade_ext'] = $this->autordao->getCidadeUsuario($this->cod_autor);
		$autor['autor']['pais_ext'] = $this->autordao->getPaisUsuario($this->cod_autor);

		// proprio
		$autor['autor']['num_audios_proprio'] = $this->getNumeroConteudoProprio($this->cod_autor, 3);
        $autor['autor']['num_videos_proprio'] = $this->getNumeroConteudoProprio($this->cod_autor, 4);
        $autor['autor']['num_textos_proprio'] = $this->getNumeroConteudoProprio($this->cod_autor, 1);
        $autor['autor']['num_imagens_proprio'] = $this->getNumeroConteudoProprio($this->cod_autor, 2);

		$autor['autor']['num_audios'] = $this->getNumeroConteudo($this->cod_autor, 3) + $autor['autor']['num_audios_proprio'];
        $autor['autor']['num_videos'] = $this->getNumeroConteudo($this->cod_autor, 4) + $autor['autor']['num_videos_proprio'];
        $autor['autor']['num_textos'] = $this->getNumeroConteudo($this->cod_autor, 1) + $autor['autor']['num_textos_proprio'];
        $autor['autor']['num_imagens'] = $this->getNumeroConteudo($this->cod_autor, 2) + $autor['autor']['num_imagens_proprio'];

		$autor['autor']['num_arquivos_total'] = ($autor['autor']['num_audios'] + $autor['autor']['num_videos'] + $autor['autor']['num_textos'] + $autor['autor']['num_imagens']);

		$autor['autor']['links'] = $this->autordao->getSitesUsuario($this->cod_autor);
		$autor['autor']['grupos'] = $this->autordao->getGrupoRelacionadoAutor($this->cod_autor);
		//$autor['autor']['colaboradores'] = $this->autordao->getColaboradorRelacionadoAutor($this->cod_autor);
		$autor['autor']['colaboradores'] = $this->autordao->getColaboradoresRepresentantes($this->cod_autor);
		$autor['autor']['noticia'] = $this->notdao->getUltimasNoticias(array(), 2);
		$autor['autor']['comunicadores'] = $this->colabdao->getComunicadoresUsuario($this->cod_autor);

        foreach((array)$this->contdao->getMaisAcessados('acessos', 0, $this->cod_autor, 0, 0, 6) as $key => $value) {
        	$autor['autor']['mais_acessados'][] = array(
				'cod_conteudo' => $value['cod_conteudo'],
				'autores' => Util::getHtmlListaAutores($value['cod_conteudo']),
				'canal'	=> Util::getHtmlCanal($value['cod_segmento']),
				'cod_formato' => $value['cod_formato'],
				'titulo' => $value['titulo'],
				'data_cadastro' => $value['data_cadastro'],
				'datahora' => $value['datahora'],
				'descricao' => $value['descricao'],
				'imagem' => $value['imagem'],
				'cod_colaborador' => $value['cod_colaborador'],
				'num_acessos' => $value['num_acessos'],
				'num_recomendacoes' => $value['num_recomendacoes'],
				'imagem_album' => $value['imagem_album'],
				'colaborador' => $value['colaborador'],
				'url_colaborador' => $value['url_colaborador'],
				'url' => $value['url'],
				'video' => $this->getConteudoVideo($value['cod_conteudo'])
			);
        }

        include('includes/include_visualizacao_autor.php');
    }

	private function getConteudoVideo($codconteudo) {
		include_once(ConfigPortalVO::getDirClassesRaiz()."dao/VideoDAO.php");
		$contdao = new VideoDAO;
		return $contdao->getArquivoVideo($codconteudo);
	}

}
