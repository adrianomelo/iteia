<?php
foreach ($lista_autores as $key => $value) {
	if ((int)$value['cod'])
		echo utf8_encode($value['nome'].' - '.$value['cidade'].' - '.$value['estado'].' |'.$value['cod'])."\n";
}