<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/NoticiaDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");

class NoticiaExibicaoBO {

    private $notdao = null;
    private $contdao = null;
	private $datas_indices = array();

    public function __construct() {
        $this->notdao = new NoticiaDAO;
        $this->contdao = new ConteudoDAO;
    }

    public function exibirConteudo($codconteudo, $conteudo) {
        $conteudo['noticia'] = $this->notdao->getNoticiaDados($codconteudo);

        if ($conteudo['conteudo']['imagem']) {
            $valores = getimagesize(ConfigVO::getDirFotos().$conteudo['conteudo']['imagem']);
			$conteudo['noticia']['largura'] = min($valores[0], 200);
			$size = 8;
			if ($conteudo['noticia']['largura'] < 200)
				$size = 0;
            $conteudo['noticia']['imagem'] = "exibir_imagem.php?img=".$conteudo['conteudo']['imagem']."&amp;tipo=5&amp;s=".$size;
        }

        include('includes/include_visualizacao_noticia.php');
	}

	public function getGroupNoticias($inicial, $mostrar, $codconteudo=0) {
		return $this->notdao->getGroupNoticias($inicial, $mostrar, $codconteudo);
	}

	public function getGroupNoticiasDatas($pagina) {
		//if (!$pagina) //lista invertida
			//$pagina = count($this->datas_indices); //lista invertida
		//$data = $this->datas_indices[$pagina - 1]; //lista invertida
		$data = $this->datas_indices[$pagina];
		return $this->notdao->getGroupNoticiasDatas($data);
	}

	public function getPaginasNoticias($pagina_atual) {
		$this->datas_indices = $this->notdao->getPaginasNoticias();
		//$this->datas_indices = array_reverse($this->datas_indices); //lista invertida
		//if (!$pagina_atual) //lista invertida
			//$pagina_atual = count($this->datas_indices) - 1; //lista invertida
		$faixa_limite = 7;
		$total_paginas = count($this->datas_indices) - 1;
		$tag1 = '<strong class="local">';
		$tag2 = '</strong>';
		$link_paginas = 'noticias.php?n=1';

		$html = '<ul id="paginacao"><li id="anterior">';
		if ($pagina_atual > 1)
			$html .= '<a href="'.$link_paginas.'&amp;pagina='.($pagina_atual - 1).'">&laquo; Anterior</a>';
		//if ($pagina_atual < $total_paginas) //lista invertida
			//$html .= '<a href="'.$link_paginas.'&amp;pagina='.($pagina_atual + 1).'">&laquo; Anterior</a>'; //lista invertida
		else
			$html .= '<span>&laquo; Anterior</span>';
		$html .= '</li>';
		$html .= '<li id="item">'."\n";

		$paginic = (int)($pagina_atual - ($faixa_limite / 2));
		if ($paginic < 1) $paginic = 1;
		$pagfim = $paginic + $faixa_limite;
		if ($pagfim > $total_paginas) $pagfim = $total_paginas;

		for ($i = 1; $i <= $total_paginas; $i++) {
		//for ($i = $total_paginas; $i >= 1; $i--) { //lista invertida
			$prefixo = '';
			$sufixo = '';
			if (($i == 1) && ($paginic > 2))
				$sufixo = '..';
				//$prefixo = '..'; //lista invertida
			if (($i == $total_paginas) && ($pagfim < ($total_paginas - 1)))
				$prefixo = '..';
				//$sufixo = '..'; //lista invertida

			if (($i == 1) || ($i >= $paginic) && ($i <= $pagfim) || ($i == $total_paginas)) {
				if ($pagina_atual == $i)
					$html .= $prefixo.' '.$tag1.$i.$tag2.' '.$sufixo;
				else
					$html .= $prefixo.' <a href="'.$link_paginas.'&amp;pagina='.$i.'" '.$estilo_link.'>'.$tag1_link.$i.$tag2_link.'</a> '.$sufixo;
			}
		}

		$html .= '</li><li id="proximo">';
		if ($pagina_atual < $total_paginas)
			$html .= '<a href="'.$link_paginas.'&amp;pagina='.($pagina_atual + 1).'">Pr&oacute;xima &raquo;</a>';
		//if ($pagina_atual > 1) //lista invertida
			//$html .= '<a href="'.$link_paginas.'&amp;pagina='.($pagina_atual - 1).'">Pr&oacute;xima &raquo;</a>'; //lista invertida
		else
			$html .= '<span>Pr&oacute;xima &raquo;</span>';
		$html .= '</li></ul>'."\n";
		return $html;
	}

}
