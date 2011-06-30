<?php
include_once("../classes/dao/ConexaoDB.php");
include_once('classes/vo/ConfigPortalVO.php');

$banco = ConexaoDB::singleton();

/*
$sql = "select * from Usuarios_Atividades where cod_sistema=1";
$query = $banco->executaQuery($sql);
while ($row = $banco->fetchArray($query))
	$banco->executaQuery("insert into Usuarios_Atividades values (NULL, 6, '".$row['atividade']."', '".$row['excluido']."')");

$sql = "select * from Conteudo_Classificacao where cod_sistema=1";
$query = $banco->executaQuery($sql);
while ($row = $banco->fetchArray($query))
	$banco->executaQuery("insert into Conteudo_Classificacao values (NULL, '".$row['cod_formato']."', 6, '".$row['nome']."', '".$row['descricao']."', '".$row['quantidade']."', '".$row['disponivel']."')");
*/

// segmentos -> canais
$sql = "select * from Conteudo_Segmento where cod_sistema=1";
$query = $banco->executaQuery($sql);
while ($row = $banco->fetchArray($query))
	$banco->executaQuery("insert into Conteudo_Segmento values (NULL, 0, 6, '".$row['nome']."', '".$row['descricao']."', '".$row['imagem']."', '".$row['verbete']."', 0, 1)");

// tags para tags
$sql = "select * from Tags where cod_sistema=1";
$query = $banco->executaQuery($sql);
while ($row = $banco->fetchArray($query))
	$banco->executaQuery("insert into Tags values (NULL, 6, '".$row['tag']."')");

echo 'ok';