<?php

class NavegacaoUtil {

	public static function menuAtivo($itemmenu, $comparar) {
		return ($itemmenu == $comparar) ? " class=\"active\"" : "";
	}

	public static function geraHtmlPaginas($endereco, $dados_form, $total, $javascript = false) {
		$html = "";

		$pagina_atual = (int)$dados_form["pagina"];
		if (!$pagina_atual)
			$pagina_atual = 1;
		$intervalo = (int)$dados_form["intervalo"];
		if (!$intervalo)
			$intervalo = 10;

		$num_paginas = floor(($total - 1) / $intervalo) + 1;

		$html_array = array();
		if ($pagina_atual > 1) {
			if (!$javascript)
				$html_array[] = "<a href=\"".self::geraLinkNavegacao($dados_form, $endereco, $pagina_atual - 1)."\">&laquo; Anterior</a>";
			else
				$html_array[] = "<a href=\"javascript:".$endereco."(".($pagina_atual - 1).")\">&laquo; Anterior</a>";
		}
		for ($i = 1; $i <= $num_paginas; $i++) {
			if ($pagina_atual == $i)
				$html_array[] = "<strong>".$i."</strong>";
			else {
				if (!$javascript)
					$html_array[] = "<a href=\"".self::geraLinkNavegacao($dados_form, $endereco, $i)."\">".$i."</a>";
				else
					$html_array[] = "<a href=\"javascript:".$endereco."(".$i.")\">".$i."</a>";
			}
		}
		if ($pagina_atual < $num_paginas) {
			if (!$javascript)
				$html_array[] = "<a href=\"".self::geraLinkNavegacao($dados_form, $endereco, $pagina_atual + 1)."\">Pr&oacute;xima &raquo;</a>";
			else
				$html_array[] = "<a href=\"javascript:".$endereco."(".($pagina_atual + 1).")\">Pr&oacute;xima &raquo;</a>";
		}

		//$html = implode(", ", $html_array);
		$html = implode(" ", $html_array);

		return $html;
	}

	private static function geraLinkNavegacao($dados_form, $endereco, $pagina_nova) {
		$dados_form["pagina"] = $pagina_nova;
		return $endereco."?".http_build_query($dados_form);
	}

}
