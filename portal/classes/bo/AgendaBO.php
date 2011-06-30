<?php
include_once("classes/vo/ConfigPortalVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."vo/ConfigVO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/AgendaDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ConteudoExibicaoDAO.php");
include_once(ConfigPortalVO::getDirClassesRaiz()."dao/ComentariosDAO.php");

class AgendaBO {

    private $agedao = null;
    private $contdao = null;
	public $comentdao = null;
	private $linkagenda = 'eventos';

    public function __construct() {
        $this->agedao = new AgendaDAO;
        $this->contdao = new ConteudoExibicaoDAO;
		$this->comentdao = new ComentariosDAO;
    }

    public function getListaAgenda($get, $inicial, $mostrar) {
        return $this->agedao->getListaAgendaPortal($get, $inicial, $mostrar);
    }

	public function getHtmlCalendario($mes, $dia) {

		if (!$dia) $dia = date("Ymd");
		if (!$mes) $mes = date("Ym");

		$month = substr($mes, 4, 2);
		$year = substr($mes, 0, 4);

		if (!$month) {
			$datearray = getdate();
			$month = $datearray["mon"];
			$year = $datearray["year"];
		}
		$diaagora = substr($dia, 8, 2);

		$start = mktime(0, 0, 0, $month, 1, $year);
		$firstdayarray = getdate($start);

		$months = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		$days = array("D", "S", "T", "Q", "Q", "S", "S");

		$mesprox = date("Ym", mktime(0, 0, 0, ($month + 1), 1, $year));
		$mesant = date("Ym", mktime(0, 0, 0, ($month - 1), 1, $year));

		$texto_html = "";
		$texto_html .= "<div id=\"calendario\">\n";
		$texto_html .= "<table class=\"calendar\" summary=\"Calendário contendo eventos do portal iTEIA\">\n";
		$texto_html .= "<caption>Eventos do mês de ".htmlentities($months[$month - 1])." de ".$year."</caption>\n";
		$texto_html .= "<thead><tr>\n";
		$texto_html .= "<th scope=\"col\" class=\"d1\" title=\"Domingo\">D</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d2\" title=\"Segunda\">S</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d3\" title=\"Terça\">T</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d4\" title=\"Quarta\">Q</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d5\" title=\"Quinta\">Q</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d6\" title=\"Sexta\">S</th>\n";
		$texto_html .= "<th scope=\"col\" class=\"d7\" title=\"Sábado\">S</th>\n";
		$texto_html .= "</tr>\n</thead>\n<tbody>\n";

		$contdia = 2;
		for ($count = 0; $count < (6*7); $count++) {

			$dayarray = getdate($start);

			if ((($count) % 7) == 0) {
				if ($dayarray["mon"] != $month) break;
				$texto_html .= "</tr><tr>\n";
			}

			$classiniciodia = '';
			$classfimdia = '';

			// proximo dia é o ultimo da semana
			if ((($count + 1) % 7) == 0) {
				$classfimdia = 'd7';
			// proximo dia é o inicio da semana
			} elseif ((($count) % 7) == 0) {
				$classiniciodia = 'd1';
			}

			if ($count < $firstdayarray["wday"] || $dayarray["mon"] != $month)
				$texto_html .= "<td>&nbsp;</td>\n";

			else {
			    $dhoje = date("d");
				$diacal = $dayarray["mday"];
				$tdc = '';
				if($diacal == $dhoje){
					$tdc='today';// title="Hoje"';
				}

				if (strlen($diacal) < 2)
					$diacal = "0".$diacal;
				$bgdia = "";

				$mescal = $dayarray["mon"];
				if (strlen($mescal) < 2)
					$mescal = "0".$mescal;

				$texto_dia = $diacal;
				if (($year."-".$mescal."-".$diacal) == $dia)
					$texto_dia = "<strong>".$diacal."</strong>";
				elseif ($this->agedao->ExisteAgenda($year."-".$mescal."-".$diacal, 0))
					$texto_dia = "<a href=\"/".$this->linkagenda."?dia=".$year."-".$mescal."-".$diacal."&amp;mes=".$mes."\">".$diacal."</a>";

				$texto_html .= "<td class=\"$tdc $classiniciodia $classfimdia\"  >".$texto_dia."</td>\n";

				$start = mktime(0, 0, 0, $month, $contdia, $year);
				$contdia++;
			}
		}

		$mesprox = date("Ym", mktime(0, 0, 0, ($month + 1), 1, $year));
		$mesant = date("Ym", mktime(0, 0, 0, ($month - 1), 1, $year));

		$mesprox_texto = $months[(int)$month];
		if ((int)$month > 11) $mesprox_texto = $months[0];
		$mesant_texto = $months[$month - 2];
		if ((int)$month < 2) $mesant_texto = $months[11];

		$texto_html .= "</tr></tbody></table>\n";

		$texto_html .= "<div id=\"controle\">\n";
		$texto_html .= "<label for=\"controle-mes\">Mês</label>\n";
        $texto_html .= "<select id=\"controle-mes\" onchange=\"javascript:mudaCalendario();\">\n";
        for ($i = 0; $i < 12; $i++)
			$texto_html .= "<option value=\"".(($i + 1) < 10 ? "0" : "").($i + 1)."\" ".(($i + 1) == $month ? "selected=\"selected\"" : "").">".$months[(int)$i]."</option>\n";
		$texto_html .= "</select>\n";

        $texto_html .= "<label for=\"controle-ano\">Ano</label>\n";
        $texto_html .= "<select id=\"controle-ano\" onchange=\"javascript:mudaCalendario();\">\n";
        for ($i = 2009; $i <= date('Y'); $i++)
			$texto_html .= "<option value=\"".$i."\" ".($i == $year ? "selected=\"selected\"" : "").">".$i."</option>\n";
		$texto_html .= "</select>\n";
        $texto_html .= "</div>\n";
		$texto_html .= "</div>\n";

		//$texto_html .= "<div id=\"doismeses\">\n";
		//$texto_html .= "<a href=\"".$this->linkagenda."?dia=".$dia."&amp;mes=".$mesant."\" title=\"".htmlentities($mesant_texto)."\" id=\"mesant\">&laquo; ".htmlentities($mesant_texto)."</a>\n";
		//$texto_html .= "<a href=\"".$this->linkagenda."?dia=".$dia."&amp;mes=".$mesprox."\" title=\"".htmlentities($mesprox_texto)."\" id=\"mesprox\">".htmlentities($mesprox_texto)." &raquo;</a>\n";
		//$texto_html .= "</div>\n";

		return $texto_html;
	}

    public function getDadosAgenda($codconteudo) {
		$conteudo = $this->agedao->getDadosAgenda($codconteudo);
		$conteudo['relacionado_tags'] = $this->contdao->getConteudoRelacionadoTags($codconteudo);
        $conteudo['comentarios'] = $this->comentdao->getQtsComentarios($codconteudo);
		$conteudo['canal'] = Util::getHtmlCanal($conteudo['cod_segmento']);
        $conteudo['tags'] = $this->getTagsConteudo($codconteudo);
		return $conteudo;
	}

	private function getTagsConteudo($codconteudo) {
		$html  = '';
		foreach($this->contdao->getTagsConteudoNovo($codconteudo) as $tag)
			$html .= (($html != '') ? ' ' : ' ').'<a href="/busca_resultado?buscar=1&amp;tag='.urlencode($tag['tag']).'" class="common0 size0">'.$tag['tag'].'</a>';
		return $html;
	}

}