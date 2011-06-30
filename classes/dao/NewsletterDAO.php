<?php
require_once("ConexaoDB.php");

class NewsletterDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function cadastrarNewsletter(&$newsvo) {
		$this->banco->sql_insert('Home_Newsletter', array('titulo' => $newsvo->getTitulo(), 'data_inicio' => $newsvo->getDataInicio(), 'hora_inicio' => $newsvo->getHoraInicio(), 'data_cadastro' => date('Y-m-d H:i:s')));
		$codnewsletter = $this->banco->insertId();
		
		$this->banco->sql_update('Home_Newsletter_Itens', array('cod_newsletter' => $codnewsletter, 'disponivel' => 1), "cod_item IN (".implode(',', $newsvo->getListaItens()).")");
		
		//$this->banco->sql_insert('Home_Newsletter_Programacao', array(
		//	'cod_newsletter' 	=> $codnewsletter,
		//	'data_envio' 		=> $newsvo->getDataEnvio()
		//));
		
		$this->banco->sql_insert('Home_Newsletter_Programacao_Lista', array(
			'cod_newsletter' 	=> $codnewsletter,
			'envio_para' 		=> $newsvo->getEnvioPara()
		));
		
		return $codnewsletter;
	}
	
	public function atualizarNewsletter(&$newsvo) {
		$this->banco->sql_update('Home_Newsletter', array('titulo' => $newsvo->getTitulo(), 'data_inicio' => $newsvo->getDataInicio(), 'hora_inicio' => $newsvo->getHoraInicio()), "cod_newsletter='".$newsvo->getCodNewsletter()."'");
		
		$this->banco->sql_update('Home_Newsletter_Itens', array('cod_newsletter' => $newsvo->getCodNewsletter(), 'disponivel' => 1), "cod_item IN (".implode(',', $newsvo->getListaItens()).")");
		
		$this->banco->sql_update('Home_Newsletter_Programacao_Lista', array(
			'envio_para' => $newsvo->getEnvioPara()
		),	"cod_newsletter='".$newsvo->getCodNewsletter()."'");
		
		//$this->banco->sql_delete('Home_Newsletter_Programacao', "cod_newsletter='".$newsvo->getCodNewsletter()."'");
		//$this->banco->sql_insert('Home_Newsletter_Programacao', array(
		//	'data_envio' 	=> $newsvo->getDataEnvio(),
		//	'cod_newsletter' => $newsvo->getCodNewsletter()
		//));
		
		return $newsvo->getCodNewsletter();
	}
	
	public function adicionaProgramacao($codnewsletter, $dataenvio, $horaenvio) {
		$this->banco->sql_update('Home_Newsletter', array(
			'enviada'		=> 0,
			'data_inicio' 	=> $dataenvio,
			'hora_inicio' 	=> $horaenvio,
		),	"cod_newsletter='".$codnewsletter."'");
		
		$this->banco->sql_delete('Home_Newsletter_Programacao', "cod_newsletter='".$codnewsletter."'");
		$this->banco->sql_insert('Home_Newsletter_Programacao', array(
			'data_envio' 		=> $dataenvio,
			'enviada'			=> 0,
			'cod_newsletter' 	=> $codnewsletter
		));
	}
	
	public function adicionarConteudoNewsletter($cod, $codnewsletter) {
		$sql = "SELECT cod_item FROM Home_Newsletter_Itens WHERE cod_conteudo='".$cod."' AND cod_newsletter='".$codnewsletter."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		if (!$sql_row["cod_item"]) {
			$sql = "SELECT ordem FROM Home_Newsletter_Itens WHERE cod_item IN (".implode(',', (array)$_SESSION['sessao_newsletter_itens']).") ORDER BY ordem DESC LIMIT 1;";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			$max_ordem = (int)$sql_row[0];
			
			$sql = "SELECT titulo, descricao, imagem, cod_formato FROM Conteudo WHERE cod_conteudo='".$cod."'";
			$sql_result = $this->banco->executaQuery($sql);
			$sql_row = $this->banco->fetchArray($sql_result);
			
			if ($sql_row["cod_formato"] == 2) {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$cod."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				$sql_row['imagem'] = $sql_row2['imagem'];
			}

			$sql = "INSERT INTO Home_Newsletter_Itens (cod_conteudo, cod_newsletter, ordem, disponivel, titulo, descricao, imagem) VALUES ('".$cod."', '".$codnewsletter."', '".($max_ordem + 10)."', 0, '".addslashes(substr($sql_row['titulo'], 0, 100))."', '".strip_tags(addslashes(substr($sql_row['descricao'], 0, 200)))."', '".$sql_row['imagem']."');";
			$sql_result = $this->banco->executaQuery($sql);
			return $this->banco->insertId();
		}
	}
	
	public function atualizaOrdenacao($cod, $move) {
		$move = Util::iif($move == 3, '-15', '15');
		$sql = "update Home_Newsletter_Itens set ordem = ordem + $move where cod_item = '".$cod."';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function organizacaoFinal() {
		$sql = "SELECT ordem, cod_item FROM Home_Newsletter_Itens WHERE cod_item IN (".implode(',', (array)$_SESSION["sessao_newsletter_itens"]).") ORDER BY ordem ASC";
		$resultado = $this->banco->executaQuery($sql);
			
		$i = 10;
		while ($row = $this->banco->fetchArray($resultado)) {
			$sql = "UPDATE Home_Newsletter_Itens SET ordem = '$i' WHERE cod_item = '".$row['cod_item']."'";
			$this->banco->executaQuery($sql);
			$i += 10;
		}
	}
	
	public function getListaConteudoSelecionados($codnewsletter, $todos = true) {
		$lista_conteudo = array();
		$sql = "SELECT HI.cod_item, HI.cod_conteudo, HI.imagem, HI.destaque, CON.cod_formato, HI.titulo, HI.descricao, CON.datahora, HI.ordem FROM Home_Newsletter_Itens HI, Conteudo CON WHERE"; 
		
		$sql .= " HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo";
		
		$sql .= " AND HI.cod_newsletter='".$codnewsletter."' AND HI.cod_item IN (".implode(',', (array)$_SESSION['sessao_newsletter_itens']).")";
			
		$sql .= " ORDER BY HI.ordem DESC;";

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$url_editar = "newsletter_item_playlist_edicao.php?cod=".$sql_row['cod_item']."&pre=1";

			$inicio_img = substr($sql_row["imagem"], 0, 10);

			if ($sql_row["imagem"])
				$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row["imagem"]."&amp;tipo=5&amp;s=1&amp;rand=".md5(microtime())."\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";

			if ($sql_row["cod_formato"] == 2 && $sql_row["imagem"] && $inicio_img == 'imggaleria') {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row["cod_conteudo"]."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				if ($sql_row2["imagem"])
					$sql_row['imagem'] = "<img src=\"exibir_imagem.php?img=".$sql_row2["imagem"]."&amp;tipo=2&amp;s=1&amp;rand=".md5(microtime())."\" width=\"50\" height=\"50\" alt=\"foto miniatura\" />";
			}
			
			if (!$sql_row['imagem'])
				$sql_row['imagem'] = "<img src=\"img/imagens-padrao/mini-texto.jpg\" width=\"50\" height=\"50\" />";

			$sql_row['url_editar'] = $url_editar;
			$lista_conteudo[$sql_row['cod_item']] = $sql_row;
		}
		return $lista_conteudo;
	}
	
	public function getConteudoNewsletter($codnewsletter, $sessao=false, $destaque=false, $demais=false) {
		$lista_conteudo = array();
		$sql = "SELECT HI.cod_item, HI.cod_conteudo, HI.imagem, HI.destaque, CON.cod_formato, HI.titulo, HI.descricao, HI.credito, CON.datahora, HI.ordem, UR.titulo AS url FROM Home_Newsletter_Itens HI, Conteudo CON, Urls UR WHERE"; 
		
		$sql .= " HI.excluido = 0 and HI.cod_conteudo = CON.cod_conteudo and HI.cod_conteudo=UR.cod_item and UR.tipo='4' and UR.cod_sistema='".ConfigVO::getCodSistema()."'";
		
		if ($codnewsletter)
			$sql .= " AND HI.cod_newsletter='".$codnewsletter."'";
			
		if ($destaque)
			$sql .= " AND HI.destaque='1'";
			
		if ($demais)
			$sql .= " AND HI.destaque='0'";
		
		if ($sessao)
			$sql .= " AND HI.cod_item IN (".implode(',', (array)$_SESSION['sessao_newsletter_itens']).")";
			
		$sql .= " ORDER BY HI.ordem ASC;";

		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$inicio_img = substr($sql_row["imagem"], 0, 10);
			if ($sql_row["cod_formato"] == 2 && $sql_row["imagem"] && $inicio_img == 'imggaleria') {
				$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row["cod_conteudo"]."' and A.cod_imagem_capa = I.cod_imagem";
				$sql_result2 = $this->banco->executaQuery($sql);
				$sql_row2 = $this->banco->fetchArray($sql_result2);
				if ($sql_row2["imagem"])
					$sql_row['imagem'] = $sql_row2["imagem"];
				$sql_row['galeria'] = true;
			}
			$lista_conteudo[] = $sql_row;
		}
		return $lista_conteudo;
	}
	
	public function limpaItens() {
		$sql = "DELETE FROM Home_Newsletter_Itens WHERE disponivel='0';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql = "DELETE FROM Home_Newsletter_Itens WHERE cod_newsletter='0';";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function removeConteudoSelecao($lista_selecionadas) {
		$sql = "DELETE FROM Home_Newsletter_Itens WHERE cod_item IN ('".implode("', '", (array)$lista_selecionadas)."');";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function getNewsletterVO($codnewsletter) {
		$sql = "SELECT * FROM Home_Newsletter WHERE cod_newsletter='".$codnewsletter."'";
		
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray();

		$newsvo = new NewsletterVO;
		$newsvo->setCodNewsletter($sql_row["cod_newsletter"]);
		$newsvo->setDataInicio($sql_row["data_inicio"]);
		$newsvo->setHoraInicio($sql_row["hora_inicio"]);
		$newsvo->setTitulo($sql_row['titulo']);
		
		$resultado = $this->banco->sql_select('envio_para', 'Home_Newsletter_Programacao_Lista', "cod_newsletter='".$codnewsletter."'");
		$row = $this->banco->fetchArray($resultado);
		$newsvo->setEnvioPara($row['envio_para']);
		
		$arrayItens = array();
		
		$sql = "SELECT * FROM Home_Newsletter_Itens WHERE cod_newsletter='".$codnewsletter."'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$arrayItens[$sql_row['cod_item']] = $sql_row;
		
		$newsvo->setListaItens($arrayItens);
		
		return $newsvo;
	}
	
	public function getNewslettersProgramadas() {
		$sql = "SELECT cod_newsletter FROM Home_Newsletter WHERE enviada='0' AND excluido='0' AND CONCAT(data_inicio, hora_inicio) <= '".date('Y-m-dH:i').":00'";
		
		$arrayItens = array();	
		
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$arrayItens[$sql_row['cod_newsletter']] = $sql_row['cod_newsletter'];
		
		return $arrayItens;
	}
	
	public function getDadosNewsletter($codnewsletter) {
		$sql = "SELECT * FROM Home_Newsletter WHERE cod_newsletter='".$codnewsletter."'";		
		$sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray();
	}
	
	public function getDadosItem($coditem) {
		$sql = "SELECT cod_item, cod_conteudo FROM Home_Newsletter_Itens WHERE cod_item='".$coditem."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row2 = $this->banco->fetchArray($sql_result);
		
		$sql = "SELECT cod_conteudo, titulo, descricao, imagem, cod_formato FROM Conteudo WHERE cod_conteudo='".$sql_row2['cod_conteudo']."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		
		$sql_row['cod_item'] = $sql_row2['cod_item'];
			
		if ($sql_row["cod_formato"] == 2) {
			$sql = "select I.imagem from Albuns A, Imagens I where A.cod_conteudo = '".$sql_row['cod_conteudo']."' and A.cod_imagem_capa = I.cod_imagem";
			$sql_result3 = $this->banco->executaQuery($sql);
			$sql_row3 = $this->banco->fetchArray($sql_result3);
			$sql_row['imagem'] = $sql_row3['imagem'];
		}
		
		return $sql_row;
	}
	
	public function salvarItens(&$coditens) {
		$sql = "update Home_Newsletter_Itens set disponivel = 1 where cod_item IN (".$coditens.");";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	//public function atualizaEnvio(&$codnewsletter) {
	//	$sql = "update Home_Newsletter set enviada = 1 where cod_newsletter = '".$codnewsletter."';";
	//	$sql_result = $this->banco->executaQuery($sql);
	//}
	
	public function apagaItensInicial() {
		$sql = "delete from Home_Newsletter_Itens where disponivel = 0;";
		$sql_result = $this->banco->executaQuery($sql);
	}
	
	public function getListaEmails() {
		$array = array();
		$sql = "SELECT email FROM Newsletters_Usuarios_Fundarpe";
		$query = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($query))
			$array[] = $row['email'];
		return $array;
	}
	
	public function getNewsletterEnviadasRecentes($inicial, $mostrar) {
		$array =  array();
		$array['resultado'] = array();
		$sql = "select N.cod_newsletter, N.titulo, NP.data_envio from Home_Newsletter N LEFT JOIN Home_Newsletter_Programacao NP ON (N.cod_newsletter=NP.cod_newsletter) where NP.data_envio < '".date('Y-m-d H:i:s')."' AND N.enviada = 1 ORDER BY NP.data_envio DESC";
		
		$sql_result = $this->banco->executaQuery($sql);
		$array['total'] = mysql_num_rows($sql_result);
		$sql_result = $this->banco->executaQuery($sql. " LIMIT ".$inicial.",".$mostrar);
		while ($row = $this->banco->fetchArray($sql_result)) {
			$row['total'] = count($this->getConteudoNewsletter($row['cod_newsletter']));
			$array['resultado'][] = $row;
		}
		return $array;
	}
	
	public function getNewsletterAgendadasRecentes() {
		$array = array();
		$sql = "select N.cod_newsletter, N.titulo, NP.data_envio from Home_Newsletter N LEFT JOIN Home_Newsletter_Programacao NP ON (N.cod_newsletter=NP.cod_newsletter) where /*NP.data_envio >= '".date('Y-m-d H:i:s')."' AND*/ N.enviada = 0 ORDER BY NP.data_envio DESC LIMIT 3;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($sql_result)) {
			$row['total'] = count($this->getConteudoNewsletter($row['cod_newsletter']));
			$array[] = $row;
		}
		return $array;
	}
	
	public function removeProgramacao($codnewsletter) {
		if (count($codnewsletter)) {
			$sql = "delete from Home_Newsletter_Programacao where cod_newsletter in (".implode(',', $codnewsletter).")";
			$sql_result = $this->banco->executaQuery($sql);
		}
	}
	
	public function getCodNewsletterListaProgramada() {
		$array = array();
		$sql = "select NP.cod_newsletter, N.data_cadastro from Home_Newsletter_Programacao AS NP LEFT JOIN Home_Newsletter AS N ON (N.cod_newsletter=NP.cod_newsletter) where NP.data_envio <= '".date('Y-m-d H:i:s')."' AND N.enviada='0' order by NP.data_envio;";
		$sql_result = $this->banco->executaQuery($sql);
		while ($row = $this->banco->fetchArray($sql_result))
			$array[] = $row;
		return $array;
	}
	
	public function getListaProgramada($codnewsletter) {
		$sql = "select envio_para from Home_Newsletter_Programacao_Lista where cod_newsletter='".$codnewsletter."';";
		$sql_result = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($sql_result);
		return $row['envio_para'];
	}
	
	public function atualizaEnvio($codnewsletter) {
		$this->banco->sql_update('Home_Newsletter', array(
			'enviada' => 1
		),	"cod_newsletter='".$codnewsletter."'");
		
		$this->banco->sql_update('Home_Newsletter_Programacao', array(
			'enviada' => 1
		),	"cod_newsletter='".$codnewsletter."'");
	}
	
}