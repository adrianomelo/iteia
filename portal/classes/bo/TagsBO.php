<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoDAO.php");

class TagsBO {

    private $contdao = null;
	private $tamanho_minimo = 0;
	private $tamanho_maximo = 5;

    public function __construct() {
        $this->contdao = new ConteudoDAO;
    }

	public function getHtmlTagsPopulares($limite, $ordem) {
		$lista_tags = $this->contdao->getTagsPopulares($limite, (!$ordem ? 'total desc' : ($ordem == 1 ? 'tag asc ' : 'total desc')));
		$maior_tag = 0;
		$menor_tag = 100000;
		foreach ($lista_tags as $tag) {
			$maior_tag = max($maior_tag, $tag['total']);
			$menor_tag = min($menor_tag, $tag['total']);
		}
		$spread = $maior_tag - $menor_tag;
		$step = ($this->tamanho_maximo - $this->tamanho_minimo) / $spread;

		if (!$ordem)
			shuffle($lista_tags);
		$lista_tags_html = array();
		foreach ($lista_tags as $tag)
			$lista_tags_html[] = '<a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;tag='.urlencode($tag['tag']).'" class="size'.round($this->tamanho_minimo + (($tag['total'] - $menor_tag) * $step)).'">'.$tag['tag'].'</a>';

		return "<p>".implode(' ', $lista_tags_html)."</p>";
	}

}