<?php
include('verificalogin.php');

//if ($_SESSION['logado_como'] == 1)
//if ($_SESSION['logado_dados']['nivel'] == 2)
	Header('Location: cadastro_autor_publicado.php?cod='.$_SESSION['logado_cod'].'&proprio=1&visual=1');
//else
	//Header('Location: cadastro_colaborador_publicado.php?cod='.$_SESSION['logado_cod'].'&proprio=1');

die;
