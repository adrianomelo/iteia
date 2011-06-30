<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/AgendaBO.php');

$agebo = new AgendaBO;

$dia = trim($_GET["dia"]);
$mes = (int)$_GET["mes"];

$pagina = ($_GET['pagina'] ? (int)$_GET['pagina'] : 1);
$mostrar = ($_GET['mostrar'] ? (int)$_GET['mostrar'] : 10);
$inicial = ($pagina - 1) * $mostrar;

$eventos = $agebo->getListaAgenda($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $eventos['total'], "eventos.php?dia=$dia&amp;mes=$mes");

$topo_class = 'cat-eventos';
$jsthickbox = true;
$titulopagina = 'Eventos';
$ativa = 7;
include ('includes/topo.php');
?>
<script type="text/javascript">
function mudaCalendario() {
	window.location = 'eventos.php?mes=' + $('#controle-ano').val() + $('#controle-mes').val();
}
</script>
    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Eventos</span></div>
	<h2 class="midia">Eventos</h2>
	<div id="rss"><a href="/feeds.php?formato=6" title="Assine e receba atualizações">Assine</a><br /> <a href="/rss.php" title="Saiba o que é RSS e como utilizar">O que é isso?</a></div>
    <div id="conteudo" class="principal">
      <h3 class="mais"><span>Lista dos</span> Próximos eventos</h3>
<?php
$datanot = '';
$meses = array(1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
$count = count($eventos);
foreach ($eventos as $key => $value):
    if ($value['cod_conteudo']):
		$li = 1;
		$datanot = substr($value['data_inicial'], 0, 10);
		$datanot1 = $data_atual;
	
	$not = false;
	if (!isset($eventos[$key + 1]))
		$not = true;
	
	$datanot_prox = substr($eventos[$key + 1]['data_inicial'], 0, 10);
	if ($data_atual != $datanot_prox && $key)
		$not = true;

	if (!$cont)
		$not = true;

	if ($data_atual != $datanot):
		$data_atual = $datanot;
		$li = 0;
		if ($cont)
			echo "</ul>\n";
?>
			  <h3 class="data"><span class="dia"><?=date('d', strtotime($value['data_inicial']))?></span> de <span class="mes"><?=$meses[(int)date('m', strtotime($value['data_inicial']))]?></span> de <span class="ano"><?=date('Y', strtotime($value['data_inicial']))?></span></h3>
			  <ul class="eventos-lista">
<?php
	endif;
?>
		<li class="vevent <?=($not ? ' no-border no-margin-b' : '')?>">
          <h1 class="summary"><a href="/evento.php?cod=<?=$value['cod_conteudo'];?>" title="Ir para a página do evento"><?=$value['titulo'];?></a></h1>
          <p class="description"><?=(strlen($value['descricao']) > 144 ? substr($value['descricao'], 0, 144):$value['descricao']);?>... <a href="/evento.php?cod=<?=$value['cod_conteudo'];?>" title="Ir para a página do evento">Saiba mais</a></p>
          <ul>
            <li><strong>Data:</strong> <span class="dtstart"><?=date('d/m/Y', strtotime($value['data_inicial']));?></span>
			<?php if ($value['data_final'] != '0000-00-00'): ?>
			até <span class="dtend"><?=date('d/m/Y', strtotime($value['data_final']));?></span>
			<?php endif; ?>
			</li>
            <li><strong>Horário:</strong> <?=date('H:i', strtotime($value['hora_inicial']));?> às <?=date('H:i', strtotime($value['hora_final']));?></li>
            <li><strong>Local:</strong> <span class="location"><?=$value['local'];?></span></li>
			<?php if ($value['site']): ?>
			<li><strong>Site/Hotsite:</strong> <span class="url"><a href="http://<?=$value['site'];?>">http://<?=$value['site'];?></a></span></li>
			<?php endif; ?>
			<?php if ($value['valor']): ?>
			<li><strong>Valor:</strong> <?=$value['valor'];?></li>
			<?php endif; ?>
			<?php if ($value['telefone']): ?>
			<li><strong>Informa&ccedil;&otilde;es:</strong> <?=$value['telefone'];?></li>
			<?php endif; ?>
          </ul>
        </li>
<?php
	$cont++;
	endif;
endforeach;
if ($cont)
	echo "</ul>\n";
?>
	<ul id="paginacao">
		<li id="anterior"><?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\" title=\Anterior\">&laquo; Anterior</a>" : '<span>&laquo; Anterior</span>');?></li>
		<li id="item"><?=$paginacao['page_string'];?></li>
		<li id="proximo"><?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\" title=\"Próxima\">Próxima &raquo;</a>" : '<span>Próxima &raquo;</span>');?></li>
    </ul>
    </div>
      <div class="lateral">
		<?=$agebo->getHtmlCalendario($mes, $dia); ?>
<?php
include('includes/banners_lateral.php');
?>
</div>
<?php
include ('includes/rodape.php');
