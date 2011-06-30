<div id="ficha">
    <div class="trecho no-border">
		<h2>Outras notí­cias</h2>
<ul>
<?php
$cont = 1;
$totalnot = count($noticias_datas);
foreach ($noticias_datas as $not) {
	if ($cont == 1) {
		$cont++;
		continue;
	}
	//if ($conteudo['conteudo']['cod_conteudo'] != $not['cod']) {
?>
<li<?=(($cont == $totalnot)?' class="no-border no-margin-b no-padding-b"':'')?>><p><small><?=$not['periodo']?></small> <br /><a href="/<?=$not['url'];?>"<?=(($cont == $totalnot)?' class="ultimas"':'')?>><?=$not['titulo'];?></a></p>
<?php
	//}
	$cont++;
}
?>
</ul>
</div>
<div id="navegacao" class="trecho">
<ul>
<?php
//if($ultima>10){
if ($paginacao['anterior']['num']) {
?>
	<li class="anterior"><a href="/<?=$conteudo['conteudo']['url'].'?pagina='.($pagina - 1)?>"><span><strong>Anterior</strong></span></a></li>
<?php
}
else {
?>
	<li class="anterior inativo"><span><strong>Anterior</strong></span></li>
<?php
}
if ($paginacao['proxima']['num']) {
?>
	<li class="proxima"><a href="/<?=$conteudo['conteudo']['url'].'?pagina='.($pagina + 1)?>"><strong>Próxima</strong></a></li>
<?php
}
else {
?>
	<li class="proxima inativo"<span><strong>Próxima</strong></span></li>
<?php
}
//}
?>

</ul>
</div>
</div>