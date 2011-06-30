<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codgrupo = (int)$_GET['cod'];

include_once("classes/bo/GrupoEdicaoBO.php");
$grupobo = new GrupoEdicaoBO;

if ($codgrupo) {
	$grupobo->setDadosCamposEdicao($codgrupo);
	$sitesrelacionados = $grupobo->getSitesGrupo($codgrupo);
	$autores = $grupobo->getAutoresGrupo($codgrupo);
}

$item_menu = "grupo";
include('includes/topo.php');
?>
    <h2>Grupos</h2>

      <h3 class="titulo">Autores  do Grupo &quot;<?=$grupobo->getValorCampo('nome')?>&quot; </h3>
      <div class="box">
        <fieldset>
        <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-autores">
          <thead>
            <tr>
              <th class="col-img" scope="col">Imagem</th>
              <th class="col-msg" scope="col">Nome do Autor</th>
              <!--<th class="col-remover" scope="col">Remover</th>-->
            </tr>
          </thead>
          <tbody>
        <?php foreach ($autores as $key => $value): ?>
        	<tr>
              <td class="col-img"><?=Util::iif($value["imagem"], "<img src=\"exibir_imagem.php?img=".$value["imagem"]."&amp;tipo=a&amp;s=1\" width=\"50\" height=\"50\" />", "<img src=\"img/imagens-padrao/mini-autor.jpg\" width=\"50\" height=\"50\" />");?></td>
              <td><strong>Nome art&iacute;stico:</strong> <a href="<?=ConfigVO::URL_SITE;?>autor/<?=$value["url"];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=htmlentities($value["nome"]);?></a> <?=Util::iif($value["sigla"], '('.$value["sigla"].')');?> </td>
              <!--<td class="col-remover"><a href="#" title="Remover">Remover</a></td>-->
            </tr>
		<?php endforeach; ?>
          </tbody>
        </table>
        </fieldset>
      </div>
	  <div class="box box-mais">
      <h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
      <ul>
        <!--
<li><a href="grupo_adicionar_autores.php?cod=<?=$codgrupo;?>">Adicionar  autores</a></li>
-->
        <li><a href="<?=ConfigVO::URL_SITE;?>grupo/<?=$grupobo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
      </ul>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
