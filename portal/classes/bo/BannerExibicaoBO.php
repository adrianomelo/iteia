<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/BannerDAO.php");

class BannerExibicaoBO {

	private $bandao = null;

	public function __construct() {
		$this->bandao = new BannerDAO;
	}

	public function getHtmlBannersLaterais() {
		$html = '';
		$html .= "<ul>\n";

		for ($i = 1; $i <= 3; $i++) {
			$dados_banner = $this->bandao->mostrarBanner();
			if ($dados_banner['cod_banner']) {
				$html .= "<li".($i == 3 ? ' class="no-margin-r"' : '').">";
				if ($dados_banner['link'])
					$html .= "<a href=\"http://".$dados_banner['link']."\" target=\"_blank\" title=\"".$dados_banner['titulo']."\">";
				$html .= "<img src=\"/exibir_imagem?img=".$dados_banner['arquivo']."&amp;tipo=a&amp;s=39\" alt=\"".$dados_banner['titulo']."\" />";
				if ($dados_banner['link'])
					$html .= "</a>";
				$html .= "</li>\n";
			}
		}

		$html .= "</ul>\n";
		return $html;
	}

	public function getHtmlBannersSuperior() {
		$html = '';

		$dados_banner = $this->bandao->mostrarBanner();
		if ($dados_banner['cod_banner']) {

		$html .= "<ul id=\"banner_top\">\n";


		for ($i = 1; $i <= 4; $i++) {
			$dados_banner = $this->bandao->mostrarBanner();
			//if ($dados_banner['cod_banner']) {
				$html .= "<li".Util::iif($i == 4, ' class="margin-none"').">";
				if ($dados_banner['link'])
					$html .= "<a href=\"http://".$dados_banner['link']."\" target=\"_blank\" title=\"".$dados_banner['titulo']."\">";
				if ($dados_banner['arquivo'])
					$html .= "<img src=\"/exibir_imagem?img=".$dados_banner['arquivo']."&amp;tipo=a&amp;s=18\" alt=\"".$dados_banner['titulo']."\" />";
				if ($dados_banner['link'])
					$html .= "</a>";
				$html .= "</li>\n";
			//}
		}

		$html .= "</ul>\n";

		}

		return $html;
	}

}
