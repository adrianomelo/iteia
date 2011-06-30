<?php
require_once("ConexaoDB.php");

class BannerDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function cadastrar(&$banvo) {
		$query = $this->banco->sql_insert('Banners', array('cod_usuario' => $_SESSION['logado_dados']['cod_colaborador'], 'cod_sistema' => ConfigVO::getCodSistema(), 'titulo' => $banvo->getTitulo(), 'link' => $banvo->getUrl(), 'peso' => $banvo->getPrioridade(), 'data_inicial' => $banvo->getDataInicial(), 'data_final' => $banvo->getDataFinal(), 'home' => $banvo->getHome(), 'situacao' => '1'));
		return $this->banco->insertId();
	}

	public function atualizar(&$banvo) {
		$query = $this->banco->sql_update('Banners', array('titulo' => $banvo->getTitulo(), 'link' => $banvo->getUrl(), 'peso' => $banvo->getPrioridade(), 'data_inicial' => $banvo->getDataInicial(), 'data_final' => $banvo->getDataFinal(), 'home' => $banvo->getHome()), "cod_banner='".$banvo->getCodBanner()."'");
		return $banvo->getCodBanner();
	}

	public function getBannerVO(&$codbanner) {
		$sql = "SELECT t1.*, t2.nome, t3.titulo AS url FROM Banners AS t1 LEFT JOIN Usuarios AS t2 ON (t1.cod_usuario=t2.cod_usuario) INNER JOIN Urls AS t3 ON (t2.cod_usuario=t3.cod_item) WHERE t1.cod_banner='".$codbanner."' AND t3.tipo='1'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();

		$banvo = new BannerVO;
		$banvo->setCodBanner($sql_row["cod_banner"]);
		$banvo->setTitulo($sql_row["titulo"]);
		$banvo->setImagem($sql_row["arquivo"]);
		$banvo->setHome($sql_row["home"]);
		$banvo->setUrl($sql_row["link"]);
		$banvo->setPrioridade($sql_row["peso"]);
		$banvo->setDataInicial($sql_row["data_inicial"]);
		$banvo->setDataFinal($sql_row["data_final"]);
		$banvo->setCodColaborador($sql_row["cod_colaborador"]);
		$banvo->setColaborador("<a href=\"".ConfigVO::URL_SITE.$sql_row['url']."\" target=\"_blank\" class=\"ext\" title=\"Visite a página deste colaborador\">".$sql_row['nome']."</a>");
		return $banvo;
	}

	public function atualizarFoto($nomearquivo, $codbanner) {
		$sql = "UPDATE Banners SET arquivo = '".$nomearquivo."' WHERE cod_banner = '".$codbanner."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getListaBanners($dados, $inicial, $mostrar) {
		$array = array();
		extract($dados);

		$array['link'] = "banners.php?buscar=$buscar&amp;palavrachave=$palavrachave&amp;buscarpor=$buscarpor&amp;situacao=$situacao&amp;de=$de&amp;ate=$ate";
		$where = "WHERE t1.excluido='0' AND t1.cod_sistema='".ConfigVO::getCodSistema()."'";

		if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6))
		    $where .= " AND t1.cod_usuario='".$_SESSION['logado_dados']['cod_colaborador']."'";

		if ($buscar) {
			if ($palavrachave && $palavrachave != 'Buscar') {
				switch($buscarpor) {
					case "titulo":
						$where .= " AND t1.titulo LIKE '%$palavrachave%'";
					break;
                    case "colaborador":
						$where .= " AND t2.nome LIKE '%$palavrachave%'";
					break;
					default: $where .= " AND (t1.titulo LIKE '%$palavrachave%' OR t2.nome LIKE '%$palavrachave%')"; break;
				}
			}
			if ($situacao) {
				if ($situacao != 0)
					$where .= " AND t1.situacao='$situacao'";
			}
			if ($de) {
				$data1 = explode('/', $de);
				if (checkdate($data1[1], $data1[0], $data1[2])) {
					$datainicial = $data1[2].'-'.$data1[1].'-'.$data1[0];
				}
				if ($ate) {
					$data2 = explode('/', $ate);
                    if (checkdate($data2[1], $data2[0], $data2[2])) {
						$datafinal = $data2[2].'-'.$data2[1].'-'.$data2[0];
					}
				}
				if ($datainicial && $datafinal)
					$where .= " AND (t1.data_inicial >= '$datainicial' AND t1.data_final <= '$datafinal')";
			}
		}

		$sql = "SELECT t1.cod_banner, t1.titulo, t1.peso, t1.situacao, t2.nome FROM Banners AS t1 LEFT JOIN Usuarios AS t2 ON (t1.cod_usuario=t2.cod_usuario) $where";

		$pesoArray = array(1 => 'Alta', 2 => 'Média', 3 => 'Baixa');
		$array['total'] = $this->banco->numRows($this->banco->executaQuery("$sql"));

		//echo $sql;

		$query = $this->banco->executaQuery("$sql ORDER BY cod_banner DESC LIMIT $inicial,$mostrar");
		while ($row = $this->banco->fetchArray($query)) {

			switch($row['situacao']) {
                case 2:	$situacao = '<span class="inativo" title="Inativo">Inativo</span>'; break;
                case 1:	$situacao = '<span class="ativo" title="Ativo">Ativo</span>'; break;
			}

			$array[] = array(
				'cod' 		 => $row['cod_banner'],
				'titulo' 	 => $row['titulo'],
				'nome' 		 => $row['nome'],
				'situacao' 	 => $situacao,
				'prioridade' => $pesoArray[$row['peso']]
			);
		}
		return $array;
	}

	public function executaAcoes($acao, $codbanner='', $prioridade='') {
		if ($acao) {
			switch($acao) {
				case 1: // apagar
					if (count($codbanner))
						$this->banco->executaQuery("UPDATE Banners SET excluido='1' WHERE cod_banner IN (".implode(',', $codbanner).")");
				break;
                case 2: // ativar
					if (count($codbanner))
						$this->banco->executaQuery("UPDATE Banners SET situacao='1' WHERE cod_banner IN (".implode(',', $codbanner).")");
				break;
                case 3: // desativar
					if (count($codbanner))
						$this->banco->executaQuery("UPDATE Banners SET situacao='2' WHERE cod_banner IN (".implode(',', $codbanner).")");
				break;
				case 4: // mudar prioridade
					if (count($codbanner)) {
						$this->banco->executaQuery("UPDATE Banners SET peso='".$prioridade."' WHERE cod_banner IN (".implode(',', $codbanner).")");;
					}
			}
		}
	}

	public function mostrarBanner($home=0) {
		if ($home)
			$and = " AND home='1'";
		$sql_banners_geral = "SELECT * FROM Banners WHERE excluido='0' AND situacao='1' AND (data_inicial = '0000-00-00' OR (data_inicial != '0000-00-00' AND data_inicial <= '".date("Y-m-d")."')) AND (data_final = '0000-00-00' OR (data_final != '0000-00-00' and data_final >= '".date("Y-m-d")."')) and cod_sistema='".ConfigVO::getCodSistema()."' $and";

		if (!count($GLOBALS["banners_exibidos"]))
			$GLOBALS["banners_exibidos"] = array(0);

		$banner_selecionado = $this->getBannerAleatorio($sql_banners_geral." AND cod_banner NOT IN (".implode(", ", $GLOBALS["banners_exibidos"]).")");

		if (!$banner_selecionado)
			$banner_selecionado = $this->getBannerAleatorio($sql_banners_geral);

		if ($banner_selecionado) {
			$GLOBALS["banners_exibidos"][] = $banner_selecionado;
			$sql_result = $this->banco->executaQuery("SELECT cod_banner, arquivo, link, titulo from Banners WHERE cod_banner='".$banner_selecionado."'");
			$sql_row = $this->banco->fetchArray($sql_result);
			return $sql_row;
		}
		return array();
	}

	private function getBannerAleatorio($sql) {
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			for ($i = 1; $i <= $sql_row["peso"]; $i++)
				$banners[] = $sql_row["cod_banner"];
		}
		$indice_selecionado = mt_rand(1, count($banners));
		return (int)$banners[$indice_selecionado - 1];
	}

}
