<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/BuscaBO.php');

$buscabo = new BuscaBO;


$pagina = ($_GET['pagina'] ? (int)$_GET['pagina'] : 1);
$mostrar = ($_GET['mostrar'] ? (int)$_GET['mostrar'] : 10);
$inicial = ($pagina - 1) * $mostrar;

if ($_GET['assunto'])
	$assunto = trim($_GET['assunto']);
	
if ($_GET['palavra'])
	$assunto = trim($_GET['palavra']);
	
if ($_GET['local'])
	$assunto = trim($_GET['local']);

if ($_GET['tag'])
	$tag = trim($_GET['tag']);

$classepagatual = ' class="local"';
$resultado = $buscabo->getResultadoBusca($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $resultado['total'], $resultado['link']);
/*
if (!$paginacao['num_total']) {
	header("Location: busca_resultado_erro.php?assunto=" . $assunto . '&resultado=1');
	die;
}*/
//print_r($resultado);


$js_busca = true;
$topo_class = 'cat-acessibilidade iteia';
$titulopagina = 'Resultado da busca';
include ('includes/topo.php');

?>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Resultado de busca</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Resultado de busca</h2>
      <? if(!$_GET['canais']){?>  <div class="msg">Mostrando de <strong><?=$paginacao['inicio'];?></strong> a <strong><?=$paginacao['fim'];?></strong> de <strong><?=$resultado['total'];?></strong> resultados encontrados<?php if ($assunto): ?> para a busca por <strong class="palavra">"<?=$assunto?>"</strong><?php endif; ?><?php if ($tag): ?> com a tag <strong class="palavra">"<?=$tag?>"</strong><?php endif; ?>.</div><? }?>
<table cellspacing="0" cellpadding="0" border="0" id="resultado-busca">
          <tbody>
<?php
//$iCanal=0;
//print_r($resultado);
if($_GET['canais']){$rs=$resultado[150];

}
if(!$_GET['canais']){
foreach ($resultado as $value):
$marcado=array();
$i=0;
	if ((int)$value['cod']):
	if($value['canal']){
	


	if(!$achou){
	$iCanal++;
		
	}
	}
	
?>
            <tr>
              <td class="col-desc"><p class="meta"><strong class="<?=$value['icon'];?>"><a href="<?=$value['url_secao'];?>"><?=$value['tformato'];?></a></strong> | <?=$value['canal'];?> <small><?=$value['data'];?></small></p>
                <h1><a href="<?=$value['url_titulo'];?>" title="Conteúdo restrito"><span class="midia"><?=Util::iif($assunto, Util::marcarPalavra(strip_tags($value['titulo']), $assunto), strip_tags($value['titulo']));?></span></a></h1>
				<?php if ($value['imagem']): ?>
				<div class="capa"><a href="<?=$value['url_titulo'];?>"><img src="/exibir_imagem.php?img=<?=$value['imagem']?>&amp;tipo=<?=$value['tipo']?>&amp;s=34" width="100" height="75" /></a></div>
				<?php endif; ?>
                <p><?=Util::cortaTexto(Util::iif($assunto, Util::marcarPalavra(Util::getTrecho(strip_tags($value['descricao']), array($assunto), 450), $assunto), strip_tags($value['descricao'])), 450);?></p></td>
            </tr>
<?php
	endif;
endforeach;

} else {

while($row=mysql_fetch_array($rs)){
 

?>         <tr>
              <td class="col-desc"><p class="meta"><strong class="<?=$value['icon'];?>"><a href="<?=$value['url_secao'];?>"> &nbsp;Canal</a></strong>  <?=$value['canal'];?> <small><?=$value['data'];?></small></p>
                <h1><a href="/canal.php?cod=<?=$row['cod_segmento']; ?>" title="Listar todo conteúdo deste canal"><span class="midia"><?=Util::iif($assunto, Util::marcarPalavra(strip_tags($row['nome']), $assunto), strip_tags($row['nome']));?></span></a></h1>
				<?php if ($row['imagem']): ?>
				<div class="capa"><a href="<?=$value['url_titulo'];?>"><img src="/exibir_imagem.php?img=<?=$row['imagem']?>&amp;tipo=1&amp;s=34" width="100" height="75" /></a></div>
				<?php endif; ?>
                <p><?=Util::cortaTexto(Util::iif($assunto, Util::marcarPalavra(Util::getTrecho(strip_tags($row['descricao']), array($assunto), 450), $assunto), strip_tags($row['descricao'])), 450);?></p><p> <? //echo '<a href="/busca_resultado.php?buscar=1&amp;novabusca=1&amp;canal='.$row['cod_segmento'].'" title="Listar todo conteúdo deste canal" class="info">'.$canal['total'].' conteúdos</a>'; ?> </p></td>
            </tr>
			<? } }?>
          </tbody>
        </table>
        <ul id="paginacao">
     <? if(!$_GET['canais']) {?>  <li id="anterior"><?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\" title=\Anterior\">&laquo; Anterior</a>" : "<span>&laquo; Anterior</span>");?></li>
        <li id="item"><?=$paginacao['page_string'];?></li>
        <li id="proximo"><?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\" title=\"Próxima\">Próxima &raquo;</a> " : "<span>Próxima &raquo;</span>");?></li><? }?>
      </ul>
      </div>
      <div class="lateral">
        <div id="busca-filtro">
<h3>Refine sua busca</h3>  
<ul>
<?=$buscabo->getResultadoBuscaFiltro($_GET,$iCanal);?>
</ul>
        </div>
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
