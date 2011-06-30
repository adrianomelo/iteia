<?php
include('verificalogin.php');

$editar = (int)$_POST['editar'];
$apagar = (int)$_GET["apagar"];
$palavrachave = strip_tags($_GET["palavrachave"]);
$codtipo = $_GET["codtipo"];

include_once("classes/bo/GrupoTipoBO.php");
$tipobo = new GrupoTipoBO;

if ($editar) {
	try {
		$cod_tipo = $tipobo->editar($_POST);
		
		Header("Location: grupo_tipos.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($apagar && !$palavrachave) {
	$tipobo->executaAcao($codtipo, 1);
	Header("Location: grupo_tipos.php");
	exit();
}

$lista_tipos = $tipobo->getListaTipos($palavrachave);

$paginatitulo = 'Tipos';
$item_menu = "grupo";
$item_submenu = "tipo";
include('includes/topo.php');

?>
    <h3 class="titulo">Adicionar Tipos</h3>
    <div class="box">
      <form action="grupo_tipos.php" method="post" id="frm-add-categoria">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
        <input type="hidden" name="codtipo" value="0" />
        <label for="textfield">Nome<span>*</span></label>
        <br />
        <input type="text" name="tipo" class="txt" value="<?=htmlentities(stripslashes($tipobo->getValorCampo("tipo")))?>" <?=$tipobo->verificaErroCampo("tipo")?> id="textfield" />
       
        <input type="submit" value="Adicionar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    <h3 class="titulo">Tipos</h3>
<div id="resultado" class="box">
	<form method="get" id="form-result" action="grupo_tipos.php">
	<input type="hidden" name="apagar" value="1" />
	<input type="hidden" id="acao" name="acao" value="0" />
	
	<p>
    <label for="textfield2">Filtrar tipos</label>
    <br />
    
      <input name="palavrachave" type="text" class="txt" id="textfield2" />
  </p>
	
  <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" />            </th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Apagar</th>
          </tr>
        </thead>
        <tbody>
        	<?php
			foreach ($lista_tipos as $value):
				if (intval($value['cod'])):
			?>
          <tr>
            <td class="col-1"><input type="checkbox" name="codtipo[]" class="check" value="<?=$value['cod'];?>"  /></td>
            <td class="col-titulo"><?=$value['tipo'];?></td>
            <td class="col-editar"><a href="grupo_tipos_editar.php?cod=<?=$value['cod'];?>&amp;height=100&amp;width=310" title="Editar tipo" class="thickbox">Editar</a></td>
            <td class="col-remover"><a href="grupo_tipos.php?apagar=1&amp;codtipo[]=<?=$value['cod'];?>">Apagar</a></td>
          </tr>
          	<?php
				endif;
			endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
      </form>
      <hr class="both" />
    </div>
  </div>

<?php include('includes/rodape.php'); ?>
