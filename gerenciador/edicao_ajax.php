<?php
$dom = new DOMDocument("1.0", "iso-8859-1");

$acao = trim($_GET["acao"]);
switch ($acao) {
	case "getcidades":
		$codestado = (int)$_GET["codestado"];
		include_once("classes/bo/AutorEdicaoBO.php");
		$colabbo = new AutorEdicaoBO;
		$lista_cidades = $colabbo->getListaCidades($codestado);
		$elem_cidades = $dom->createElement("cidades");
		foreach ($lista_cidades as $cidade) {
			$elem_cidade = $dom->createElement("cidade", utf8_encode($cidade["cidade"]));
			$elem_cidade->setAttribute("cod", $cidade["cod_cidade"]);
			$elem_cidades->appendChild($elem_cidade);
		}
		$dom->appendChild($elem_cidades);
	break;
	case "getcolaboradores":
        $nome = trim($_GET["nomebusca"]);
		$cod = (int)$_GET["cod"];
		include_once("classes/bo/ColaboradorEdicaoBO.php");
		$colabbo = new ColaboradorEdicaoBO;
		$lista_colaboradores = $colabbo->getColaboradoresBuscaNome($nome, $cod);
		$elem_colaboradores = $dom->createElement("colaboradores");
		foreach ($lista_colaboradores as $colaborador) {
			$elem_colaborador = $dom->createElement("colaborador", utf8_encode($colaborador["nome"]));
			$elem_colaborador->setAttribute("cod", $colaborador["cod"]);
			$elem_colaboradores->appendChild($elem_colaborador);
		}
		$dom->appendChild($elem_colaboradores);
	break;
	case "getautores":
        $trecho = trim($_GET["trecho"]);
		include_once("classes/bo/AutorEdicaoBO.php");
		$autbo = new AutorEdicaoBO;
		$lista_autores = $autbo->getAutoresBuscaNome($trecho);
		$elem_autores = $dom->createElement("autores");
		foreach ($lista_autores as $autor) {
			$elem_autor = $dom->createElement("autor", utf8_encode($autor["nome"]));
			$elem_autor->setAttribute("cod", $autor["cod_autor"]);
			$elem_autores->appendChild($elem_autor);
		}
		$dom->appendChild($elem_autores);
	break;
	case "getdadosautores":
        $lista_autores = $_GET["autores"];
		if (!is_array($lista_autores))
			$lista_autores = array();
		include_once("classes/bo/AutorEdicaoBO.php");
		$autbo = new AutorEdicaoBO;
		$lista_autores = $autbo->getListaDadosAutores($lista_autores);
		$elem_autores = $dom->createElement("autores");
		foreach ($lista_autores as $autor) {
			$elem_autor = $dom->createElement("autor");
			$elem_autor->setAttribute("cod", $autor["cod_autor"]);
			$elem_autor->setAttribute("nome", utf8_encode($autor["nome"]));
			$elem_autor->setAttribute("nomeartistico", utf8_encode($autor["nomeartistico"]));
			$elem_autor->setAttribute("estado", $autor["sigla"]);
			$elem_autor->setAttribute("foto", $autor["foto"]);
			$elem_autores->appendChild($elem_autor);
		}
		$dom->appendChild($elem_autores);
	break;
	case "getenderecopagina":
		$endereco = trim($_GET['endereco']);
		$tipo = trim($_GET['tipo']);
		if ($tipo == 'autor') {
			include_once("classes/bo/AutorEdicaoBO.php");
			$autorbo = new AutorEdicaoBO;
			$finalendereco = $autorbo->existeFinalEndereco($endereco);
			$t = $dom->createElement("lista");
				$retorna = $dom->createElement("status", (int)$finalendereco);
				$retorna->setAttribute("cod", (int)$finalendereco);
				$t->appendChild($retorna);
		   	$dom->appendChild($t);
		}
        if ($tipo == 'colaborador') {
			include_once("classes/bo/ColaboradorEdicaoBO.php");
			$colabbo = new ColaboradorEdicaoBO;
			$finalendereco = $colabbo->existeFinalEndereco($endereco);
			$t = $dom->createElement("lista");
				$retorna = $dom->createElement("status", (int)$finalendereco);
				$retorna->setAttribute("cod", (int)$finalendereco);
				$t->appendChild($retorna);
		   	$dom->appendChild($t);
		}
	break;
}

header("Content-type: application/xml");
echo $dom->saveXML();
